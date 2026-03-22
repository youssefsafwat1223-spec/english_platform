<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use App\Http\Requests\StoreLessonCommentRequest;
use App\Http\Requests\StoreUserNoteRequest;
use App\Services\AchievementService;
use App\Services\VdoCipherService;

class LessonController extends Controller
{
    protected $achievementService;
    protected $vdoCipherService;

    public function __construct(AchievementService $achievementService, VdoCipherService $vdoCipherService)
    {
        $this->achievementService = $achievementService;
        $this->vdoCipherService = $vdoCipherService;
    }

    public function show(Course $course, Lesson $lesson)
    {
        $user = auth()->user();

        // Check enrollment
        if (!$user->isEnrolledIn($course->id)) {
            return redirect()->route('student.courses.show', $course)
                ->with('error', __('يجب عليك التسجيل في هذا الكورس أولاً.'));
        }

        $enrollment = $user->getEnrollment($course->id);

        // All lessons are now open — no sequential access restriction

        $lesson->load([
            'attachments',
            'audio',
            'quiz.questions',
            'pronunciationExercise',
            'comments.user',
        ]);

        // Get or create progress record
        $progress = LessonProgress::firstOrCreate([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'enrollment_id' => $enrollment->id,
        ]);

        // Get user's notes for this lesson
        $notes = $user->notes()->where('lesson_id', $lesson->id)->get();

        // Get previous and next lessons
        $previousLesson = $lesson->previous_lesson;
        $nextLesson = $lesson->next_lesson;

        // VdoCipher OTP generation
        $vdoCipherOtp = null;
        $vdoCipherPlaybackInfo = null;

        if ($lesson->isVdoCipherVideo()) {
            $watermarkText = $user->name;
            if ($user->phone) {
                $watermarkText .= ' • ' . $user->phone;
            }

            $otpData = $this->vdoCipherService->getOTPWithWatermark(
                $lesson->vdocipher_video_id,
                $watermarkText
            );

            if ($otpData) {
                $vdoCipherOtp = $otpData['otp'];
                $vdoCipherPlaybackInfo = $otpData['playbackInfo'];
            }
        }

        return view('student.lessons.show', compact(
            'course',
            'lesson',
            'progress',
            'notes',
            'previousLesson',
            'nextLesson',
            'vdoCipherOtp',
            'vdoCipherPlaybackInfo'
        ));
    }

    public function complete(Course $course, Lesson $lesson)
    {
        $user = auth()->user();
        $enrollment = $user->getEnrollment($course->id);

        $progress = LessonProgress::where([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'enrollment_id' => $enrollment->id,
        ])->first();

        if (!$progress) {
            return response()->json(['error' => 'Progress not found'], 404);
        }

        // Check if quiz is required and passed
        if ($lesson->has_quiz) {
            $quiz = $lesson->quiz;
            
            if ($quiz && !$quiz->hasUserPassed($user)) {
                return response()->json([
                    'error' => 'You must pass the quiz to complete this lesson',
                ], 400);
            }
        }

        $progress->markAsCompleted();

        // Check for achievements
        $this->achievementService->checkAchievements($user, 'lesson_completed');

        return response()->json([
            'success' => true,
            'message' => 'Lesson completed successfully!',
            'points_earned' => config('app.points_per_lesson', 10),
        ]);
    }

    public function updateProgress(Course $course, Lesson $lesson)
    {
        $user = auth()->user();
        $enrollment = $user->getEnrollment($course->id);

        $progress = LessonProgress::where([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'enrollment_id' => $enrollment->id,
        ])->first();

        if (!$progress) {
            return response()->json(['error' => 'Progress not found'], 404);
        }

        // Update video position
        if (request()->has('position')) {
            $progress->updatePosition(request('position'));
        }

        // Update time spent
        if (request()->has('time_spent')) {
            $progress->addTimeSpent(request('time_spent'));
        }

        return response()->json(['success' => true]);
    }

    public function storeComment(StoreLessonCommentRequest $request, Course $course, Lesson $lesson)
    {
        $comment = $lesson->comments()->create([
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'comment_text' => $request->comment_text,
            'is_admin_reply' => false,
        ]);

        return back()->with('success', __('تم نشر التعليق بنجاح!'));
    }

    public function storeNote(StoreUserNoteRequest $request)
    {
        $note = auth()->user()->notes()->create([
            'lesson_id' => $request->lesson_id,
            'note_text' => $request->note_text,
        ]);

        return response()->json([
            'success' => true,
            'note' => $note,
        ]);
    }

    public function updateNote($noteId)
    {
        $validated = request()->validate([
            'note_text' => 'required|string|max:10000',
        ]);

        $note = auth()->user()->notes()->findOrFail($noteId);

        $note->update([
            'note_text' => $validated['note_text'],
        ]);

        return response()->json([
            'success' => true,
            'note' => $note,
        ]);
    }

    public function deleteNote($noteId)
    {
        $note = auth()->user()->notes()->findOrFail($noteId);
        $note->delete();

        return response()->json(['success' => true]);
    }

}
