<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #334155;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .message-box {
            background: #f1f5f9;
            border-left: 4px solid #2563eb;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .sender-info {
            background: #eff6ff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .sender-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 Case Update Message</h1>
        </div>
        <div class="content">
            <div class="sender-info">
                <p><strong>From:</strong> {{ $senderName }}</p>
                <p><strong>Email:</strong> {{ $senderEmail }}</p>
            </div>
            
            <h2 style="color: #1e293b; margin-top: 0;">Message:</h2>
            <div class="message-box">
                {!! nl2br(e($messageContent)) !!}
            </div>
        </div>
        <div class="footer">
            <p>This message was sent from the Prosecutor Case Tracking System</p>
            <p>&copy; {{ date('Y') }} Office of the Prosecutor. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
