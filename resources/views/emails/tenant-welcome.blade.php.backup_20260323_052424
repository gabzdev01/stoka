<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; line-height: 1.6; color: #1C1814; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1C1814; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .header h1 { color: #FAF7F2; margin: 0; font-size: 28px; font-weight: 300; letter-spacing: 0.05em; }
        .content { background: white; padding: 40px; border: 1px solid #E8E2DA; border-top: none; border-radius: 0 0 8px 8px; }
        .credentials { background: #FAF7F2; border: 1px solid #E8E2DA; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .credentials p { margin: 8px 0; }
        .credentials strong { color: #1C1814; }
        .button { display: inline-block; background: #4A6741; color: white; padding: 14px 28px; text-decoration: none; border-radius: 6px; margin: 20px 0; font-weight: 600; }
        .footer { text-align: center; margin-top: 30px; color: #8C8279; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>STOKA</h1>
    </div>
    <div class="content">
        <h2>Welcome to Stoka, {{ $ownerName }}! 🎉</h2>
        
        <p>Your boutique management system is ready. {{ $shopName }} is now live and you can start managing your inventory, staff, and sales.</p>
        
        <div class="credentials">
            <p><strong>Shop URL:</strong> <a href="{{ $shopUrl }}">{{ $shopUrl }}</a></p>
            <p><strong>Phone:</strong> {{ $phone }}</p>
            <p><strong>Password:</strong> {{ $password }}</p>
        </div>
        
        <p><strong>⚠️ Important:</strong> Please change your password after your first login for security.</p>
        
        <a href="{{ $shopUrl }}/login" class="button">Login to Dashboard</a>
        
        <h3>What's Next?</h3>
        <ol>
            <li>Login to your dashboard</li>
            <li>Add your products and suppliers</li>
            <li>Create staff accounts (optional)</li>
            <li>Share your shop link with customers</li>
        </ol>
        
        <p>Need help? We're here for you.</p>
        <p>Email: support@getstoka.com<br>
        WhatsApp: +254 XXX XXX XXX</p>
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} Stoka. Boutique management. Aware by design.</p>
    </div>
</body>
</html>
