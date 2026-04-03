@php
    $editing = isset($liveSession);
    $selectedCourses = collect(old('course_ids', $editing ? $liveSession->courses->pluck('id')->all() : []))
        ->map(fn ($id) => (int) $id)
        ->all();
@endphp

<div class="glass-card overflow-hidden">
    <div class="glass-card-body space-y-6">
        <div>
            <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('live_sessions.title_label') }}</label>
            <input type="text" name="title" class="input-glass @error('title') border-red-500 @enderror" value="{{ old('title', $editing ? $liveSession->title : '') }}" required>
            @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('live_sessions.description') }}</label>
            <textarea name="description" rows="4" class="input-glass @error('description') border-red-500 @enderror">{{ old('description', $editing ? $liveSession->description : '') }}</textarea>
            @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('live_sessions.zoom_join_url') }}</label>
                <input type="url" name="zoom_join_url" class="input-glass @error('zoom_join_url') border-red-500 @enderror" value="{{ old('zoom_join_url', $editing ? $liveSession->zoom_join_url : '') }}" required>
                @error('zoom_join_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('live_sessions.recording_url') }}</label>
                <input type="url" name="recording_url" class="input-glass @error('recording_url') border-red-500 @enderror" value="{{ old('recording_url', $editing ? $liveSession->recording_url : '') }}">
                @error('recording_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('live_sessions.start_time') }}</label>
                <input type="datetime-local" name="starts_at" class="input-glass @error('starts_at') border-red-500 @enderror" value="{{ old('starts_at', $editing && $liveSession->starts_at ? $liveSession->starts_at->format('Y-m-d\TH:i') : '') }}" required>
                @error('starts_at')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('live_sessions.end_time') }}</label>
                <input type="datetime-local" name="ends_at" class="input-glass @error('ends_at') border-red-500 @enderror" value="{{ old('ends_at', $editing && $liveSession->ends_at ? $liveSession->ends_at->format('Y-m-d\TH:i') : '') }}" required>
                @error('ends_at')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ __('live_sessions.status') }}</label>
                <select name="status" class="input-glass @error('status') border-red-500 @enderror" required>
                    @foreach(['draft', 'scheduled', 'live', 'ended', 'cancelled'] as $status)
                        <option value="{{ $status }}" {{ old('status', $editing ? $liveSession->status : 'draft') === $status ? 'selected' : '' }}>{{ __('live_sessions.statuses.' . $status) }}</option>
                    @endforeach
                </select>
                @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold mb-3" style="color: var(--color-text);">{{ __('live_sessions.courses') }}</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 rounded-2xl p-4" style="background: var(--color-surface-hover); border: 1px solid var(--color-border);">
                @foreach($courses as $course)
                    <label class="flex items-center gap-3 text-sm" style="color: var(--color-text);">
                        <input type="checkbox" name="course_ids[]" value="{{ $course->id }}" {{ in_array($course->id, $selectedCourses, true) ? 'checked' : '' }} class="w-4 h-4 rounded text-primary-500">
                        <span>{{ $course->title }}</span>
                    </label>
                @endforeach
            </div>
            @error('course_ids')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            @error('course_ids.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <label class="flex items-center gap-3 text-sm font-bold" style="color: var(--color-text);">
                <input type="hidden" name="banner_enabled" value="0">
                <input type="checkbox" name="banner_enabled" value="1" {{ old('banner_enabled', $editing ? $liveSession->banner_enabled : true) ? 'checked' : '' }} class="w-4 h-4 rounded text-primary-500">
                {{ __('live_sessions.show_banner') }}
            </label>
            <label class="flex items-center gap-3 text-sm font-bold" style="color: var(--color-text);">
                <input type="hidden" name="notifications_enabled" value="0">
                <input type="checkbox" name="notifications_enabled" value="1" {{ old('notifications_enabled', $editing ? $liveSession->notifications_enabled : true) ? 'checked' : '' }} class="w-4 h-4 rounded text-primary-500">
                {{ __('live_sessions.send_notifications') }}
            </label>
        </div>
    </div>

    <div class="glass-card-footer flex justify-between">
        <a href="{{ route('admin.live-sessions.index') }}" class="btn-secondary">{{ __('live_sessions.cancel') }}</a>
        <button type="submit" class="btn-primary ripple-btn">{{ $editing ? __('live_sessions.update') : __('live_sessions.create') }}</button>
    </div>
</div>
