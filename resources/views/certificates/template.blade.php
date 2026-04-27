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

        /* Full-page wrapper using table — most reliable for mPDF */
        .layout {
            width: 297mm;
            height: 210mm;
            border-collapse: collapse;
        }

        .layout > tr > td { padding: 0; }

        /* Brand stripe (vertical column) */
        .stripe {
            width: 32mm;
            background-color: #007bb5;
            color: #ffffff;
            text-align: center;
            vertical-align: middle;
        }

        .stripe-accent {
            width: 4mm;
            background-color: #f59e0b;
        }

        .stripe-text {
            font-size: 10pt;
            font-weight: bold;
            letter-spacing: 5px;
            text-transform: uppercase;
            line-height: 1.6;
        }

        /* Main body cell — fills the rest */
        .main {
            text-align: center;
            vertical-align: middle;
            padding: 14mm 18mm;
        }

        .frame {
            border: 2px solid #007bb5;
            padding: 6mm;
            height: 178mm;
        }

        .inner {
            border: 1px solid #cbd5e1;
            padding: 8mm 10mm;
            height: 162mm;
        }

        /* Logo */
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
            margin: 3mm auto 6mm auto;
        }

        /* Title block */
        .title-eyebrow {
            font-size: 9pt;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 2mm;
        }

        .title-main {
            font-size: 32pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 6px;
            text-transform: uppercase;
            margin: 1mm 0;
        }

        .title-sub {
            font-size: 11pt;
            color: #f59e0b;
            font-weight: bold;
            letter-spacing: 6px;
            text-transform: uppercase;
            margin-bottom: 8mm;
        }

        /* Recipient */
        .label-small {
            font-size: 9pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 3mm;
        }

        .student-name-wrap {
            margin: 0 18mm 5mm 18mm;
            border-bottom: 2px solid #007bb5;
            padding-bottom: 2mm;
        }

        .student-name {
            font-size: 26pt;
            font-weight: bold;
            color: #0f172a;
        }

        .achievement-line {
            font-size: 11pt;
            color: #475569;
            line-height: 1.5;
            margin-top: 4mm;
            margin-bottom: 2mm;
        }

        .course-title {
            font-size: 14pt;
            font-weight: bold;
            color: #007bb5;
            margin-top: 2mm;
        }

        /* Score pill */
        .score-pill {
            display: inline-block;
            padding: 2.5mm 10mm;
            background-color: #f59e0b;
            color: #ffffff;
            font-size: 11pt;
            font-weight: bold;
            margin-top: 5mm;
        }

        /* Footer */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10mm;
        }

        .footer-cell {
            width: 33%;
            text-align: center;
            padding: 0 4mm;
            vertical-align: bottom;
        }

        .sign-label {
            font-size: 7.5pt;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 2mm;
        }

        .sign-line {
            border-bottom: 1.2px solid #007bb5;
            margin: 0 8mm 2mm 8mm;
            height: 1px;
            font-size: 0;
        }

        .sign-name {
            font-size: 11pt;
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

        .qr-img { width: 18mm; height: 18mm; }

        .qr-caption {
            font-size: 7pt;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 1.5mm;
        }

        /* Cert ID */
        .cert-id {
            text-align: center;
            font-size: 8pt;
            color: #007bb5;
            letter-spacing: 2px;
            margin-top: 5mm;
        }
    </style>
</head>
<body>

<table class="layout" @if($useRtl) dir="rtl" @endif><tr>

@if($useRtl)
    <td class="stripe">
        <div class="stripe-text">{{ strtoupper($appName) }}<br>&bull;<br>{{ $useRtl ? 'شهادة معتمدة' : 'OFFICIAL' }}</div>
    </td>
    <td class="stripe-accent">&nbsp;</td>
@endif

    <td class="main">
        <div class="frame">
            <div class="inner">

                @if($logoPath)
                    <img src="{{ $logoPath }}" class="logo-img" alt="Logo"><br>
                @endif
                <div class="brand-name">{{ $appName }}</div>
                <span class="brand-divider"></span>

                <div class="title-eyebrow">{{ $useRtl ? 'تشهد بأن' : 'Hereby Awards' }}</div>
                <div class="title-main">{{ $useRtl ? 'شهادة' : 'Certificate' }}</div>
                <div class="title-sub">{{ $useRtl ? 'إنجاز ومستوى' : 'of Achievement' }}</div>

                <div class="label-small">{{ $useRtl ? 'تُمنح بفخر إلى' : 'Proudly Presented To' }}</div>
                <div class="student-name-wrap">
                    <span class="student-name">{{ $user_name }}</span>
                </div>

                <div class="achievement-line">
                    {{ $useRtl ? 'لإتمامه/إتمامها بنجاح كورس' : 'For successfully completing the course' }}<br>
                    <span class="course-title">{{ $course_title }}</span>
                </div>

                <div class="score-pill">
                    {{ $useRtl ? 'الدرجة النهائية' : 'Final Score' }}: {{ $final_score }}%
                </div>

                <table class="footer-table">
                    <tr>
                        <td class="footer-cell">
                            <div class="sign-label">{{ $useRtl ? 'تاريخ الإصدار' : 'Date of Issue' }}</div>
                            <div class="sign-line">&nbsp;</div>
                            <div class="sign-name">{{ $issue_date }}</div>
                        </td>

                        <td class="footer-cell">
                            @if(!empty($qr_code_path))
                                <img src="{{ $qr_code_path }}" class="qr-img" alt="QR"><br>
                                <div class="qr-caption">{{ $useRtl ? 'تحقق' : 'Verify' }}</div>
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

                <div class="cert-id">
                    {{ $useRtl ? 'رقم الشهادة' : 'Certificate ID' }}: {{ $certificate_id }}
                </div>

            </div>
        </div>
    </td>

@if(!$useRtl)
    <td class="stripe-accent">&nbsp;</td>
    <td class="stripe">
        <div class="stripe-text">{{ strtoupper($appName) }}<br>&bull;<br>OFFICIAL</div>
    </td>
@endif

</tr></table>

</body>
</html>
