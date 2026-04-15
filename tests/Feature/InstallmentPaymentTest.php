<?php

namespace Tests\Feature;

use App\Mail\InstallmentConfirmationMail;
use App\Mail\InstallmentOverdueMail;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\InstallmentPlan;
use App\Models\Payment;
use App\Models\PromoCode;
use App\Models\User;
use App\Services\InstallmentService;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InstallmentPaymentTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================================
    // Helpers
    // =========================================================================

    private function makeInstallmentCourse(float $price = 300): Course
    {
        return Course::factory()->create([
            'price'        => $price,
            'payment_type' => 'installment',
            'total_students' => 0,
        ]);
    }

    private function makeFullCourse(float $price = 300): Course
    {
        return Course::factory()->create([
            'price'        => $price,
            'payment_type' => 'full',
            'total_students' => 0,
        ]);
    }

    private function fakeStreamPaySubscription(
        string $subscriptionId = 'sub-123',
        string $invoiceId = 'inv-456',
        string $invoiceUrl = 'https://streampay.sa/s/test'
    ): void {
        Http::fake([
            '*consumers*'     => Http::response(['id' => 'consumer-1']),
            '*products*'      => Http::response(['id' => 'product-1']),
            '*subscriptions*' => Http::response([
                'id'               => $subscriptionId,
                'latest_invoice_id'=> $invoiceId,   // PaymentService reads 'latest_invoice_id', not 'latest_invoice.id'
            ]),
            "*invoices/{$invoiceId}*" => Http::response([
                'id'  => $invoiceId,
                'url' => $invoiceUrl,
            ]),
        ]);
    }

    private function makeWebhookHeaders(string $payload, string $secret = 'test-secret'): array
    {
        $timestamp = (string) now()->timestamp;
        $signature = hash_hmac('sha256', "{$timestamp}.{$payload}", $secret);

        return [
            'CONTENT_TYPE'            => 'application/json',
            'HTTP_X-WEBHOOK-SIGNATURE' => "t={$timestamp},v1={$signature}",
        ];
    }

    // =========================================================================
    // 1. initiateInstallmentPlan
    // =========================================================================

    public function test_initiate_installment_plan_returns_redirect_url(): void
    {
        Mail::fake();
        config(['services.streampay.consumer_enabled' => true]);

        $user   = User::factory()->create();
        $course = $this->makeInstallmentCourse(300);

        $this->fakeStreamPaySubscription('sub-abc', 'inv-xyz', 'https://streampay.sa/s/abc');

        $result = app(InstallmentService::class)->initiateInstallmentPlan($user, $course);

        $this->assertTrue($result['success']);
        $this->assertSame('https://streampay.sa/s/abc', $result['redirect_url']);

        $this->assertDatabaseHas('installment_plans', [
            'user_id'                  => $user->id,
            'course_id'                => $course->id,
            'installments_count'       => 3,
            'installments_paid'        => 0,
            'status'                   => 'active',
            'streampay_subscription_id'=> 'sub-abc',
        ]);
    }

    public function test_initiate_installment_plan_fails_for_non_installment_course(): void
    {
        $user   = User::factory()->create();
        $course = $this->makeFullCourse();

        $result = app(InstallmentService::class)->initiateInstallmentPlan($user, $course);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('does not support installment', $result['message']);
        $this->assertDatabaseCount('installment_plans', 0);
    }

    public function test_initiate_installment_plan_rejects_duplicate_active_plan(): void
    {
        Mail::fake();
        config(['services.streampay.consumer_enabled' => true]);

        $user   = User::factory()->create();
        $course = $this->makeInstallmentCourse();

        $this->fakeStreamPaySubscription();

        // First plan
        app(InstallmentService::class)->initiateInstallmentPlan($user, $course);

        // Attempt second plan for same course
        $result = app(InstallmentService::class)->initiateInstallmentPlan($user, $course);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('already have an active installment plan', $result['message']);
        $this->assertDatabaseCount('installment_plans', 1);
    }

    public function test_initiate_installment_plan_cleans_up_on_gateway_failure(): void
    {
        config(['services.streampay.consumer_enabled' => true]);

        Http::fake([
            '*consumers*' => Http::response(['id' => 'consumer-1']),
            '*products*'  => Http::response([], 500),
        ]);

        $user   = User::factory()->create();
        $course = $this->makeInstallmentCourse();

        $result = app(InstallmentService::class)->initiateInstallmentPlan($user, $course);

        $this->assertFalse($result['success']);
        $this->assertDatabaseCount('installment_plans', 0);
    }

    // =========================================================================
    // 2. handleSubscriptionInvoicePaid — Cycle 1 (opens course)
    // =========================================================================

    public function test_first_installment_creates_enrollment_and_opens_course(): void
    {
        Mail::fake();

        $user   = User::factory()->create();
        $course = $this->makeInstallmentCourse(300);

        $plan = InstallmentPlan::create([
            'user_id'                   => $user->id,
            'course_id'                 => $course->id,
            'total_amount'              => 300,
            'installment_amount'        => 100,
            'installments_count'        => 3,
            'installments_paid'         => 0,
            'status'                    => 'active',
            'streampay_subscription_id' => 'sub-001',
        ]);

        app(InstallmentService::class)->handleSubscriptionInvoicePaid(
            $plan, 'gateway-pay-1', 1, ['subscription_id' => 'sub-001']
        );

        // Payment record created and completed
        $this->assertDatabaseHas('payments', [
            'user_id'             => $user->id,
            'course_id'           => $course->id,
            'installment_plan_id' => $plan->id,
            'installment_number'  => 1,
            'payment_status'      => 'completed',
            'final_amount'        => '100.00',
        ]);

        // Enrollment created
        $this->assertDatabaseHas('enrollments', [
            'user_id'   => $user->id,
            'course_id' => $course->id,
        ]);

        // Plan updated
        $plan->refresh();
        $this->assertSame(1, $plan->installments_paid);
        $this->assertSame('active', $plan->status);
        $this->assertNotNull($plan->enrollment_id);
        $this->assertNotNull($plan->next_due_at);

        // Student count incremented
        $this->assertSame(1, $course->fresh()->total_students);

        // Confirmation mail sent
        Mail::assertSent(InstallmentConfirmationMail::class);
    }

    // =========================================================================
    // 3. handleSubscriptionInvoicePaid — Cycle 2 (keeps course open)
    // =========================================================================

    public function test_second_installment_advances_plan_and_restores_access_if_suspended(): void
    {
        Mail::fake();

        $user   = User::factory()->create();
        $course = $this->makeInstallmentCourse(300);

        // Simulate a plan that already has one installment paid and an enrollment
        $plan = InstallmentPlan::create([
            'user_id'                   => $user->id,
            'course_id'                 => $course->id,
            'total_amount'              => 300,
            'installment_amount'        => 100,
            'installments_count'        => 3,
            'installments_paid'         => 1,
            'status'                    => 'suspended',
            'streampay_subscription_id' => 'sub-002',
            'next_due_at'               => now()->subDays(10),
        ]);

        // Create the first installment payment so the DB is consistent
        $firstPayment = Payment::create([
            'user_id'             => $user->id,
            'course_id'           => $course->id,
            'installment_plan_id' => $plan->id,
            'installment_number'  => 1,
            'transaction_id'      => 'SUB-FIRST',
            'amount'              => 100,
            'currency'            => 'SAR',
            'discount_amount'     => 0,
            'final_amount'        => 100,
            'payment_status'      => 'completed',
            'paid_at'             => now()->subDays(30),
        ]);

        $enrollment = Enrollment::create([
            'user_id'             => $user->id,
            'course_id'           => $course->id,
            'payment_id'          => $firstPayment->id,
            'price_paid'          => 100,
            'total_lessons'       => 0,
            'started_at'          => now()->subDays(30),
            'access_suspended_at' => now()->subDays(5),
        ]);

        $plan->update(['enrollment_id' => $enrollment->id]);

        // Process second installment
        app(InstallmentService::class)->handleSubscriptionInvoicePaid(
            $plan, 'gateway-pay-2', 2, ['subscription_id' => 'sub-002']
        );

        // Second payment created
        $this->assertDatabaseHas('payments', [
            'installment_plan_id' => $plan->id,
            'installment_number'  => 2,
            'payment_status'      => 'completed',
        ]);

        // Access restored
        $this->assertNull($enrollment->fresh()->access_suspended_at);

        // Plan advanced
        $plan->refresh();
        $this->assertSame(2, $plan->installments_paid);
        $this->assertSame('active', $plan->status);
    }

    // =========================================================================
    // 4. handleSubscriptionInvoicePaid — Cycle 3 (completes the plan)
    // =========================================================================

    public function test_third_installment_completes_the_plan(): void
    {
        Mail::fake();

        $user   = User::factory()->create();
        $course = $this->makeInstallmentCourse(300);

        $plan = InstallmentPlan::create([
            'user_id'                   => $user->id,
            'course_id'                 => $course->id,
            'total_amount'              => 300,
            'installment_amount'        => 100,
            'installments_count'        => 3,
            'installments_paid'         => 2,
            'status'                    => 'active',
            'streampay_subscription_id' => 'sub-003',
            'next_due_at'               => now()->addDays(1),
        ]);

        $firstPayment = Payment::create([
            'user_id'             => $user->id,
            'course_id'           => $course->id,
            'installment_plan_id' => $plan->id,
            'installment_number'  => 1,
            'transaction_id'      => 'SUB-FIRST-3',
            'amount'              => 100,
            'currency'            => 'SAR',
            'discount_amount'     => 0,
            'final_amount'        => 100,
            'payment_status'      => 'completed',
            'paid_at'             => now()->subDays(60),
        ]);

        $enrollment = Enrollment::create([
            'user_id'       => $user->id,
            'course_id'     => $course->id,
            'payment_id'    => $firstPayment->id,
            'price_paid'    => 100,
            'total_lessons' => 0,
            'started_at'    => now()->subDays(60),
        ]);

        $plan->update(['enrollment_id' => $enrollment->id]);

        app(InstallmentService::class)->handleSubscriptionInvoicePaid(
            $plan, 'gateway-pay-3', 3, ['subscription_id' => 'sub-003']
        );

        $plan->refresh();
        $this->assertSame(3, $plan->installments_paid);
        $this->assertSame('completed', $plan->status);
        $this->assertNull($plan->next_due_at);
    }

    // =========================================================================
    // 5. Idempotency — same cycle not processed twice
    // =========================================================================

    public function test_same_installment_cycle_is_not_processed_twice(): void
    {
        Mail::fake();

        $user   = User::factory()->create();
        $course = $this->makeInstallmentCourse(300);

        $plan = InstallmentPlan::create([
            'user_id'                   => $user->id,
            'course_id'                 => $course->id,
            'total_amount'              => 300,
            'installment_amount'        => 100,
            'installments_count'        => 3,
            'installments_paid'         => 0,
            'status'                    => 'active',
            'streampay_subscription_id' => 'sub-idem',
        ]);

        $service = app(InstallmentService::class);

        // First call — should create enrollment + payment
        $service->handleSubscriptionInvoicePaid($plan, 'gw-pay-idem', 1, []);
        // Second call — duplicate webhook, should be ignored
        $service->handleSubscriptionInvoicePaid($plan->fresh(), 'gw-pay-idem', 1, []);

        // Only ONE payment for cycle 1
        $this->assertSame(1, Payment::where('installment_plan_id', $plan->id)
            ->where('installment_number', 1)->count());

        // Only ONE enrollment
        $this->assertSame(1, Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)->count());

        // Student count incremented exactly once
        $this->assertSame(1, $course->fresh()->total_students);
    }

    // =========================================================================
    // 6. suspendOverduePlan
    // =========================================================================

    public function test_suspend_overdue_plan_freezes_subscription_and_blocks_access(): void
    {
        Mail::fake();
        config(['services.streampay.secret_key' => 'test-secret']);

        Http::fake([
            '*subscriptions/sub-freeze/freeze' => Http::response(['status' => 'FROZEN']),
        ]);

        $user   = User::factory()->create();
        $course = $this->makeInstallmentCourse(300);

        $firstPayment = Payment::create([
            'user_id'             => $user->id,
            'course_id'           => $course->id,
            'installment_number'  => 1,
            'transaction_id'      => 'SUB-SUSP',
            'amount'              => 100,
            'currency'            => 'SAR',
            'discount_amount'     => 0,
            'final_amount'        => 100,
            'payment_status'      => 'completed',
            'paid_at'             => now()->subDays(40),
        ]);

        $enrollment = Enrollment::create([
            'user_id'       => $user->id,
            'course_id'     => $course->id,
            'payment_id'    => $firstPayment->id,
            'price_paid'    => 100,
            'total_lessons' => 0,
            'started_at'    => now()->subDays(40),
        ]);

        $plan = InstallmentPlan::create([
            'user_id'                   => $user->id,
            'course_id'                 => $course->id,
            'enrollment_id'             => $enrollment->id,
            'total_amount'              => 300,
            'installment_amount'        => 100,
            'installments_count'        => 3,
            'installments_paid'         => 1,
            'status'                    => 'active',
            'streampay_subscription_id' => 'sub-freeze',
            'next_due_at'               => now()->subDays(10),
        ]);

        app(InstallmentService::class)->suspendOverduePlan($plan);

        // Plan suspended
        $this->assertSame('suspended', $plan->fresh()->status);

        // Enrollment access blocked
        $this->assertNotNull($enrollment->fresh()->access_suspended_at);

        // Overdue email sent
        Mail::assertSent(InstallmentOverdueMail::class, fn ($mail) => $mail->hasTo($user->email));
    }

    // =========================================================================
    // 7. Webhook — subscription events route to installment handler
    // =========================================================================

    public function test_webhook_with_subscription_id_routes_to_installment_handler(): void
    {
        Mail::fake();
        config(['services.streampay.secret_key' => 'test-secret']);

        $user   = User::factory()->create();
        $course = $this->makeInstallmentCourse(300);

        $plan = InstallmentPlan::create([
            'user_id'                   => $user->id,
            'course_id'                 => $course->id,
            'total_amount'              => 300,
            'installment_amount'        => 100,
            'installments_count'        => 3,
            'installments_paid'         => 0,
            'status'                    => 'active',
            'streampay_subscription_id' => 'sub-webhook',
        ]);

        $payload = json_encode([
            'event_type' => 'PAYMENT_SUCCEEDED',
            'status'     => 'SUCCEEDED',
            'data'       => [
                'subscription_id' => 'sub-webhook',
                'payment'         => ['id' => 'gw-webhook-1'],
                'subscription'    => ['current_cycle_number' => 1],
            ],
        ], JSON_THROW_ON_ERROR);

        $response = $this->call(
            'POST',
            route('payment.webhook'),
            [], [], [],
            $this->makeWebhookHeaders($payload),
            $payload
        );

        $response->assertOk()->assertJson(['received' => true]);

        // Enrollment created via webhook
        $this->assertDatabaseHas('enrollments', [
            'user_id'   => $user->id,
            'course_id' => $course->id,
        ]);

        $this->assertDatabaseHas('payments', [
            'installment_plan_id' => $plan->id,
            'installment_number'  => 1,
            'payment_status'      => 'completed',
        ]);
    }

    public function test_webhook_invoice_expired_suspends_installment_plan(): void
    {
        Mail::fake();
        config(['services.streampay.secret_key' => 'test-secret']);

        Http::fake([
            '*subscriptions/sub-expire/freeze' => Http::response(['status' => 'FROZEN']),
        ]);

        $user   = User::factory()->create();
        $course = $this->makeInstallmentCourse(300);

        $firstPayment = Payment::create([
            'user_id'             => $user->id,
            'course_id'           => $course->id,
            'installment_number'  => 1,
            'transaction_id'      => 'SUB-EXP',
            'amount'              => 100,
            'currency'            => 'SAR',
            'discount_amount'     => 0,
            'final_amount'        => 100,
            'payment_status'      => 'completed',
            'paid_at'             => now()->subDays(35),
        ]);

        $enrollment = Enrollment::create([
            'user_id'       => $user->id,
            'course_id'     => $course->id,
            'payment_id'    => $firstPayment->id,
            'price_paid'    => 100,
            'total_lessons' => 0,
            'started_at'    => now()->subDays(35),
        ]);

        $plan = InstallmentPlan::create([
            'user_id'                   => $user->id,
            'course_id'                 => $course->id,
            'enrollment_id'             => $enrollment->id,
            'total_amount'              => 300,
            'installment_amount'        => 100,
            'installments_count'        => 3,
            'installments_paid'         => 1,
            'status'                    => 'active',
            'streampay_subscription_id' => 'sub-expire',
            'next_due_at'               => now()->subDays(5),
        ]);

        $payload = json_encode([
            'event_type' => 'INVOICE_EXPIRED',
            'data'       => [
                'subscription_id' => 'sub-expire',
                'status'          => 'EXPIRED',
            ],
        ], JSON_THROW_ON_ERROR);

        $this->call(
            'POST',
            route('payment.webhook'),
            [], [], [],
            $this->makeWebhookHeaders($payload),
            $payload
        )->assertOk();

        $this->assertSame('suspended', $plan->fresh()->status);
        $this->assertNotNull($enrollment->fresh()->access_suspended_at);
    }

    // =========================================================================
    // 8. Webhook — regular payment still works (no subscription_id)
    // =========================================================================

    public function test_webhook_without_subscription_id_processes_regular_payment(): void
    {
        Mail::fake();
        config(['services.streampay.secret_key' => 'test-secret']);

        $user   = User::factory()->create();
        $course = $this->makeFullCourse(300);

        $payment = Payment::create([
            'user_id'             => $user->id,
            'course_id'           => $course->id,
            'transaction_id'      => 'TXN-REG',
            'amount'              => 300,
            'currency'            => 'SAR',
            'discount_amount'     => 0,
            'final_amount'        => 300,
            'payment_status'      => 'pending',
            'gateway_payment_id'  => 'payment-link-reg',
            'gateway_response'    => ['payment_link_id' => 'payment-link-reg'],
        ]);

        $payload = json_encode([
            'event_type' => 'PAYMENT_SUCCEEDED',
            'entity_type'=> 'PAYMENT',
            'entity_id'  => 'stream-pay-reg',
            'status'     => 'SUCCEEDED',
            'data'       => [
                'payment'      => ['id' => 'stream-pay-reg', 'current_status' => 'SUCCEEDED'],
                'payment_link' => ['id' => 'payment-link-reg'],
                'metadata'     => ['payment_id' => (string) $payment->id],
            ],
        ], JSON_THROW_ON_ERROR);

        $this->call(
            'POST',
            route('payment.webhook'),
            [], [], [],
            $this->makeWebhookHeaders($payload),
            $payload
        )->assertOk();

        $this->assertDatabaseHas('payments', [
            'id'             => $payment->id,
            'payment_status' => 'completed',
        ]);

        $this->assertDatabaseHas('enrollments', [
            'user_id'   => $user->id,
            'course_id' => $course->id,
        ]);
    }

    // =========================================================================
    // 9. Promo code used_count decremented on refund
    // =========================================================================

    public function test_refund_decrements_promo_code_used_count(): void
    {
        Mail::fake();
        config([
            'services.streampay.api_key'    => 'api-key',
            'services.streampay.secret_key' => 'api-secret',
        ]);

        Http::fake([
            '*payments/stream-pay-promo/refund' => Http::response([
                'id'             => 'stream-pay-promo',
                'current_status' => 'REFUNDED',
                'refunded_at'    => now()->toIso8601String(),
            ]),
        ]);

        $user   = User::factory()->create();
        $course = $this->makeFullCourse(200);

        $promo = PromoCode::create([
            'code'            => 'SAVE20',
            'discount_type'   => 'percentage',
            'discount_amount' => 20,
            'usage_limit'     => 10,
            'used_count'      => 3,
            'is_active'       => true,
        ]);

        $payment = Payment::create([
            'user_id'            => $user->id,
            'course_id'          => $course->id,
            'promo_code_id'      => $promo->id,
            'transaction_id'     => 'TXN-PROMO',
            'amount'             => 200,
            'currency'           => 'SAR',
            'discount_amount'    => 40,
            'discount_type'      => 'promo',
            'discount_code'      => 'SAVE20',
            'final_amount'       => 160,
            'payment_status'     => 'pending',
            'gateway_payment_id' => 'payment-link-promo',
            'gateway_response'   => ['payment_link_id' => 'payment-link-promo'],
        ]);

        $service = app(PaymentService::class);

        $service->finalizeSuccessfulPayment($payment, 'stream-pay-promo', [
            'payment_id'      => 'stream-pay-promo',
            'payment_link_id' => 'payment-link-promo',
            'current_status'  => 'SUCCEEDED',
        ]);

        // used_count should be 4 after payment
        $this->assertSame(4, $promo->fresh()->used_count);

        $service->refund($payment->fresh(), 'REQUESTED_BY_CUSTOMER', 'Test refund');

        // used_count should drop back to 3 after refund
        $this->assertSame(3, $promo->fresh()->used_count);
    }

    // =========================================================================
    // 10. Suspended enrollment blocks course access
    // =========================================================================

    public function test_suspended_enrollment_redirects_away_from_learn_page(): void
    {
        $user   = User::factory()->create(['onboarding_completed' => true]);
        $course = $this->makeInstallmentCourse(300);

        $plan = InstallmentPlan::create([
            'user_id'             => $user->id,
            'course_id'           => $course->id,
            'total_amount'        => 300,
            'installment_amount'  => 100,
            'installments_count'  => 3,
            'installments_paid'   => 1,
            'status'              => 'suspended',
        ]);

        $firstPayment = Payment::create([
            'user_id'            => $user->id,
            'course_id'          => $course->id,
            'installment_plan_id'=> $plan->id,
            'installment_number' => 1,
            'transaction_id'     => 'SUB-SUSP-UI',
            'amount'             => 100,
            'currency'           => 'SAR',
            'discount_amount'    => 0,
            'final_amount'       => 100,
            'payment_status'     => 'completed',
            'paid_at'            => now()->subDays(35),
        ]);

        $enrollment = Enrollment::create([
            'user_id'             => $user->id,
            'course_id'           => $course->id,
            'payment_id'          => $firstPayment->id,
            'price_paid'          => 100,
            'total_lessons'       => 0,
            'started_at'          => now()->subDays(35),
            'access_suspended_at' => now()->subDays(5),
        ]);

        $plan->update(['enrollment_id' => $enrollment->id]);

        $response = $this->actingAs($user)
            ->get(route('student.courses.learn', $course));

        $response->assertRedirect(route('student.courses.show', $course));
    }

    // =========================================================================
    // 11. InstallmentPlan model — installment_amount accessor
    // =========================================================================

    public function test_course_installment_amount_is_one_third_of_price(): void
    {
        $course = $this->makeInstallmentCourse(300);

        $this->assertEqualsWithDelta(100.0, (float) $course->installment_amount, 0.01);
    }

    public function test_course_is_installment_flag(): void
    {
        $installment = $this->makeInstallmentCourse();
        $full        = $this->makeFullCourse();

        $this->assertTrue($installment->is_installment);
        $this->assertFalse($full->is_installment);
    }

    // =========================================================================
    // 12. InstallmentPlan::markInstallmentPaid completes on 3rd
    // =========================================================================

    public function test_mark_installment_paid_sets_completed_on_last_installment(): void
    {
        $user   = User::factory()->create();
        $course = $this->makeInstallmentCourse();

        $plan = InstallmentPlan::create([
            'user_id'             => $user->id,
            'course_id'           => $course->id,
            'total_amount'        => 300,
            'installment_amount'  => 100,
            'installments_count'  => 3,
            'installments_paid'   => 2,
            'status'              => 'active',
            'next_due_at'         => now()->addDays(5),
        ]);

        $plan->markInstallmentPaid();

        $plan->refresh();
        $this->assertSame('completed', $plan->status);
        $this->assertNull($plan->next_due_at);
        $this->assertSame(3, $plan->installments_paid);
    }
}
