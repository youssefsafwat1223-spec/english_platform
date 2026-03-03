@extends('layouts.admin')

@section('title', __('Email Campaigns'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8" data-aos="fade-down">
        <div>
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-400 to-pink-600">
                {{ __('📧 Email Campaigns') }}
            </h1>
            <p class="text-gray-400 mt-2">{{ __('Manage and send promotional emails to your students') }}</p>
        </div>
        <a href="{{ route('admin.email-campaigns.create') }}" 
           class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-xl shadow-lg hover:shadow-purple-500/30 transform hover:-translate-y-1 transition-all duration-300">
            {{ __('+ New Campaign') }}
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/50 text-green-400 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 backdrop-blur-sm" role="alert">
            <span>✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 backdrop-blur-sm" role="alert">
            <span>⚠️</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="backdrop-blur-xl bg-gray-900/60 border border-white/10 rounded-2xl overflow-hidden shadow-2xl" data-aos="fade-up">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-800/50 text-gray-400 uppercase text-xs tracking-wider">
                        <th class="px-6 py-4 font-semibold">{{ __('Subject') }}</th>
                        <th class="px-6 py-4 font-semibold">{{ __('Audience') }}</th>
                        <th class="px-6 py-4 font-semibold">{{ __('Recipients') }}</th>
                        <th class="px-6 py-4 font-semibold">{{ __('Status') }}</th>
                        <th class="px-6 py-4 font-semibold">{{ __('Sent At') }}</th>
                        <th class="px-6 py-4 font-semibold text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($campaigns as $campaign)
                        <tr class="hover:bg-white/5 transition-colors duration-200 group">
                            <td class="px-6 py-4">
                                <div class="font-bold text-white group-hover:text-purple-400 transition-colors">{{ $campaign->subject }}</div>
                                <div class="text-sm text-gray-500 mt-1 truncate max-w-xs">{{ Str::limit($campaign->body, 60) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $audienceLabels = [
                                        'all' => ['🌍 All Students', 'bg-blue-500/20 text-blue-400 border-blue-500/30'],
                                        'active' => ['✅ Active', 'bg-green-500/20 text-green-400 border-green-500/30'],
                                        'inactive' => ['⏸️ Inactive', 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30'],
                                        'course_specific' => ['📚 ' . ($campaign->targetCourse->title ?? 'Course'), 'bg-purple-500/20 text-purple-400 border-purple-500/30'],
                                    ];
                                    $label = $audienceLabels[$campaign->target_audience] ?? ['Unknown', 'bg-gray-500/20 text-gray-400'];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $label[1] }}">
                                    {{ $label[0] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-300">
                                <span class="font-bold text-white">{{ $campaign->sent_count }}</span> 
                                <span class="text-gray-600">/ {{ $campaign->recipients_count }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusStyles = [
                                        'draft' => 'bg-gray-700 text-gray-300',
                                        'sending' => 'bg-blue-600 text-white animate-pulse',
                                        'sent' => 'bg-green-600 text-white shadow-lg shadow-green-500/20',
                                        'failed' => 'bg-red-600 text-white',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusStyles[$campaign->status] ?? 'bg-gray-700' }}">
                                    {{ ucfirst($campaign->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $campaign->sent_at ? $campaign->sent_at->format('M d, Y H:i') : '—' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-3">
                                    @if($campaign->status === 'draft')
                                        <form action="{{ route('admin.email-campaigns.send', $campaign) }}" method="POST" onsubmit="return confirm('Send this campaign to all targeted students?')">
                                            @csrf
                                            <button type="submit" class="p-2 text-green-400 hover:text-green-300 hover:bg-green-500/10 rounded-lg transition-colors" title="Send Now">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                                </svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.email-campaigns.destroy', $campaign) }}" method="POST" onsubmit="return confirm('Delete this draft?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-colors" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-16">
                                <div class="text-6xl mb-4 opacity-50">📬</div>
                                <p class="text-xl font-bold text-white mb-2">{{ __('No campaigns yet') }}</p>
                                <p class="text-gray-500 mb-6">{{ __('Create your first email campaign to engage your students.') }}</p>
                                <a href="{{ route('admin.email-campaigns.create') }}" class="px-6 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-lg transition-colors">
                                    {{ __('Create Campaign') }}
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($campaigns->hasPages())
        <div class="mt-8">
            {{ $campaigns->links() }}
        </div>
    @endif
</div>
@endsection
