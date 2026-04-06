<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::with(['user', 'course']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('certificate_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by course
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $certificates = $query->orderBy('issued_at', 'desc')
            ->paginate(50);

        $courses = Course::orderBy('title')->get();

        $stats = [
            'total_issued' => Certificate::count(),
            'issued_this_month' => Certificate::whereYear('issued_at', now()->year)
                ->whereMonth('issued_at', now()->month)
                ->count(),
            'average_score' => Certificate::avg('final_score'),
            'total_downloads' => Certificate::sum('download_count'),
            'total_views' => Certificate::sum('view_count'),
        ];

        return view('admin.certificates.index', compact('certificates', 'stats', 'courses'));
    }

    public function show(Certificate $certificate)
    {
        $certificate->load(['user', 'course', 'enrollment']);

        return view('admin.certificates.show', compact('certificate'));
    }

    public function settings()
    {
        $settings = SystemSetting::getByGroup('certificates');

        return view('admin.certificates.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'certificate_prefix' => 'nullable|string|max:20',
            'signatory_name' => 'nullable|string|max:255',
            'signatory_title' => 'nullable|string|max:255',
            'certificate_logo' => 'nullable|url',
            'enable_qr_code' => 'nullable|boolean',
        ]);

        SystemSetting::set('certificate_prefix', $request->certificate_prefix, 'string', 'certificates');
        SystemSetting::set('signatory_name', $request->signatory_name, 'string', 'certificates');
        SystemSetting::set('signatory_title', $request->signatory_title, 'string', 'certificates');
        SystemSetting::set('certificate_logo', $request->certificate_logo, 'string', 'certificates');
        SystemSetting::set('enable_qr_code', $request->boolean('enable_qr_code'), 'boolean', 'certificates');

        return back()->with('success', 'Certificate settings updated successfully!');
    }

    public function preview()
    {
        $settings = SystemSetting::getByGroup('certificates');

        // Preview certificate template
        return view('certificates.template', [
            'user_name' => 'John Doe',
            'course_title' => 'English Grammar Course',
            'certificate_id' => 'EGC-2026-0001',
            'final_score' => 85,
            'issue_date' => now()->format('F d, Y'),
            'qr_code_path' => null,
            'certificate_logo' => $settings['certificate_logo'] ?? null,
            'signatory_name' => $settings['signatory_name'] ?? 'Platform Director',
            'signatory_title' => $settings['signatory_title'] ?? 'Director',
        ]);
    }
}
