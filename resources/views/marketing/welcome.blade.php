<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Welcome to Stoka</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{--espresso:#1C1814;--terracotta:#C17F4A;--parchment:#FAF7F2;--forest:#4A6741;--surface:#F2EDE6;--muted:#8C7B6E;--border:#E5DDD4;--forest-bg:#EDF2EC;}
body{background:var(--parchment);color:var(--espresso);font-family:'Plus Jakarta Sans',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:40px 20px;-webkit-font-smoothing:antialiased;}
.wrap{max-width:480px;width:100%;}
.logo{font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;color:var(--espresso);text-align:center;margin-bottom:48px;}
.logo-dot{color:var(--terracotta);}
@if(session('welcome.provisioned'))
.status-icon{width:48px;height:48px;border-radius:50%;background:var(--forest);display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:20px;color:white;}
@else
.status-icon{width:48px;height:48px;border-radius:50%;background:var(--terracotta);display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:20px;color:white;}
@endif
.headline{font-family:'Cormorant Garamond',serif;font-size:32px;font-weight:500;color:var(--espresso);text-align:center;line-height:1.15;margin-bottom:10px;}
.sub{font-size:13px;color:var(--muted);text-align:center;line-height:1.7;margin-bottom:36px;}
.creds{background:white;border:1px solid var(--border);border-radius:12px;padding:20px 22px;margin-bottom:24px;}
.cred-row{display:flex;justify-content:space-between;align-items:baseline;padding:7px 0;border-bottom:1px solid var(--border);}
.cred-row:last-child{border-bottom:none;}
.cred-label{font-size:11px;color:var(--muted);}
.cred-val{font-family:'DM Mono',monospace;font-size:13px;color:var(--espresso);font-weight:500;}
.cred-val.url{color:var(--forest);}
.btn-go{display:block;width:100%;padding:14px;background:var(--espresso);color:var(--parchment);border:none;border-radius:8px;font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:600;text-align:center;text-decoration:none;margin-bottom:12px;transition:opacity 0.2s;}
.btn-go:hover{opacity:0.84;}
.help-note{font-size:12px;text-align:center;color:var(--muted);line-height:1.6;}
.help-note a{color:var(--terracotta);font-weight:600;text-decoration:none;}
.pending-box{background:var(--terra-bg);border:1px solid #E8D4B8;border-radius:12px;padding:20px 22px;margin-bottom:24px;}
.pending-title{font-size:14px;font-weight:600;color:var(--espresso);margin-bottom:6px;}
.pending-body{font-size:13px;color:var(--muted);line-height:1.65;}
</style>
</head>
<body>
@php $w = session('welcome', []); @endphp
<div class="wrap">
  <div class="logo">stoka<span class="logo-dot">&middot;</span></div>

  @if(!empty($w['provisioned']))
  <div class="status-icon">&#10003;</div>
  <h1 class="headline">Your shop is ready.</h1>
  <p class="sub">{{ $w['shop_name'] }} is live. Here are your login details &mdash; save them somewhere safe.</p>

  <div class="creds">
    <div class="cred-row"><span class="cred-label">Shop URL</span><span class="cred-val url">{{ $w['shop_url'] }}</span></div>
    <div class="cred-row"><span class="cred-label">Phone</span><span class="cred-val">{{ $w['phone'] }}</span></div>
    <div class="cred-row"><span class="cred-label">Password</span><span class="cred-val">{{ $w['password'] }}</span></div>
  </div>

  <a href="{{ $w['shop_url'] }}" class="btn-go">Open my shop &rarr;</a>
  <p class="help-note">Change your password in Settings after your first login.<br>Need help? <a href="https://wa.me/254741641925?text=Hi%2C+I+just+set+up+{{ urlencode($w['shop_name']) }}+on+Stoka+and+need+help+getting+started.">Message us on WhatsApp &rarr;</a></p>

  @else
  <div class="status-icon">&#128338;</div>
  <h1 class="headline">You&rsquo;re on the list.</h1>
  <p class="sub">We&rsquo;re setting up {{ $w['shop_name'] ?? 'your shop' }} now. You&rsquo;ll hear from us on WhatsApp at {{ $w['phone'] ?? 'the number you provided' }} within a few hours.</p>

  <div class="pending-box">
    <div class="pending-title">What happens next</div>
    <div class="pending-body">We&rsquo;ll configure your system, load your products, and send you a login link &mdash; all within 24 hours. Your 30-day free trial starts the moment you first log in.</div>
  </div>

  <p class="help-note">Want to see the demo while you wait? <a href="https://demo.tempforest.com/demo">Try it now &rarr;</a><br><br>Questions? <a href="https://wa.me/254741641925?text=Hi%2C+I+just+registered+{{ urlencode($w['shop_name'] ?? 'my shop') }}+on+Stoka.">Message us on WhatsApp &rarr;</a></p>
  @endif
</div>
</body>
</html>