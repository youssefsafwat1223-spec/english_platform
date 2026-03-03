<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PromotionalEmail;
use App\Models\Course;
use App\Models\EmailCampaign;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailCampaignController extends Controller
{
    public function index()
    {
        $campaigns = EmailCampaign::with(['creator', 'targetCourse'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.email-campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $courses = Course::where('is_active', true)->orderBy('title')->get();

        return view('admin.email-campaigns.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'cta_text' => 'nullable|string|max:100',
            'cta_url' => 'nullable|url|max:500',
            'target_audience' => 'required|in:all,active,inactive,course_specific',
            'target_course_id' => 'required_if:target_audience,course_specific|nullable|exists:courses,id',
            'send_now' => 'nullable|boolean',
        ]);

        $campaign = EmailCampaign::create([
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'cta_text' => $validated['cta_text'] ?? null,
            'cta_url' => $validated['cta_url'] ?? null,
            'target_audience' => $validated['target_audience'],
            'target_course_id' => $validated['target_course_id'] ?? null,
            'created_by' => auth()->id(),
            'status' => 'draft',
        ]);

        if ($request->boolean('send_now')) {
            return $this->send($campaign);
        }

        return redirect()->route('admin.email-campaigns.index')
            ->with('success', 'Campaign saved as draft.');
    }

    public function send(EmailCampaign $campaign)
    {
        if ($campaign->status === 'sent') {
            return redirect()->route('admin.email-campaigns.index')
                ->with('error', 'This campaign has already been sent.');
        }

        $campaign->update(['status' => 'sending']);

        $recipients = $this->getRecipients($campaign);
        $campaign->update(['recipients_count' => $recipients->count()]);

        $sent = 0;
        $failed = 0;

        foreach ($recipients as $user) {
            try {
                Mail::to($user)->send(new PromotionalEmail(
                    $campaign->subject,
                    $campaign->body,
                    $user->name,
                    $campaign->cta_text,
                    $campaign->cta_url
                ));
                $sent++;
            } catch (\Exception $e) {
                $failed++;
                Log::error("Campaign email failed for user {$user->id}: " . $e->getMessage());
            }
        }

        $campaign->update([
            'sent_count' => $sent,
            'status' => $failed === $recipients->count() ? 'failed' : 'sent',
            'sent_at' => now(),
        ]);

        return redirect()->route('admin.email-campaigns.index')
            ->with('success', "Campaign sent! {$sent} delivered, {$failed} failed.");
    }

    public function destroy(EmailCampaign $campaign)
    {
        if ($campaign->status !== 'draft') {
            return back()->with('error', 'Only draft campaigns can be deleted.');
        }

        $campaign->delete();

        return back()->with('success', 'Campaign deleted.');
    }

    private function getRecipients(EmailCampaign $campaign)
    {
        $query = User::where('role', 'student')->where('is_active', true);

        switch ($campaign->target_audience) {
            case 'active':
                $query->where('last_activity_at', '>=', now()->subDays(7));
                break;
            case 'inactive':
                $query->where(function ($q) {
                    $q->where('last_activity_at', '<=', now()->subDays(7))
                      ->orWhereNull('last_activity_at');
                });
                break;
            case 'course_specific':
                if ($campaign->target_course_id) {
                    $query->whereHas('enrollments', function ($q) use ($campaign) {
                        $q->where('course_id', $campaign->target_course_id);
                    });
                }
                break;
            case 'all':
            default:
                break;
        }

        return $query->get();
    }
}
