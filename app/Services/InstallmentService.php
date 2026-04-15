<?php

namespace App\Services;

use App\Mail\InstallmentConfirmationMail;
use App\Mail\InstallmentOverdueMail;
use App\Models\Course;
use App\Models\InstallmentPlan;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InstallmentService
{
    public function __construct(private readonly PaymentService $paymentService)
    {
    }

    /**
     * Start an installment plan via StreamPay Subscription.
     * Returns the first invoice URL to redirect the student to.
     */
    public function initiateInstallmentPlan(User $user, Course $course): array
    {
        if (!$course->is_installment) {
            return ['success' => false, 'message' => 'This course does not support installment payments.'];
        }

        $existing = InstallmentPlan::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereNotIn('status', ['defaulted', 'cancelled'])
            ->first();

        if ($existing) {
            return ['success' => false, 'message' => 'You already have an active installment plan for this course.'];
        }

        $plan = InstallmentPlan::create([
            'user_id'            => $user->id,
            'course_id'          => $course->id,
            'total_amount'       => $course->price,
            'installment_amount' => $course->installment_amount,
            'installments_count' => 3,
            'installments_paid'  => 0,
            'status'             => 'active',
        ]);

        // Create StreamPay subscription (handles all 3 monthly billings)
        $result = $this->paymentService->createSubscription($user, $course, $plan);

        if (!$result['success']) {
            $plan->delete();
        }

        return $result;
    }

    /**
     * Called when a subscription invoice payment is confirmed via webhook.
     * Creates a local Payment record and handles enrollment/plan progression.
     */
    public function handleSubscriptionInvoicePaid(
        InstallmentPlan $plan,
        string $gatewayPaymentId,
        int $cycleNumber,
        array $gatewayResponse = []
    ): void {
        DB::transaction(function () use ($plan, $gatewayPaymentId, $cycleNumber, $gatewayResponse) {
            $plan = InstallmentPlan::with(['user', 'course', 'enrollment'])
                ->lockForUpdate()
                ->findOrFail($plan->id);

            // Idempotency: don't process same installment twice
            $alreadyProcessed = Payment::where('installment_plan_id', $plan->id)
                ->where('installment_number', $cycleNumber)
                ->where('payment_status', 'completed')
                ->exists();

            if ($alreadyProcessed) {
                Log::info('Subscription invoice already processed', [
                    'plan_id'      => $plan->id,
                    'cycle_number' => $cycleNumber,
                ]);
                return;
            }

            // Create a local Payment record for this installment
            $payment = Payment::create([
                'user_id'              => $plan->user_id,
                'course_id'            => $plan->course_id,
                'installment_plan_id'  => $plan->id,
                'installment_number'   => $cycleNumber,
                'transaction_id'       => 'SUB-' . strtoupper(Str::random(12)),
                'amount'               => $plan->installment_amount,
                'currency'             => 'SAR',
                'discount_amount'      => 0,
                'final_amount'         => $plan->installment_amount,
                'payment_status'       => 'completed',
                'gateway_payment_id'   => $gatewayPaymentId,
                'gateway_response'     => array_merge($gatewayResponse, [
                    'subscription_id' => $plan->streampay_subscription_id,
                    'cycle_number'    => $cycleNumber,
                ]),
                'paid_at'              => now(),
            ]);

            if ($cycleNumber === 1) {
                // First installment — open the course
                $enrollment = $payment->createEnrollment();

                $plan->update([
                    'enrollment_id'     => $enrollment->id,
                    'installments_paid' => 1,
                    'next_due_at'       => now()->addDays(30),
                    'status'            => 'active',
                ]);

                // Send confirmation with schedule
                try {
                    Mail::to($plan->user->email)->send(
                        new InstallmentConfirmationMail($plan->fresh(['user', 'course']))
                    );
                } catch (\Throwable $e) {
                    Log::error('InstallmentConfirmationMail failed: ' . $e->getMessage());
                }
            } else {
                // 2nd or 3rd installment — restore access if suspended, advance plan
                if ($plan->enrollment?->access_suspended_at) {
                    $plan->enrollment->update(['access_suspended_at' => null]);
                }

                $plan->markInstallmentPaid();
            }

            Log::info('Subscription installment processed', [
                'plan_id'      => $plan->id,
                'cycle_number' => $cycleNumber,
                'payment_id'   => $payment->id,
            ]);
        });
    }

    /**
     * Suspend an overdue plan: freeze the StreamPay subscription + lock course access.
     */
    public function suspendOverduePlan(InstallmentPlan $plan): void
    {
        $plan->loadMissing(['user', 'course', 'enrollment']);

        // Freeze StreamPay subscription to pause further invoice generation
        if ($plan->streampay_subscription_id) {
            $this->paymentService->freezeSubscription($plan->streampay_subscription_id, 30);
        }

        $plan->suspend();

        try {
            Mail::to($plan->user->email)->send(new InstallmentOverdueMail($plan));
        } catch (\Throwable $e) {
            Log::error('InstallmentOverdueMail failed', [
                'plan_id' => $plan->id,
                'error'   => $e->getMessage(),
            ]);
        }

        Log::info('Installment plan suspended (overdue)', ['plan_id' => $plan->id]);
    }

    /**
     * Cancel the StreamPay subscription and mark the plan as defaulted.
     * Called when a plan is permanently abandoned.
     */
    public function cancelPlan(InstallmentPlan $plan): void
    {
        if ($plan->streampay_subscription_id) {
            $this->paymentService->cancelSubscription($plan->streampay_subscription_id);
        }

        $plan->update(['status' => 'defaulted']);

        if ($plan->enrollment) {
            $plan->enrollment->update(['access_suspended_at' => now()]);
        }
    }
}
