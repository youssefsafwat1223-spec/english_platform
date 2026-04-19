<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\LocaleController;

// Language Switch
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Admin\CourseLevelController as AdminCourseLevelController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\Admin\QuizController as AdminQuizController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\CertificateController as AdminCertificateController;
use App\Http\Controllers\Admin\ForumController as AdminForumController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\TwoFactorController as AdminTwoFactorController;
use App\Http\Controllers\Admin\EmailCampaignController as AdminEmailCampaignController;
use App\Http\Controllers\Admin\GameSessionController as AdminGameSessionController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\PromoVideoController as AdminPromoVideoController;
use App\Http\Controllers\Admin\LiveSessionController as AdminLiveSessionController;
use App\Http\Controllers\Admin\DeviceAccessRequestController as AdminDeviceAccessRequestController;

// Student Controllers
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\LessonController as StudentLessonController;
use App\Http\Controllers\Student\QuizController as StudentQuizController;
use App\Http\Controllers\Student\PronunciationController;
use App\Http\Controllers\Student\PronunciationUploadController;
use App\Http\Controllers\Student\CertificateController as StudentCertificateController;
use App\Http\Controllers\Student\ForumController as StudentForumController;
use App\Http\Controllers\Student\ReferralController as StudentReferralController;
use App\Http\Controllers\Student\NotesController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\NotificationController;
use App\Http\Controllers\Student\LiveSessionController as StudentLiveSessionController;
use App\Http\Controllers\Student\LiveGameController;
use App\Http\Controllers\Student\BattleController;
use App\Http\Controllers\Student\OnboardingController;
use App\Http\Controllers\Student\TestimonialController as StudentTestimonialController;
use App\Http\Controllers\Student\WritingController as StudentWritingController;
use App\Http\Controllers\Student\ListeningController as StudentListeningController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'sendContact'])
    ->middleware('throttle:contact-form')
    ->name('contact.send');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
Route::get('/careers', [HomeController::class, 'careers'])->name('careers');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/courses', [HomeController::class, 'courses'])->name('courses.index');
Route::get('/courses/{course}', [HomeController::class, 'courseShow'])->name('courses.show');

// Dynamic Sitemap
Route::get('/sitemap.xml', function () {
    $courses = \App\Models\Course::where('is_active', true)->get();

    $content = '<?xml version="1.0" encoding="UTF-8"?>';
    $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    // Static pages
    $staticPages = [
        ['url' => route('home'), 'priority' => '1.0', 'changefreq' => 'daily'],
        ['url' => route('about'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['url' => route('contact'), 'priority' => '0.7', 'changefreq' => 'monthly'],
        ['url' => route('pricing'), 'priority' => '0.9', 'changefreq' => 'weekly'],
        ['url' => route('register'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['url' => route('login'), 'priority' => '0.6', 'changefreq' => 'monthly'],
    ];

    foreach ($staticPages as $page) {
        $content .= '<url>';
        $content .= '<loc>' . htmlspecialchars($page['url']) . '</loc>';
        $content .= '<changefreq>' . $page['changefreq'] . '</changefreq>';
        $content .= '<priority>' . $page['priority'] . '</priority>';
        $content .= '</url>';
    }

    // Course pages
    foreach ($courses as $course) {
        $content .= '<url>';
        $content .= '<loc>' . htmlspecialchars(route('courses.show', $course)) . '</loc>';
        $content .= '<lastmod>' . $course->updated_at->toW3cString() . '</lastmod>';
        $content .= '<changefreq>weekly</changefreq>';
        $content .= '<priority>0.9</priority>';
        $content .= '</url>';
    }

    $content .= '</urlset>';

    return response($content, 200)->header('Content-Type', 'application/xml');
});

// Certificate Verification (Public)
Route::get('/verify/{certificateId}', [StudentCertificateController::class, 'verify'])
    ->name('certificates.verify');

// StreamPay Payment Callback (Public - StreamPay redirects user here after payment)
Route::get('/payment/callback/{payment}', [\App\Http\Controllers\Api\PaymentController::class, 'callback'])
    ->name('payment.callback');

// Test routes — only accessible in local development
if (app()->environment('local')) {
    Route::get('/test-certificate', function () {
        return view('certificates.template', [
            'certificate_id' => 'CERT-2026-0001',
            'user_name' => 'Youssef Safwat',
            'course_title' => 'Advanced English Grammar',
            'final_score' => 95,
            'issue_date' => now()->format('F d, Y'),
            'signatory_name' => 'Dr. Ahmed Hassan',
            'signatory_title' => 'Academic Director',
            'certificate_logo' => '',
            'qr_code_path' => '',
        ]);
    });
}

// Referral Landing
Route::get('/ref/{referralCode}', [ReferralController::class, 'track'])
    ->name('referral.track');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Language Switcher
Route::get('lang/{locale}', function ($locale) {
    if ($locale === 'sa') {
        $locale = 'ar';
    }

    if (in_array($locale, ['en', 'ar'], true)) {
        session(['locale' => $locale]);
        return back()->withCookie(cookie('locale', $locale, 60 * 24 * 365));
    }
    return back();
})->name('switch-lang');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])
        ->middleware('throttle:registration');

    // Google Social Auth
    Route::get('auth/google', [\App\Http\Controllers\Auth\SocialController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [\App\Http\Controllers\Auth\SocialController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

Route::prefix('admin/two-factor')->name('admin.two-factor.')->middleware(['auth', 'admin', 'active'])->group(function () {
    Route::get('/', [AdminTwoFactorController::class, 'showChallenge'])->name('challenge');
    Route::post('/', [AdminTwoFactorController::class, 'verifyChallenge'])
        ->middleware('throttle:admin-two-factor')
        ->name('verify');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin', 'active', 'admin.2fa'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Courses
    Route::resource('courses', AdminCourseController::class);
    Route::post('/courses/{course}/toggle-status', [AdminCourseController::class, 'toggleStatus'])
        ->name('courses.toggle-status');

    // Lessons
    Route::prefix('courses/{course}/lessons')->name('courses.lessons.')->group(function () {
        Route::get('/', [AdminLessonController::class, 'index'])->name('index');
        Route::get('/create', [AdminLessonController::class, 'create'])->name('create');
        Route::post('/', [AdminLessonController::class, 'store'])->name('store');
        Route::get('/{lesson}', [AdminLessonController::class, 'show'])->name('show');
        Route::get('/{lesson}/edit', [AdminLessonController::class, 'edit'])->name('edit');
        Route::put('/{lesson}', [AdminLessonController::class, 'update'])->name('update');
        Route::delete('/{lesson}', [AdminLessonController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [AdminLessonController::class, 'reorder'])->name('reorder');
    });

    // Course Levels
    Route::prefix('courses/{course}/levels')->name('courses.levels.')->group(function () {
        Route::get('/', [AdminCourseLevelController::class, 'index'])->name('index');
        Route::get('/create', [AdminCourseLevelController::class, 'create'])->name('create');
        Route::post('/', [AdminCourseLevelController::class, 'store'])->name('store');
        Route::get('/{level}/edit', [AdminCourseLevelController::class, 'edit'])->name('edit');
        Route::put('/{level}', [AdminCourseLevelController::class, 'update'])->name('update');
        Route::delete('/{level}', [AdminCourseLevelController::class, 'destroy'])->name('destroy');
    });

    // Questions
    Route::get('/analytics/questions', [App\Http\Controllers\Admin\AnalyticsController::class, 'questions'])->name('analytics.questions');
    Route::get('/questions/reference', [AdminQuestionController::class, 'reference'])
        ->name('questions.reference');
    Route::get('/questions/import', [AdminQuestionController::class, 'import'])
        ->name('questions.import');
    Route::post('/questions/import', [AdminQuestionController::class, 'processImport'])
        ->name('questions.process-import');
    Route::resource('questions', AdminQuestionController::class);
    Route::post('/questions/{question}/generate-audio', [AdminQuestionController::class, 'generateAudio'])
        ->name('questions.generate-audio');
    Route::delete('/questions/{question}/audio', [AdminQuestionController::class, 'deleteAudio'])
        ->name('questions.delete-audio');
    Route::post('/questions/ajax-store', [AdminQuestionController::class, 'storeAjax'])
        ->name('questions.ajax-store');

    // Quizzes
    Route::resource('quizzes', AdminQuizController::class);
    Route::get('/quizzes/{quiz}/attempts', [AdminQuizController::class, 'attempts'])
        ->name('quizzes.attempts');
    Route::get('/quizzes/{quiz}/attempts/{attempt}', [AdminQuizController::class, 'attemptDetails'])
        ->name('quizzes.attempt-details');
    Route::get('/courses/{course}/lessons/{lesson}/questions', [AdminQuizController::class, 'getQuestions'])
        ->name('quizzes.get-questions');
    Route::get('/courses/{course}/questions', [AdminQuizController::class, 'getCourseQuestions'])
        ->name('quizzes.get-course-questions');

    // Students
    Route::resource('students', AdminStudentController::class)->only(['index', 'show', 'destroy']);
    Route::post('/students/{student}/toggle-status', [AdminStudentController::class, 'toggleStatus'])
        ->name('students.toggle-status');
    Route::post('/students/{student}/send-message', [AdminStudentController::class, 'sendMessage'])
        ->name('students.send-message');
    Route::get('/students/{student}/enrollments', [AdminStudentController::class, 'enrollments'])
        ->name('students.enrollments');
    Route::get('/students/{student}/progress/{enrollment}', [AdminStudentController::class, 'progress'])
        ->name('students.progress');
    Route::post('/students/{student}/enrollments/{enrollment}/toggle-access', [AdminStudentController::class, 'toggleEnrollmentAccess'])
        ->name('students.enrollments.toggle-access');
    Route::post('/students/{student}/grant-access', [AdminStudentController::class, 'grantAccess'])
        ->name('students.grant-access');

    Route::get('/device-requests', [AdminDeviceAccessRequestController::class, 'index'])
        ->name('device-requests.index');
    Route::get('/device-requests/{deviceReplacementRequest}', [AdminDeviceAccessRequestController::class, 'show'])
        ->name('device-requests.show');
    Route::post('/device-requests/{deviceReplacementRequest}/approve', [AdminDeviceAccessRequestController::class, 'approve'])
        ->name('device-requests.approve');
    Route::post('/device-requests/{deviceReplacementRequest}/reject', [AdminDeviceAccessRequestController::class, 'reject'])
        ->name('device-requests.reject');


    // Promo Codes
    Route::resource('promo-codes', App\Http\Controllers\Admin\PromoCodeController::class)->except(['show']);

    // Live Sessions
    Route::resource('live-sessions', AdminLiveSessionController::class);

    // Payments
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/reports', [AdminPaymentController::class, 'reports'])
        ->name('payments.reports');
    Route::post('/payments/reports/export', [AdminPaymentController::class, 'exportReport'])
        ->name('payments.export-report');
    Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/refund', [AdminPaymentController::class, 'refund'])
        ->name('payments.refund');

    // Certificates
    Route::get('/certificates', [AdminCertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/settings', [AdminCertificateController::class, 'settings'])
        ->name('certificates.settings');
    Route::post('/certificates/settings', [AdminCertificateController::class, 'updateSettings'])
        ->name('certificates.update-settings');
    Route::get('/certificates/preview', [AdminCertificateController::class, 'preview'])
        ->name('certificates.preview');
    Route::get('/certificates/{certificate}', [AdminCertificateController::class, 'show'])
        ->name('certificates.show');

    // Forum Management
    Route::prefix('forum')->name('forum.')->group(function () {
        Route::get('/', [AdminForumController::class, 'index'])->name('index');
        Route::get('/categories', [AdminForumController::class, 'categories'])->name('categories');
        Route::post('/categories', [AdminForumController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}', [AdminForumController::class, 'updateCategory'])
            ->name('categories.update');
        Route::delete('/categories/{category}', [AdminForumController::class, 'deleteCategory'])
            ->name('categories.delete');

        Route::get('/topics', [AdminForumController::class, 'topics'])->name('topics');
        Route::get('/topics/{topic}', [AdminForumController::class, 'showTopic'])->name('topics.show');
        Route::post('/topics/{topic}/pin', [AdminForumController::class, 'pinTopic'])->name('topics.pin');
        Route::post('/topics/{topic}/unpin', [AdminForumController::class, 'unpinTopic'])->name('topics.unpin');
        Route::post('/topics/{topic}/lock', [AdminForumController::class, 'lockTopic'])->name('topics.lock');
        Route::post('/topics/{topic}/unlock', [AdminForumController::class, 'unlockTopic'])->name('topics.unlock');
        Route::delete('/topics/{topic}', [AdminForumController::class, 'deleteTopic'])->name('topics.delete');
        Route::delete('/replies/{reply}', [AdminForumController::class, 'deleteReply'])->name('replies.delete');

        Route::get('/reports', [AdminForumController::class, 'reports'])->name('reports');
        Route::post('/reports/{report}/review', [AdminForumController::class, 'reviewReport'])
            ->name('reports.review');
        Route::post('/reports/{report}/resolve', [AdminForumController::class, 'resolveReport'])
            ->name('reports.resolve');
        Route::post('/reports/{report}/dismiss', [AdminForumController::class, 'dismissReport'])
            ->name('reports.dismiss');
    });

    // Email Campaigns
    Route::prefix('email-campaigns')->name('email-campaigns.')->group(function () {
        Route::get('/', [AdminEmailCampaignController::class, 'index'])->name('index');
        Route::get('/create', [AdminEmailCampaignController::class, 'create'])->name('create');
        Route::post('/', [AdminEmailCampaignController::class, 'store'])->name('store');
        Route::post('/{campaign}/send', [AdminEmailCampaignController::class, 'send'])->name('send');
        Route::delete('/{campaign}', [AdminEmailCampaignController::class, 'destroy'])->name('destroy');
    });

    // Game Arena
    Route::prefix('games')->name('games.')->group(function () {
        Route::get('/', [AdminGameSessionController::class, 'index'])->name('index');
        Route::get('/create', [AdminGameSessionController::class, 'create'])->name('create');
        Route::post('/', [AdminGameSessionController::class, 'store'])->name('store');
        Route::get('/eligible-students', [AdminGameSessionController::class, 'getEligibleStudents'])->name('eligible-students');
        Route::get('/{game}', [AdminGameSessionController::class, 'show'])->name('show');
        Route::get('/{game}/poll', [AdminGameSessionController::class, 'poll'])->name('poll');
        Route::post('/{game}/start', [AdminGameSessionController::class, 'start'])->name('start');
        Route::post('/{game}/next-question', [AdminGameSessionController::class, 'nextQuestion'])->name('next-question');
        Route::post('/{game}/end', [AdminGameSessionController::class, 'end'])->name('end');
        Route::post('/{game}/notify', [AdminGameSessionController::class, 'notify'])->name('notify');
        Route::delete('/{game}', [AdminGameSessionController::class, 'destroy'])->name('destroy');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [AdminSettingsController::class, 'index'])->name('index');

        Route::get('/general', [AdminSettingsController::class, 'general'])->name('general');
        Route::post('/general', [AdminSettingsController::class, 'updateGeneral'])->name('general.update');

        Route::get('/security', [AdminTwoFactorController::class, 'showSecurity'])->name('security');
        Route::post('/security/two-factor/setup', [AdminTwoFactorController::class, 'beginSetup'])
            ->name('security.two-factor.setup');
        Route::post('/security/two-factor/confirm', [AdminTwoFactorController::class, 'confirmSetup'])
            ->middleware('throttle:admin-two-factor')
            ->name('security.two-factor.confirm');
        Route::post('/security/two-factor/recovery-codes', [AdminTwoFactorController::class, 'regenerateRecoveryCodes'])
            ->name('security.two-factor.recovery-codes');
        Route::delete('/security/two-factor', [AdminTwoFactorController::class, 'disable'])
            ->name('security.two-factor.disable');

        Route::get('/telegram', [AdminSettingsController::class, 'telegram'])->name('telegram');
        Route::post('/telegram', [AdminSettingsController::class, 'updateTelegram'])->name('telegram.update');
        Route::post('/telegram/webhook/set', [AdminSettingsController::class, 'setWebhook'])
            ->name('telegram.webhook.set');
        Route::post('/telegram/webhook/delete', [AdminSettingsController::class, 'deleteWebhook'])
            ->name('telegram.webhook.delete');

        Route::get('/payment', [AdminSettingsController::class, 'payment'])->name('payment');
        Route::post('/payment', [AdminSettingsController::class, 'updatePayment'])->name('payment.update');

        Route::get('/points', [AdminSettingsController::class, 'points'])->name('points');
        Route::post('/points', [AdminSettingsController::class, 'updatePoints'])->name('points.update');

        Route::get('/battle', [AdminSettingsController::class, 'battle'])->name('battle');
        Route::post('/battle', [AdminSettingsController::class, 'updateBattle'])->name('battle.update');
    });

    // Testimonials (آراء الطلاب)
    Route::resource('testimonials', AdminTestimonialController::class)->except(['show']);

    // Promo Videos (عينة من الشروحات)
    Route::resource('promo-videos', AdminPromoVideoController::class)->except(['show']);
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::prefix('student')->name('student.')->middleware(['auth', 'student', 'active', 'approved.device', 'onboarding', 'track.activity'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

    // Onboarding
    Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
    Route::get('/onboarding/complete', [OnboardingController::class, 'complete'])->name('onboarding.complete');
    Route::get('/onboarding/check-telegram', [OnboardingController::class, 'checkTelegram'])->name('onboarding.check-telegram');

    // Courses
    Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/my-courses', [StudentCourseController::class, 'myCourses'])->name('courses.my-courses');
    Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/enroll', [StudentCourseController::class, 'enroll'])->name('courses.enroll');
    Route::post('/courses/{course}/payment', [StudentCourseController::class, 'processPayment'])
        ->middleware('throttle:course-payment')
        ->name('courses.payment');
    Route::get('/courses/{course}/certificate', [StudentCourseController::class, 'certificateInfo'])
        ->name('courses.certificate.info')
        ->middleware('enrolled');
    Route::post('/courses/{course}/certificate', [StudentCourseController::class, 'sendCertificate'])
        ->name('courses.certificate')
        ->middleware(['enrolled', 'throttle:certificate-email']);
    Route::get('/courses/{course}/learn', [StudentCourseController::class, 'learn'])
        ->name('courses.learn')
        ->middleware('enrolled');

    // Remove Discount
    Route::get('/courses/{course}/remove-discount', function (\App\Models\Course $course) {
        $user = auth()->user();
        session()->forget('referral_code');

        // If they had a referral discount, clear it from their profile
        if ($user->has_referral_discount) {
            \App\Models\Referral::where('referee_id', $user->id)
                ->where('status', '!=', 'purchased')
                ->delete();

            $user->update([
                'referred_by' => null,
                'referral_discount_expires_at' => null,
            ]);
        }

        return redirect()->route('student.courses.enroll', $course)->with('success', __('تم إزالة الخصم بنجاح.'));
    })->name('courses.remove-discount');

    // Lessons
    Route::prefix('courses/{course}/lessons')->name('lessons.')->middleware('enrolled')->group(function () {
        Route::get('/{lesson}', [StudentLessonController::class, 'show'])->name('show');
        Route::post('/{lesson}/complete', [StudentLessonController::class, 'complete'])->name('complete');
        Route::post('/{lesson}/progress', [StudentLessonController::class, 'updateProgress'])
            ->name('update-progress');
        Route::post('/{lesson}/comments', [StudentLessonController::class, 'storeComment'])->name('comments.store');
    });

    // Notes
    Route::post('/notes', [StudentLessonController::class, 'storeNote'])->name('notes.store');
    Route::put('/notes/{note}', [StudentLessonController::class, 'updateNote'])->name('notes.update');
    Route::delete('/notes/{note}', [StudentLessonController::class, 'deleteNote'])->name('notes.delete');
    Route::get('/my-notes', [NotesController::class, 'index'])->name('notes.index');
    Route::get('/my-notes/{note}', [NotesController::class, 'show'])->name('notes.show');
    Route::get('/my-notes/export/pdf', [NotesController::class, 'export'])->name('notes.export');

    // Quizzes
    Route::get('/quizzes/{quiz}/start', [StudentQuizController::class, 'start'])->name('quizzes.start');
    Route::post('/quizzes/{quiz}/submit', [StudentQuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/quizzes/attempts/{attempt}', [StudentQuizController::class, 'result'])->name('quizzes.result');
    Route::get('/my-quizzes', [StudentQuizController::class, 'myAttempts'])->name('quizzes.my-attempts');

    // Pop-up Questions
    Route::get('/popup-question/random', [\App\Http\Controllers\Student\PopUpQuestionController::class, 'getRandom'])
        ->name('popup-question.random');
    Route::post('/popup-question/{question}/check', [\App\Http\Controllers\Student\PopUpQuestionController::class, 'checkAnswer'])
        ->name('popup-question.check');

    // Pronunciation
    Route::get('/pronunciation/{exercise}', [PronunciationController::class, 'show'])
        ->name('pronunciation.show');
    Route::post('/pronunciation/upload', [PronunciationUploadController::class, 'upload'])
        ->middleware('throttle:pronunciation')
        ->name('pronunciation.upload');
    Route::get('/pronunciation/status/{token}', [PronunciationUploadController::class, 'status'])
        ->middleware('throttle:120,1')
        ->name('pronunciation.upload-status');
    Route::get('/my-pronunciation', [PronunciationController::class, 'myAttempts'])
        ->name('pronunciation.my-attempts');
    Route::post('/pronunciation/{exercise}/evaluate', [PronunciationController::class, 'evaluate'])
        ->middleware('throttle:pronunciation')
        ->name('pronunciation.evaluate');
    Route::post('/pronunciation/{exercise}/stream/start', [PronunciationController::class, 'startStream'])
        ->middleware('throttle:pronunciation')
        ->name('pronunciation.stream.start');
    Route::post('/pronunciation/{exercise}/stream/compare', [PronunciationController::class, 'compareStream'])
        ->middleware('throttle:pronunciation')
        ->name('pronunciation.stream.compare');
    Route::post('/pronunciation/{exercise}/stream/finalize', [PronunciationController::class, 'finalizeStream'])
        ->middleware('throttle:pronunciation')
        ->name('pronunciation.stream.finalize');

    // Writing
    Route::get('/writing/{writingExercise}', [StudentWritingController::class, 'show'])
        ->name('writing.show');
    Route::post('/writing/{writingExercise}/submit', [StudentWritingController::class, 'submit'])
        ->middleware('throttle:60,1')
        ->name('writing.submit');

    // Listening
    Route::get('/listening/{listeningExercise}', [StudentListeningController::class, 'show'])
        ->name('listening.show');
    Route::post('/listening/{listeningExercise}/submit', [StudentListeningController::class, 'submit'])
        ->middleware('throttle:60,1')
        ->name('listening.submit');

    // Certificates
    Route::get('/certificates', [StudentCertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{certificate}', [StudentCertificateController::class, 'show'])
        ->name('certificates.show');
    Route::get('/certificates/{certificate}/download', [StudentCertificateController::class, 'download'])
        ->name('certificates.download');
    Route::get('/certificates/{certificate}/share-linkedin', [StudentCertificateController::class, 'shareLinkedIn'])
        ->name('certificates.share-linkedin');
    Route::post('/certificates/{certificate}/send-email', [StudentCertificateController::class, 'sendEmail'])
        ->middleware('throttle:certificate-email')
        ->name('certificates.send-email');

    // Forum
    Route::prefix('forum')->name('forum.')->group(function () {
        Route::get('/', [StudentForumController::class, 'index'])->name('index');
        Route::get('/my-topics', [StudentForumController::class, 'myTopics'])->name('my-topics');
        Route::get('/my-replies', [StudentForumController::class, 'myReplies'])->name('my-replies');
        Route::get('/{category:slug}', [StudentForumController::class, 'category'])->name('category');
        Route::get('/{category:slug}/create', [StudentForumController::class, 'createTopic'])->name('create-topic');
        Route::post('/topics', [StudentForumController::class, 'storeTopic'])
            ->middleware('throttle:forum-topic')
            ->name('store-topic');
        Route::get('/{category:slug}/{topic:slug}', [StudentForumController::class, 'showTopic'])->name('topic');
        Route::post('/{category:slug}/{topic:slug}/reply', [StudentForumController::class, 'storeReply'])
            ->middleware('throttle:forum-reply')
            ->name('store-reply');
        Route::post('/replies/{reply}/like', [StudentForumController::class, 'toggleLike'])->name('toggle-like');
        Route::post('/report', [StudentForumController::class, 'report'])
            ->middleware('throttle:forum-report')
            ->name('report');
    });

    // Referrals
    Route::get('/referrals', [StudentReferralController::class, 'index'])->name('referrals.index');
    Route::get('/referrals/how-it-works', [StudentReferralController::class, 'howItWorks'])
        ->name('referrals.how-it-works');

    // Testimonial
    Route::get('/testimonial', [StudentTestimonialController::class, 'edit'])->name('testimonial.edit');
    Route::post('/testimonial', [StudentTestimonialController::class, 'store'])
        ->middleware('throttle:testimonial-submit')
        ->name('testimonial.store');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])
        ->name('profile.change-password');
    Route::post('/profile/change-password', [ProfileController::class, 'updatePassword'])
        ->name('profile.update-password');
    Route::get('/profile/achievements', [ProfileController::class, 'achievements'])
        ->name('profile.achievements');
    Route::get('/profile/points-history', [ProfileController::class, 'pointsHistory'])
        ->name('profile.points-history');
    Route::get('/leaderboard', [ProfileController::class, 'leaderboard'])->name('leaderboard');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])
        ->name('notifications.unread-count');
    Route::get('/notifications/recent-json', [NotificationController::class, 'recentJson'])
        ->name('notifications.recent-json');

    // Live Sessions
    Route::middleware('feature:live-sessions')->group(function () {
        Route::get('/live-sessions', [StudentLiveSessionController::class, 'index'])->name('live-sessions.index');
        Route::get('/live-sessions/{liveSession}', [StudentLiveSessionController::class, 'show'])->name('live-sessions.show');
    });

    // Game Arena
    Route::prefix('games')->name('games.')->group(function () {
        Route::get('/', [LiveGameController::class, 'index'])->name('index');
        Route::get('/{game}/room', [LiveGameController::class, 'room'])->name('room');
        Route::post('/{game}/submit-answer', [LiveGameController::class, 'submitAnswer'])->name('submit-answer');
        Route::post('/{game}/send-chat', [LiveGameController::class, 'sendChat'])->name('send-chat');
        Route::get('/{game}/poll', [LiveGameController::class, 'poll'])->name('poll');
    });

    // Battle Arena
    Route::prefix('battle')->name('battle.')->group(function () {
        Route::get('/', [BattleController::class, 'index'])->name('index');
        Route::post('/{course}/join', [BattleController::class, 'join'])->name('join');
        Route::get('/{room}/lobby', [BattleController::class, 'lobby'])->name('lobby');
        Route::get('/{room}/play', [BattleController::class, 'play'])->name('play');
        Route::get('/{room}/poll', [BattleController::class, 'poll'])->name('poll');
        Route::post('/{room}/answer', [BattleController::class, 'answer'])->name('answer');
        Route::post('/{room}/leave', [BattleController::class, 'leave'])->name('leave');
        Route::get('/{room}/results', [BattleController::class, 'results'])->name('results');
    });

    // Telegram Guide
    Route::view('/telegram-guide', 'student.telegram.guide')->name('telegram.guide');
});

if (app()->environment('local')) {
    Route::get('/test-telegram', function () {
        $token = config('services.telegram.bot_token');
        $webhook = config('services.telegram.webhook_url');

        if (!$token)
            return response()->json(['error' => 'No bot token found in config']);

        try {
            $response = \Illuminate\Support\Facades\Http::get("https://api.telegram.org/bot{$token}/getWebhookInfo");
            return response()->json([
                'local_config_webhook' => $webhook,
                'telegram_api_response' => $response->json()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'hint' => 'Check your internet connection or if the token is correct.'
            ]);
        }
    });
}
// Forgot Password Routes
Route::get('forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

// Reset Password Routes
Route::get('reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])
    ->middleware(['guest', 'throttle:password-reset-submit'])
    ->name('password.update');
