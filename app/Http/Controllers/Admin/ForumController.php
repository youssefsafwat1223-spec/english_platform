<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumCategory;
use App\Models\ForumTopic;
use App\Models\ForumReply;
use App\Models\ForumReport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    public function index()
    {
        $categories = ForumCategory::withCount(['topics'])
            ->orderBy('order_index')
            ->get();

        $recentTopics = ForumTopic::with(['user', 'category'])
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'total_categories' => ForumCategory::count(),
            'total_topics' => ForumTopic::count(),
            'total_replies' => ForumReply::count(),
            'pending_reports' => ForumReport::pending()->count(),
        ];

        return view('admin.forum.index', compact('categories', 'recentTopics', 'stats'));
    }

    public function categories()
    {
        $categories = ForumCategory::withCount(['topics'])
            ->orderBy('order_index')
            ->get();

        return view('admin.forum.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
        ]);

        ForumCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon,
            'order_index' => ForumCategory::max('order_index') + 1,
        ]);

        return back()->with('success', 'Category created successfully!');
    }

    public function updateCategory(Request $request, ForumCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Category updated successfully!');
    }

    public function deleteCategory(ForumCategory $category)
    {
        $category->delete();

        return back()->with('success', 'Category deleted successfully!');
    }

    public function topics(Request $request)
    {
        $query = ForumTopic::with(['user', 'category']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        $topics = $query->orderBy('created_at', 'desc')
            ->paginate(50);

        $categories = ForumCategory::all();

        return view('admin.forum.topics', compact('topics', 'categories'));
    }

    public function showTopic(ForumTopic $topic)
    {
        $topic->load(['user', 'category', 'replies.user']);

        return view('admin.forum.topic-details', compact('topic'));
    }

    public function pinTopic(ForumTopic $topic)
    {
        $topic->pin();

        return back()->with('success', 'Topic pinned successfully!');
    }

    public function unpinTopic(ForumTopic $topic)
    {
        $topic->unpin();

        return back()->with('success', 'Topic unpinned successfully!');
    }

    public function lockTopic(ForumTopic $topic)
    {
        $topic->lock();

        return back()->with('success', 'Topic locked successfully!');
    }

    public function unlockTopic(ForumTopic $topic)
    {
        $topic->unlock();

        return back()->with('success', 'Topic unlocked successfully!');
    }

    public function deleteTopic(ForumTopic $topic)
    {
        $topic->delete();

        return redirect()->route('admin.forum.topics')
            ->with('success', 'Topic deleted successfully!');
    }

    public function deleteReply(ForumReply $reply)
    {
        $reply->delete();

        return back()->with('success', 'Reply deleted successfully!');
    }

    public function reports()
    {
        $reports = ForumReport::with(['user', 'reportable', 'reviewer'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.forum.reports', compact('reports'));
    }

    public function reviewReport(ForumReport $report)
    {
        $report->markAsReviewed(auth()->user());

        return back()->with('success', 'Report reviewed successfully!');
    }

    public function resolveReport(ForumReport $report)
    {
        $report->resolve(auth()->user());

        return back()->with('success', 'Report resolved successfully!');
    }

    public function dismissReport(ForumReport $report)
    {
        $report->dismiss(auth()->user());

        return back()->with('success', 'Report dismissed successfully!');
    }
}