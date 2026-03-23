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
<title>Stoka — See it as your boutique</title>
<meta name="description" content="Type your shop name. See Stoka running as your boutique. No account needed.">
<meta property="og:title" content="Stoka — Your shop. Before you commit to anything.">
<meta property="og:description" content="Type your shop name. See it running as yours. No account needed. Enter as owner or staff.">
<meta property="og:image" content="https://demo.stoka.co.ke/og-demo.jpg">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Stoka — Your shop. Before you commit to anything.">
<meta name="twitter:description" content="Type your shop name. See it running as yours. No account needed.">
<meta name="twitter:image" content="https://demo.stoka.co.ke/og-demo.jpg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,400&family=Plus+Jakarta+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{--espresso:#1C1814;--terracotta:#C17F4A;--parchment:#FAF7F2;--forest:#4A6741;--surface:#F2EDE6;--muted:#8C7B6E;--border:#E5DDD4;}
html,body{height:100%;}
body{background:var(--espresso);color:var(--parchment);font-family:'Plus Jakarta Sans',sans-serif;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;-webkit-font-smoothing:antialiased;overflow:hidden;}
body::before{content:'';position:fixed;inset:0;background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");pointer-events:none;z-index:0;opacity:0.5;}
body::after{content:'';position:fixed;width:600px;height:600px;border-radius:50%;background:radial-gradient(circle,rgba(193,127,74,0.12) 0%,transparent 70%);top:50%;left:50%;transform:translate(-50%,-50%);pointer-events:none;z-index:0;}
.stage{position:relative;z-index:1;width:100%;max-width:440px;padding:0 28px;text-align:center;}
.logo{font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:600;color:rgba(250,247,242,0.35);letter-spacing:-0.01em;margin-bottom:52px;opacity:0;animation:fadeUp 0.6s ease 0.1s forwards;}
.logo-dot{color:var(--terracotta);}
.step{display:none;flex-direction:column;align-items:center;}
.step.active{display:flex;}
.question{font-family:'Cormorant Garamond',serif;font-size:clamp(30px,6vw,44px);font-weight:400;line-height:1.1;color:var(--parchment);margin-bottom:36px;letter-spacing:-0.01em;min-height:50px;}
.question em{font-style:italic;color:var(--terracotta);}
.input-wrap{position:relative;width:100%;margin-bottom:14px;}
.big-input{width:100%;padding:18px 20px;background:rgba(250,247,242,0.05);border:1px solid rgba(250,247,242,0.1);border-radius:12px;font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:400;color:var(--parchment);text-align:center;outline:none;transition:border-color 0.2s,background 0.2s;caret-color:var(--terracotta);}
.big-input::placeholder{color:rgba(250,247,242,0.15);font-style:italic;}
.big-input:focus{border-color:rgba(193,127,74,0.45);background:rgba(250,247,242,0.07);}
.continue-btn{width:100%;padding:15px;background:var(--terracotta);color:var(--parchment);border:none;border-radius:10px;font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:600;cursor:pointer;transition:opacity 0.2s,transform 0.15s;opacity:0;transform:translateY(8px);pointer-events:none;}
.continue-btn.ready{opacity:1;transform:translateY(0);pointer-events:auto;}
.continue-btn:hover{opacity:0.88;}
.role-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;width:100%;margin-bottom:14px;}
.role-card{padding:20px 14px;border:1px solid rgba(250,247,242,0.1);border-radius:12px;cursor:pointer;text-align:left;transition:border-color 0.2s,background 0.2s,transform 0.15s;background:rgba(250,247,242,0.03);}
.role-card:hover{border-color:rgba(250,247,242,0.2);transform:translateY(-1px);}
.role-card.selected{border-color:var(--terracotta);background:rgba(193,127,74,0.1);}
.rc-icon{font-size:18px;margin-bottom:10px;display:block;opacity:0.6;}
.rc-title{font-size:13px;font-weight:600;color:var(--parchment);margin-bottom:4px;}
.rc-sub{font-size:11px;color:rgba(250,247,242,0.35);line-height:1.5;}
.role-card.selected .rc-sub{color:rgba(250,247,242,0.55);}
.hint-small{font-size:12px;color:rgba(250,247,242,0.28);margin-bottom:16px;line-height:1.6;}
.enter-btn{width:100%;padding:16px;background:var(--parchment);color:var(--espresso);border:none;border-radius:10px;font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:700;cursor:pointer;transition:opacity 0.2s,transform 0.15s;letter-spacing:0.01em;}
.enter-btn:hover{opacity:0.9;transform:translateY(-1px);}
.dots{display:flex;align-items:center;justify-content:center;gap:6px;margin-bottom:44px;opacity:0;animation:fadeUp 0.6s ease 0.3s forwards;}
.dot{width:5px;height:5px;border-radius:50%;background:rgba(250,247,242,0.18);transition:background 0.3s,transform 0.3s;}
.dot.active{background:var(--terracotta);transform:scale(1.3);}
.dot.done{background:rgba(250,247,242,0.4);}
.skip-link{display:block;margin-top:16px;font-size:11px;color:rgba(250,247,242,0.18);cursor:pointer;transition:color 0.15s;background:none;border:none;font-family:'Plus Jakarta Sans',sans-serif;letter-spacing:0.02em;}
.skip-link:hover{color:rgba(250,247,242,0.45);}
.progress-line{position:fixed;top:0;left:0;height:2px;background:linear-gradient(90deg,var(--terracotta),#E8A56A);transition:width 0.5s cubic-bezier(0.4,0,0.2,1);z-index:10;}
@keyframes fadeUp{from{opacity:0;transform:translateY(16px);}to{opacity:1;transform:translateY(0);}}
.step-enter{animation:fadeUp 0.35s ease forwards;}
.step-exit{animation:fadeDown 0.22s ease forwards;}
@keyframes fadeDown{from{opacity:1;transform:translateY(0);}to{opacity:0;transform:translateY(-10px);}}
@media(max-width:480px){.stage{padding:0 20px;}.question{font-size:28px;}}
</style>
<script>
localStorage.removeItem('demo_preview_name');
localStorage.removeItem('demo_owner_name');
localStorage.removeItem('demo_skipped');
</script>
</head>
<body>

<div class="progress-line" id="progress-line" style="width:0%"></div>

<div class="stage">
  <div class="logo">stoka<span class="logo-dot">&middot;</span></div>

  <div class="dots" id="dots">
    <div class="dot active" id="dot-0"></div>
    <div class="dot" id="dot-1"></div>
    <div class="dot" id="dot-2"></div>
  </div>

  <form method="POST" action="/demo" id="demo-form" style="display:none;">
    @csrf
    <input type="hidden" name="shop_name" id="f-shop">
    <input type="hidden" name="owner_name" id="f-name">
    <input type="hidden" name="role" id="f-role" value="owner">
  </form>

  <!-- STEP 1 — boutique name -->
  <div class="step active" id="step-1">
    <h1 class="question" id="q1">Give your boutique<br>a name.</h1>
    <div class="input-wrap">
      <input type="text" class="big-input" id="input-shop"
             placeholder="Zawadi Boutique"
             autocomplete="off" autocorrect="off" spellcheck="false">
    </div>
    <button class="continue-btn" id="btn-shop" onclick="goStep2()">That&rsquo;s the one &rarr;</button>
    <button class="skip-link" onclick="skipShop()">skip</button>
  </div>

  <!-- STEP 2 — owner name -->
  <div class="step" id="step-2">
    <h1 class="question" id="q2">And who&rsquo;s<br>running it?</h1>
    <div class="input-wrap">
      <input type="text" class="big-input" id="input-name"
             placeholder="Your name"
             autocomplete="given-name" autocorrect="off" spellcheck="false">
    </div>
    <button class="continue-btn" id="btn-name" onclick="goStep3()">That&rsquo;s me &rarr;</button>
    <button class="skip-link" onclick="goStep3(true)">skip</button>
  </div>

  <!-- STEP 3 — role -->
  <div class="step" id="step-3">
    <h1 class="question" id="q3">One last thing.</h1>
    <p class="hint-small">Two views. Same boutique.</p>
    <div class="role-grid">
      <div class="role-card selected" id="card-owner" onclick="selectRole('owner')">
        <span class="rc-icon">◈</span>
        <div class="rc-title">As the owner</div>
        <div class="rc-sub">Everything Amina sees.</div>
      </div>
      <div class="role-card" id="card-staff" onclick="selectRole('staff')">
        <span class="rc-icon">◇</span>
        <div class="rc-title">As staff</div>
        <div class="rc-sub">Everything James does.</div>
      </div>
    </div>
    <button class="enter-btn" onclick="submitDemo()">Show me &rarr;</button>
    <button class="skip-link" onclick="submitDemo()">enter without selecting</button>
  </div>

</div>

<script>
var shopName='', ownerName='', role='owner', currentStep=1;
var progressMap={1:'8%',2:'50%',3:'88%'};

(function(){
  var r=new URLSearchParams(window.location.search).get('role');
  if(r==='staff')selectRole('staff');
})();

function updateDots(s){
  for(var i=0;i<3;i++){
    var d=document.getElementById('dot-'+i);
    d.className='dot';
    if(i+1<s)d.classList.add('done');
    else if(i+1===s)d.classList.add('active');
  }
}

function goStep(from,to){
  var f=document.getElementById('step-'+from);
  var t=document.getElementById('step-'+to);
  f.classList.add('step-exit');
  setTimeout(function(){
    f.classList.remove('active','step-exit');
    t.classList.add('active','step-enter');
    setTimeout(function(){
      t.classList.remove('step-enter');
      var inp=t.querySelector('.big-input');
      if(inp)inp.focus();
    },350);
  },220);
  currentStep=to;
  updateDots(to);
  document.getElementById('progress-line').style.width=progressMap[to];
}

// STEP 1
var shopInput=document.getElementById('input-shop');
var btnShop=document.getElementById('btn-shop');
var q1=document.getElementById('q1');
shopInput.addEventListener('input',function(){
  shopName=this.value.trim();
  if(shopName){
    q1.innerHTML='<em>'+esc(shopName)+'.</em>';
  } else {
    q1.innerHTML='Give your boutique<br>a name.';
  }
  btnShop.classList.toggle('ready',shopName.length>0);
});
shopInput.addEventListener('keydown',function(e){if(e.key==='Enter'&&shopName)goStep2();});

function goStep2(){
  document.getElementById('f-shop').value=shopName||'';
  goStep(1,2);
}
function skipShop(){
  shopName='';
  document.getElementById('f-shop').value='';
  goStep(1,2);
}

// STEP 2
var nameInput=document.getElementById('input-name');
var btnName=document.getElementById('btn-name');
var q2=document.getElementById('q2');
nameInput.addEventListener('input',function(){
  ownerName=this.value.trim().split(' ')[0];
  if(ownerName){
    q2.innerHTML='Good morning,<br><em>'+esc(ownerName)+'.</em>';
  } else {
    q2.innerHTML='And who&rsquo;s<br>running it?';
  }
  btnName.classList.toggle('ready',ownerName.length>0);
});
nameInput.addEventListener('keydown',function(e){if(e.key==='Enter')goStep3();});

function goStep3(skip){
  if(!skip&&ownerName){
    document.getElementById('f-name').value=ownerName;
    var q3=document.getElementById('q3');
    q3.innerHTML='One last thing,<br><em>'+esc(ownerName)+'.</em>';
  } else {
    document.getElementById('f-name').value='';
  }
  goStep(2,3);
}

// STEP 3
function selectRole(r){
  role=r;
  document.getElementById('f-role').value=r;
  document.getElementById('card-owner').classList.toggle('selected',r==='owner');
  document.getElementById('card-staff').classList.toggle('selected',r==='staff');
}

function submitDemo(){
  document.getElementById('progress-line').style.width='100%';
  var curtain=document.createElement('div');
  curtain.style.cssText='position:fixed;inset:0;background:#1C1814;opacity:0;z-index:9999;transition:opacity 0.45s ease;pointer-events:none;';
  document.body.appendChild(curtain);
  requestAnimationFrame(function(){
    requestAnimationFrame(function(){
      curtain.style.opacity='1';
      setTimeout(function(){
        document.getElementById('demo-form').submit();
      },420);
    });
  });
}

function esc(s){
  return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

setTimeout(function(){document.getElementById('input-shop').focus();},700);
document.addEventListener('keydown',function(e){
  if(e.key==='Enter'&&currentStep===3)submitDemo();
});
</script>
<script>
if('serviceWorker' in navigator){
  navigator.serviceWorker.register('/sw.js').catch(function(){});
}
</script>
</body>
</html>
