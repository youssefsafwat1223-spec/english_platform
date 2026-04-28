@extends('layouts.admin')
@section('title', __('Certificate Settings'))
@section('content')
<div class="py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[400px] bg-gradient-to-b from-primary-500/8 to-transparent pointer-events-none z-0"></div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-extrabold"><span class="text-gradient">{{ __('Certificate Settings') }}</span></h1>
            <div class="mt-2 flex items-center gap-4">
                <a href="{{ route('admin.certificates.index') }}" class="text-primary-500 font-bold text-sm hover:underline inline-block">{{ __('Back to Certificates') }}</a>
                <a href="{{ route('admin.certificates.preview') }}" target="_blank" rel="noopener" class="text-sm font-bold hover:underline" style="color: var(--color-text-muted);">{{ __('Open PDF Preview') }}</a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 font-medium">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.certificates.update-settings') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            <div class="glass-card overflow-hidden" data-aos="fade-up">
                <div class="glass-card-body space-y-6">
                    @foreach([['Certificate Title Prefix', 'certificate_prefix', 'text', $settings['certificate_prefix'] ?? 'CERT'], ['Signatory Name', 'signatory_name', 'text', $settings['signatory_name'] ?? 'Platform Director'], ['Signatory Title', 'signatory_title', 'text', $settings['signatory_title'] ?? 'Director'], ['Certificate Seal or Logo Path / URL', 'certificate_logo', 'text', $settings['certificate_logo'] ?? '']] as [$label, $name, $type, $val])
                    <div>
                        <label class="block text-sm font-bold mb-2" style="color: var(--color-text);">{{ $label }}</label>
                        <input type="{{ $type }}" name="{{ $name }}" class="input-glass" value="{{ old($name, $val) }}">
                        @if($name === 'certificate_logo')
                            <p class="mt-2 text-xs" style="color: var(--color-text-muted);">
                                {{ __('You can use a full URL or a public local path such as logo.jpg.') }}
                            </p>
                        @endif
                    </div>
                    @endforeach
                    <div class="flex items-center">
                        <input type="hidden" name="enable_qr_code" value="0">
                        <input type="checkbox" name="enable_qr_code" value="1" {{ old('enable_qr_code', $settings['enable_qr_code'] ?? true) ? 'checked' : '' }} class="w-4 h-4 text-primary-500 rounded" style="border-color: var(--color-border);">
                        <label class="ml-2 text-sm" style="color: var(--color-text);">{{ __('Enable QR code for verification') }}</label>
                    </div>
                </div>
                <div class="glass-card-footer">
                    <button type="submit" class="btn-primary ripple-btn" :class="{ 'opacity-50': loading }" :disabled="loading">
                        <span x-show="!loading">{{ __('Save Settings') }}</span><span x-show="loading">{{ __('Saving...') }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
