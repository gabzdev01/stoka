<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>New registration</title>
<style>
body{margin:0;padding:0;background:#F2EDE6;font-family:'Helvetica Neue',Arial,sans-serif;-webkit-font-smoothing:antialiased;}
table{border-collapse:collapse;}
.wrap{max-width:560px;margin:0 auto;padding:32px 16px;}
.card{background:#FAF7F2;border:1px solid #E8E2DA;}
.header{background:#1C1814;padding:32px 40px;}
.header-logo{font-size:11px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:rgba(250,247,242,0.5);margin:0;}
.header-tagline{font-size:13px;color:rgba(250,247,242,0.25);margin:6px 0 0;letter-spacing:0.04em;}
.body{padding:40px;}
.greeting{font-size:22px;font-weight:400;color:#1C1814;margin:0 0 16px;line-height:1.3;}
.body p{font-size:14px;line-height:1.75;color:#5A4E47;margin:0 0 16px;}
.divider{height:1px;background:#E8E2DA;margin:28px 0;}
.label{font-size:10px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#8C8279;margin:0 0 4px;}
.value{font-size:14px;color:#1C1814;font-weight:500;margin:0 0 16px;}
.value a{color:#1C1814;}
.value code{font-family:'Courier New',monospace;font-size:13px;background:#F2EDE6;padding:2px 6px;border:1px solid #E8E2DA;}
.btn{display:inline-block;background:#1C1814;color:#FAF7F2 !important;padding:14px 28px;font-size:13px;font-weight:600;letter-spacing:0.04em;text-decoration:none;}
.btn-terra{background:#C17F4A;}
.footer{padding:24px 40px;border-top:1px solid #E8E2DA;}
.footer p{font-size:11px;color:#8C8279;margin:0;line-height:1.6;}
.footer a{color:#8C8279;}
</style></head>
<body>
<div class="wrap">
<div class="card">
  <div class="header">
    <p class="header-logo">Stoka &nbsp;·&nbsp; New Registration</p>
  </div>
  <div class="body">
    <h1 class="greeting">{{ $shopName }}</h1>
    <p>A new registration just came in.</p>
    <div class="divider"></div>
    <p class="label">Owner</p>
    <p class="value">{{ $ownerName }}</p>
    <p class="label">Phone</p>
    <p class="value"><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $phone) }}">{{ $phone }}</a></p>
    @if($email)
    <p class="label">Email</p>
    <p class="value"><a href="mailto:{{ $email }}">{{ $email }}</a></p>
    @endif
    @if($city)
    <p class="label">City</p>
    <p class="value">{{ $city }}</p>
    @endif
    <div class="divider"></div>
    <a href="https://tempforest.com/admin" class="btn">Open admin →</a>
  </div>
</div>
</div>
</body>
</html>