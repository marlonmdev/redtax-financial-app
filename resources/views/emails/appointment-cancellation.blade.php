<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Appointment Cancellation</title>
    <style>
        * {
            font-family: "Montserrat", Verdana, Arial, sans-serif;
            font-size: 1rem;
            font-weight: 500;
            color: #0f111a;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            background-color: #aa1926;
            text-align: center;
            padding: 15px;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            color: #f4f4f4 !important;
            font-size: 1.5rem;
        }
        .content {
            padding: 20px 0;
        }
        .content p {
            margin: 10px 0;
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
    <div class="container">
        <div class="header">
            <h1>Appointment Cancellation</h1>
        </div>
        <div class="content">
            <p>Dear {{ $details['name'] }},</p>
            <p>We regret to inform you that your appointment has been cancelled.</p>
            <p>Here are the details of the canceled appointment:</p>
            <ul>
                <li>Service: {{ $details['serviceName'] }}</li>
                <li>Location: {{ $details['location'] }}</li>
                <li>Date: {{ $details['appointmentDate'] }}</li>
                <li>Time: {{ $details['appointmentTime'] }}</li>
            </ul>
            <p>If you would like to reschedule, please contact us or visit our website to book a new appointment.</p>
            <p>We apologize for any inconvenience this may cause, and we look forward to assisting you in the future.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} RED Tax Financial Services</p>
        </div>
    </div>
</body>
</html>
