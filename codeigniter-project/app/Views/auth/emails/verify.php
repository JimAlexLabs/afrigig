<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify Your Email Address</title>
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
        <h2>Verify Your Email Address</h2>
        
        <p>Thank you for registering with AfriGig! Before you can start using your account, please verify your email address by clicking the button below:</p>
        
        <p>
            <a href="<?= site_url('verify-email/' . $token) ?>" class="button">
                Verify Email Address
            </a>
        </p>
        
        <p>If you did not create an account, no further action is required.</p>
        
        <div class="footer">
            <p>If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser:</p>
            <p><?= site_url('verify-email/' . $token) ?></p>
            <hr>
            <p>© <?= date('Y') ?> AfriGig. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 