<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Payment;
use App\Models\PromoCode;
use App\Services\CertificateService;
use App\Services\PaymentService;
use App\Services\ReferralService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    private $paymentService;
    private $referralService;
    private $certificateService;

    public function __construct(
        PaymentService $paymentService,
        ReferralService $referralService,
        CertificateService $certificateService
    )
    {
        $this->paymentService = $paymentService;
        $this->referralService = $referralService;
        $this->certificateService = $certificateService;
    }

    public function index(Request $request)
    {
        $query = Course::active()
            ->withCount(['students', 'lessons']);

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        switch ($request->input('sort')) {
            case 'popular':
                $query->orderBy('total_students', 'desc');
                break;
            case 'rating':
                $query->orderBy('average_rating', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('order_index');
                break;
        }

        $courses = $query->paginate(12)->appends($request->query());

        return view('student.courses.index', compact('courses'));
    }

    public function show(Course $course)
    {
        if (!$course->is_active) {
            abort(404);
        }

        $course->load(['lessons' => function ($query) {
            $query->orderBy('order_index');
        }]);

        $user = auth()->user();
        $isEnrolled = $user->isEnrolledIn($course->id);
        $enrollment = null;
        $progress = 0;

        if ($isEnrolled) {
            $enrollment = $user->getEnrollment($course->id);
            $enrollment->load(['lessonProgress', 'quizAttempts']);
            $progress = (float) ($enrollment->progress_percentage ?? 0);
        }

        // Calculate discount if applicable
        $discount = $this->paymentService->calculateDiscount($user, $course);

        return view('student.courses.show', compact(
            'course',
            'isEnrolled',
            'enrollment',
            'discount',
            'progress'
        ));
    }

    public function myCourses(Request $request)
    {
        $user = auth()->user();

        $query = $user->enrollments()
            ->with(['course', 'lessonProgress']);

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->whereHas('course', function ($courseQuery) use ($search) {
                $courseQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->input('status') === 'completed') {
                $query->whereNotNull('completed_at');
            } elseif ($request->input('status') === 'active') {
                $query->whereNull('completed_at');
            }
        }

        $enrollments = $query
            ->orderBy('last_accessed_at', 'desc')
            ->paginate(12)
            ->appends($request->query());

        return view('student.courses.my-courses', compact('enrollments'));
    }

    public function enroll(Request $request, Course $course)
    {
        $user = auth()->user();

        if ($user->isEnrolledIn($course->id)) {
            return redirect()->route('student.courses.learn', $course)
                ->with('info', 'You are already enrolled in this course.');
        }

        $promoCode = null;
        if ($request->filled('promo_code')) {
            $promoCode = PromoCode::where('code', strtoupper($request->promo_code))->first();
            if (!$promoCode || !$promoCode->isValid()) {
                return back()->with('error', 'Invalid or expired promo code.');
            }
        }

        // Calculate discount
        $discountData = $this->paymentService->calculateDiscount($user, $course, $promoCode);
        $discount = $discountData['discount_amount'];
        $finalAmount = $discountData['final_amount'];

        return view('student.courses.checkout', compact('course', 'discount', 'finalAmount', 'promoCode'));
    }

    public function processPayment(Request $request, Course $course)
    {
        $user = auth()->user();

        if ($user->isEnrolledIn($course->id)) {
            return redirect()->route('student.courses.learn', $course)
                ->with('info', 'You are already enrolled in this course.');
        }

        $validated = $request->validate([
            'referral_code' => ['nullable', 'string', 'max:50'],
            'promo_code_id' => ['nullable', 'exists:promo_codes,id'], // ID passed from the verified view
        ]);

        $referralCode = $validated['referral_code'] ?? null;
        $promoCode = null;
        $discountCode = null;

        if (!empty($validated['promo_code_id'])) {
            $promoCode = PromoCode::find($validated['promo_code_id']);
            if (!$promoCode || !$promoCode->isValid()) {
                return back()->with('error', 'Invalid or expired promo code.');
            }
            $discountCode = $promoCode->code;
        } 
        elseif (!empty($referralCode)) {
            $result = $this->referralService->applyReferralCode($user, $referralCode);

            if (!($result['success'] ?? false)) {
                return back()
                    ->withErrors(['referral_code' => $result['message'] ?? 'Invalid referral code.'])
                    ->withInput();
            }

            session(['referral_code' => $referralCode]);
            $discountCode = strtoupper(trim($referralCode));
        }

        // Calculate discount
        $discountData = $this->paymentService->calculateDiscount($user, $course, $promoCode);
        $discountAmount = $discountData['discount_amount'];
        $finalAmount = $discountData['final_amount'];

        // If free (100% discount), enroll directly without payment gateway
        if ($finalAmount <= 0) {
            $payment = Payment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'promo_code_id' => $promoCode?->id,
                'transaction_id' => 'FREE-' . strtoupper(\Illuminate\Support\Str::random(16)),
                'amount' => $course->price,
                'currency' => 'SAR',
                'discount_amount' => $discountAmount,
                'discount_type' => $discountData['discount_type'],
                'discount_code' => $discountCode,
                'final_amount' => 0,
                'payment_status' => 'completed',
                'paid_at' => now(),
            ]);

            // Create enrollment
            $payment->createEnrollment();
            $this->paymentService->applySuccessfulPaymentEffects($payment);

            return redirect()->route('student.courses.learn', $course)
                ->with('success', __('تم تسجيلك في الكورس مجاناً! 🎉'));
        }

        // Create payment via StreamPay gateway
        $result = $this->paymentService->createCharge($user, $course, $discountData, $promoCode, $discountCode);

        if ($result['success']) {
            return redirect()->away($result['redirect_url']);
        }

        return back()
            ->withInput()
            ->with('error', $result['message'])
            ->withErrors(['payment' => $result['message']]);
    }

    public function learn(Course $course)
    {
        $user = auth()->user();

        if (!$user->isEnrolledIn($course->id)) {
            return redirect()->route('student.courses.show', $course)
                ->with('error', __('يجب عليك التسجيل في هذا الكورس أولاً.'));
        }

        $enrollment = $user->getEnrollment($course->id);
        $enrollment->load(['course.lessons', 'lessonProgress']);
        
        // Update last accessed
        $enrollment->updateLastAccess();
        $enrollment->markAsStarted();

        // Get current or next lesson
        $currentLesson = $this->getCurrentLesson($enrollment);

        return view('student.courses.learn', compact('course', 'enrollment', 'currentLesson'));
    }

    public function certificateInfo(Course $course)
    {
        $user = auth()->user();
        $enrollment = $user->getEnrollment($course->id);

        if (!$enrollment) {
            return redirect()->route('student.courses.show', $course)
                ->with('error', __('يجب عليك التسجيل في هذا الكورس أولاً.'));
        }

        $isCompleted = $enrollment->completed_at || $enrollment->progress_percentage >= 100;

        if (!$isCompleted) {
            return back()->with('error', 'Complete the course first to receive your certificate.');
        }

        $enrollment->load('certificate');

        // If certificate already exists, go to its page
        if ($enrollment->certificate) {
            return redirect()->route('student.certificates.show', $enrollment->certificate);
        }

        // Generate the certificate
        $result = $this->certificateService->generateCertificate($enrollment);

        if ($result['success'] ?? false) {
            $enrollment->load('certificate');
            return redirect()->route('student.certificates.show', $enrollment->certificate);
        }

        return back()->with('error', 'Failed to generate certificate. Please try again.');
    }

    public function sendCertificate(Course $course)
    {
        $user = auth()->user();

        if (!$user->isEnrolledIn($course->id)) {
            return redirect()->route('student.courses.show', $course)
                ->with('error', __('يجب عليك التسجيل في هذا الكورس أولاً.'));
        }

        if (!$user->telegram_chat_id) {
            return back()->with('error', __('يرجى ربط حساب تيليجرام الخاص بك أولاً.'));
        }

        $enrollment = $user->getEnrollment($course->id);
        $enrollment->load('certificate');

        $isCompleted = $enrollment->completed_at || $enrollment->progress_percentage >= 100;

        if (!$isCompleted) {
            return back()->with('error', 'Complete the course first to receive your certificate.');
        }

        if ($enrollment->certificate) {
            $sent = $this->certificateService->sendCertificateNotification($user, $course, $enrollment->certificate);
            return $sent
                ? back()->with('success', 'تم الارسال')
                : back()->with('error', 'فشل الإرسال. تأكد من ربط التليجرام.');
        }

        $result = $this->certificateService->generateCertificate($enrollment);

        if ($result['success'] ?? false) {
            return back()->with('success', 'تم الارسال');
        }

        return back()->with('error', 'Failed to generate certificate. Please try again.');
    }

    private function getCurrentLesson($enrollment)
    {
        // Get last accessed lesson
        $lastProgress = $enrollment->lessonProgress()
            ->orderBy('updated_at', 'desc')
            ->first();

        if ($lastProgress && !$lastProgress->is_completed) {
            return $lastProgress->lesson;
        }

        // Get next incomplete lesson
        $completedLessonIds = $enrollment->lessonProgress()
            ->where('is_completed', true)
            ->pluck('lesson_id');

        return $enrollment->course->lessons()
            ->whereNotIn('id', $completedLessonIds)
            ->orderBy('order_index')
            ->first();
    }
}
