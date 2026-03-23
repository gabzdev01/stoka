<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Get started &mdash; Stoka</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;1,400&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{
  --espresso:#1C1814;--terracotta:#C17F4A;--parchment:#FAF7F2;
  --forest:#4A6741;--surface:#F2EDE6;--muted:#8C7B6E;--border:#E8E2DA;
}
html{height:100%;}
body{
  background:var(--parchment);color:var(--espresso);
  font-family:'Plus Jakarta Sans',sans-serif;
  min-height:100vh;-webkit-font-smoothing:antialiased;
}
a{color:inherit;text-decoration:none;}

.page{display:grid;grid-template-columns:1fr 1fr;min-height:100vh;}
@media(max-width:768px){.page{grid-template-columns:1fr;}}

/* ── Left ─────────────────────────────────────────────── */
.left{
  background:var(--espresso);
  padding:52px 56px;
  display:flex;flex-direction:column;
  justify-content:space-between;
  position:relative;overflow:hidden;
}
.left::after{
  content:'';position:absolute;
  width:500px;height:500px;border-radius:50%;
  background:radial-gradient(circle,rgba(193,127,74,0.08) 0%,transparent 70%);
  bottom:-200px;right:-100px;pointer-events:none;
}
@media(max-width:768px){.left{padding:36px 28px 40px;}}

.left-logo{
  font-family:'Cormorant Garamond',serif;
  font-size:20px;font-weight:500;
  color:var(--parchment);letter-spacing:0.04em;
}
.left-logo span{color:var(--terracotta);}

.left-body{flex:1;display:flex;flex-direction:column;justify-content:center;padding:40px 0;}

.left-headline{
  font-family:'Cormorant Garamond',serif;
  font-size:clamp(32px,3.2vw,48px);
  font-weight:400;line-height:1.08;
  color:var(--parchment);
  letter-spacing:-0.01em;
  margin-bottom:20px;
}
.left-headline em{font-style:italic;color:var(--terracotta);}

.left-sub{
  font-size:14px;line-height:1.75;
  color:rgba(250,247,242,0.5);
  max-width:320px;
}

.left-proof{margin-top:36px;display:flex;flex-direction:column;gap:10px;}
.proof-item{
  display:flex;align-items:center;gap:10px;
  font-size:12px;color:rgba(250,247,242,0.45);
  letter-spacing:0.01em;
}
.proof-dot{width:4px;height:4px;border-radius:50%;background:var(--forest);flex-shrink:0;}

.left-bottom{font-size:12px;color:rgba(250,247,242,0.25);}
.left-bottom a{color:var(--terracotta);transition:opacity 0.15s;}
.left-bottom a:hover{opacity:0.75;}

@media(max-width:768px){.left-proof,.left-bottom{display:none;}}

/* ── Right ────────────────────────────────────────────── */
.right{
  padding:52px 56px;
  display:flex;align-items:center;justify-content:center;
}
@media(max-width:768px){.right{padding:36px 24px 52px;}}

.form-wrap{width:100%;max-width:400px;}

.form-back{
  display:inline-flex;align-items:center;gap:6px;
  font-size:12px;color:var(--muted);
  margin-bottom:40px;transition:color 0.15s;
}
.form-back:hover{color:var(--espresso);}

.form-title{
  font-family:'Cormorant Garamond',serif;
  font-size:32px;font-weight:400;
  color:var(--espresso);margin-bottom:8px;
}
.form-sub{
  font-size:13px;color:var(--muted);
  line-height:1.65;margin-bottom:36px;
}

.field{margin-bottom:20px;}
.field label{
  display:block;font-size:10px;font-weight:700;
  text-transform:uppercase;letter-spacing:0.1em;
  color:var(--muted);margin-bottom:7px;
}
.field input{
  width:100%;padding:13px 14px;
  border:1px solid var(--border);border-radius:0;
  font-family:'Plus Jakarta Sans',sans-serif;
  font-size:14px;color:var(--espresso);
  background:white;outline:none;
  transition:border-color 0.15s;
  -webkit-appearance:none;
}
.field input:focus{border-color:var(--espresso);}
.field input::placeholder{color:#C4BAB3;}

.errors{
  background:#F8EDE8;border-left:3px solid var(--terracotta);
  padding:12px 16px;margin-bottom:24px;
  font-size:13px;color:var(--espresso);line-height:1.6;
}

.submit-btn{
  width:100%;padding:15px;
  background:var(--espresso);color:var(--parchment);
  border:none;border-radius:0;
  font-family:'Plus Jakarta Sans',sans-serif;
  font-size:13px;font-weight:600;letter-spacing:0.04em;
  cursor:pointer;margin-top:4px;
  transition:opacity 0.15s;
}
.submit-btn:hover{opacity:0.85;}

.form-note{
  font-size:11px;color:var(--muted);
  text-align:center;margin-top:16px;
  line-height:1.65;
}

/* ── Success state ────────────────────────────────────── */
.success-wrap{text-align:center;padding:20px 0;}
.success-icon{
  width:48px;height:48px;border-radius:50%;
  background:rgba(74,103,65,0.1);
  display:flex;align-items:center;justify-content:center;
  margin:0 auto 24px;
}
.success-title{
  font-family:'Cormorant Garamond',serif;
  font-size:28px;font-weight:400;
  color:var(--espresso);margin-bottom:12px;
}
.success-body{
  font-size:13px;color:var(--muted);
  line-height:1.75;max-width:320px;margin:0 auto;
}
</style>
</head>
<body>
<div class="page">

  {{-- Left --}}
  <div class="left">
    <a href="/" class="left-logo">stoka<span>&middot;</span></a>

    <div class="left-body">
      <h1 class="left-headline">
        The shop you run<br>
        and the shop<br>
        you <em>actually see.</em>
      </h1>
      <p class="left-sub">
        Tell us your shop name and we will have everything configured. Your first shift opens the same day.
      </p>
      <div class="left-proof">
        <div class="proof-item"><span class="proof-dot"></span>30 days free. No card required.</div>
        <div class="proof-item"><span class="proof-dot"></span>We handle the setup. You just sell.</div>
        <div class="proof-item"><span class="proof-dot"></span>First shift report on your phone the same day.</div>
        <div class="proof-item"><span class="proof-dot"></span>Cancel any time. No questions asked.</div>
      </div>
    </div>

    <div class="left-bottom">
      Want to see it first? <a href="https://demo.stoka.co.ke/demo">Try the demo &rarr;</a>
    </div>
  </div>

  {{-- Right --}}
  <div class="right">
    <div class="form-wrap">
      <a href="/" class="form-back">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Back
      </a>

      @if(session('welcome'))
      <div class="success-wrap">
        <div class="success-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4A6741" stroke-width="2" stroke-linecap="round"><path d="M20 6L9 17l-5-5"/></svg>
        </div>
        <h2 class="success-title">We have your request.</h2>
        <p class="success-body">
          Someone from Stoka will be in touch today &mdash; usually within a few hours. Check your email for a confirmation.
        </p>
      </div>

      @else

      <h2 class="form-title">Tell us about your shop.</h2>
      <p class="form-sub">Two things and you are done. We will take it from there.</p>

      @if($errors->any())
      <div class="errors">{{ implode('. ', $errors->all()) }}</div>
      @endif

      <form method="POST" action="/register">
        @csrf
        <div class="field">
          <label for="shop_name">Shop name</label>
          <input type="text" id="shop_name" name="shop_name"
            placeholder="Zawadi Boutique"
            required value="{{ old('shop_name') }}" autocomplete="off">
        </div>
        <div class="field">
          <label for="owner_name">Your name</label>
          <input type="text" id="owner_name" name="owner_name"
            placeholder="Wanjiku"
            required value="{{ old('owner_name') }}" autocomplete="name">
        </div>
        <div class="field">
          <label for="email">Email</label>
          <input type="email" id="email" name="email"
            placeholder="wanjiku@gmail.com"
            required value="{{ old('email') }}" autocomplete="email">
        </div>
        {{-- Hidden fields to satisfy validation --}}
        <input type="hidden" name="phone" value="0000000000">
        <button type="submit" class="submit-btn">Get started &rarr;</button>
        <p class="form-note">
          30 days free &nbsp;&middot;&nbsp; No card required &nbsp;&middot;&nbsp; We handle setup
        </p>
      </form>

      @endif
    </div>
  </div>

</div>
</body>
</html>