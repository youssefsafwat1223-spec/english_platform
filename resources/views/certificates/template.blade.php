<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>Certificate - {{ $certificate_id }}</title>
    @php
        $isArabicLocale = app()->getLocale() === 'ar';
        $hasArabicContent = preg_match('/\p{Arabic}/u', ($user_name ?? '') . ' ' . ($course_title ?? '')) === 1;
        $useRtl = $isArabicLocale || $hasArabicContent;
        $appName = config('app.name', 'Simple English');
        $nameLength = mb_strlen((string) $user_name, 'UTF-8');
        $courseLength = mb_strlen((string) $course_title, 'UTF-8');
        $nameFontSize = $nameLength > 34 ? 22 : ($nameLength > 24 ? 26 : 31);
        $courseFontSize = $courseLength > 64 ? 13 : ($courseLength > 40 ? 15 : 18);
    @endphp
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: dejavusans, sans-serif;
            color: #0f172a;
            background: #ffffff;
        }

        .page {
            width: 267mm;
            height: 180mm;
            margin: 14mm 15mm;
            padding: 0;
            border: 2px solid #007bb5;
            background: #fbfdff;
        }

        .inner {
            height: 168mm;
            margin: 5mm;
            padding: 7mm 14mm 5mm 14mm;
            border: 1px solid #cbd5e1;
            text-align: center;
        }

        .top-rule {
            height: 5mm;
            background: #007bb5;
            border-bottom: 2mm solid #f59e0b;
            margin: -7mm -14mm 6mm -14mm;
        }

        .logo {
            width: 20mm;
            margin-bottom: 2.5mm;
        }

        .brand {
            color: #007bb5;
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 4mm;
        }

        .divider {
            width: 42mm;
            border-top: 2px solid #f59e0b;
            margin: 0 auto 5mm auto;
            height: 1px;
        }

        .eyebrow {
            color: #64748b;
            font-size: 9pt;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 2mm;
        }

        .title {
            color: #0f172a;
            font-size: 30pt;
            font-weight: bold;
            letter-spacing: 2px;
            line-height: 1.1;
            text-transform: uppercase;
        }

        .subtitle {
            color: #f59e0b;
            font-size: 12pt;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 2mm;
            margin-bottom: 6mm;
        }

        .presented {
            color: #64748b;
            font-size: 9pt;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 3mm;
        }

        .student-name {
            display: inline-block;
            min-width: 86mm;
            max-width: 180mm;
            color: #0f172a;
            font-size: {{ $nameFontSize }}pt;
            font-weight: bold;
            line-height: 1.2;
            padding-bottom: 2.5mm;
            border-bottom: 2px solid #007bb5;
        }

        .achievement {
            margin-top: 5mm;
            color: #475569;
            font-size: 10.5pt;
            line-height: 1.45;
        }

        .course-title {
            color: #007bb5;
            font-size: {{ $courseFontSize }}pt;
            font-weight: bold;
            line-height: 1.35;
            margin-top: 1.5mm;
        }

        .score {
            display: inline-block;
            margin-top: 4mm;
            padding: 2.3mm 11mm;
            background: #f59e0b;
            color: #ffffff;
            font-size: 11pt;
            font-weight: bold;
        }

        .footer {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8mm;
        }

        .footer td {
            width: 33.333%;
            text-align: center;
            vertical-align: bottom;
            padding: 0 3mm;
        }

        .footer-label {
            color: #94a3b8;
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 1.2mm;
        }

        .footer-line {
            border-top: 1.2px solid #007bb5;
            width: 40mm;
            height: 1mm;
            margin: 0 auto 1.3mm auto;
        }

        .footer-value {
            color: #0f172a;
            font-size: 9pt;
            font-weight: bold;
            line-height: 1.35;
        }

        .footer-subvalue {
            color: #64748b;
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 0.8mm;
        }

        .qr {
            width: 16mm;
        }

        .cert-id {
            color: #007bb5;
            font-size: 7.3pt;
            font-weight: bold;
            letter-spacing: 0.8px;
            text-align: center;
            margin-top: 3.5mm;
        }
    </style>
</head>
<body dir="{{ $useRtl ? 'rtl' : 'ltr' }}">
    <div class="page">
        <div class="inner">
            <div class="top-rule"></div>

            @if(!empty($certificate_logo))
                <img src="{{ $certificate_logo }}" class="logo" alt="Logo">
            @endif

            <div class="brand">{{ $appName }}</div>
            <div class="divider"></div>

            <div class="eyebrow">
                {!! $useRtl ? '&#1578;&#1605;&#1606;&#1581; &#1607;&#1584;&#1607; &#1575;&#1604;&#1588;&#1607;&#1575;&#1583;&#1577; &#1573;&#1604;&#1609;' : 'This Certificate Is Awarded To' !!}
            </div>
            <div class="title">
                {!! $useRtl ? '&#1588;&#1607;&#1575;&#1583;&#1577; &#1581;&#1590;&#1608;&#1585;' : 'Certificate' !!}
            </div>
            <div class="subtitle">
                {!! $useRtl ? '&#1573;&#1578;&#1605;&#1575;&#1605; &#1575;&#1604;&#1576;&#1585;&#1606;&#1575;&#1605;&#1580; &#1575;&#1604;&#1578;&#1583;&#1585;&#1610;&#1576;&#1610;' : 'Of Attendance' !!}
            </div>

            <div class="presented">
                {!! $useRtl ? '&#1576;&#1603;&#1604; &#1578;&#1602;&#1583;&#1610;&#1585; &#1573;&#1604;&#1609;' : 'Proudly Presented To' !!}
            </div>
            <div class="student-name">{{ $user_name }}</div>

            <div class="achievement">
                {!! $useRtl ? '&#1608;&#1584;&#1604;&#1603; &#1576;&#1593;&#1583; &#1573;&#1578;&#1605;&#1575;&#1605;&#1607; &#1576;&#1606;&#1580;&#1575;&#1581;' : 'For successfully completing' !!}
            </div>
            <div class="course-title">{{ $course_title }}</div>

            <div class="score">
                {!! $useRtl ? '&#1575;&#1604;&#1583;&#1585;&#1580;&#1577; &#1575;&#1604;&#1606;&#1607;&#1575;&#1574;&#1610;&#1577;' : 'Final Score' !!}: {{ $final_score }}%
            </div>

            <table class="footer">
                <tr>
                    <td>
                        <div class="footer-label">{!! $useRtl ? '&#1578;&#1575;&#1585;&#1610;&#1582; &#1575;&#1604;&#1573;&#1589;&#1583;&#1575;&#1585;' : 'Date of Issue' !!}</div>
                        <div class="footer-line"></div>
                        <div class="footer-value">{{ $issue_date }}</div>
                    </td>
                    <td>
                        @if(!empty($qr_code_path))
                            <img src="{{ $qr_code_path }}" class="qr" alt="QR Code">
                        @endif
                    </td>
                    <td>
                        <div class="footer-label">{!! $useRtl ? '&#1575;&#1604;&#1578;&#1608;&#1602;&#1610;&#1593; &#1575;&#1604;&#1605;&#1593;&#1578;&#1605;&#1583;' : 'Authorized Signature' !!}</div>
                        <div class="footer-line"></div>
                        <div class="footer-value">{{ $signatory_name }}</div>
                        <div class="footer-subvalue">{{ $signatory_title }}</div>
                    </td>
                </tr>
            </table>

            <div class="cert-id">
                {!! $useRtl ? '&#1585;&#1602;&#1605; &#1575;&#1604;&#1588;&#1607;&#1575;&#1583;&#1577;' : 'Certificate ID' !!}: {{ $certificate_id }}
            </div>
        </div>
    </div>
</body>
</html>
