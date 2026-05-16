<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; }
        .header { background-color: #3b82f6; color: #ffffff; padding: 40px 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { padding: 30px; background-color: #ffffff; }
        .footer { background-color: #f9fafb; color: #6b7280; padding: 20px; text-align: center; font-size: 12px; }
        .button { display: inline-block; padding: 14px 28px; background-color: #3b82f6; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .reset-card { margin: 25px 0; padding: 25px; border: 2px dashed #d1d5db; border-radius: 8px; text-align: center; }
        .credentials { background-color: #f3f4f6; padding: 15px; border-radius: 6px; margin-top: 15px; text-align: left; }
        .credentials b { color: #3b82f6; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Reset</h1>
        </div>
        <div class="content">
            <p>Hello {{ $user->name }},</p>
            <p>Your account password for the <b>TmcsSmart Portal</b> has been reset by an administrator.</p>
            
            <div class="reset-card">
                <p>Your new login credentials are provided below. For security reasons, you will be required to change this password when you first log in.</p>
            </div>
            
            <div class="credentials">
                <p><b>Login Details:</b></p>
                <p>Login URL: <a href="{{ config('app.url') }}/login">{{ config('app.url') }}/login</a></p>
                <p>Email: <b>{{ $user->email }}</b></p>
                <p>New Password: <b>{{ $newPassword }}</b></p>
                <p style="font-size: 11px; color: #ef4444;">* Note: This password is temporary and case-sensitive.</p>
            </div>

            <p>If you did not request this change, please contact the system administrator immediately.</p>
            
            <a href="{{ config('app.url') }}/login" class="button">Login to Portal</a>
            
            <p style="margin-top: 30px;">Regards,</p>
            <p><b>TMCS Administration</b></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} TMCS SMART. All rights reserved.</p>
            <p>This is a security notification from the TMCS SMART Portal.</p>
        </div>
    </div>
</body>
</html>
