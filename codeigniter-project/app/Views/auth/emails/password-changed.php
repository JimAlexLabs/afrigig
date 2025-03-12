<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Changed</title>
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
        <h2>Password Changed Successfully</h2>
        
        <p>Hello <?= esc($name) ?>,</p>
        
        <p>Your password has been changed successfully. You can now log in to your account using your new password.</p>
        
        <p>
            <a href="<?= site_url('login') ?>" class="button">
                Login to Your Account
            </a>
        </p>
        
        <p>If you did not change your password, please contact our support team immediately.</p>
        
        <div class="footer">
            <hr>
            <p>© <?= date('Y') ?> AfriGig. All rights reserved.</p>
            <p>This is a system-generated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html> 