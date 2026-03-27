<?php

namespace App\Services;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Certificate;
use App\Models\SystemSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
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
        // Get final exam attempt
        $finalExam = $enrollment->course->quizzes()
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
        $certificateLogo = $settings['certificate_logo'] ?? null;
        if ($certificateLogo && !Str::startsWith($certificateLogo, ['http://', 'https://'])) {
            $publicPath = public_path($certificateLogo);
            if (file_exists($publicPath)) {
                $certificateLogo = $publicPath;
            }
        }

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

        $pdf = Pdf::loadView('certificates.template', $data)
            ->setPaper('a4', 'landscape')
            ->setOption('isRemoteEnabled', true)
            ->setOption('margin-top', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0);

        $path = "certificates/pdfs/{$certificateId}.pdf";

        Storage::put($path, $pdf->output());

        return $path;
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

        $params = http_build_query([
            'name' => "{$certificate->course->title} - Certificate of Completion",
            'certUrl' => $certificate->verification_url,
            'certId' => $certificate->certificate_id,
        ]);

        return "https://www.linkedin.com/profile/add?startTask=CERTIFICATION_NAME&{$params}";
    }
}
