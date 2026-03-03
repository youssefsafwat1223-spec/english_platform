<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background: #f3f4f6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; padding: 40px 30px; text-align: center; border-radius: 12px 12px 0 0; }
        .header h1 { margin: 0 0 8px; font-size: 28px; }
        .header p { margin: 0; opacity: 0.9; font-size: 16px; }
        .content { background: #ffffff; padding: 35px 30px; border-radius: 0 0 12px 12px; }
        .greeting { font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px; }
        .step { display: flex; align-items: flex-start; margin: 16px 0; padding: 16px; background: #f9fafb; border-radius: 10px; border-left: 4px solid #6366f1; }
        .step-number { width: 32px; height: 32px; background: #6366f1; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px; margin-right: 14px; flex-shrink: 0; }
        .step-content { flex: 1; }
        .step-title { font-weight: bold; color: #1f2937; margin-bottom: 4px; }
        .step-desc { color: #6b7280; font-size: 14px; }
        .button { display: inline-block; padding: 14px 36px; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; text-decoration: none; border-radius: 8px; margin: 24px 0; font-weight: bold; font-size: 16px; }
        .footer { text-align: center; padding: 20px; color: #9ca3af; font-size: 13px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Welcome to {{ config('app.name') }}!</h1>
            <p>Your English learning journey starts now</p>
        </div>
        <div class="content">
            <div class="greeting">Hello {{ $user->name }}!</div>
            
            <p>We're thrilled to have you join our learning community! Here's how to get started:</p>

            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <div class="step-title">Browse Courses</div>
                    <div class="step-desc">Explore our collection of English courses designed for all levels.</div>
                </div>
            </div>

            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <div class="step-title">Enroll & Learn</div>
                    <div class="step-desc">Pick a course that matches your level and start learning at your own pace.</div>
                </div>
            </div>

            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <div class="step-title">Connect with Telegram Bot</div>
                    <div class="step-desc">
                        Link your account to our Telegram bot to receive notifications, daily questions, and certificates directly on your phone.
                        <br>
                        @if(config('services.telegram.bot_username'))
                        <a href="https://t.me/{{ config('services.telegram.bot_username') }}?start={{ $user->id }}" style="color: #6366f1; text-decoration: none; font-weight: bold;">
                            Click here to connect →
                        </a>
                        @else
                        <em>(Bot username not configured)</em>
                        @endif
                    </div>
                </div>
            </div>

            <div class="step">
                <div class="step-number">4</div>
                <div class="step-content">
                    <div class="step-title">Take Quizzes & Earn Certificates</div>
                    <div class="step-desc">Test your knowledge and earn certificates to showcase your skills.</div>
                </div>
            </div>

            <p style="text-align: center;">
                <a href="{{ route('student.courses.index') }}" class="button">
                    Start Learning Now →
                </a>
            </p>

            <p style="color: #6b7280; font-size: 14px;">If you have any questions, feel free to reach out through our forum or contact us directly.</p>

            <p>Happy learning!<br><strong>The {{ config('app.name') }} Team</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>