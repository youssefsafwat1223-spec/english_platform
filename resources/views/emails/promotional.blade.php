<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background: #f3f4f6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%); color: white; padding: 40px 30px; text-align: center; border-radius: 12px 12px 0 0; }
        .header h1 { margin: 0 0 8px; font-size: 24px; }
        .content { background: #ffffff; padding: 35px 30px; border-radius: 0 0 12px 12px; }
        .greeting { font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px; }
        .email-body { font-size: 16px; color: #374151; line-height: 1.8; }
        .email-body p { margin: 12px 0; }
        .button { display: inline-block; padding: 14px 36px; background: linear-gradient(135deg, #6366f1, #ec4899); color: white; text-decoration: none; border-radius: 8px; margin: 24px 0; font-weight: bold; font-size: 16px; }
        .footer { text-align: center; padding: 20px; color: #9ca3af; font-size: 13px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📢 {{ $emailSubject }}</h1>
        </div>
        <div class="content">
            <div class="greeting">Hello {{ $recipientName }}!</div>

            <div class="email-body">
                {!! nl2br(e($emailBody)) !!}
            </div>

            @if($ctaText && $ctaUrl)
                <p style="text-align: center;">
                    <a href="{{ $ctaUrl }}" class="button">
                        {{ $ctaText }} →
                    </a>
                </p>
            @endif

            <p>Best regards,<br><strong>The {{ config('app.name') }} Team</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
