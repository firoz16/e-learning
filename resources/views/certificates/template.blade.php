<!DOCTYPE html>
<html>
<head>
    <style>
        body { text-align: center; font-family: DejaVu Sans, sans-serif; }
        .title { font-size: 30px; margin-top: 50px; }
        .name { font-size: 24px; margin: 40px 0; }
        .course { font-size: 20px; }
        .footer { margin-top: 100px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="title">Certificate of Completion</div>
    <div class="name"><strong>{{ $user->name }}</strong></div>
    <div class="course">has successfully completed the course:</div>
    <h2>{{ $course->title }}</h2>
    <div class="footer">
        <p>Date: {{ now()->format('F d, Y') }}</p>
        <p>eLearning Platform</p>
    </div>
</body>
</html>
