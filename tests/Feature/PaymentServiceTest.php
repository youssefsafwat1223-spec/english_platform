<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Payment;
use App\Models\PromoCode;
use App\Models\Referral;
use App\Models\User;
use App\Services\PaymentService;
use App\Services\ReferralService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_finalize_successful_payment_processes_promo_once(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create([
            'price' => 100,
            'total_students' => 0,
        ]);
        $promoCode = PromoCode::create([
            'code' => 'SAVE10',
            'discount_type' => 'percentage',
            'discount_amount' => 10,
            'usage_limit' => 10,
            'used_count' => 0,
            'is_active' => true,
        ]);
        $payment = Payment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'promo_code_id' => $promoCode->id,
            'transaction_id' => 'TXN-TEST-0001',
            'amount' => 100,
            'currency' => 'SAR',
            'discount_amount' => 10,
            'discount_type' => 'promo',
            'discount_code' => $promoCode->code,
            'final_amount' => 90,
            'payment_status' => 'pending',
        ]);

        $service = app(PaymentService::class);

        $service->finalizeSuccessfulPayment($payment, 'gateway-1', ['status' => 'paid']);
        $service->finalizeSuccessfulPayment($payment->fresh(), 'gateway-1', ['status' => 'paid']);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'payment_status' => 'completed',
            'discount_type' => 'promo',
            'discount_code' => 'SAVE10',
        ]);
        $this->assertDatabaseHas('enrollments', [
            'payment_id' => $payment->id,
            'discount_type' => 'coupon',
            'discount_code' => 'SAVE10',
        ]);
        $this->assertSame(1, $promoCode->fresh()->used_count);
        $this->assertSame(1, $course->fresh()->total_students);
        $this->assertNotNull($payment->fresh()->benefits_processed_at);
    }

    public function test_calculate_discount_identifies_referee_referral_discount(): void
    {
        $referrer = User::factory()->create();
        $referee = User::factory()->create([
            'referred_by' => $referrer->id,
            'referral_discount_used' => false,
            'referral_discount_expires_at' => now()->addDays(7),
        ]);
        $course = Course::factory()->create([
            'price' => 100,
        ]);

        Referral::create([
            'referrer_id' => $referrer->id,
            'referee_id' => $referee->id,
            'referral_code' => $referrer->referral_code,
            'clicked_at' => now(),
            'registered_at' => now(),
            'status' => 'registered',
            'referee_discount_used' => false,
        ]);

        $discount = app(PaymentService::class)->calculateDiscount($referee, $course);

        $this->assertSame('referee_referral', $discount['discount_type']);
        $this->assertSame(10.0, (float) $discount['discount_amount']);
    }

    public function test_calculate_discount_identifies_referrer_referral_discount(): void
    {
        $referrer = User::factory()->create([
            'referral_discount_used' => false,
            'referral_discount_expires_at' => now()->addDays(7),
        ]);
        $referee = User::factory()->create();
        $course = Course::factory()->create([
            'price' => 100,
        ]);

        Referral::create([
            'referrer_id' => $referrer->id,
            'referee_id' => $referee->id,
            'referral_code' => $referrer->referral_code,
            'clicked_at' => now(),
            'registered_at' => now(),
            'first_purchase_at' => now(),
            'first_purchase_amount' => 100,
            'referrer_discount_earned' => true,
            'referrer_discount_used' => false,
            'status' => 'purchased',
        ]);

        $discount = app(PaymentService::class)->calculateDiscount($referrer, $course);

        $this->assertSame('referrer_referral', $discount['discount_type']);
        $this->assertSame(10.0, (float) $discount['discount_amount']);
    }

    public function test_create_charge_fails_when_gateway_does_not_return_checkout_url(): void
    {
        config(['services.streampay.consumer_enabled' => false]);

        Http::fake([
            '*products' => Http::response([
                'id' => 'product-1',
            ]),
            '*payment_links' => Http::response([
                'id' => 'payment-link-1',
            ]),
        ]);

        $user = User::factory()->create();
        $course = Course::factory()->create([
            'price' => 100,
        ]);

        $result = app(PaymentService::class)->createCharge($user, $course, [
            'discount_amount' => 0,
            'discount_type' => null,
            'final_amount' => 100,
        ]);

        $this->assertFalse($result['success']);
        $this->assertSame('Payment gateway did not return a checkout link. Please try again.', $result['message']);
        $this->assertDatabaseHas('payments', [
            'user_id' => $user->id,
            'course_id' => $course->id,
            'payment_status' => 'failed',
        ]);
    }

    public function test_create_charge_continues_without_consumer_when_consumer_creation_fails(): void
    {
        config([
            'services.streampay.consumer_enabled' => true,
            'services.streampay.consumer_communication_methods' => 'WHATSAPP,EMAIL',
        ]);

        Http::fake([
            '*products' => Http::response([
                'id' => 'product-1',
            ]),
            '*consumers' => function () {
                throw new ConnectionException('cURL error 7: failed consumer lookup');
            },
            '*payment_links' => Http::response([
                'id' => 'payment-link-1',
                'url' => 'https://checkout.example.test/pay/1',
            ]),
        ]);

        $user = User::factory()->create([
            'phone' => '+966537191862',
        ]);
        $course = Course::factory()->create([
            'price' => 100,
        ]);

        $result = app(PaymentService::class)->createCharge($user, $course, [
            'discount_amount' => 0,
            'discount_type' => null,
            'final_amount' => 100,
        ]);

        $this->assertTrue($result['success']);
        $this->assertSame('https://checkout.example.test/pay/1', $result['redirect_url']);
        $this->assertDatabaseHas('payments', [
            'user_id' => $user->id,
            'course_id' => $course->id,
            'payment_status' => 'pending',
            'gateway_payment_id' => 'payment-link-1',
        ]);
    }

    public function test_create_charge_marks_payment_failed_when_gateway_times_out(): void
    {
        Http::fake(function () {
            throw new ConnectionException('cURL error 28: Operation timed out');
        });

        $user = User::factory()->create();
        $course = Course::factory()->create([
            'price' => 100,
        ]);

        $result = app(PaymentService::class)->createCharge($user, $course, [
            'discount_amount' => 0,
            'discount_type' => null,
            'final_amount' => 100,
        ]);

        $this->assertFalse($result['success']);
        $this->assertSame('The payment gateway took too long to respond. Please try again in a moment.', $result['message']);
        $this->assertDatabaseHas('payments', [
            'user_id' => $user->id,
            'course_id' => $course->id,
            'payment_status' => 'failed',
            'error_message' => 'The payment gateway took too long to respond. Please try again in a moment.',
        ]);
    }

    public function test_checkout_page_displays_payment_error_inline(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create([
            'slug' => 'english-basics',
        ]);

        $response = $this->actingAs($user)
            ->withSession(['error' => 'The payment gateway took too long to respond. Please try again in a moment.'])
            ->get(route('student.courses.enroll', $course));

        $response->assertOk();
        $response->assertSee('The payment gateway took too long to respond. Please try again in a moment.');
    }

    public function test_finalize_successful_payment_applies_referral_effects_only_after_success(): void
    {
        Mail::fake();

        $referrer = User::factory()->create();
        $referee = User::factory()->create([
            'referred_by' => null,
            'referral_discount_used' => false,
            'referral_discount_expires_at' => null,
        ]);
        $course = Course::factory()->create([
            'price' => 100,
            'total_students' => 0,
        ]);

        $referral = Referral::create([
            'referrer_id' => $referrer->id,
            'referee_id' => $referee->id,
            'referral_code' => $referrer->referral_code,
            'clicked_at' => now(),
            'registered_at' => now(),
            'status' => 'registered',
            'referee_discount_used' => false,
        ]);

        $payment = Payment::create([
            'user_id' => $referee->id,
            'course_id' => $course->id,
            'transaction_id' => 'TXN-REF-0001',
            'amount' => 100,
            'currency' => 'SAR',
            'discount_amount' => 10,
            'discount_type' => 'referee_referral',
            'discount_code' => $referrer->referral_code,
            'final_amount' => 90,
            'payment_status' => 'pending',
        ]);

        $this->assertNull($referee->fresh()->referred_by);
        $this->assertFalse((bool) $referee->fresh()->referral_discount_used);
        $this->assertSame('registered', $referral->fresh()->status);

        app(PaymentService::class)->finalizeSuccessfulPayment($payment, 'gateway-ref-1', [
            'payment_id' => 'gateway-ref-1',
            'payment_link_id' => 'payment-link-ref-1',
            'amount' => '90.00',
            'currency' => 'SAR',
            'current_status' => 'SUCCEEDED',
        ]);

        $this->assertSame($referrer->id, $referee->fresh()->referred_by);
        $this->assertTrue((bool) $referee->fresh()->referral_discount_used);
        $this->assertSame('purchased', $referral->fresh()->status);
        $this->assertNotNull($referral->fresh()->first_purchase_at);
    }

    public function test_applying_referral_code_does_not_mutate_user_before_payment_success(): void
    {
        $referrer = User::factory()->create();
        $referee = User::factory()->create([
            'referred_by' => null,
            'referral_discount_used' => false,
            'referral_discount_expires_at' => null,
        ]);

        $result = app(ReferralService::class)->applyReferralCode($referee, $referrer->referral_code);

        $this->assertTrue($result['success']);
        $this->assertSame('referee_referral', $result['discount_type']);
        $this->assertNull($referee->fresh()->referred_by);
        $this->assertNull($referee->fresh()->referral_discount_expires_at);
        $this->assertFalse((bool) $referee->fresh()->referral_discount_used);
    }
}
