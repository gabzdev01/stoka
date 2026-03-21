<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Get Started &mdash; Stoka</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --espresso: #1C1814; --terracotta: #C17F4A; --parchment: #FAF7F2;
  --forest: #4A6741; --surface: #F2EDE6; --muted: #8C7B6E; --border: #E8E2DA;
}
body { background: var(--parchment); color: var(--espresso); font-family: 'Plus Jakarta Sans', sans-serif; min-height: 100vh; -webkit-font-smoothing: antialiased; }
.page { display: grid; grid-template-columns: 1fr 1fr; min-height: 100vh; }
@media (max-width: 768px) { .page { grid-template-columns: 1fr; } }
.page-left {
  background: var(--espresso); padding: 56px 52px; display: flex; flex-direction: column; justify-content: space-between;
}
@media (max-width: 768px) { .page-left { padding: 36px 28px 40px; min-height: auto; } }
.reg-logo { font-family: 'Cormorant Garamond', serif; font-size: 22px; font-weight: 600; color: var(--parchment); }
.reg-logo-dot { color: var(--terracotta); }
.left-copy { margin-top: 48px; }
.left-headline { font-family: 'Cormorant Garamond', serif; font-size: clamp(28px, 3vw, 42px); font-weight: 500; color: var(--parchment); line-height: 1.1; }
.left-headline em { font-style: normal; color: var(--terracotta); }
.left-sub { font-size: 14px; color: rgba(250,247,242,0.55); line-height: 1.7; margin-top: 16px; max-width: 340px; }
.left-proof { margin-top: 40px; display: flex; flex-direction: column; gap: 12px; }
.proof-item { display: flex; align-items: center; gap: 10px; font-size: 13px; color: rgba(250,247,242,0.65); }
.proof-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--forest); flex-shrink: 0; }
.left-bottom { margin-top: 48px; }
.left-demo { font-size: 12px; color: rgba(250,247,242,0.35); }
.left-demo a { color: var(--terracotta); text-decoration: none; font-weight: 600; }
@media (max-width: 768px) { .left-copy { margin-top: 28px; } .left-proof, .left-bottom { display: none; } }
.page-right { padding: 56px 52px; display: flex; align-items: flex-start; justify-content: center; }
@media (max-width: 768px) { .page-right { padding: 36px 24px 48px; } }
.reg-form-wrap { width: 100%; max-width: 420px; }
.reg-back { font-size: 12px; color: var(--muted); margin-bottom: 36px; display: inline-flex; align-items: center; gap: 6px; }
.reg-back:hover { color: var(--espresso); }
.form-title { font-family: 'Cormorant Garamond', serif; font-size: 28px; font-weight: 500; color: var(--espresso); margin-bottom: 6px; }
.form-sub { font-size: 13px; color: var(--muted); margin-bottom: 36px; }
.form-sub strong { color: var(--forest); font-weight: 600; }
.form-group { margin-bottom: 18px; }
.form-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em; color: var(--muted); margin-bottom: 6px; display: block; }
.form-input {
  width: 100%; padding: 12px 14px; border: 1px solid var(--border); border-radius: 8px;
  font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; color: var(--espresso);
  background: white; outline: none; transition: border-color 0.15s;
}
.form-input:focus { border-color: var(--espresso); }
.form-input::placeholder { color: #BDB5AE; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
@media (max-width: 480px) { .form-row { grid-template-columns: 1fr; } }
.form-submit {
  width: 100%; padding: 14px; background: var(--espresso); color: var(--parchment);
  border: none; border-radius: 8px; font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 14px; font-weight: 600; cursor: pointer; margin-top: 8px;
  transition: opacity 0.2s;
}
.form-submit:hover { opacity: 0.84; }
.form-note { font-size: 11px; color: var(--muted); text-align: center; margin-top: 14px; line-height: 1.6; }
.form-note a { color: var(--terracotta); font-weight: 600; }
@if(session('success'))
.form-success {
  background: #EDF2EC; border: 1px solid var(--forest); border-radius: 10px;
  padding: 20px; margin-bottom: 24px;
}
.form-success-title { font-size: 14px; font-weight: 600; color: var(--forest); margin-bottom: 6px; }
.form-success-body { font-size: 13px; color: var(--muted); line-height: 1.6; }
@endif
</style>
</head>
<body>
<div class="page">
  <div class="page-left">
    <a href="/" class="reg-logo">stoka<span class="reg-logo-dot">&middot;</span></a>
    <div class="left-copy">
      <h2 class="left-headline">Your shop,<br>finally <em>legible.</em></h2>
      <p class="left-sub">Tell us about your boutique. We&rsquo;ll have your system configured and your first shift ready within 24 hours.</p>
      <div class="left-proof">
        <div class="proof-item"><span class="proof-dot"></span>30 days free. No card required.</div>
        <div class="proof-item"><span class="proof-dot"></span>Live in 24 hours. We handle setup.</div>
        <div class="proof-item"><span class="proof-dot"></span>First shift report on your phone the same day.</div>
        <div class="proof-item"><span class="proof-dot"></span>Cancel any time. No contract.</div>
      </div>
    </div>
    <div class="left-bottom">
      <p class="left-demo">Want to see it first? <a href="https://demo.tempforest.com/quick-login/owner">Try the live demo &rarr;</a></p>
    </div>
  </div>
  <div class="page-right">
    <div class="reg-form-wrap">
      <a href="/" class="reg-back">&larr; Back to stoka</a>

      @if(session('success'))
      <div style="background:#EDF2EC;border:1px solid var(--forest);border-radius:10px;padding:20px;margin-bottom:24px;">
        <div style="font-size:14px;font-weight:600;color:var(--forest);margin-bottom:6px;">You&rsquo;re on the list.</div>
        <div style="font-size:13px;color:var(--muted);line-height:1.6;">We&rsquo;ll have your shop configured within 24 hours. You&rsquo;ll hear from us on WhatsApp at the number you provided.</div>
      </div>
      @else

      <div class="form-title">Get started</div>
      <p class="form-sub"><strong>30 days free.</strong> No card. No commitment. We&rsquo;ll set up your system and you&rsquo;ll be live within 24 hours.</p>

      <form method="POST" action="/register">
        @csrf
        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="shop_name">Shop name</label>
            <input type="text" id="shop_name" name="shop_name" class="form-input" placeholder="Zawadi Boutique" required value="{{ old('shop_name') }}">
          </div>
          <div class="form-group">
            <label class="form-label" for="owner_name">Your name</label>
            <input type="text" id="owner_name" name="owner_name" class="form-input" placeholder="Wanjiku M." required value="{{ old('owner_name') }}">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label" for="phone">WhatsApp number</label>
          <input type="tel" id="phone" name="phone" class="form-input" placeholder="0712 345 678" required value="{{ old('phone') }}">
        </div>
        <div class="form-group">
          <label class="form-label" for="email">Email (optional)</label>
          <input type="email" id="email" name="email" class="form-input" placeholder="you@boutique.co.ke" value="{{ old('email') }}">
        </div>
        <div class="form-group">
          <label class="form-label" for="city">Where is your shop?</label>
          <input type="text" id="city" name="city" class="form-input" placeholder="Nairobi, Westlands" value="{{ old('city') }}">
        </div>
        <button type="submit" class="form-submit">Request my free trial &rarr;</button>
        <p class="form-note">
          By submitting you agree to our <a href="#">terms</a>. We&rsquo;ll reach you on WhatsApp within a few hours.
        </p>
      </form>

      @endif
    </div>
  </div>
</div>
</body>
</html>