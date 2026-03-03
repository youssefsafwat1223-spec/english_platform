@extends('layouts.admin')
@section('title', __('Certificates'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Issued Certificates') }}</span></h1>
                <p class="mt-2" style="color: var(--color-text-muted);">{{ __('Track certificates and verification metrics') }}</p>
            </div>
            <a href="{{ route('admin.certificates.settings') }}" class="btn-secondary">{{ __('Settings') }}</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
            @foreach([['Total Issued', $stats['total_issued'], 'from-primary-500 to-accent-500'], ['This Month', $stats['issued_this_month'], 'from-emerald-500 to-green-400'], ['Avg Score', number_format($stats['average_score'] ?? 0, 1).'%', 'from-blue-500 to-cyan-400'], ['Downloads', $stats['total_downloads'], 'from-orange-500 to-amber-400'], ['Views', $stats['total_views'], 'from-purple-500 to-pink-400']] as [$label, $val, $grad])
            <div class="glass-card overflow-hidden" data-aos="fade-up">
                <div class="glass-card-body text-center">
                    <div class="text-2xl font-black bg-gradient-to-r {{ $grad }} bg-clip-text text-transparent">{{ $val }}</div>
                    <div class="text-xs font-bold mt-1" style="color: var(--color-text-muted);">{{ $label }}</div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="glass-card mb-6" data-aos="fade-up">
            <div class="glass-card-body">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="text" name="search" placeholder="{{ __('Search certificate ID or student name') }}" class="input-glass" value="{{ request('search') }}">
                    <select name="course_id" class="input-glass">
                        <option value="">{{ __('All Courses') }}</option>
                        @foreach($courses as $course)<option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>@endforeach
                    </select>
                    <button type="submit" class="btn-primary ripple-btn">{{ __('Filter') }}</button>
                </form>
            </div>
        </div>
        <div class="glass-card overflow-hidden" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="table-glass">
                    <thead><tr><th>{{ __('Certificate ID') }}</th><th>{{ __('Student') }}</th><th>{{ __('Course') }}</th><th>{{ __('Score') }}</th><th>{{ __('Issued Date') }}</th><th>{{ __('Downloads') }}</th><th>{{ __('Actions') }}</th></tr></thead>
                    <tbody>
                        @forelse($certificates as $certificate)
                        <tr>
                            <td><span class="font-mono text-xs font-bold text-primary-500">{{ $certificate->certificate_id }}</span></td>
                            <td>{{ $certificate->user->name }}</td>
                            <td>{{ $certificate->course->title }}</td>
                            <td>
                                <span class="font-bold text-emerald-500">{{ $certificate->final_score }}%</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-emerald-500/10 text-emerald-500 text-[10px] font-bold ml-1">{{ $certificate->grade }}</span>
                            </td>
                            <td>{{ $certificate->issued_at->format('M d, Y') }}</td>
                            <td>{{ $certificate->download_count }}</td>
                            <td><a href="{{ route('admin.certificates.show', $certificate) }}" class="text-primary-500 text-sm font-bold hover:underline">{{ __('View') }}</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-12" style="color: var(--color-text-muted);">{{ __('No certificates issued yet') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="glass-card-footer">{{ $certificates->links() }}</div>
        </div>
    </div>
</div>
@endsection
