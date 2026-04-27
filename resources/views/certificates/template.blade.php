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
        @page { sheet-size: A4-L; margin: 0; }

        body {
            font-family: dejavusans, sans-serif;
            color: #0f172a;
            margin: 0;
            padding: 0;
        }

        .layout {
            width: 100%;
            border-collapse: collapse;
        }

        .layout td {
            vertical-align: top;
            padding: 0;
        }

        /* Brand stripe column */
        .stripe {
            width: 30mm;
            background-color: #007bb5;
            color: #ffffff;
            text-align: center;
            vertical-align: middle;
        }

        .stripe-accent {
            width: 3mm;
            background-color: #f59e0b;
        }

        .stripe-text {
            font-size: 9pt;
            font-weight: bold;
            letter-spacing: 4px;
            text-transform: uppercase;
            line-height: 2;
        }

        /* Main content cell */
        .main {
            padding: 12mm 14mm 0 14mm;
            text-align: center;
        }

        .main-frame {
            border: 1.5px solid #007bb5;
            padding: 8mm;
        }

        .main-inner {
            border: 0.5px solid #cbd5e1;
            padding: 10mm 8mm;
        }

        /* Header */
        .logo-img {
            width: 22mm;
            height: 22mm;
        }

        .brand-name {
            font-size: 14pt;
            font-weight: bold;
            color: #007bb5;
            letter-spacing: 5px;
            text-transform: uppercase;
            margin-top: 2mm;
        }

        .brand-divider {
            display: block;
            width: 35mm;
            height: 2px;
            background-color: #f59e0b;
            margin: 3mm auto;
        }

        /* Title */
        .title-eyebrow {
            font-size: 9pt;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 2mm;
        }

        .title-main {
            font-size: 30pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 5px;
            text-transform: uppercase;
            margin: 2mm 0 1mm 0;
        }

        .title-sub {
            font-size: 11pt;
            color: #f59e0b;
            font-weight: bold;
            letter-spacing: 6px;
            text-transform: uppercase;
            margin-bottom: 6mm;
        }

        /* Recipient */
        .label-small {
            font-size: 9pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 3mm;
        }

        .student-name {
            font-size: 24pt;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 2px solid #007bb5;
            padding-bottom: 2mm;
            margin: 0 14mm 5mm 14mm;
        }

        .achievement-line {
            font-size: 10.5pt;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 3mm;
        }

        .course-title {
            font-size: 13pt;
            font-weight: bold;
            color: #007bb5;
            margin-top: 2mm;
        }

        /* Score */
        .score-pill {
            display: inline-block;
            padding: 2mm 9mm;
            background-color: #f59e0b;
            color: #ffffff;
            font-size: 11pt;
            font-weight: bold;
            margin-top: 4mm;
            margin-bottom: 6mm;
        }

        /* Footer signatures */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4mm;
        }

        .footer-cell {
            width: 33%;
            text-align: center;
            padding: 0 3mm;
            vertical-align: bottom;
        }

        .sign-label {
            font-size: 7pt;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 2mm;
        }

        .sign-line {
            border-bottom: 1.2px solid #007bb5;
            margin: 0 8mm 1.5mm 8mm;
            height: 1px;
        }

        .sign-name {
            font-size: 10pt;
            font-weight: bold;
            color: #0f172a;
        }

        .sign-position {
            font-size: 7.5pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 1mm;
        }

        .qr-img {
            width: 16mm;
            height: 16mm;
        }

        .qr-caption {
            font-size: 6.5pt;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 1.5mm;
        }

        /* Cert ID strip */
        .cert-id {
            text-align: center;
            font-size: 7.5pt;
            color: #007bb5;
            letter-spacing: 2px;
            margin-top: 4mm;
        }
    </style>
</head>
<body>

@if($useRtl)
    <table class="layout" dir="rtl"><tr>
        <td class="stripe">
            <div class="stripe-text">
                {{ strtoupper($appName) }}<br>&bull;<br>{{ $useRtl ? 'شهادة معتمدة' : 'OFFICIAL' }}
            </div>
        </td>
        <td class="stripe-accent">&nbsp;</td>
        <td class="main">
@else
    <table class="layout"><tr>
        <td class="main">
@endif

            <div class="main-frame">
                <div class="main-inner">

                    {{-- Header: Logo + Brand --}}
                    @if($logoPath)
                        <img src="{{ $logoPath }}" class="logo-img" alt="Logo"><br>
                    @endif
                    <div class="brand-name">{{ $appName }}</div>
                    <span class="brand-divider"></span>

                    {{-- Title --}}
                    <div class="title-eyebrow">{{ $useRtl ? 'تشهد بأن' : 'Hereby Awards' }}</div>
                    <div class="title-main">{{ $useRtl ? 'شهادة' : 'Certificate' }}</div>
                    <div class="title-sub">{{ $useRtl ? 'إنجاز ومستوى' : 'of Achievement' }}</div>

                    {{-- Recipient --}}
                    <div class="label-small">{{ $useRtl ? 'تُمنح بفخر إلى' : 'Proudly Presented To' }}</div>
                    <div class="student-name">{{ $user_name }}</div>

                    <div class="achievement-line">
                        {{ $useRtl ? 'لإتمامه/إتمامها بنجاح كورس' : 'For successfully completing the course' }}<br>
                        <span class="course-title">{{ $course_title }}</span>
                    </div>

                    <div class="score-pill">
                        {{ $useRtl ? 'الدرجة النهائية' : 'Final Score' }}: {{ $final_score }}%
                    </div>

                    {{-- Footer signatures --}}
                    <table class="footer-table">
                        <tr>
                            <td class="footer-cell">
                                <div class="sign-label">{{ $useRtl ? 'تاريخ الإصدار' : 'Date of Issue' }}</div>
                                <div class="sign-line"></div>
                                <div class="sign-name">{{ $issue_date }}</div>
                            </td>

                            <td class="footer-cell">
                                @if(!empty($qr_code_path))
                                    <img src="{{ $qr_code_path }}" class="qr-img" alt="QR"><br>
                                    <div class="qr-caption">{{ $useRtl ? 'تحقق من الشهادة' : 'Verify' }}</div>
                                @endif
                            </td>

                            <td class="footer-cell">
                                <div class="sign-label">{{ $useRtl ? 'التوقيع المعتمد' : 'Authorized Signature' }}</div>
                                <div class="sign-line"></div>
                                <div class="sign-name">{{ $signatory_name }}</div>
                                <div class="sign-position">{{ $signatory_title }}</div>
                            </td>
                        </tr>
                    </table>

                    <div class="cert-id">
                        {{ $useRtl ? 'رقم الشهادة' : 'Certificate ID' }}: {{ $certificate_id }}
                    </div>

                </div>
            </div>

        </td>

@if(!$useRtl)
        <td class="stripe-accent">&nbsp;</td>
        <td class="stripe">
            <div class="stripe-text">
                {{ strtoupper($appName) }}<br>&bull;<br>OFFICIAL
            </div>
        </td>
@endif

    </tr></table>
</body>
</html>
