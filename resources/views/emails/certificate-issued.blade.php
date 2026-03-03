<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #10b981;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .stats {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 Congratulations!</h1>
        </div>
        <div class="content">
            <h2>Hello {{ $certificate->user->name }}!</h2>
            
            <p>Congratulations on completing <strong>{{ $certificate->course->title }}</strong>!</p>
            
            <p>Your certificate is now ready for download.</p>

            <div class="stats">
                <h3>Your Results:</h3>
                <ul>
                    <li><strong>Final Score:</strong> {{ $certificate->final_score }}%</li>
                    <li><strong>Grade:</strong> {{ $certificate->grade }}</li>
                    <li><strong>Performance:</strong> {{ $certificate->performance_level }}</li>
                    <li><strong>Certificate ID:</strong> {{ $certificate->certificate_id }}</li>
                </ul>
            </div>

            <p style="text-align: center;">
                <a href="{{ route('student.certificates.show', $certificate) }}" class="button">
                    Download Certificate
                </a>
            </p>

            <p>You can also verify your certificate at any time using the certificate ID.</p>

            <p>Share your achievement on LinkedIn to showcase your skills!</p>

            <p>Keep up the great work!<br>The {{ config('app.name') }} Team</p>
        </div>
    </div>
</body>
</html>