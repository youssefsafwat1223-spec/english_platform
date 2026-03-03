<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\UserNote;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class NotesController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->notes()
            ->with('lesson.course');

        // Filter by course
        if ($request->filled('course_id')) {
            $query->whereHas('lesson', function ($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }

        // Search
        if ($request->filled('search')) {
            $query->where('note_text', 'like', "%{$request->search}%");
        }

        $notes = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        $courses = auth()->user()->enrollments()
            ->with('course')
            ->get()
            ->pluck('course');

        $stats = [
            'total_notes' => auth()->user()->notes()->count(),
            'notes_this_week' => auth()->user()->notes()
                ->where('created_at', '>=', now()->subWeek())
                ->count(),
        ];

        return view('student.notes.index', compact('notes', 'courses', 'stats'));
    }

    public function show(UserNote $note)
    {
        // Check ownership
        if ($note->user_id !== auth()->id()) {
            abort(403);
        }

        $note->load('lesson.course');

        return view('student.notes.show', compact('note'));
    }

    public function export()
    {
        $notes = auth()->user()->notes()
            ->with('lesson.course')
            ->orderBy('created_at', 'desc')
            ->get();

        $user = auth()->user();

        $pdf = Pdf::loadView('student.notes.export-pdf', compact('notes', 'user'));

        return $pdf->download('my-notes-' . now()->format('Y-m-d') . '.pdf');
    }
}