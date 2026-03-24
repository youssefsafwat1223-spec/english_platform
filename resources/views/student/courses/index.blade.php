@extends('layouts.app')

@section('title', __('Courses') . ' — ' . config('app.name'))

@section('content')
<div class="py-12 relative min-h-screen z-10">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        {{-- ─── HEADER SECTION ─── --}}
        {{-- ─── HEADER SECTION ─── --}}
        <div class="relative glass-card overflow-hidden rounded-[2rem] p-8 mb-6" data-aos="fade-down">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-500/10 via-transparent to-primary-500/10 opacity-50"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-500 dark:text-violet-400 text-sm font-bold mb-4 shadow-sm">
                        <span>🧭</span> {{ __('Explore Catalog') }}
                    </div>
                    <h1 class="text-3xl md:text-5xl font-extrabold mb-2 text-slate-900 dark:text-white tracking-tight">
                        {{ __('Discover New') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-500 to-primary-500">{{ __('Courses') }}</span>
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400 font-medium max-w-2xl mt-4 text-lg">
                        {{ __('Find the perfect course to level up your skills. Browse our expanding catalog of high-quality educational content.') }}
                    </p>
                </div>
                
                <div class="shrink-0 flex items-center justify-center w-32 h-32 relative group perspective-1000 hidden md:flex">
                    <div class="absolute inset-0 bg-gradient-to-tr from-primary-500 to-accent-500 rounded-full blur-2xl opacity-40 group-hover:opacity-60 animate-pulse transition-opacity"></div>
                    <div class="relative w-full h-full glass-card rounded-full border border-white/20 dark:border-white/10 flex flex-col items-center justify-center p-4 backdrop-blur-xl transform transition-transform duration-500 group-hover:rotate-y-12">
                        <span class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-br from-primary-600 to-accent-500">{{ $courses->total() }}</span>
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mt-1">{{ __('Available') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── FILTERS ─── --}}
        <form method="GET" action="{{ route('student.courses.index') }}" class="glass-card" data-aos="fade-up" data-aos-delay="100">
            <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-4 gap-6">
                {{-- Search --}}
                <div class="md:col-span-2">
                    <label for="q" class="block text-sm font-bold mb-2 text-slate-700 dark:text-slate-300">{{ __('Search') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input id="q" name="q" type="text" class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-3 pl-12 pr-4 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all shadow-inner" value="{{ request('q') }}" placeholder="{{ __('Course title or keyword...') }}">
                    </div>
                </div>

                {{-- Sort --}}
                <div>
                    <label for="sort" class="block text-sm font-bold mb-2 text-slate-700 dark:text-slate-300">{{ __('Sort By') }}</label>
                    <div class="relative">
                        <select id="sort" name="sort" class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 pr-10 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-inner appearance-none relative">
                            <option value="" class="text-slate-900">{{ __('Default') }}</option>
                            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }} class="text-slate-900">{{ __('Most Popular') }}</option>
                            <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }} class="text-slate-900">{{ __('Top Rated') }}</option>
                            <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }} class="text-slate-900">{{ __('Price: Low > High') }}</option>
                            <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }} class="text-slate-900">{{ __('Price: High > Low') }}</option>
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }} class="text-slate-900">{{ __('Newest') }}</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Price Range --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="price_min" class="block text-sm font-bold mb-2 text-slate-700 dark:text-slate-300">{{ __('Min Price') }}</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500 font-bold">{{ __('ر.س') }}</span>
                            <input id="price_min" name="price_min" type="number" min="0" step="0.01" class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-3 pl-8 pr-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-inner" value="{{ request('price_min') }}" placeholder="0">
                        </div>
                    </div>
                    <div>
                        <label for="price_max" class="block text-sm font-bold mb-2 text-slate-700 dark:text-slate-300">{{ __('Max Price') }}</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500 font-bold">{{ __('ر.س') }}</span>
                            <input id="price_max" name="price_max" type="number" min="0" step="0.01" class="w-full bg-slate-50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl py-3 pl-8 pr-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-inner" value="{{ request('price_max') }}" placeholder="100">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-200 dark:border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-50/50 dark:bg-black/10 rounded-b-2xl">
                <button type="submit" class="btn-primary ripple-btn px-8 py-2.5 rounded-xl shadow-lg shadow-primary-500/25 w-full sm:w-auto font-bold flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    {{ __('Apply Filters') }}
                </button>
                @if(request()->query())
                    <a href="{{ route('student.courses.index') }}" class="btn-ghost btn-sm text-slate-500 hover:text-rose-500 font-bold flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        {{ __('Clear Filters') }}
                    </a>
                @endif
            </div>
        </form>

        {{-- ─── COURSE GRID ─── --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 p-1">
            @forelse($courses as $course)
                <div class="glass-card relative overflow-hidden group hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 flex flex-col h-full border-t border-slate-200 dark:border-white/10" data-aos="fade-up" data-aos-delay="{{ min($loop->index * 100, 500) }}">
                    
                    {{-- Thumbnail --}}
                    <div class="relative h-56 overflow-hidden shrink-0">
                        @if($course->thumbnail)
                            <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-out">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-80 group-hover:opacity-100 transition-opacity"></div>
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-primary-600 to-accent-600 flex items-center justify-center relative overflow-hidden transform group-hover:scale-105 transition-transform duration-700">
                                <div class="absolute inset-0 bg-white/10 opacity-20" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
                                <span class="text-white text-3xl font-black relative z-10 opacity-30">{{ substr($course->title, 0, 2) }}</span>
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-transparent to-transparent"></div>
                            </div>
                        @endif
                        
                        {{-- Badges --}}
                        <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-white/90 dark:bg-black/50 backdrop-blur-md text-xs font-bold text-slate-900 dark:text-white shadow-sm border border-white/20">
                                📚 {{ $course->lessons_count }}
                            </span>
                        </div>
                        
                        @if($course->average_rating)
                        <div class="absolute top-4 right-4 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900/80 backdrop-blur-md text-xs font-bold text-white shadow-sm border border-white/10">
                            <span class="text-amber-400">★</span> {{ number_format($course->average_rating, 1) }}
                        </div>
                        @endif
                    </div>

                    {{-- Body --}}
                    <div class="p-6 md:p-8 flex flex-col flex-1 relative z-10 bg-white dark:bg-transparent">
                        <h3 class="text-xl font-bold mb-3 text-slate-900 dark:text-white group-hover:text-primary-500 transition-colors leading-snug line-clamp-2">
                            {{ $course->title }}
                        </h3>
                        <p class="text-sm mb-6 text-slate-600 dark:text-slate-400 line-clamp-3 leading-relaxed flex-1">
                            {{ $course->short_description ?: Str::limit($course->description, 120) }}
                        </p>

                        <div class="flex items-center justify-between text-xs font-medium text-slate-500 dark:text-slate-400 mb-6 bg-slate-50 dark:bg-white/5 py-2 px-3 rounded-lg border border-slate-200 dark:border-white/5">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354l-5.646 2.353A2 2 0 005 8.562V14a10 10 0 007 9.878 10 10 0 007-9.878V8.561a2 2 0 00-1.354-1.854L12 4.354z"/></svg>
                                {{ $course->students_count }} {{ __('Students') }}
                            </span>
                            @if($course->estimated_duration_weeks)
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $course->estimated_duration_weeks }} {{ __('Weeks') }}
                                </span>
                            @endif
                        </div>

                        {{-- Footer / CTA --}}
                        <div class="flex items-center justify-between pt-4 mt-auto border-t border-slate-200 dark:border-white/10">
                            <span class="text-3xl font-black text-slate-900 dark:text-white filter drop-shadow-sm">
                                @if($course->price == 0)
                                    <span class="text-emerald-500">{{ __('Free') }}</span>
                                @else
                                    {{ number_format($course->price, 0) }} <span class="text-primary-500 text-lg">{{ __('ر.س') }}</span>
                                @endif
                            </span>
                            <a href="{{ route('student.courses.show', $course) }}" class="btn-primary ripple-btn px-6 py-2.5 rounded-xl shadow-lg shadow-primary-500/25 font-bold group-hover:scale-105 transition-transform flex items-center gap-2">
                                {{ __('Details') }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3">
                    <div class="glass-card text-center py-24 px-6 relative overflow-hidden" data-aos="fade-up">
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-500/5 to-accent-500/5"></div>
                        <div class="w-24 h-24 rounded-full bg-white dark:bg-slate-800 flex items-center justify-center mx-auto mb-6 text-5xl shadow-xl border border-slate-200 dark:border-white/10 relative z-10">
                            🔍
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-3 relative z-10">{{ __('No courses found') }}</h3>
                        <p class="text-slate-500 dark:text-slate-400 mb-8 max-w-md mx-auto relative z-10 font-medium">{{ __('We couldn\'t find any courses matching your search criteria. Try adjusting your filters.') }}</p>
                        <a href="{{ route('student.courses.index') }}" class="btn-primary ripple-btn px-8 py-3 rounded-xl shadow-lg shadow-primary-500/30 relative z-10 font-bold">
                            {{ __('Clear All Filters') }}
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- ─── PAGINATION ─── --}}
        @if($courses->hasPages())
        <div class="glass-card p-6 flex justify-center mt-10">
            {{ $courses->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
