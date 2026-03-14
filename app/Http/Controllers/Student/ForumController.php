<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ForumCategory;
use App\Models\ForumTopic;
use App\Models\ForumReply;
use App\Models\ForumReport;
use App\Http\Requests\StoreForumTopicRequest;
use App\Http\Requests\StoreForumReplyRequest;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index()
    {
        $categories = ForumCategory::active()
            ->withCount('topics')
            ->ordered()
            ->get();

        $recentTopics = ForumTopic::with(['user', 'category', 'lastReplyUser'])
            ->orderBy('last_reply_at', 'desc')
            ->take(10)
            ->get();

        $popularTopics = ForumTopic::popular()
            ->with(['user', 'category'])
            ->take(5)
            ->get();

        return view('student.forum.index', compact('categories', 'recentTopics', 'popularTopics'));
    }

    public function category(ForumCategory $category)
    {
        if (!$category->is_active) {
            abort(404);
        }

        $topics = $category->topics()
            ->with(['user', 'lastReplyUser'])
            ->orderBy('is_pinned', 'desc')
            ->orderBy('last_reply_at', 'desc')
            ->paginate(20);

        return view('student.forum.category', compact('category', 'topics'));
    }

    public function createTopic(ForumCategory $category)
    {
        if (!$category->is_active) {
            abort(404);
        }

        return view('student.forum.create-topic', compact('category'));
    }

    public function storeTopic(StoreForumTopicRequest $request)
    {
        $topic = ForumTopic::create([
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('student.forum.category', $topic->category)
            ->with('success', __('تم إنشاء الموضوع بنجاح!'));
    }

    public function showTopic(ForumCategory $category, ForumTopic $topic)
    {
        if ($topic->category_id !== $category->id) {
            abort(404);
        }

        // Increment view count
        $topic->incrementViews();

        $topic->load(['user', 'replies.user', 'replies.likes']);

        return view('student.forum.topic', compact('category', 'topic'));
    }

    public function storeReply(StoreForumReplyRequest $request, ForumCategory $category, ForumTopic $topic)
    {
        if ($topic->is_locked) {
            return back()->with('error', __('هذا الموضوع مغلق ولا يقبل ردود جديدة.'));
        }

        $reply = $topic->replies()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back()->with('success', __('تم إضافة الرد بنجاح!'));
    }

    public function toggleLike(ForumReply $reply)
    {
        $liked = $reply->toggleLike(auth()->user());

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'like_count' => $reply->like_count,
        ]);
    }

    public function report(Request $request)
    {
        $request->validate([
            'reportable_type' => 'required|in:topic,reply',
            'reportable_id' => 'required|integer',
            'reason' => 'required|in:spam,inappropriate,offensive,other',
            'description' => 'nullable|string|max:1000',
        ]);

        $reportableType = $request->reportable_type === 'topic' 
            ? ForumTopic::class 
            : ForumReply::class;

        ForumReport::create([
            'user_id' => auth()->id(),
            'reportable_type' => $reportableType,
            'reportable_id' => $request->reportable_id,
            'reason' => $request->reason,
            'description' => $request->description,
        ]);

        return back()->with('success', __('تم إرسال البلاغ بنجاح. شكرًا لك!'));
    }

    public function myTopics()
    {
        $topics = auth()->user()->forumTopics()
            ->with(['category', 'lastReplyUser'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('student.forum.my-topics', compact('topics'));
    }

    public function myReplies()
    {
        $replies = auth()->user()->forumReplies()
            ->with(['topic.category'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('student.forum.my-replies', compact('replies'));
    }
}