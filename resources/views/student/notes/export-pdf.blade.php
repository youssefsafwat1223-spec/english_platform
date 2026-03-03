<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Notes — {{ config('app.name') }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 40px; color: #1a1a2e; }
        h1 { font-size: 24px; color: #6366f1; margin-bottom: 24px; border-bottom: 2px solid #6366f1; padding-bottom: 8px; }
        .note { margin-bottom: 24px; padding: 16px; border: 1px solid #e2e8f0; border-radius: 8px; page-break-inside: avoid; }
        .note-title { font-size: 16px; font-weight: bold; margin-bottom: 4px; color: #1e293b; }
        .note-meta { font-size: 12px; color: #64748b; margin-bottom: 8px; }
        .note-content { font-size: 14px; line-height: 1.6; color: #334155; }
        .footer { text-align: center; font-size: 11px; color: #94a3b8; margin-top: 40px; border-top: 1px solid #e2e8f0; padding-top: 16px; }
    </style>
</head>
<body>
    <h1>📝 My Notes</h1>

    @foreach($notes as $note)
        <div class="note">
            <div class="note-title">{{ $note->title ?? 'Note' }}</div>
            <div class="note-meta">
                {{ $note->lesson->course->title ?? '' }} · {{ $note->lesson->title ?? '' }} · {{ $note->updated_at->format('M d, Y') }}
            </div>
            <div class="note-content">{!! nl2br(e($note->content)) !!}</div>
        </div>
    @endforeach

    <div class="footer">
        Exported from {{ config('app.name') }} on {{ now()->format('F d, Y') }}
    </div>
</body>
</html>
