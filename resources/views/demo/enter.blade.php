<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#1C1814">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<link rel="apple-touch-icon" href="/icons/icon-192.png">
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Stoka Demo</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{--espresso:#1C1814;--terracotta:#C17F4A;--parchment:#FAF7F2;--forest:#4A6741;--surface:#F2EDE6;--muted:#8C7B6E;--border:#E5DDD4;}
body{background:var(--parchment);color:var(--espresso);font-family:'Plus Jakarta Sans',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;-webkit-font-smoothing:antialiased;}
.wrap{width:100%;max-width:420px;padding:40px 28px;}
.logo{font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;color:var(--espresso);text-align:center;margin-bottom:40px;}
.logo-dot{color:var(--terracotta);}
.headline{font-family:'Cormorant Garamond',serif;font-size:clamp(28px,4vw,38px);font-weight:500;color:var(--espresso);line-height:1.1;text-align:center;margin-bottom:10px;}
.headline em{font-style:normal;color:var(--terracotta);}
.sub{font-size:13px;color:var(--muted);text-align:center;line-height:1.7;margin-bottom:36px;}
.form-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:6px;display:block;}
.form-input{width:100%;padding:13px 14px;border:1px solid var(--border);border-radius:8px;font-family:'Plus Jakarta Sans',sans-serif;font-size:15px;color:var(--espresso);background:white;outline:none;transition:border-color 0.15s;margin-bottom:14px;}
.form-input:focus{border-color:var(--espresso);}
.form-input::placeholder{color:#C5BEB6;}
.role-btns{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px;}
.role-btn{padding:12px;border-radius:8px;border:1.5px solid var(--border);text-align:center;cursor:pointer;transition:border-color 0.15s,background 0.15s;}
.role-btn.selected{border-color:var(--espresso);background:var(--espresso);}
.role-btn.selected .rb-title{color:var(--parchment);}
.role-btn.selected .rb-sub{color:rgba(250,247,242,0.55);}
.rb-title{font-size:13px;font-weight:600;color:var(--espresso);margin-bottom:2px;}
.rb-sub{font-size:10px;color:var(--muted);}
.submit-btn{width:100%;padding:14px;background:var(--espresso);color:var(--parchment);border:none;border-radius:8px;font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:600;cursor:pointer;transition:opacity 0.2s;margin-top:4px;}
.submit-btn:hover{opacity:0.84;}
.skip{display:block;text-align:center;font-size:12px;color:var(--muted);margin-top:14px;}
.skip a{color:var(--terracotta);font-weight:600;text-decoration:none;}
@media(max-width:480px){.wrap{padding:32px 20px;}}
</style>
<script>
// Clear any old demo state on page load
localStorage.removeItem('demo_preview_name');
localStorage.removeItem('demo_owner_name');
localStorage.removeItem('demo_skipped');
</script>
</head>
<body>
<div class="wrap">
  <div class="logo">stoka<span class="logo-dot">&middot;</span></div>
  <h1 class="headline">See it as <em>your</em> boutique.</h1>
  <p class="sub">Enter your shop name and the demo will show your branding throughout &mdash; exactly how it looks when you go live.</p>

  <form method="POST" action="/demo">
    @csrf
    <label class="form-label" for="owner_name">Your name</label>
    <input type="text" id="owner_name" name="owner_name" class="form-input"
           placeholder="e.g. Sarah" autocomplete="given-name" autofocus>
    
    <label class="form-label" for="shop_name">Your boutique name</label>
    <input type="text" id="shop_name" name="shop_name" class="form-input"
           placeholder="e.g. Zawadi Boutique" autocomplete="organization">

    <label class="form-label" style="margin-bottom:10px;">Experience as</label>
    <div class="role-btns">
      <div class="role-btn selected" id="btn-owner" onclick="selectRole('owner')">
        <div class="rb-title">Owner</div>
        <div class="rb-sub">Dashboard, shifts, reports</div>
      </div>
      <div class="role-btn" id="btn-staff" onclick="selectRole('staff')">
        <div class="rb-title">Staff</div>
        <div class="rb-sub">Till, sales, shift close</div>
      </div>
    </div>
    <input type="hidden" name="role" id="role-input" value="owner">

    <button type="submit" class="submit-btn">Enter the demo &rarr;</button>
  </form>
  <span class="skip">No name? <a href="/demo" onclick="document.getElementById('shop_name').value='Boutique Demo'">Skip &rarr;</a></span>
</div>
<script>
function selectRole(r){
  ['owner','staff'].forEach(x=>{
    document.getElementById('btn-'+x).classList.toggle('selected',x===r);
  });
  document.getElementById('role-input').value=r;
}
</script>
<script>
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/sw.js').catch(function(){});
}
</script>
</body>
</html><script>
// demo_role_store
(function() {
    // Store role selection in localStorage when form submits
    var form = document.getElementById('demo-enter-form');
    if (!form) return;

    form.addEventListener('submit', function() {
        var role = document.querySelector('input[name="role"]:checked');
        if (role) localStorage.setItem('demo_role', role.value);
    });

    // Also store on role button click if using button toggles
    document.querySelectorAll('[data-role]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            localStorage.setItem('demo_role', this.dataset.role);
        });
    });
})();
</script>
