<?php

namespace App\Console\Commands;

use App\Models\InstallmentPlan;
use App\Services\InstallmentService;
use Illuminate\Console\Command;

class ProcessInstallmentsDue extends Command
{
    protected $signature   = 'installments:process';
    protected $description = 'Suspend installment plans that are overdue (fallback — StreamPay handles reminders)';

    public function handle(InstallmentService $installmentService): void
    {
        // StreamPay sends invoices and reminders to the consumer automatically.
        // This command only handles the fallback: suspend access when payment is
        // still missing more than 7 days after the due date.

        $overdue = InstallmentPlan::scopeOverdue(InstallmentPlan::query())
            ->with(['user', 'course', 'enrollment'])
            ->get();

        foreach ($overdue as $plan) {
            $this->warn("Suspending overdue plan #{$plan->id} (user {$plan->user_id}, due {$plan->next_due_at})");
            $installmentService->suspendOverduePlan($plan);
        }

        $this->info("Suspended {$overdue->count()} overdue plan(s).");
    }
}
