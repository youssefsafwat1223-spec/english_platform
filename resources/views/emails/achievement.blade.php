<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background: #f3f4f6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 40px 30px; text-align: center; border-radius: 12px 12px 0 0; }
        .header h1 { margin: 0 0 8px; font-size: 28px; }
        .header p { margin: 0; opacity: 0.9; font-size: 16px; }
        .content { background: #ffffff; padding: 35px 30px; border-radius: 0 0 12px 12px; }
        .greeting { font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px; }
        .achievement-box { background: linear-gradient(135deg, #f0fdf4, #ecfdf5); border: 2px solid #86efac; border-radius: 12px; padding: 24px; margin: 20px 0; text-align: center; }
        .achievement-icon { font-size: 48px; margin-bottom: 12px; }
        .achievement-title { font-size: 20px; font-weight: bold; color: #166534; margin-bottom: 8px; }
        .achievement-detail { color: #15803d; font-size: 16px; }
        .button { display: inline-block; padding: 14px 36px; background: linear-gradient(135deg, #10b981, #059669); color: white; text-decoration: none; border-radius: 8px; margin: 24px 0; font-weight: bold; font-size: 16px; }
        .footer { text-align: center; padding: 20px; color: #9ca3af; font-size: 13px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if($achievementType === 'course_completed')
                <h1>🎉 Course Completed!</h1>
                <p>You've reached an amazing milestone</p>
            @elseif($achievementType === 'high_score')
                <h1>🌟 Outstanding Score!</h1>
                <p>Your hard work is paying off</p>
            @elseif($achievementType === 'certificate_earned')
                <h1>🎓 Certificate Earned!</h1>
                <p>Your achievement is now official</p>
            @else
                <h1>🏆 Congratulations!</h1>
                <p>You've achieved something great</p>
            @endif
        </div>
        <div class="content">
            <div class="greeting">Well done, {{ $user->name }}!</div>

            <div class="achievement-box">
                @if($achievementType === 'course_completed')
                    <div class="achievement-icon">🎉</div>
                    <div class="achievement-title">Course Completed!</div>
                    <div class="achievement-detail">
                        <strong>{{ $achievementData['course_title'] ?? '' }}</strong>
                    </div>
                @elseif($achievementType === 'high_score')
                    <div class="achievement-icon">🌟</div>
                    <div class="achievement-title">Score: {{ $achievementData['score'] ?? '' }}%</div>
                    <div class="achievement-detail">
                        Quiz: <strong>{{ $achievementData['quiz_title'] ?? '' }}</strong><br>
                        Course: {{ $achievementData['course_title'] ?? '' }}
                    </div>
                @elseif($achievementType === 'certificate_earned')
                    <div class="achievement-icon">🎓</div>
                    <div class="achievement-title">Certificate Ready!</div>
                    <div class="achievement-detail">
                        <strong>{{ $achievementData['course_title'] ?? '' }}</strong><br>
                        Final Score: {{ $achievementData['score'] ?? '' }}% &mdash; Grade: {{ $achievementData['grade'] ?? '' }}
                    </div>
                @endif
            </div>

            <p style="text-align: center;">
                <a href="{{ $achievementData['action_url'] ?? route('student.dashboard') }}" class="button">
                    @if($achievementType === 'certificate_earned')
                        View Certificate →
                    @else
                        View Dashboard →
                    @endif
                </a>
            </p>

            <p>Keep up the great work! Every step brings you closer to fluency.</p>

            <p>Proudly,<br><strong>The {{ config('app.name') }} Team</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
