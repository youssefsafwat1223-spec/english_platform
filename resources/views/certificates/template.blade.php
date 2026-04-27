<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>Certificate - {{ $certificate_id }}</title>
    @php
        $isArabicLocale = app()->getLocale() === 'ar';
        $hasArabicContent = preg_match('/\p{Arabic}/u', ($user_name ?? '') . ' ' . ($course_title ?? '')) === 1;
        $useRtl = $isArabicLocale || $hasArabicContent;
        $appName = config('app.name', 'Simple English');
        $logoSource = $certificate_logo ?? null;
        $nameLength = mb_strlen((string) $user_name, 'UTF-8');
        $courseLength = mb_strlen((string) $course_title, 'UTF-8');
        $nameFontSize = $nameLength > 34 ? 24 : ($nameLength > 24 ? 28 : 34);
        $courseFontSize = $courseLength > 64 ? 14 : ($courseLength > 40 ? 16 : 19);
    @endphp
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: dejavusans, sans-serif;
            color: #0f172a;
            background: #ffffff;
        }

        .certificate {
            position: relative;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
            background: #fbfdff;
        }

        .side-band {
            position: absolute;
            top: 0;
            bottom: 0;
            {{ $useRtl ? 'right' : 'left' }}: 0;
            width: 33mm;
            background: #007bb5;
        }

        .side-accent {
            position: absolute;
            top: 0;
            bottom: 0;
            {{ $useRtl ? 'right: 33mm' : 'left: 33mm' }};
            width: 4mm;
            background: #f59e0b;
        }

        .side-text {
            position: absolute;
            top: 76mm;
            {{ $useRtl ? 'right' : 'left' }}: 3mm;
            width: 27mm;
            color: #ffffff;
            font-size: 9pt;
            font-weight: 700;
            letter-spacing: 3px;
            line-height: 1.9;
            text-align: center;
            text-transform: uppercase;
        }

        .outer-frame {
            position: absolute;
            top: 13mm;
            bottom: 13mm;
            {{ $useRtl ? 'right: 48mm; left: 13mm;' : 'left: 48mm; right: 13mm;' }}
            border: 2px solid #007bb5;
        }

        .inner-frame {
            position: absolute;
            top: 18mm;
            bottom: 18mm;
            {{ $useRtl ? 'right: 53mm; left: 18mm;' : 'left: 53mm; right: 18mm;' }}
            border: 1px solid #cbd5e1;
        }

        .content {
            position: absolute;
            top: 24mm;
            bottom: 16mm;
            {{ $useRtl ? 'right: 57mm; left: 22mm;' : 'left: 57mm; right: 22mm;' }}
            text-align: center;
        }

        .logo {
            height: 18mm;
            max-width: 42mm;
            object-fit: contain;
            margin-bottom: 4mm;
        }

        .brand {
            font-size: 12pt;
            color: #007bb5;
            font-weight: 700;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 5mm;
        }

        .divider {
            width: 44mm;
            border-top: 2px solid #f59e0b;
            margin: 0 auto 6mm auto;
        }

        .eyebrow {
            color: #64748b;
            font-size: 10pt;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 3mm;
        }

        .title {
            color: #0f172a;
            font-size: 34pt;
            font-weight: 800;
            letter-spacing: 4px;
            text-transform: uppercase;
            line-height: 1.1;
        }

        .subtitle {
            color: #f59e0b;
            font-size: 13pt;
            font-weight: 800;
            letter-spacing: 5px;
            text-transform: uppercase;
            margin-top: 2mm;
            margin-bottom: 9mm;
        }

        .presented {
            color: #64748b;
            font-size: 10pt;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 4mm;
        }

        .student-name {
            display: inline-block;
            min-width: 90mm;
            max-width: 185mm;
            color: #0f172a;
            font-size: {{ $nameFontSize }}pt;
            font-weight: 800;
            line-height: 1.18;
            padding-bottom: 3mm;
            border-bottom: 2px solid #007bb5;
            word-wrap: break-word;
        }

        .achievement {
            margin-top: 7mm;
            color: #475569;
            font-size: 11pt;
            line-height: 1.6;
        }

        .course-title {
            color: #007bb5;
            font-size: {{ $courseFontSize }}pt;
            font-weight: 800;
            line-height: 1.35;
            margin-top: 2mm;
            word-wrap: break-word;
        }

        .score {
            display: inline-block;
            margin-top: 6mm;
            padding: 3mm 13mm;
            background: #f59e0b;
            color: #ffffff;
            font-size: 12pt;
            font-weight: 800;
        }

        .footer {
            position: absolute;
            {{ $useRtl ? 'right: 57mm; left: 22mm;' : 'left: 57mm; right: 22mm;' }}
            bottom: 22mm;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-cell {
            width: 33.333%;
            text-align: center;
            vertical-align: bottom;
            padding: 0 4mm;
        }

        .footer-label {
            color: #94a3b8;
            font-size: 7.5pt;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 2mm;
        }

        .footer-line {
            border-top: 1.2px solid #007bb5;
            height: 1mm;
            margin: 0 auto 2mm auto;
            width: 44mm;
        }

        .footer-value {
            color: #0f172a;
            font-size: 10pt;
            font-weight: 700;
            line-height: 1.35;
        }

        .footer-subvalue {
            color: #64748b;
            font-size: 7pt;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 1mm;
        }

        .qr {
            width: 18mm;
            height: 18mm;
        }

        .cert-id {
            position: absolute;
            bottom: 8mm;
            {{ $useRtl ? 'right: 57mm; left: 22mm;' : 'left: 57mm; right: 22mm;' }}
            color: #007bb5;
            font-size: 8pt;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-align: center;
        }
    </style>
</head>
<body dir="{{ $useRtl ? 'rtl' : 'ltr' }}">
    <div class="certificate">
        <div class="side-band"></div>
        <div class="side-accent"></div>
        <div class="side-text">{{ $appName }}</div>

        <div class="outer-frame"></div>
        <div class="inner-frame"></div>

        <div class="content">
            @if($logoSource)
                <img src="{{ $logoSource }}" class="logo" alt="Logo">
            @endif

            <div class="brand">{{ $appName }}</div>
            <div class="divider"></div>

            <div class="eyebrow">{{ $useRtl ? 'تمنح هذه الشهادة إلى' : 'This Certificate Is Awarded To' }}</div>
            <div class="title">{{ $useRtl ? 'شهادة حضور' : 'Certificate' }}</div>
            <div class="subtitle">{{ $useRtl ? 'إتمام البرنامج التدريبي' : 'Of Attendance' }}</div>

            <div class="presented">{{ $useRtl ? 'بكل تقدير إلى' : 'Proudly Presented To' }}</div>
            <div class="student-name">{{ $user_name }}</div>

            <div class="achievement">
                {{ $useRtl ? 'وذلك بعد إتمامه بنجاح' : 'For successfully completing' }}
            </div>
            <div class="course-title">{{ $course_title }}</div>

            <div class="score">{{ $useRtl ? 'الدرجة النهائية' : 'Final Score' }}: {{ $final_score }}%</div>
        </div>

        <div class="footer">
            <table class="footer-table">
                <tr>
                    <td class="footer-cell">
                        <div class="footer-label">{{ $useRtl ? 'تاريخ الإصدار' : 'Date of Issue' }}</div>
                        <div class="footer-line"></div>
                        <div class="footer-value">{{ $issue_date }}</div>
                    </td>
                    <td class="footer-cell">
                        @if(!empty($qr_code_path))
                            <img src="{{ $qr_code_path }}" class="qr" alt="QR Code">
                        @endif
                    </td>
                    <td class="footer-cell">
                        <div class="footer-label">{{ $useRtl ? 'التوقيع المعتمد' : 'Authorized Signature' }}</div>
                        <div class="footer-line"></div>
                        <div class="footer-value">{{ $signatory_name }}</div>
                        <div class="footer-subvalue">{{ $signatory_title }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="cert-id">
            {{ $useRtl ? 'رقم الشهادة' : 'Certificate ID' }}: {{ $certificate_id }}
        </div>
    </div>
</body>
</html>
