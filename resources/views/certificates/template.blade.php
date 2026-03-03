<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate - {{ $certificate_id }}</title>
    <style>
        @page {
            size: 297mm 210mm landscape;
            margin: 0;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: "DejaVu Sans", sans-serif;
            width: 297mm;
            height: 210mm;
            color: #1e293b;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .certificate {
            width: 297mm;
            height: 210mm;
            position: relative;
            overflow: hidden;
            background: #fffef9;
        }

        /* ─── Background Texture ─── */
        .bg-base {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 15% 20%, rgba(212,175,55,0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 85% 80%, rgba(212,175,55,0.05) 0%, transparent 50%),
                linear-gradient(180deg, #fffef9 0%, #fdfaf0 100%);
            z-index: 0;
        }

        /* ─── Gold Border Frame ─── */
        .frame-outer {
            position: absolute;
            top: 12mm;
            left: 12mm;
            right: 12mm;
            bottom: 12mm;
            border: 2.5px solid #c9a534;
            z-index: 2;
        }
        .frame-inner {
            position: absolute;
            top: 15mm;
            left: 15mm;
            right: 15mm;
            bottom: 15mm;
            border: 0.8px solid rgba(201,165,52,0.35);
            z-index: 2;
        }

        /* ─── Corner Ornaments ─── */
        .corner {
            position: absolute;
            width: 20mm;
            height: 20mm;
            z-index: 3;
        }
        .corner-tl { top: 10mm; left: 10mm; border-top: 4px solid #c9a534; border-left: 4px solid #c9a534; }
        .corner-tr { top: 10mm; right: 10mm; border-top: 4px solid #c9a534; border-right: 4px solid #c9a534; }
        .corner-bl { bottom: 10mm; left: 10mm; border-bottom: 4px solid #c9a534; border-left: 4px solid #c9a534; }
        .corner-br { bottom: 10mm; right: 10mm; border-bottom: 4px solid #c9a534; border-right: 4px solid #c9a534; }

        /* ─── Content ─── */
        .content {
            position: relative;
            z-index: 10;
            width: 100%;
            height: 100%;
            text-align: center;
            padding: 22mm 35mm 20mm 35mm;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ─── Logo ─── */
        .logo-section {
            margin-bottom: 3mm;
        }
        .logo-img {
            height: 14mm;
            margin-bottom: 1mm;
        }
        .logo-text {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 5px;
            color: #c9a534;
        }

        /* ─── Divider ─── */
        .divider {
            width: 50mm;
            height: 1px;
            background: linear-gradient(90deg, transparent, #c9a534, transparent);
            margin: 4mm auto;
        }

        /* ─── Title ─── */
        .cert-title {
            font-size: 38pt;
            font-weight: bold;
            letter-spacing: 5px;
            color: #1e293b;
            text-transform: uppercase;
            line-height: 1;
            margin-bottom: 1mm;
        }
        .cert-subtitle {
            font-size: 12pt;
            text-transform: uppercase;
            letter-spacing: 8px;
            color: #c9a534;
            font-weight: bold;
            margin-bottom: 6mm;
        }

        /* ─── Recipient ─── */
        .presented-to {
            font-size: 9pt;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 3mm;
        }

        .student-name {
            font-size: 30pt;
            font-weight: bold;
            color: #1e293b;
            border-bottom: 2.5px solid #c9a534;
            display: inline-block;
            padding: 0 15mm 2mm 15mm;
            margin-bottom: 4mm;
            line-height: 1.2;
        }

        /* ─── Course ─── */
        .course-info {
            color: #64748b;
            font-size: 10pt;
            line-height: 1.6;
            margin-bottom: 3mm;
        }
        .course-title {
            color: #1e293b;
            font-weight: bold;
            font-size: 14pt;
            display: block;
            margin-top: 1mm;
        }

        /* ─── Score Badge ─── */
        .score-badge {
            display: inline-block;
            background: linear-gradient(135deg, #c9a534 0%, #b8941e 100%);
            color: #ffffff;
            font-weight: bold;
            font-size: 10pt;
            padding: 2mm 8mm;
            border-radius: 12px;
            letter-spacing: 0.5px;
        }

        /* ─── Footer ─── */
        .footer {
            position: absolute;
            bottom: 22mm;
            left: 35mm;
            right: 35mm;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-td {
            vertical-align: bottom;
            width: 33.33%;
            padding: 0 5mm;
        }
        .footer-left { text-align: center; }
        .footer-center { text-align: center; }
        .footer-right { text-align: center; }

        .sign-label {
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #94a3b8;
            margin-bottom: 2mm;
        }
        .sign-line {
            width: 55mm;
            border-top: 1.5px solid #c9a534;
            margin: 0 auto 2mm auto;
        }
        .sign-name {
            font-weight: bold;
            font-size: 11pt;
            color: #1e293b;
        }
        .sign-title-text {
            font-size: 8pt;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ─── QR Code ─── */
        .qr-container {
            width: 18mm;
            height: 18mm;
            border: 1px solid rgba(201,165,52,0.3);
            border-radius: 3mm;
            display: inline-block;
            padding: 1.5mm;
            background: #fff;
        }
        .qr-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* ─── Seal ─── */
        .seal {
            position: absolute;
            bottom: 38mm;
            right: 42mm;
            width: 26mm;
            height: 26mm;
            border: 2px solid rgba(201,165,52,0.35);
            border-radius: 50%;
            z-index: 5;
        }
        .seal-inner {
            position: absolute;
            top: 2mm;
            left: 2mm;
            right: 2mm;
            bottom: 2mm;
            border: 1px solid rgba(201,165,52,0.25);
            border-radius: 50%;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .seal-text {
            font-size: 6pt;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #c9a534;
            font-weight: bold;
        }
        .seal-icon {
            font-size: 14pt;
            color: #c9a534;
            display: block;
            line-height: 1;
        }

        /* ─── Certificate ID ─── */
        .cert-id {
            position: absolute;
            bottom: 14mm;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 7pt;
            color: #cbd5e1;
            letter-spacing: 1.5px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="bg-base"></div>

        <!-- Border Frame -->
        <div class="frame-outer"></div>
        <div class="frame-inner"></div>

        <!-- Corner Ornaments -->
        <div class="corner corner-tl"></div>
        <div class="corner corner-tr"></div>
        <div class="corner corner-bl"></div>
        <div class="corner corner-br"></div>

        <div class="content">
            <!-- Logo -->
            <div class="logo-section">
                @if(!empty($certificate_logo))
                    <img src="{{ $certificate_logo }}" class="logo-img" alt="Logo"><br>
                @endif
                <div class="logo-text">{{ config('app.name', 'English Platform') }}</div>
            </div>

            <div class="divider"></div>

            <div class="cert-title">{{ __('Certificate') }}</div>
            <div class="cert-subtitle">{{ __('of Achievement') }}</div>

            <div class="presented-to">{{ __('This certificate is proudly presented to') }}</div>

            <div class="student-name">{{ $user_name }}</div>

            <div class="course-info">
                {{ __('For the successful completion of') }}
                <span class="course-title">{{ $course_title }}</span>
            </div>

            <div class="score-badge">&#9733; {{ __('Score') }}: {{ $final_score }}%</div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <table class="footer-table">
                <tr>
                    <td class="footer-td footer-left">
                        <div class="sign-label">{{ __('Date of Issue') }}</div>
                        <div class="sign-line"></div>
                        <div class="sign-name">{{ $issue_date }}</div>
                    </td>

                    <td class="footer-td footer-center">
                        @if(!empty($qr_code_path))
                        <div class="qr-container">
                            <img src="{{ $qr_code_path }}" class="qr-img" alt="QR">
                        </div>
                        @endif
                    </td>

                    <td class="footer-td footer-right">
                        <div class="sign-label">{{ __('Authorized Signature') }}</div>
                        <div class="sign-line"></div>
                        <div class="sign-name">{{ $signatory_name }}</div>
                        <div class="sign-title-text">{{ $signatory_title }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Seal -->
        <div class="seal">
            <div class="seal-inner">
                <span class="seal-icon">&#10022;</span>
                <span class="seal-text">{{ __('Verified') }}</span>
            </div>
        </div>

        <div class="cert-id">{{ __('ID') }}: {{ $certificate_id }} &bull; {{ __('Verify at') }} {{ route('certificates.verify', $certificate_id) }}</div>
    </div>
</body>
</html>
