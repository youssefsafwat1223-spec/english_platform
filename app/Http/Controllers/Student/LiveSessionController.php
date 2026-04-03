<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\LiveSession;

class LiveSessionController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $sessions = LiveSession::with('courses')
            ->visibleToStudent($user)
            ->orderBy('starts_at')
            ->get();

        $liveSessions = $sessions->filter(fn (LiveSession $session) => $session->display_status === LiveSession::STATUS_LIVE);
        $upcomingSessions = $sessions->filter(fn (LiveSession $session) => $session->display_status === LiveSession::STATUS_SCHEDULED);
        $pastSessions = $sessions->filter(fn (LiveSession $session) => $session->display_status === LiveSession::STATUS_ENDED)
            ->sortByDesc('ends_at');

        return view('student.live-sessions.index', compact('liveSessions', 'upcomingSessions', 'pastSessions'));
    }

    public function show(LiveSession $liveSession)
    {
        abort_unless(
            $liveSession->canBeViewedBy(auth()->user())
            && !in_array($liveSession->status, [LiveSession::STATUS_DRAFT, LiveSession::STATUS_CANCELLED], true),
            403
        );

        $liveSession->load('courses');

        return view('student.live-sessions.show', compact('liveSession'));
    }
}
