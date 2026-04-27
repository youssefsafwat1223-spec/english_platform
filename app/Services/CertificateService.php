<?php

namespace App\Services;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Certificate;
use App\Models\SystemSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateService
{
    /**
     * Generate certificate for user
     */
    public function generateCertificate(Enrollment $enrollment)
    {
        $user = $enrollment->user;
        $course = $enrollment->course;

        if (!$user || !$course) {
            Log::error('Certificate generation failed: missing enrollment relation.', [
                'enrollment_id' => $enrollment->id,
                'user_id' => $enrollment->user_id,
                'course_id' => $enrollment->course_id,
            ]);

            return [
                'success' => false,
                'message' => 'Certificate cannot be generated because the enrollment is missing its user or course.',
            ];
        }

        $settings = SystemSetting::getByGroup('certificates');
        if ($settings instanceof \Illuminate\Support\Collection) {
            $settings = $settings->toArray();
        }

        // Check if certificate already exists
        if ($enrollment->certificate) {
            return [
                'success' => true,
                'certificate' => $enrollment->certificate,
            ];
        }

        // Calculate final score from quiz attempts
        $finalScore = $this->calculateFinalScore($enrollment);

        // Generate certificate ID
        $certificateId = Certificate::generateCertificateId(
            $course->id,
            $settings['certificate_prefix'] ?? null
        );

        // Generate QR code
        $enableQrCode = (bool) ($settings['enable_qr_code'] ?? true);
        $qrCodePath = $enableQrCode ? $this->generateQrCode($certificateId) : null;

        // Generate PDF
        $issueDate = $enrollment->completed_at ?? now();
        $pdfPath = $this->generatePdf(
            $user,
            $course,
            $certificateId,
            $finalScore,
            $qrCodePath,
            $settings,
            $issueDate
        );

        // Create certificate record
        $certificate = Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrollment_id' => $enrollment->id,
            'certificate_id' => $certificateId,
            'certificate_type' => 'completion',
            'final_score' => $finalScore,
            'pdf_path' => $pdfPath,
            'qr_code_path' => $qrCodePath,
            'issued_at' => now(),
        ]);

        // Update enrollment
        $enrollment->update([
            'certificate_id' => $certificateId,
            'certificate_issued_at' => now(),
        ]);

        // Send notification
        $this->sendCertificateNotification($user, $course, $certificate);

        return [
            'success' => true,
            'certificate' => $certificate,
        ];
    }

    /**
     * Calculate final score
     */
    private function calculateFinalScore(Enrollment $enrollment)
    {
        $course = $enrollment->course;

        if (!$course) {
            return 70;
        }

        // Get final exam attempt
        $finalExam = $course->quizzes()
            ->where('quiz_type', 'final_exam')
            ->first();

        if ($finalExam) {
            $bestAttempt = $finalExam->getBestAttempt($enrollment->user);

            if ($bestAttempt) {
                return $bestAttempt->score;
            }
        }

        // If no final exam, calculate average of all quiz attempts
        $quizAttempts = $enrollment->quizAttempts()->where('passed', true)->get();

        if ($quizAttempts->isEmpty()) {
            return 70; // Default passing score
        }

        return round($quizAttempts->avg('score'));
    }

    /**
     * Generate QR code
     */
    private function generateQrCode($certificateId)
    {
        if (!extension_loaded('imagick')) {
            return null;
        }

        $verificationUrl = route('certificates.verify', $certificateId);

        $qrCode = QrCode::format('png')
            ->size(200)
            ->margin(1)
            ->generate($verificationUrl);

        $path = "certificates/qr-codes/{$certificateId}.png";

        Storage::put($path, $qrCode);

        return $path;
    }

    /**
     * Generate PDF certificate
     */
    private function generatePdf(User $user, Course $course, $certificateId, $finalScore, $qrCodePath, array $settings, $issueDate)
    {
        $certificateLogo = $this->resolveCertificateLogo($settings['certificate_logo'] ?? null);

        $data = [
            'user_name' => $user->name,
            'course_title' => $course->title,
            'certificate_id' => $certificateId,
            'final_score' => $finalScore,
            'issue_date' => $issueDate->format('F d, Y'),
            'qr_code_path' => $qrCodePath ? Storage::path($qrCodePath) : null,
            'certificate_logo' => $certificateLogo,
            'signatory_name' => $settings['signatory_name'] ?? 'Platform Director',
            'signatory_title' => $settings['signatory_title'] ?? 'Director',
        ];

        $html = view('certificates.template', $data)->render();

        if (!class_exists(\Mpdf\Mpdf::class)) {
            Log::warning('mPDF is not installed. Falling back to DomPDF for certificate generation.');

            $pdfBytes = Pdf::loadHTML($html)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'defaultFont' => 'DejaVu Sans',
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                ])
                ->output();

            $path = "certificates/pdfs/{$certificateId}.pdf";
            Storage::put($path, $pdfBytes);

            return $path;
        }

        return $this->generatePdfWithMpdfCanvas($data);

        // mPDF is preferred for Arabic shaping and RTL support.
        $tempDir = storage_path('app/mpdf-tmp');
        if (!is_dir($tempDir)) {
            @mkdir($tempDir, 0775, true);
        }

        $mpdf = new \Mpdf\Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'A4-L',
            'margin_left'   => 0,
            'margin_right'  => 0,
            'margin_top'    => 0,
            'margin_bottom' => 0,
            'tempDir'       => $tempDir,
            'autoScriptToLang' => true,
            'autoLangToFont'   => true,
            'default_font'     => 'dejavusans',
        ]);

        // Single-page certificate — disable auto page breaks so absolute-positioned
        // content doesn't spill into extra pages.
        $mpdf->SetAutoPageBreak(false, 0);

        $hasArabic = preg_match('/\p{Arabic}/u', $user->name . ' ' . $course->title) === 1;
        if ($hasArabic || app()->getLocale() === 'ar') {
            $mpdf->SetDirectionality('rtl');
        }

        $mpdf->WriteHTML($html);

        $pdfBytes = $mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);

        $path = "certificates/pdfs/{$certificateId}.pdf";
        Storage::put($path, $pdfBytes);

        return $path;
    }

    private function generatePdfWithMpdfCanvas(array $data): string
    {
        $tempDir = storage_path('app/mpdf-tmp');
        if (!is_dir($tempDir)) {
            @mkdir($tempDir, 0775, true);
        }

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'tempDir' => $tempDir,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'default_font' => 'dejavusans',
        ]);

        $mpdf->SetAutoPageBreak(false, 0);
        $mpdf->AddPage('L');

        $hasArabic = preg_match('/\p{Arabic}/u', $data['user_name'] . ' ' . $data['course_title']) === 1;
        $rtl = $hasArabic || app()->getLocale() === 'ar';

        if ($rtl) {
            $mpdf->SetDirectionality('rtl');
        }

        $appName = config('app.name', 'Simple English');

        $mpdf->SetFillColor(251, 253, 255);
        $mpdf->Rect(0, 0, 297, 210, 'F');
        $mpdf->SetFillColor(0, 123, 181);
        $mpdf->Rect(0, 0, 297, 12, 'F');
        $mpdf->SetFillColor(245, 158, 11);
        $mpdf->Rect(0, 12, 297, 2.5, 'F');

        $mpdf->SetDrawColor(0, 123, 181);
        $mpdf->SetLineWidth(0.7);
        $mpdf->Rect(12, 20, 273, 170, 'D');
        $mpdf->SetDrawColor(203, 213, 225);
        $mpdf->SetLineWidth(0.3);
        $mpdf->Rect(17, 25, 263, 160, 'D');

        if (!empty($data['certificate_logo']) && file_exists($data['certificate_logo'])) {
            $mpdf->Image($data['certificate_logo'], 136, 29, 25, 0);
            $brandTop = 56;
        } else {
            $brandTop = 35;
        }

        $this->writeFixed($mpdf, $this->div($this->escape($appName), 'color:#007bb5;font-size:13pt;font-weight:bold;letter-spacing:3px;text-transform:uppercase;'), 30, $brandTop, 237, 10);

        $mpdf->SetDrawColor(245, 158, 11);
        $mpdf->SetLineWidth(0.7);
        $mpdf->Line(125, $brandTop + 13, 172, $brandTop + 13);

        $titleTop = $brandTop + 21;
        $this->writeFixed($mpdf, $this->div($rtl ? '&#1588;&#1607;&#1575;&#1583;&#1577; &#1581;&#1590;&#1608;&#1585;' : 'CERTIFICATE', 'color:#0f172a;font-size:38pt;font-weight:bold;letter-spacing:2px;'), 30, $titleTop, 237, 18);
        $this->writeFixed($mpdf, $this->div($rtl ? '&#1573;&#1578;&#1605;&#1575;&#1605; &#1575;&#1604;&#1576;&#1585;&#1606;&#1575;&#1605;&#1580; &#1575;&#1604;&#1578;&#1583;&#1585;&#1610;&#1576;&#1610;' : 'OF ATTENDANCE', 'color:#f59e0b;font-size:13pt;font-weight:bold;letter-spacing:2px;'), 30, $titleTop + 20, 237, 9);
        $this->writeFixed($mpdf, $this->div($rtl ? '&#1578;&#1605;&#1606;&#1581; &#1607;&#1584;&#1607; &#1575;&#1604;&#1588;&#1607;&#1575;&#1583;&#1577; &#1573;&#1604;&#1609;' : 'THIS CERTIFICATE IS AWARDED TO', 'color:#64748b;font-size:10pt;font-weight:bold;letter-spacing:1px;'), 30, $titleTop + 35, 237, 8);

        $nameFont = mb_strlen($data['user_name'], 'UTF-8') > 30 ? 24 : 31;
        $this->writeFixed($mpdf, '<div style="font-family:dejavusans;text-align:center;color:#0f172a;font-size:' . $nameFont . 'pt;font-weight:bold;border-bottom:2px solid #007bb5;padding-bottom:7px;">' . $this->escape($data['user_name']) . '</div>', 55, $titleTop + 45, 187, 22);
        $this->writeFixed($mpdf, $this->div($rtl ? '&#1608;&#1584;&#1604;&#1603; &#1576;&#1593;&#1583; &#1573;&#1578;&#1605;&#1575;&#1605;&#1607; &#1576;&#1606;&#1580;&#1575;&#1581;' : 'For successfully completing', 'color:#475569;font-size:11pt;'), 40, $titleTop + 72, 217, 8);

        $courseFont = mb_strlen($data['course_title'], 'UTF-8') > 50 ? 14 : 18;
        $this->writeFixed($mpdf, $this->div($this->escape($data['course_title']), 'color:#007bb5;font-size:' . $courseFont . 'pt;font-weight:bold;line-height:1.35;'), 38, $titleTop + 82, 221, 18);

        $scoreLabel = $rtl ? '&#1575;&#1604;&#1583;&#1585;&#1580;&#1577; &#1575;&#1604;&#1606;&#1607;&#1575;&#1574;&#1610;&#1577;' : 'Final Score';
        $this->writeFixed($mpdf, '<div style="font-family:dejavusans;text-align:center;"><span style="background:#f59e0b;color:#ffffff;font-size:12pt;font-weight:bold;padding:9px 40px;">' . $scoreLabel . ': ' . (int) $data['final_score'] . '%</span></div>', 30, $titleTop + 106, 237, 12);

        $footerTop = 160;
        $this->footerBlock($mpdf, $rtl ? '&#1578;&#1575;&#1585;&#1610;&#1582; &#1575;&#1604;&#1573;&#1589;&#1583;&#1575;&#1585;' : 'Date of Issue', $data['issue_date'], 27, $footerTop);

        if (!empty($data['qr_code_path']) && file_exists($data['qr_code_path'])) {
            $mpdf->Image($data['qr_code_path'], 139.5, $footerTop - 3, 18, 18);
        }

        $this->footerBlock($mpdf, $rtl ? '&#1575;&#1604;&#1578;&#1608;&#1602;&#1610;&#1593; &#1575;&#1604;&#1605;&#1593;&#1578;&#1605;&#1583;' : 'Authorized Signature', $data['signatory_name'], 200, $footerTop, $data['signatory_title']);
        $this->writeFixed($mpdf, $this->div(($rtl ? '&#1585;&#1602;&#1605; &#1575;&#1604;&#1588;&#1607;&#1575;&#1583;&#1577;' : 'Certificate ID') . ': ' . $data['certificate_id'], 'color:#007bb5;font-size:8pt;font-weight:bold;letter-spacing:1px;'), 30, 181, 237, 8);

        $pdfBytes = $mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);

        $path = "certificates/pdfs/{$data['certificate_id']}.pdf";
        Storage::put($path, $pdfBytes);

        return $path;
    }

    private function writeFixed(\Mpdf\Mpdf $mpdf, string $html, float $x, float $y, float $w, float $h): void
    {
        $mpdf->WriteFixedPosHTML($html, $x, $y, $w, $h);
    }

    private function div(string $content, string $style): string
    {
        return '<div style="font-family:dejavusans;text-align:center;' . $style . '">' . $content . '</div>';
    }

    private function footerBlock(\Mpdf\Mpdf $mpdf, string $label, string $value, float $x, float $y, ?string $subvalue = null): void
    {
        $html = '<div style="font-family:dejavusans;text-align:center;">'
            . '<div style="color:#94a3b8;font-size:7.5pt;font-weight:bold;letter-spacing:1px;text-transform:uppercase;">' . $label . '</div>'
            . '<div style="border-top:1.2px solid #007bb5;margin:5px auto 4px auto;width:45mm;height:1px;"></div>'
            . '<div style="color:#0f172a;font-size:10pt;font-weight:bold;">' . $this->escape($value) . '</div>';

        if ($subvalue) {
            $html .= '<div style="color:#64748b;font-size:7pt;font-weight:bold;text-transform:uppercase;margin-top:2px;">' . $this->escape($subvalue) . '</div>';
        }

        $html .= '</div>';

        $this->writeFixed($mpdf, $html, $x, $y, 70, 22);
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public function regenerateCertificatePdf(Certificate $certificate): bool
    {
        $certificate->loadMissing(['user', 'course']);

        if (!$certificate->user || !$certificate->course) {
            Log::error('Certificate PDF regeneration failed: missing certificate relation.', [
                'certificate_id' => $certificate->id,
                'certificate_code' => $certificate->certificate_id,
                'user_id' => $certificate->user_id,
                'course_id' => $certificate->course_id,
            ]);

            return false;
        }

        $settings = SystemSetting::getByGroup('certificates');
        if ($settings instanceof \Illuminate\Support\Collection) {
            $settings = $settings->toArray();
        }

        $enableQrCode = (bool) ($settings['enable_qr_code'] ?? true);
        $qrCodePath = $certificate->qr_code_path;

        if ($enableQrCode && !$qrCodePath) {
            $qrCodePath = $this->generateQrCode($certificate->certificate_id);
        }

        $pdfPath = $this->generatePdf(
            $certificate->user,
            $certificate->course,
            $certificate->certificate_id,
            $certificate->final_score,
            $qrCodePath,
            $settings,
            $certificate->issued_at ?? now()
        );

        $certificate->update([
            'pdf_path' => $pdfPath,
            'qr_code_path' => $qrCodePath,
        ]);

        return true;
    }

    private function resolveCertificateLogo(?string $configuredLogo): ?string
    {
        $candidates = [
            $configuredLogo,
            public_path('logo.jpg'),
            public_path('favicon.jpg'),
        ];

        foreach ($candidates as $candidate) {
            if (!$candidate) {
                continue;
            }

            if (Str::startsWith($candidate, ['http://', 'https://'])) {
                return $candidate;
            }

            if (file_exists($candidate)) {
                return $candidate;
            }

            $publicPath = public_path(ltrim($candidate, '/\\'));
            if (file_exists($publicPath)) {
                return $publicPath;
            }
        }

        return null;
    }

    /**
     * Send certificate notification
     */
    public function sendCertificateNotification(User $user, Course $course, Certificate $certificate): bool
    {
        $telegram = app(TelegramService::class);
        $caption = "<b>Congratulations!</b>\n\n";
        $caption .= "Course: {$course->title}\n";
        $caption .= "Final Score: {$certificate->final_score}%\n";
        $caption .= "Grade: {$certificate->grade}\n";
        $caption .= "Certificate ID: {$certificate->certificate_id}";

        $pdfPath = $certificate->pdf_path ? Storage::path($certificate->pdf_path) : null;

        $sent = false;

        if ($pdfPath && file_exists($pdfPath)) {
            $sent = (bool) $telegram->sendDocument($user->telegram_chat_id, $pdfPath, $caption);

            if (!$sent) {
                $sent = (bool) $telegram->sendMessage($user->telegram_chat_id, $caption . "\n\nDownload: {$certificate->verification_url}");
            }
        } else {
            $sent = (bool) $telegram->sendMessage($user->telegram_chat_id, $caption . "\n\nDownload: {$certificate->verification_url}");
        }

        // Create in-app notification
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'notification_type' => 'certificate_issued',
            'title' => __('Certificate Issued!'),
            'message' => __('Congratulations! Your certificate for :course is ready.', [
                'course' => $course->title,
            ]),
            'action_url' => route('student.certificates.show', $certificate->id),
        ]);

        // Send Email
        try {
            \Illuminate\Support\Facades\Mail::to($user)->send(new \App\Mail\CertificateIssued($certificate));
        } catch (\Exception $e) {
            // Log error or ignore if mail fails
            \Illuminate\Support\Facades\Log::error('Failed to send certificate email: ' . $e->getMessage());
        }

        return (bool) $sent;
    }

    /**
     * Verify certificate
     */
    public function verifyCertificate($certificateId)
    {
        $certificate = Certificate::where('certificate_id', $certificateId)->first();

        if (!$certificate) {
            return [
                'valid' => false,
                'message' => 'Certificate not found',
            ];
        }

        // Increment view count
        $certificate->incrementViews();

        return [
            'valid' => true,
            'certificate' => $certificate,
            'user' => $certificate->user,
            'course' => $certificate->course,
        ];
    }

    /**
     * Share certificate to LinkedIn
     */
    public function getLinkedInShareUrl(Certificate $certificate)
    {
        $certificate->markAsSharedOnLinkedIn();

        $courseTitle = $certificate->course?->title ?? 'Course';

        $params = http_build_query([
            'name' => "{$courseTitle} - Certificate of Completion",
            'certUrl' => $certificate->verification_url,
            'certId' => $certificate->certificate_id,
        ]);

        return "https://www.linkedin.com/profile/add?startTask=CERTIFICATION_NAME&{$params}";
    }
}
