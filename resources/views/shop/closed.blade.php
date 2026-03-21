<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $tenant?->name ?? 'Shop' }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Plus+Jakarta+Sans:wght@400;500&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#1C1814;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;}
.wrap{text-align:center;}
h1{font-family:'Cormorant Garamond',serif;font-size:38px;font-weight:600;color:#FAF7F2;letter-spacing:0.04em;margin-bottom:12px;}
p{color:#8C8279;font-size:14px;line-height:1.6;}
.dot{width:6px;height:6px;background:#C17F4A;border-radius:50%;display:inline-block;margin:28px auto 0;animation:pulse 2s ease-in-out infinite;}
@keyframes pulse{0%,100%{opacity:0.4;transform:scale(1);}50%{opacity:1;transform:scale(1.3);}}
</style>
</head>
<body>
<div class="wrap">
    <h1>{{ $tenant?->name ?? 'Coming Soon' }}</h1>
    <p>Our online shop is being set up.<br>Check back soon.</p>
    <div class="dot"></div>
</div>
</body>
</html>
