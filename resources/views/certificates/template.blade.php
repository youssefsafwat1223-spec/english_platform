<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate - {{ $certificate_id }}</title>
    @php
        $isArabicLocale = app()->getLocale() === 'ar';
        $hasArabic = preg_match('/\p{Arabic}/u', ($user_name ?? '') . ' ' . ($course_title ?? '')) === 1;
        $useRtl = $isArabicLocale || $hasArabic;
        $appName = config('app.name', 'Simple English');

        $logoPath = null;
        $logoCandidates = [
            $certificate_logo ?? null,
            public_path('logo.jpg'),
            public_path('favicon.jpg'),
        ];
        foreach ($logoCandidates as $candidate) {
            if ($candidate && file_exists($candidate)) {
                $logoPath = $candidate;
                break;
            }
        }
    @endphp
    <style>
        @page {
            sheet-size: A4-L;
            margin: 0;
        }

        body {
            font-family: dejavusans, sans-serif;
            color: #0f172a;
            margin: 0;
            padding: 0;
        }

        /* Outer page = single big div with explicit dimensions */
        .page {
            width: 297mm;
            height: 210mm;
            position: relative;
        }

        /* ─── Brand stripe ─── */
        .stripe-bg {
            position: absolute;
            top: 0;
            {{ $useRtl ? 'right' : 'left' }}: 0;
            width: 30mm;
            height: 210mm;
            background-color: #007bb5;
        }

        .stripe-accent {
            position: absolute;
            top: 0;
            {{ $useRtl ? 'right: 30mm' : 'left: 30mm' }};
            width: 4mm;
            height: 210mm;
            background-color: #f59e0b;
        }

        .stripe-label {
            position: absolute;
            top: 80mm;
            {{ $useRtl ? 'right: 4mm' : 'left: 4mm' }};
            width: 22mm;
            color: #ffffff;
            font-size: 9pt;
            font-weight: bold;
            letter-spacing: 4px;
            text-align: center;
            text-transform: uppercase;
            line-height: 2;
        }

        /* ─── Outer gold frame ─── */
        .frame-outer {
            position: absolute;
            top: 14mm;
            {{ $useRtl ? 'left' : 'right' }}: 14mm;
            {{ $useRtl ? 'right' : 'left' }}: 50mm;
            bottom: 14mm;
            border: 2px solid #007bb5;
        }

        .frame-inner {
            position: absolute;
            top: 18mm;
            {{ $useRtl ? 'left' : 'right' }}: 18mm;
            {{ $useRtl ? 'right' : 'left' }}: 54mm;
            bottom: 18mm;
            border: 1px solid #cbd5e1;
        }

        /* ─── Content layers (each at fixed top offset) ─── */
        .layer { position: absolute; left: 50mm; right: 18mm; text-align: center; }

        .layer-rtl { position: absolute; right: 50mm; left: 18mm; text-align: center; }

        .l-logo  { top: 26mm; }
        .l-brand { top: 50mm; }
        .l-divider { top: 60mm; }
        .l-eyebrow { top: 67mm; }
        .l-title { top: 73mm; }
        .l-sub { top: 92mm; }
        .l-label { top: 102mm; }
        .l-name { top: 110mm; }
        .l-line { top: 124mm; }
        .l-course { top: 132mm; }
        .l-score { top: 142mm; }
        .l-footer { top: 162mm; }
        .l-cert-id { top: 188mm; }

        .logo-img { width: 18mm; height: 18mm; }

        .brand-name {
            font-size: 13pt;
            font-weight: bold;
            color: #007bb5;
            letter-spacing: 5px;
            text-transform: uppercase;
        }

        .divider {
            display: inline-block;
            width: 35mm;
            height: 2px;
            background-color: #f59e0b;
        }

        .eyebrow {
            font-size: 9pt;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        .title-main {
            font-size: 28pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 6px;
            text-transform: uppercase;
        }

        .title-sub {
            font-size: 11pt;
            color: #f59e0b;
            font-weight: bold;
            letter-spacing: 6px;
            text-transform: uppercase;
        }

        .label-small {
            font-size: 9pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        .student-name {
            font-size: 24pt;
            font-weight: bold;
            color: #0f172a;
        }

        .name-underline {
            margin: 2mm 30mm 0 30mm;
            border-bottom: 2px solid #007bb5;
        }

        .achievement {
            font-size: 11pt;
            color: #475569;
        }

        .course-title {
            font-size: 14pt;
            font-weight: bold;
            color: #007bb5;
        }

        .score-pill {
            display: inline-block;
            padding: 2.5mm 12mm;
            background-color: #f59e0b;
            color: #ffffff;
            font-size: 12pt;
            font-weight: bold;
        }

        /* Footer table — kept simple and compact */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-cell {
            width: 33%;
            text-align: center;
            padding: 0 4mm;
            vertical-align: top;
        }

        .sign-label {
            font-size: 7.5pt;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 1mm;
        }

        .sign-line {
            border-bottom: 1.2px solid #007bb5;
            margin: 0 5mm 1mm 5mm;
            font-size: 0;
        }

        .sign-name {
            font-size: 10pt;
            font-weight: bold;
            color: #0f172a;
        }

        .sign-position {
            font-size: 7pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 0.5mm;
        }

        .qr-img { width: 16mm; height: 16mm; }

        .cert-id {
            font-size: 8pt;
            color: #007bb5;
            letter-spacing: 2px;
        }
    </style>
</head>
<body @if($useRtl) dir="rtl" @endif>
    <div class="page">

        {{-- Brand stripe --}}
        <div class="stripe-bg"></div>
        <div class="stripe-accent"></div>
        <div class="stripe-label">{{ strtoupper($appName) }}</div>

        {{-- Frames --}}
        <div class="frame-outer"></div>
        <div class="frame-inner"></div>

        {{-- Logo --}}
        @if($logoPath)
            <div class="{{ $useRtl ? 'layer-rtl' : 'layer' }} l-logo">
                <img src="{{ $logoPath }}" class="logo-img" alt="Logo">
            </div>
        @endif

        {{-- Brand name --}}
        <div class="{{ $useRtl ? 'layer-rtl' : 'layer' }} l-brand">
            <span class="brand-name">{{ $appName }}</span>
        </div>

        {{-- Gold divider --}}
        <div class="{{ $useRtl ? 'layer-rtl' : 'layer' }} l-divider">
            <span class="divider"></span>
        </div>

        {{-- Eyebrow --}}
        <div class="{{ $useRtl ? 'layer-rtl' : 'layer' }} l-eyebrow">
            <span class="eyebrow">{{ $useRtl ? 'تشهد بأن' : 'Hereby Awards' }}</span>
        </div>

        {{-- Title --}}
        <div class="{{ $useRtl ? 'layer-rtl' : 'layer' }} l-title">
            <span class="title-main">{{ $useRtl ? 'شهادة' : 'CERTIFICATE' }}</span>
        </div>

        {{-- Subtitle --}}
        <div class="{{ $useRtl ? 'layer-rtl' : 'layer' }} l-sub">
            <span class="title-sub">{{ $useRtl ? 'إنجاز ومستوى' : 'OF ACHIEVEMENT' }}</span>
        </div>

        {{-- Recipient label --}}
        <div class="{{ $useRtl ? 'layer-rtl' : 'layer' }} l-label">
            <span class="label-small">{{ $useRtl ? 'تُمنح بفخر إلى' : 'Proudly Presented To' }}</span>
        </div>

        {{-- Student Name --}}
        <div class="{{ $useRtl ? 'layer-rtl' : 'layer' }} l-name">
            <span class="student-name">{{ $user_name }}</span>
            <div class="name-underline"></div>
        </div>

        {{-- Achievement Line --}}
        <div class="{{ $useRtl ? 'layer-rtl' : 'layer' }} l-line">
            <span class="achievement">{{ $useRtl ? 'لإتمامه/إتمامها بنجاح كورس' : 'For successfully completing the course' }}</span>
        </div>

        {{-- Course Title --}}
        <div class="{{ $useRtl ? 'layer-rtl' : 'layer' }} l-course">
            <span class="course-title">{{ $course_title }}</span>
        </div>

        {{-- Score --}}
        <div class="{{ $useRtl ? 'layer-rtl' : 'layer' }} l-score">
            <span class="score-pill">{{ $useRtl ? 'الدرجة النهائية' : 'Final Score' }}: {{ $final_score }}%</span>
        </div>

        {{-- Footer --}}
        <div class="{{ $useRtl ? 'layer-rtl' : 'layer' }} l-footer">
            <table class="footer-table">
                <tr>
                    <td class="footer-cell">
                        <div class="sign-label">{{ $useRtl ? 'تاريخ الإصدار' : 'Date of Issue' }}</div>
                        <div class="sign-line">&nbsp;</div>
                        <div class="sign-name">{{ $issue_date }}</div>
                    </td>
                    <td class="footer-cell">
                        @if(!empty($qr_code_path))
                            <img src="{{ $qr_code_path }}" class="qr-img" alt="QR">
                        @endif
                    </td>
                    <td class="footer-cell">
                        <div class="sign-label">{{ $useRtl ? 'التوقيع المعتمد' : 'Authorized Signature' }}</div>
                        <div class="sign-line">&nbsp;</div>
                        <div class="sign-name">{{ $signatory_name }}</div>
                        <div class="sign-position">{{ $signatory_title }}</div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Cert ID --}}
        <div class="{{ $useRtl ? 'layer-rtl' : 'layer' }} l-cert-id">
            <span class="cert-id">{{ $useRtl ? 'رقم الشهادة' : 'Certificate ID' }}: {{ $certificate_id }}</span>
        </div>

    </div>
</body>
</html>
