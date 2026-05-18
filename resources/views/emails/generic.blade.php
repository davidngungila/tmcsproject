<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Sora', sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; border: 1px solid #eee; border-radius: 20px; overflow: hidden; }
        .header { background: #042f1e; color: white; padding: 40px 20px; text-align: center; }
        .content { padding: 40px 30px; }
        .footer { background: #f9f9f9; padding: 20px; text-align: center; font-size: 12px; color: #888; }
        .btn { display: inline-block; padding: 12px 25px; background: #059669; color: white; text-decoration: none; border-radius: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div style="font-size: 14px; font-weight: 800; margin-bottom: 5px; letter-spacing: 1px;">ST. JOSEPH THE WORKER CHAPLAINCY</div>
            <div style="font-size: 10px; font-weight: 700; margin-bottom: 20px; opacity: 0.8; text-transform: uppercase;">CATHOLIC COMMUNITY OF MOSHI CO-OPERATIVE UNIVERSITY</div>
            <h1 style="margin:0; font-size: 24px;">TMCS SMART SYSTEM</h1>
            <p style="margin:10px 0 0; opacity: 0.8; font-size: 14px;">Official Notification</p>
        </div>
        <div class="content">
            {!! $content !!}
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} St. Joseph the Worker Chaplaincy - TMCS MoCU. All rights reserved.
        </div>
    </div>
</body>
</html>
