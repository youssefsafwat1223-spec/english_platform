<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Arial, sans-serif; line-height: 1.8; color: #333; margin: 0; padding: 0; background: #f3f4f6; direction: rtl; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; padding: 40px 30px; text-align: center; border-radius: 12px 12px 0 0; }
        .header h1 { margin: 0 0 8px; font-size: 28px; }
        .header p { margin: 0; opacity: 0.9; font-size: 16px; }
        .content { background: #ffffff; padding: 35px 30px; border-radius: 0 0 12px 12px; }
        .greeting { font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 15px; }
        .step { display: flex; align-items: flex-start; margin: 16px 0; padding: 16px; background: #f9fafb; border-radius: 10px; border-right: 4px solid #6366f1; }
        .step-number { width: 32px; height: 32px; background: #6366f1; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px; margin-left: 14px; flex-shrink: 0; }
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
            <h1>🎉 {{ __('أهلاً بك في Simple English!') }}</h1>
            <p>{{ __('رحلتك في تعلم اللغة الإنجليزية تبدأ الآن') }}</p>
        </div>
        <div class="content">
            <div class="greeting">{{ __('مرحباً') }} {{ $user->name }}!</div>
            
            <p>{{ __('سعداء بانضمامك لمجتمعنا التعليمي! إليك كيف تبدأ:') }}</p>

            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <div class="step-title">{{ __('تصفح الكورس') }}</div>
                    <div class="step-desc">{{ __('استكشف كورس اللغة الإنجليزية الشامل المصمم لجميع المستويات.') }}</div>
                </div>
            </div>

            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <div class="step-title">{{ __('اشترك وتعلم') }}</div>
                    <div class="step-desc">{{ __('اشترك في الكورس وابدأ التعلم بالسرعة التي تناسبك.') }}</div>
                </div>
            </div>

            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <div class="step-title">{{ __('ربط بوت التليجرام') }}</div>
                    <div class="step-desc">
                        {{ __('اربط حسابك ببوت التليجرام الخاص بنا لتلقي الإشعارات والأسئلة اليومية والشهادات مباشرة على هاتفك.') }}
                        <br>
                        @if(config('services.telegram.bot_username'))
                        <a href="https://t.me/{{ config('services.telegram.bot_username') }}?start={{ $user->id }}" style="color: #6366f1; text-decoration: none; font-weight: bold;">
                            {{ __('اضغط هنا للربط') }} &larr;
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="step">
                <div class="step-number">4</div>
                <div class="step-content">
                    <div class="step-title">{{ __('حل الاختبارات واحصل على الشهادات') }}</div>
                    <div class="step-desc">{{ __('اختبر معلوماتك واحصل على شهادات لإثبات مهاراتك.') }}</div>
                </div>
            </div>

            <p style="text-align: center;">
                <a href="{{ route('student.courses.index') }}" class="button">
                    {{ __('ابدأ التعلم الآن') }} &larr;
                </a>
            </p>

            <p style="color: #6b7280; font-size: 14px;">{{ __('إذا كان لديك أي استفسارات، لا تتردد في التواصل معنا عبر واتساب.') }}</p>

            <p>{{ __('تعلم ممتع!') }}<br><strong>{{ __('فريق Simple English') }}</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Simple English. {{ __('جميع الحقوق محفوظة.') }}
        </div>
    </div>
</body>
</html>