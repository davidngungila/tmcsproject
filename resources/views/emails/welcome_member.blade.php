<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; }
        .header { background-color: #059669; color: #ffffff; padding: 40px 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { padding: 30px; background-color: #ffffff; }
        .footer { background-color: #f9fafb; color: #6b7280; padding: 20px; text-align: center; font-size: 12px; }
        .button { display: inline-block; padding: 14px 28px; background-color: #059669; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .welcome-card { margin: 25px 0; padding: 25px; border: 2px dashed #d1d5db; border-radius: 8px; text-align: center; }
        .credentials { background-color: #f3f4f6; padding: 15px; border-radius: 6px; margin-top: 15px; text-align: left; }
        .credentials b { color: #059669; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to TMCS!</h1>
        </div>
        <div class="content">
            <p>Dear {{ $member->full_name }},</p>
            <p>Welcome to the <b>Tanzania Movement of Catholic Students (TMCS)</b>! We are thrilled to have you as part of our vibrant Catholic community.</p>
            
            <div class="welcome-card">
                <p>Your registration is successful. Your member ID is:</p>
                <h2 style="color: #059669; margin: 10px 0;">{{ $member->registration_number }}</h2>
            </div>

            <p>We have created a user account for you to access our online portal where you can track your contributions, view upcoming events, and participate in community discussions.</p>
            
            <div class="credentials">
                <p><b>Portal Access Details:</b></p>
                <p>Login URL: <a href="{{ config('app.url') }}/login">{{ config('app.url') }}/login</a></p>
                <p>Username: <b>{{ $member->email }}</b></p>
                <p>Temporary Password: <b>{{ $password }}</b></p>
                <p style="font-size: 11px; color: #ef4444;">* Please change your password after your first login.</p>
            </div>

            <p>If you have any questions or need assistance, feel free to reach out to our administration team.</p>
            
            <a href="{{ config('app.url') }}/login" class="button">Access Member Portal</a>
            
            <p style="margin-top: 30px;">In Christ,</p>
            <p><b>TMCS Administration</b></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} TMCS SMART. All rights reserved.</p>
            <p>This is an automated welcome message from TMCS SMART Portal.</p>
        </div>
    </div>
</body>
</html>
