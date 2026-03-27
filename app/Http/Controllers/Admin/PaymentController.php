<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    private $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $query = Payment::with(['user', 'course']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Search by transaction ID
        if ($request->filled('search')) {
            $query->where('transaction_id', 'like', "%{$request->search}%");
        }

        $payments = $query->orderBy('created_at', 'desc')
            ->paginate(50);

        // Summary statistics
        $stats = [
            'total_revenue' => Payment::completed()->sum('final_amount'),
            'revenue_today' => Payment::completed()
                ->whereDate('paid_at', today())
                ->sum('final_amount'),
            'revenue_this_month' => Payment::completed()
                ->whereMonth('paid_at', now()->month)
                ->sum('final_amount'),
            'pending_amount' => Payment::pending()->sum('final_amount'),
            'total_transactions' => Payment::count(),
            'successful_transactions' => Payment::completed()->count(),
            'failed_transactions' => Payment::failed()->count(),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['user', 'course', 'enrollment']);

        return view('admin.payments.show', compact('payment'));
    }

    public function refund(Request $request, Payment $payment)
    {
        if (!$payment->is_completed) {
            return back()->with('error', 'Can only refund completed payments.');
        }

        $validated = $request->validate([
            'refund_reason' => ['required', 'in:REQUESTED_BY_CUSTOMER,DUPLICATE,FRAUDULENT,OTHER'],
            'refund_note' => ['nullable', 'string', 'max:500'],
            'allow_refund_multiple_related_payments' => ['nullable', 'boolean'],
        ]);

        $result = $this->paymentService->refund(
            $payment,
            $validated['refund_reason'],
            $validated['refund_note'] ?? null,
            (bool) ($validated['allow_refund_multiple_related_payments'] ?? false)
        );

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    public function reports(Request $request)
    {
        $query = Payment::query()->with(['user', 'course']);
        $stats = [
            'total_revenue' => 0,
            'count' => 0,
            'average_value' => 0,
        ];

        $hasFilters = $request->anyFilled(['start_date', 'end_date', 'status']);
        $payments = collect([]); // Empty by default until generated

        if ($hasFilters) {
            // Filter by status
            if ($request->filled('status')) {
                $query->where('payment_status', $request->status);
            }

            // Filter by date range
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }

            // Get statistics for the filtered result
            $stats['total_revenue'] = $query->sum('final_amount');
            $stats['count'] = $query->count();
            $stats['average_value'] = $stats['count'] > 0 ? $stats['total_revenue'] / $stats['count'] : 0;

            // Get paginated results
            $payments = $query->orderBy('created_at', 'desc')->paginate(50)->appends($request->query());
        }

        return view('admin.payments.reports', compact('payments', 'stats', 'hasFilters'));
    }

    public function exportReport(Request $request)
    {
        // Export payment report as Excel/CSV
        // Implementation depends on requirements
    }
}
