<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Certificate Verification') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="dark" style="background: var(--color-bg); color: var(--color-text);">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-2xl w-full">
            <div class="glass-card overflow-hidden gradient-border text-center">
                <div class="glass-card-body py-12">
                    <div class="text-8xl mb-6">✅</div>
                    <h1 class="text-3xl font-extrabold text-emerald-500 mb-4">{{ __('Certificate Verified!') }}</h1>

                    <div class="rounded-xl p-6 mb-6 text-left" style="background: var(--color-surface-hover);">
                        <div class="grid grid-cols-2 gap-4">
                            @php
                                $fields = [
                                    ['Student Name', $user->name],
                                    ['Course', $course->title],
                                    ['Final Score', $certificate->final_score . '%'],
                                    ['Grade', $certificate->grade],
                                    ['Issue Date', $certificate->issued_at->format('F d, Y')],
                                    ['Certificate ID', $certificate->certificate_id],
                                ];
                            @endphp
                            @foreach($fields as $f)
                                <div>
                                    <div class="text-xs font-medium mb-1" style="color: var(--color-text-muted);">{{ $f[0] }}</div>
                                    <div class="font-bold {{ $f[0] === 'Certificate ID' ? 'font-mono text-primary-500' : '' }}" style="{{ $f[0] !== 'Certificate ID' ? 'color: var(--color-text);' : '' }}">{{ $f[1] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <p class="mb-6" style="color: var(--color-text-muted);">This certificate was issued by {{ config('app.name') }} and is valid.</p>
                    <a href="{{ route('home') }}" class="btn-primary ripple-btn">{{ __('Visit Platform') }}</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>