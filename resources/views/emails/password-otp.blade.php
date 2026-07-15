<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Reset OTP</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f8fafc; margin:0; padding:24px;">
    <div style="max-width:600px; margin:0 auto; background:#ffffff; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden;">
        <div style="background:#00529b; color:#fff; padding:20px 24px;">
            <h2 style="margin:0; font-size:22px;">Password Reset OTP</h2>
        </div>
        <div style="padding:24px; color:#111827;">
            <p style="margin-top:0;">Hello {{ $user->full_name ?? $user->name ?? 'User' }},</p>
            <p>Your OTP for password reset is:</p>
            <div style="font-size:32px; font-weight:bold; letter-spacing:6px; padding:16px 0; text-align:center; color:#00529b;">
                {{ $otp }}
            </div>
            <p>This OTP will expire in {{ $minutes }} minutes.</p>
            <p>If you did not request this, you can ignore this email.</p>
        </div>
    </div>
</body>
</html>
