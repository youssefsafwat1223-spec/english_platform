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

        // Resolve absolute logo path so DomPDF can embed it
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
        @page { size: A4 landscape; margin: 0; }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: "DejaVu Sans", sans-serif;
            color: #0f172a;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .page {
            position: relative;
            width: 297mm;
            height: 210mm;
            background: #ffffff;
            overflow: hidden;
        }

        /* ─── Side blue bar (brand stripe) ─── */
        .side-stripe {
            position: absolute;
            top: 0;
            {{ $useRtl ? 'right' : 'left' }}: 0;
            width: 28mm;
            height: 210mm;
            background: #007bb5;
        }

        .side-stripe-accent {
            position: absolute;
            top: 0;
            {{ $useRtl ? 'right' : 'left' }}: 28mm;
            width: 3mm;
            height: 210mm;
            background: #f59e0b;
        }

        /* Vertical brand name on stripe */
        .stripe-text {
            position: absolute;
            top: 50%;
            {{ $useRtl ? 'right' : 'left' }}: 8mm;
            color: #ffffff;
            font-size: 8pt;
            font-weight: bold;
            letter-spacing: 6px;
            text-transform: uppercase;
            transform: rotate({{ $useRtl ? '90deg' : '-90deg' }});
            transform-origin: {{ $useRtl ? 'right top' : 'left top' }};
            white-space: nowrap;
        }

        /* ─── Main content area ─── */
        .content-frame {
            position: absolute;
            top: 18mm;
            {{ $useRtl ? 'left' : 'right' }}: 18mm;
            {{ $useRtl ? 'right' : 'left' }}: 50mm;
            bottom: 18mm;
            border: 1.5px solid #007bb5;
            background: #ffffff;
        }

        .content-inner {
            position: absolute;
            top: 4mm;
            left: 4mm;
            right: 4mm;
            bottom: 4mm;
            border: 0.5px solid #cbd5e1;
            padding: 12mm 14mm;
            text-align: center;
        }

        /* ─── Header (logo + brand) ─── */
        .header {
            text-align: center;
            margin-bottom: 8mm;
        }

        .logo-box {
            display: inline-block;
            width: 22mm;
            height: 22mm;
            border: 2px solid #007bb5;
            background: #ffffff;
            padding: 1.5mm;
            margin-bottom: 3mm;
        }

        .logo-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .brand-name {
            font-size: 14pt;
            font-weight: bold;
            color: #007bb5;
            letter-spacing: 5px;
            text-transform: uppercase;
            margin-top: 1mm;
        }

        .brand-divider {
            display: inline-block;
            width: 35mm;
            height: 2px;
            background: #f59e0b;
            margin: 3mm auto 0;
        }

        /* ─── Title ─── */
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
            letter-spacing: 5px;
            text-transform: uppercase;
            line-height: 1;
            margin-bottom: 1mm;
        }

        .title-sub {
            font-size: 11pt;
            color: #f59e0b;
            font-weight: bold;
            letter-spacing: 6px;
            text-transform: uppercase;
            margin-bottom: 7mm;
        }

        /* ─── Recipient ─── */
        .label {
            font-size: 9pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 3mm;
        }

        .student-name {
            font-size: 26pt;
            font-weight: bold;
            color: #0f172a;
            line-height: 1.15;
            padding-bottom: 2mm;
            margin: 0 14mm 5mm 14mm;
            border-bottom: 2px solid #007bb5;
        }

        .achievement-line {
            font-size: 10.5pt;
            color: #475569;
            line-height: 1.5;
        }

        .course-title {
            display: block;
            font-size: 13pt;
            font-weight: bold;
            color: #007bb5;
            margin-top: 2mm;
        }

        /* ─── Score ─── */
        .score-row {
            margin-top: 6mm;
        }

        .score-pill {
            display: inline-block;
            padding: 2mm 9mm;
            background: #f59e0b;
            color: #ffffff;
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* ─── Footer ─── */
        .footer {
            position: absolute;
            bottom: 5mm;
            left: 14mm;
            right: 14mm;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-cell {
            width: 33.33%;
            vertical-align: bottom;
            text-align: center;
            padding: 0 3mm;
        }

        .sign-label {
            font-size: 7pt;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 2mm;
        }

        .sign-line {
            width: 50mm;
            border-top: 1.2px solid #007bb5;
            margin: 0 auto 1.5mm auto;
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
            margin-top: 0.5mm;
        }

        .qr-wrap {
            display: inline-block;
            padding: 1.5mm;
            background: #ffffff;
            border: 1px solid #007bb5;
        }

        .qr-img {
            width: 16mm;
            height: 16mm;
            display: block;
        }

        .qr-caption {
            font-size: 6pt;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 1.5mm;
        }

        /* ─── Cert ID strip ─── */
        .cert-id-strip {
            position: absolute;
            bottom: 3mm;
            left: 50mm;
            right: 18mm;
            text-align: center;
            font-size: 7pt;
            color: #007bb5;
            letter-spacing: 2px;
        }

        /* ─── Decorative corners ─── */
        .corner {
            position: absolute;
            width: 8mm;
            height: 8mm;
        }
        .corner-tl { top: 0; left: 0; border-top: 2px solid #f59e0b; border-left: 2px solid #f59e0b; }
        .corner-tr { top: 0; right: 0; border-top: 2px solid #f59e0b; border-right: 2px solid #f59e0b; }
        .corner-bl { bottom: 0; left: 0; border-bottom: 2px solid #f59e0b; border-left: 2px solid #f59e0b; }
        .corner-br { bottom: 0; right: 0; border-bottom: 2px solid #f59e0b; border-right: 2px solid #f59e0b; }
    </style>
</head>
<body @if($useRtl) dir="rtl" @endif>
    <div class="page">

        {{-- Brand stripe down the side --}}
        <div class="side-stripe"></div>
        <div class="side-stripe-accent"></div>
        <div class="stripe-text">{{ strtoupper($appName) }} &bull; {{ $useRtl ? 'شهادة معتمدة' : 'OFFICIAL CERTIFICATE' }}</div>

        {{-- Main framed content --}}
        <div class="content-frame">
            <div class="content-inner">

                {{-- Decorative gold corners --}}
                <div class="corner corner-tl"></div>
                <div class="corner corner-tr"></div>
                <div class="corner corner-bl"></div>
                <div class="corner corner-br"></div>

                {{-- Logo + Brand --}}
                <div class="header">
                    @if($logoPath)
                        <div class="logo-box">
                            <img src="{{ $logoPath }}" class="logo-img" alt="Logo">
                        </div>
                    @endif
                    <div class="brand-name">{{ $appName }}</div>
                    <div class="brand-divider"></div>
                </div>

                {{-- Title --}}
                <div class="title-eyebrow">{{ $useRtl ? 'تشهد بأن' : 'Hereby Awards' }}</div>
                <div class="title-main">{{ $useRtl ? 'شهادة' : 'Certificate' }}</div>
                <div class="title-sub">{{ $useRtl ? 'إنجاز ومستوى' : 'of Achievement' }}</div>

                {{-- Recipient --}}
                <div class="label">{{ $useRtl ? 'تُمنح بفخر إلى' : 'Proudly Presented To' }}</div>
                <div class="student-name">{{ $user_name }}</div>

                <div class="achievement-line">
                    {{ $useRtl ? 'لإتمامه/إتمامها بنجاح كورس' : 'For successfully completing the course' }}
                    <span class="course-title">{{ $course_title }}</span>
                </div>

                <div class="score-row">
                    <span class="score-pill">
                        {{ $useRtl ? 'الدرجة النهائية' : 'Final Score' }}
                        : {{ $final_score }}%
                    </span>
                </div>

                {{-- Footer with signatures --}}
                <div class="footer">
                    <table class="footer-table">
                        <tr>
                            <td class="footer-cell">
                                <div class="sign-label">{{ $useRtl ? 'تاريخ الإصدار' : 'Date of Issue' }}</div>
                                <div class="sign-line"></div>
                                <div class="sign-name">{{ $issue_date }}</div>
                            </td>

                            <td class="footer-cell">
                                @if(!empty($qr_code_path))
                                    <div class="qr-wrap">
                                        <img src="{{ $qr_code_path }}" class="qr-img" alt="QR">
                                    </div>
                                    <div class="qr-caption">{{ $useRtl ? 'تحقق من الشهادة' : 'Verify Certificate' }}</div>
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
                </div>
            </div>
        </div>

        {{-- Certificate ID strip --}}
        <div class="cert-id-strip">
            {{ $useRtl ? 'رقم الشهادة' : 'Certificate ID' }}: {{ $certificate_id }}
        </div>
    </div>
</body>
</html>
