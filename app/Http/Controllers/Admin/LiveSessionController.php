<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\LiveSession;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class LiveSessionController extends Controller
{
    public function index()
    {
        $liveSessions = LiveSession::with('courses')
            ->orderByDesc('starts_at')
            ->paginate(20);

        return view('admin.live-sessions.index', compact('liveSessions'));
    }

    public function create()
    {
        $courses = Course::orderBy('title')->get();

        return view('admin.live-sessions.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);
        $liveSession = new LiveSession($validated);
        $liveSession->created_by = auth()->id();
        $liveSession->save();
        $liveSession->courses()->sync($validated['course_ids']);

        $this->sendPublishedNotificationIfNeeded($liveSession);

        return redirect()->route('admin.live-sessions.index')
            ->with('success', __('live_sessions.created_success'));
    }

    public function show(LiveSession $liveSession)
    {
        $liveSession->load('courses');
        $eligibleStudentsCount = $this->eligibleStudentsQuery($liveSession)->count();

        return view('admin.live-sessions.show', compact('liveSession', 'eligibleStudentsCount'));
    }

    public function edit(LiveSession $liveSession)
    {
        $liveSession->load('courses');
        $courses = Course::orderBy('title')->get();

        return view('admin.live-sessions.edit', compact('liveSession', 'courses'));
    }

    public function update(Request $request, LiveSession $liveSession)
    {
        $validated = $this->validateRequest($request, $liveSession);
        $wasDraft = $liveSession->status === LiveSession::STATUS_DRAFT;

        $liveSession->update($validated);
        $liveSession->courses()->sync($validated['course_ids']);

        if ($wasDraft && $liveSession->status !== LiveSession::STATUS_DRAFT) {
            $liveSession->forceFill(['published_notification_sent_at' => null])->save();
        }

        $this->sendPublishedNotificationIfNeeded($liveSession->fresh('courses'));

        return redirect()->route('admin.live-sessions.show', $liveSession)
            ->with('success', __('live_sessions.updated_success'));
    }

    public function destroy(LiveSession $liveSession)
    {
        $liveSession->courses()->detach();
        $liveSession->delete();

        return redirect()->route('admin.live-sessions.index')
            ->with('success', __('live_sessions.deleted_success'));
    }

    private function validateRequest(Request $request, ?LiveSession $liveSession = null): array
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'zoom_join_url' => 'required|url',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'status' => 'required|in:draft,scheduled,live,ended,cancelled',
            'banner_enabled' => 'sometimes|boolean',
            'notifications_enabled' => 'sometimes|boolean',
            'recording_url' => 'nullable|url',
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => 'integer|exists:courses,id',
        ]);

        $validated['banner_enabled'] = $request->boolean('banner_enabled');
        $validated['notifications_enabled'] = $request->boolean('notifications_enabled');

        return $validated;
    }

    private function sendPublishedNotificationIfNeeded(LiveSession $liveSession): void
    {
        if (!$liveSession->notifications_enabled
            || $liveSession->status === LiveSession::STATUS_DRAFT
            || $liveSession->status === LiveSession::STATUS_CANCELLED
            || $liveSession->published_notification_sent_at) {
            return;
        }

        $liveSession->loadMissing('courses');
        $students = $this->eligibleStudentsQuery($liveSession)->get();

        if ($students->isEmpty()) {
            $liveSession->forceFill(['published_notification_sent_at' => now()])->save();
            return;
        }

        $actionUrl = route('student.live-sessions.show', $liveSession);
        $startsAt = $liveSession->starts_at->format('M d, Y h:i A');
        $primaryCourse = $liveSession->primary_course?->title ?? __('live_sessions.your_course');

        $payload = $students->map(fn (User $user) => [
            'user_id' => $user->id,
            'notification_type' => 'live_session_scheduled',
            'title' => __('live_sessions.scheduled_title'),
            'message' => __('live_sessions.scheduled_message', [
                'title' => $liveSession->title,
                'date' => $startsAt,
                'course' => $primaryCourse,
            ]),
            'action_url' => $actionUrl,
            'created_at' => now(),
            'updated_at' => now(),
        ])->all();

        Notification::insert($payload);

        $liveSession->forceFill(['published_notification_sent_at' => now()])->save();
    }

    private function eligibleStudentsQuery(LiveSession $liveSession)
    {
        return User::students()
            ->whereHas('enrollments', function ($query) use ($liveSession) {
                $query->whereIn('course_id', $liveSession->courses()->pluck('courses.id'));
            });
    }
}
