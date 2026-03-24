@extends('layouts.admin')

@section('title', __('Create Email Campaign'))

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <div class="mb-8" data-aos="fade-down">
        <a href="{{ route('admin.email-campaigns.index') }}" class="inline-flex items-center text-gray-400 hover:text-white mb-4 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            {{ __('Back to Campaigns') }}
        </a>
        <h1 class="text-3xl font-bold text-white">{{ __('📝 Create Email Campaign') }}</h1>
        <p class="text-gray-400 mt-2">{{ __('Compose and send a promotional email to your students') }}</p>
    </div>

    @if($errors->any())
        <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-6 py-4 rounded-xl mb-8 backdrop-blur-sm" role="alert">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.email-campaigns.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Content -->
            <div class="lg:col-span-2 space-y-8" data-aos="fade-up" data-aos-delay="100">
                <div class="backdrop-blur-xl bg-gray-900/60 border border-white/10 rounded-2xl p-8 shadow-2xl">
                    <h5 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <span>📧</span> {{ __('Email Content') }}
                    </h5>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">{{ __('Subject *') }}</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" placeholder="{{ __('e.g. 🎉 Special Offer: 50% Off All Courses!') }}" 
                                class="w-full bg-gray-800/50 border border-gray-700 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder-gray-600" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">{{ __('Email Body *') }}</label>
                            <textarea name="body" rows="12" placeholder="{{ __('Write your promotional message here...&#10;&#10;You can write about new courses, special offers, upcoming events, etc.') }}" 
                                class="w-full bg-gray-800/50 border border-gray-700 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder-gray-600 font-mono text-sm leading-relaxed" required>{{ old('body') }}</textarea>
                            <p class="text-xs text-gray-500 mt-2">{{ __('✨ Plain text. Line breaks will be preserved.') }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-white/5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">{{ __('Button Text (Optional)') }}</label>
                                <input type="text" name="cta_text" value="{{ old('cta_text') }}" placeholder="{{ __('e.g. View Courses') }}" 
                                    class="w-full bg-gray-800/50 border border-gray-700 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder-gray-600">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">{{ __('Button URL (Optional)') }}</label>
                                <input type="url" name="cta_url" value="{{ old('cta_url') }}" placeholder="{{ __('https://...') }}" 
                                    class="w-full bg-gray-800/50 border border-gray-700 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder-gray-600">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings -->
            <div class="space-y-8" data-aos="fade-up" data-aos-delay="200">
                <div class="backdrop-blur-xl bg-gray-900/60 border border-white/10 rounded-2xl p-8 shadow-2xl">
                    <h5 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <span>🎯</span> {{ __('Target Audience') }}
                    </h5>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">{{ __('Send to') }}</label>
                            <div class="relative">
                                <select name="target_audience" id="target_audience" onchange="toggleCourseSelect()"
                                    class="w-full bg-gray-800/50 border border-gray-700 text-white rounded-xl px-4 py-3 appearance-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all cursor-pointer">
                                    <option value="all" {{ old('target_audience') === 'all' ? 'selected' : '' }}>{{ __('🌍 All Students') }}</option>
                                    <option value="active" {{ old('target_audience') === 'active' ? 'selected' : '' }}>✅ {{ __('Active Students (last 7 days)') }}</option>
                                    <option value="inactive" {{ old('target_audience') === 'inactive' ? 'selected' : '' }}>⏸️ {{ __('Inactive Students (7+ days)') }}</option>
                                    <option value="course_specific" {{ old('target_audience') === 'course_specific' ? 'selected' : '' }}>{{ __('📚 Specific Course Students') }}</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div id="course_select_wrapper" style="display: none;" class="animate-fade-in-down">
                            <label class="block text-sm font-semibold text-gray-300 mb-2">{{ __('Select Course') }}</label>
                            <div class="relative">
                                <select name="target_course_id" 
                                    class="w-full bg-gray-800/50 border border-gray-700 text-white rounded-xl px-4 py-3 appearance-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all cursor-pointer">
                                    <option value="">{{ __('Choose a course...') }}</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('target_course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="backdrop-blur-xl bg-gray-900/60 border border-white/10 rounded-2xl p-8 shadow-2xl">
                    <h5 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <span>⚡</span> {{ __('Actions') }}
                    </h5>

                    <div class="space-y-4">
                        <button type="submit" name="send_now" value="0" 
                            class="w-full px-6 py-3 bg-gray-700/50 hover:bg-gray-700 text-white font-bold rounded-xl border border-white/10 transition-all duration-300 hover:border-white/30">
                            {{ __('💾 Save as Draft') }}
                        </button>

                        <button type="submit" name="send_now" value="1" onclick="return confirm('Are you sure you want to send this campaign immediately?')"
                            class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-xl shadow-lg hover:shadow-purple-500/30 transform hover:-translate-y-1 transition-all duration-300">
                            {{ __('📤 Save & Send Now') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function toggleCourseSelect() {
        const audience = document.getElementById('target_audience').value;
        const wrapper = document.getElementById('course_select_wrapper');
        
        if (audience === 'course_specific') {
            wrapper.style.display = 'block';
            wrapper.classList.add('animate-fade-in');
        } else {
            wrapper.style.display = 'none';
        }
    }
    
    // Run on load
    document.addEventListener('DOMContentLoaded', toggleCourseSelect);
</script>
@endsection
