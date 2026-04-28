<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentController extends Controller
{
    private $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $query = $this->buildPaymentsQuery($request, [
            'status_field' => 'status',
            'from_field' => 'from_date',
            'to_field' => 'to_date',
            'search_field' => 'search',
        ]);

        $payments = $query->orderBy('created_at', 'desc')
            ->paginate(50);

        // Summary statistics
        $stats = [
            'total_revenue' => Payment::completed()->sum('final_amount'),
            'revenue_today' => Payment::completed()
                ->whereDate('paid_at', today())
                ->sum('final_amount'),
            'revenue_this_month' => Payment::completed()
                ->whereYear('paid_at', now()->year)
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
        $query = $this->buildPaymentsQuery($request, [
            'status_field' => 'status',
            'from_field' => 'start_date',
            'to_field' => 'end_date',
            'search_field' => null,
        ]);
        $stats = [
            'total_revenue' => 0,
            'count' => 0,
            'average_value' => 0,
        ];

        $hasFilters = $request->anyFilled(['start_date', 'end_date', 'status']);
        $payments = collect([]); // Empty by default until generated

        if ($hasFilters) {
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
        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', 'in:completed,pending,failed,refunded'],
        ]);

        $payments = $this->buildPaymentsQuery($request, [
            'status_field' => 'status',
            'from_field' => 'start_date',
            'to_field' => 'end_date',
            'search_field' => null,
        ])->orderBy('created_at', 'desc')->get();

        $filename = 'payment-report-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($payments) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Date',
                'Transaction ID',
                'User',
                'Email',
                'Course',
                'Amount',
                'Status',
                'Paid At',
            ]);

            foreach ($payments as $payment) {
                fputcsv($handle, [
                    optional($payment->created_at)->format('Y-m-d H:i:s'),
                    $payment->transaction_id,
                    $payment->user?->name,
                    $payment->user?->email,
                    $payment->course?->title,
                    $payment->final_amount,
                    $payment->payment_status,
                    optional($payment->paid_at)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function buildPaymentsQuery(Request $request, array $fields)
    {
        $query = Payment::query()->with(['user', 'course']);

        $statusField = $fields['status_field'] ?? null;
        $fromField = $fields['from_field'] ?? null;
        $toField = $fields['to_field'] ?? null;
        $searchField = $fields['search_field'] ?? null;

        if ($statusField && $request->filled($statusField)) {
            $query->where('payment_status', $request->input($statusField));
        }

        if ($fromField && $request->filled($fromField)) {
            $query->whereDate('created_at', '>=', $request->input($fromField));
        }

        if ($toField && $request->filled($toField)) {
            $query->whereDate('created_at', '<=', $request->input($toField));
        }

        if ($searchField && $request->filled($searchField)) {
            $query->where('transaction_id', 'like', '%' . $request->input($searchField) . '%');
        }

        return $query;
    }
}
