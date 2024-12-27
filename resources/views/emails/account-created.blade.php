<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Created</title>
    <style>
        * {
            font-family: "Montserrat", Verdana, Arial, sans-serif;
            font-size: 1rem;
            font-weight: 500;
            color: #0f111a;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #aa1926;
            padding: 15px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            color: #f4f4f4 !important;
            font-size: 24px;
        }
        .body-content {
            padding: 20px;
            text-align: center;
        }
        .body-content h2 {
            font-size: 22px;
            margin-bottom: 10px;
            color: #333;
        }
        .body-content p {
            font-size: 16px;
            line-height: 1.5;
            color: #555;
        }
        .cta-button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            font-size: 16px;
            background-color: #aa1926;
            color: #fff !important;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .cta-button:hover {
            background-color: #bf535c;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .footer p {
            margin: 0;
            font-size: 0.875rem;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="email-container">

        <div class="header">
            <h1>Account Created!</h1>
        </div>
    
        <div class="body-content">
            <h2>Hello, {{ $details['name'] }}!</h2>
            <p>
                We're pleased to inform you that your user account has been successfully created. You may now request access for account login.
            </p>
            <p>
                To begin the access request process, click the button below.
            </p>
            <a href="{{ route('client-login') }}" class="cta-button">Request Access</a>
        </div>
    
        <div class="footer">
            <p>
                &copy; {{ date('Y') }} RED Tax Financial Services
            </p>
        </div>
    
    </div>
    
</body>
</html>
