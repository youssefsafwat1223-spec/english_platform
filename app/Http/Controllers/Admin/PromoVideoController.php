<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromoVideoController extends Controller
{
    public function index()
    {
        $promoVideos = PromoVideo::orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.promo-videos.index', compact('promoVideos'));
    }

    public function create()
    {
        return view('admin.promo-videos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'video_url' => 'required|url|max:500',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('promo-videos', 'public');
        }

        PromoVideo::create($validated);

        return redirect()->route('admin.promo-videos.index')
            ->with('success', __('تم إضافة الفيديو بنجاح.'));
    }

    public function edit(PromoVideo $promoVideo)
    {
        return view('admin.promo-videos.edit', compact('promoVideo'));
    }

    public function update(Request $request, PromoVideo $promoVideo)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'video_url' => 'required|url|max:500',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        if ($request->hasFile('thumbnail')) {
            if ($promoVideo->thumbnail) {
                Storage::disk('public')->delete($promoVideo->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('promo-videos', 'public');
        }

        $promoVideo->update($validated);

        return redirect()->route('admin.promo-videos.index')
            ->with('success', __('تم تعديل الفيديو بنجاح.'));
    }

    public function destroy(PromoVideo $promoVideo)
    {
        if ($promoVideo->thumbnail) {
            Storage::disk('public')->delete($promoVideo->thumbnail);
        }
        $promoVideo->delete();
        return redirect()->route('admin.promo-videos.index')
            ->with('success', __('تم حذف الفيديو بنجاح.'));
    }
}
