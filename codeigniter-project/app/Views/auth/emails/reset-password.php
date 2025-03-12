<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 20px;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            background-color: #3490dc;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Your Password</h2>
        
        <p>Hello <?= esc($name) ?>,</p>
        
        <p>You are receiving this email because we received a password reset request for your account. Click the button below to reset your password:</p>
        
        <p>
            <a href="<?= site_url('reset-password/' . $token) ?>" class="button">
                Reset Password
            </a>
        </p>
        
        <p>This password reset link will expire in 60 minutes.</p>
        
        <p>If you did not request a password reset, no further action is required.</p>
        
        <div class="footer">
            <p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:</p>
            <p><?= site_url('reset-password/' . $token) ?></p>
            <hr>
            <p>© <?= date('Y') ?> AfriGig. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 