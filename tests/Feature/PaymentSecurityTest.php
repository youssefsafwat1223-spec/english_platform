<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class PaymentSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_callback_does_not_trust_failed_status_from_query_string(): void
    {
        Http::fake([
            '*payment_links/link-1' => Http::response([
                'id' => 'link-1',
                'status' => 'ACTIVE',
            ]),
        ]);

        $payment = $this->createPendingPayment([
            'gateway_payment_id' => 'link-1',
            'gateway_response' => ['payment_link_id' => 'link-1'],
        ]);

        $response = $this->actingAs($payment->user)->get(route('payment.callback', [
            'payment' => $payment->id,
            'status' => 'failed',
            'message' => 'forged',
        ]));

        $response
            ->assertRedirect(route('student.courses.show', $payment->course))
            ->assertSessionHas('info');

        $this->assertSame('pending', $payment->fresh()->payment_status);
    }

    public function test_callback_requires_signed_url_for_anonymous_visits(): void
    {
        $payment = $this->createPendingPayment();

        $this->get(route('payment.callback', $payment))->assertForbidden();
    }

    public function test_signed_callback_verifies_stream_payment_id_before_completion(): void
    {
        Mail::fake();

        Http::fake([
            '*payments/stream-payment-1' => Http::response([
                'id' => 'stream-payment-1',
                'amount' => '100.00',
                'currency' => 'SAR',
                'current_status' => 'SUCCEEDED',
            ]),
        ]);

        $payment = $this->createPendingPayment([
            'gateway_payment_id' => 'payment-link-1',
            'gateway_response' => ['payment_link_id' => 'payment-link-1'],
        ]);

        $signedUrl = URL::temporarySignedRoute('payment.callback', now()->addMinutes(10), [
            'payment' => $payment->id,
        ]);
        $signedUrl .= '&payment_id=stream-payment-1&payment_link_id=payment-link-1';

        $this->get($signedUrl)
            ->assertRedirect(route('student.courses.learn', $payment->course))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'payment_status' => 'completed',
            'gateway_payment_id' => 'stream-payment-1',
        ]);
        $this->assertDatabaseHas('enrollments', [
            'payment_id' => $payment->id,
        ]);
    }

    public function test_webhook_rejects_requests_without_signature(): void
    {
        config(['services.streampay.secret_key' => 'test-secret']);

        $response = $this->postJson(route('payment.webhook'), [
            'event_type' => 'PAYMENT_SUCCEEDED',
            'data' => [],
        ]);

        $response->assertForbidden();
    }

    public function test_webhook_rejects_invalid_signature(): void
    {
        config(['services.streampay.secret_key' => 'test-secret']);

        $payload = json_encode([
            'event_type' => 'PAYMENT_SUCCEEDED',
            'data' => [],
        ], JSON_THROW_ON_ERROR);

        $response = $this->call('POST', route('payment.webhook'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X-WEBHOOK-SIGNATURE' => 't=123456,v1=not-valid',
        ], $payload);

        $response->assertForbidden();
    }

    public function test_webhook_processes_payment_only_with_valid_official_signature(): void
    {
        Mail::fake();

        config(['services.streampay.secret_key' => 'test-secret']);

        $payment = $this->createPendingPayment([
            'gateway_payment_id' => 'payment-link-1',
            'gateway_response' => ['payment_link_id' => 'payment-link-1'],
        ]);

        $payload = json_encode([
            'event_type' => 'PAYMENT_SUCCEEDED',
            'entity_type' => 'PAYMENT',
            'entity_id' => 'stream-payment-1',
            'status' => 'SUCCEEDED',
            'data' => [
                'payment' => [
                    'id' => 'stream-payment-1',
                    'current_status' => 'SUCCEEDED',
                ],
                'payment_link' => [
                    'id' => 'payment-link-1',
                ],
                'metadata' => [
                    'payment_id' => (string) $payment->id,
                ],
            ],
        ], JSON_THROW_ON_ERROR);

        $timestamp = (string) now()->timestamp;
        $signature = hash_hmac('sha256', $timestamp . '.' . $payload, 'test-secret');

        $response = $this->call('POST', route('payment.webhook'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X-WEBHOOK-SIGNATURE' => "t={$timestamp},v1={$signature}",
            'HTTP_X-WEBHOOK-TIMESTAMP' => $timestamp,
        ], $payload);

        $response->assertOk()->assertJson(['received' => true]);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'payment_status' => 'completed',
            'gateway_payment_id' => 'stream-payment-1',
        ]);
    }

    public function test_refund_calls_streampay_and_marks_payment_refunded(): void
    {
        Mail::fake();

        config([
            'services.streampay.api_key' => 'api-key',
            'services.streampay.secret_key' => 'api-secret',
        ]);

        Http::fake([
            '*payments/stream-payment-1/refund' => Http::response([
                'id' => 'stream-payment-1',
                'current_status' => 'REFUNDED',
                'refunded_at' => now()->toIso8601String(),
                'refund_reason' => 'REQUESTED_BY_CUSTOMER',
                'refund_note' => 'Student requested a refund.',
            ]),
        ]);

        $payment = $this->createPendingPayment([
            'gateway_payment_id' => 'payment-link-1',
            'gateway_response' => ['payment_link_id' => 'payment-link-1'],
        ]);

        $service = app(PaymentService::class);

        $service->finalizeSuccessfulPayment($payment, 'stream-payment-1', [
            'payment_id' => 'stream-payment-1',
            'payment_link_id' => 'payment-link-1',
            'amount' => '100.00',
            'currency' => 'SAR',
            'current_status' => 'SUCCEEDED',
        ]);

        $result = $service->refund(
            $payment->fresh(),
            'REQUESTED_BY_CUSTOMER',
            'Student requested a refund.'
        );

        $this->assertTrue($result['success']);
        $this->assertSame('refunded', $payment->fresh()->payment_status);
        $this->assertNotNull($payment->fresh()->refunded_at);
        $this->assertDatabaseMissing('enrollments', [
            'payment_id' => $payment->id,
        ]);
        $this->assertSame(0, $payment->course->fresh()->total_students);
    }

    private function createPendingPayment(array $overrides = []): Payment
    {
        $user = User::factory()->create();
        $course = Course::factory()->create([
            'price' => 100,
            'total_students' => 0,
        ]);

        return Payment::create(array_merge([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'transaction_id' => 'TXN-' . strtoupper(fake()->bothify('SECURE####')),
            'amount' => 100,
            'currency' => 'SAR',
            'discount_amount' => 0,
            'discount_type' => null,
            'discount_code' => null,
            'final_amount' => 100,
            'payment_status' => 'pending',
        ], $overrides));
    }
}
