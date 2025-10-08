<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Two-Factor Authentication</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding:20px;">
    <div style="max-width: 600px; margin:auto; background:#fff; padding:20px; border-radius:8px;">
        <h2 style="color:#2c3e50;">Hello, {{ $user->name }}</h2>
        <p>We received a login attempt on your account. To continue, please use the following verification code:</p>
        <div style="font-size:24px; font-weight:bold; margin:20px 0; color:#e74c3c;">
            {{ $code }}
        </div>
        <p>This code will expire in <strong>10 minutes</strong>. If you did not request this, please secure your account immediately.</p>
        <p style="margin-top:30px;">Thanks,<br> The Theatre System Team</p>
    </div>
</body>
</html>
