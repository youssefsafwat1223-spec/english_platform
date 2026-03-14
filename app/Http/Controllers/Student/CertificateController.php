<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Services\CertificateService;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    private $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    public function index()
    {
        $certificates = auth()->user()->certificates()
            ->with('course')
            ->orderBy('issued_at', 'desc')
            ->get();

        $stats = [
            'total_certificates' => $certificates->count(),
            'average_score' => $certificates->avg('final_score'),
            'total_downloads' => $certificates->sum('download_count'),
        ];

        return view('student.certificates.index', compact('certificates', 'stats'));
    }

    public function show(Certificate $certificate)
    {
        // Check ownership
        if ($certificate->user_id !== auth()->id()) {
            abort(403);
        }

        $certificate->load(['course', 'enrollment']);

        return view('student.certificates.show', compact('certificate'));
    }

    public function download(Certificate $certificate)
    {
        // Check ownership
        if ($certificate->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$certificate->pdf_path) {
            return back()->with('error', __('ملف الشهادة غير موجود.'));
        }

        // Increment download count
        $certificate->incrementDownloads();

        return Storage::download($certificate->pdf_path, "{$certificate->certificate_id}.pdf");
    }

    public function shareLinkedIn(Certificate $certificate)
    {
        // Check ownership
        if ($certificate->user_id !== auth()->id()) {
            abort(403);
        }

        $shareUrl = $this->certificateService->getLinkedInShareUrl($certificate);

        return redirect()->away($shareUrl);
    }

    public function sendEmail(Certificate $certificate)
    {
        // Check ownership
        if ($certificate->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            \Illuminate\Support\Facades\Mail::to(auth()->user())->send(new \App\Mail\CertificateIssued($certificate));
            return back()->with('success', __('تم إرسال الشهادة إلى بريدك الإلكتروني بنجاح!'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Manual certificate email failed: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error($e->getTraceAsString());
            return back()->with('error', __('فشل في إرسال البريد الإلكتروني: ') . $e->getMessage()); // Show error to user temporarily
        }
    }

    public function verify($certificateId)
    {
        $result = $this->certificateService->verifyCertificate($certificateId);

        if (!$result['valid']) {
            return view('student.certificates.verify-failed', [
                'message' => $result['message'],
            ]);
        }

        return view('student.certificates.verify', [
            'certificate' => $result['certificate'],
            'user' => $result['user'],
            'course' => $result['course'],
        ]);
    }
}