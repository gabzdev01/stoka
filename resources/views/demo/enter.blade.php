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
.logo{font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:600;color:rgba(250,247,242,0.45);letter-spacing:-0.01em;margin-bottom:52px;opacity:0;animation:fadeUp 0.6s ease 0.1s forwards;}
.logo-dot{color:var(--terracotta);}
.step{display:none;flex-direction:column;align-items:center;}
.step.active{display:flex;}
.question{font-family:'Cormorant Garamond',serif;font-size:clamp(30px,6vw,44px);font-weight:400;line-height:1.1;color:var(--parchment);margin-bottom:12px;letter-spacing:-0.01em;min-height:50px;}
.question em{font-style:italic;color:var(--terracotta);}
.hint{font-size:13px;color:rgba(250,247,242,0.35);line-height:1.7;margin-bottom:36px;max-width:320px;}
.input-wrap{position:relative;width:100%;margin-bottom:14px;}
.big-input{width:100%;padding:18px 20px;background:rgba(250,247,242,0.05);border:1px solid rgba(250,247,242,0.1);border-radius:12px;font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:400;color:var(--parchment);text-align:center;outline:none;transition:border-color 0.2s,background 0.2s;caret-color:var(--terracotta);}
.big-input::placeholder{color:rgba(250,247,242,0.18);font-style:italic;}
.big-input:focus{border-color:rgba(193,127,74,0.45);background:rgba(250,247,242,0.07);}
.continue-btn{width:100%;padding:15px;background:var(--terracotta);color:var(--parchment);border:none;border-radius:10px;font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:600;cursor:pointer;transition:opacity 0.2s,transform 0.15s;opacity:0;transform:translateY(8px);pointer-events:none;}
.continue-btn.ready{opacity:1;transform:translateY(0);pointer-events:auto;}
.continue-btn:hover{opacity:0.88;}
.role-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;width:100%;margin-bottom:14px;}
.role-card{padding:20px 14px;border:1px solid rgba(250,247,242,0.1);border-radius:12px;cursor:pointer;text-align:left;transition:border-color 0.2s,background 0.2s,transform 0.15s;background:rgba(250,247,242,0.03);}
.role-card:hover{border-color:rgba(250,247,242,0.2);transform:translateY(-1px);}
.role-card.selected{border-color:var(--terracotta);background:rgba(193,127,74,0.1);}
.rc-icon{font-size:18px;margin-bottom:10px;display:block;opacity:0.7;}
.rc-title{font-size:13px;font-weight:600;color:var(--parchment);margin-bottom:4px;}
.rc-sub{font-size:11px;color:rgba(250,247,242,0.38);line-height:1.5;}
.role-card.selected .rc-sub{color:rgba(250,247,242,0.55);}
.enter-btn{width:100%;padding:16px;background:var(--parchment);color:var(--espresso);border:none;border-radius:10px;font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:700;cursor:pointer;transition:opacity 0.2s,transform 0.15s;letter-spacing:0.01em;}
.enter-btn:hover{opacity:0.9;transform:translateY(-1px);}
.dots{display:flex;align-items:center;justify-content:center;gap:6px;margin-bottom:44px;opacity:0;animation:fadeUp 0.6s ease 0.3s forwards;}
.dot{width:5px;height:5px;border-radius:50%;background:rgba(250,247,242,0.18);transition:background 0.3s,transform 0.3s;}
.dot.active{background:var(--terracotta);transform:scale(1.3);}
.dot.done{background:rgba(250,247,242,0.4);}
.skip-link{display:block;margin-top:14px;font-size:12px;color:rgba(250,247,242,0.22);cursor:pointer;transition:color 0.15s;background:none;border:none;font-family:'Plus Jakarta Sans',sans-serif;}
.skip-link:hover{color:rgba(250,247,242,0.5);}
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

  <!-- STEP 1 -->
  <div class="step active" id="step-1">
    <h1 class="question" id="q1">What&rsquo;s your boutique called?</h1>
    <p class="hint">The demo carries your name throughout &mdash; so it feels like yours from the first screen.</p>
    <div class="input-wrap">
      <input type="text" class="big-input" id="input-shop"
             placeholder="e.g. Zawadi Boutique"
             autocomplete="organization" autocorrect="off" spellcheck="false">
    </div>
    <button class="continue-btn" id="btn-shop" onclick="goStep2()">Continue &rarr;</button>
    <button class="skip-link" onclick="skipShop()">Skip &rarr;</button>
  </div>

  <!-- STEP 2 -->
  <div class="step" id="step-2">
    <h1 class="question" id="q2">And your name?</h1>
    <p class="hint" id="hint2">So the dashboard greets you the way it will every morning.</p>
    <div class="input-wrap">
      <input type="text" class="big-input" id="input-name"
             placeholder="e.g. Sarah"
             autocomplete="given-name" autocorrect="off" spellcheck="false">
    </div>
    <button class="continue-btn" id="btn-name" onclick="goStep3()">Continue &rarr;</button>
    <button class="skip-link" onclick="goStep3(true)">Skip &rarr;</button>
  </div>

  <!-- STEP 3 -->
  <div class="step" id="step-3">
    <h1 class="question" id="q3">How do you want<br>to explore?</h1>
    <p class="hint" id="hint3">Two views. Same boutique.</p>
    <div class="role-grid">
      <div class="role-card selected" id="card-owner" onclick="selectRole('owner')">
        <span class="rc-icon">◈</span>
        <div class="rc-title">As the owner</div>
        <div class="rc-sub">Dashboard, shifts, reports &mdash; the full picture.</div>
      </div>
      <div class="role-card" id="card-staff" onclick="selectRole('staff')">
        <span class="rc-icon">◇</span>
        <div class="rc-title">As staff</div>
        <div class="rc-sub">The till, recording a sale, closing a shift.</div>
      </div>
    </div>
    <button class="enter-btn" onclick="submitDemo()">Enter the demo &rarr;</button>
    <button class="skip-link" onclick="submitDemo()">Just show me</button>
  </div>

</div>

<script>
var shopName='', ownerName='', role='owner', currentStep=1;
var progressMap={1:'8%',2:'50%',3:'88%'};

// Pre-select role from URL ?role= param
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
  q1.innerHTML=shopName?'<em>'+esc(shopName)+'</em>':'What&rsquo;s your boutique called?';
  btnShop.classList.toggle('ready',shopName.length>0);
});
shopInput.addEventListener('keydown',function(e){if(e.key==='Enter'&&shopName)goStep2();});

function goStep2(){
  document.getElementById('f-shop').value=shopName||'';
  if(shopName){document.getElementById('hint2').textContent='So the dashboard greets you every morning at '+shopName+'.';}
  goStep(1,2);
}
function skipShop(){shopName='';document.getElementById('f-shop').value='';goStep(1,2);}

// STEP 2
var nameInput=document.getElementById('input-name');
var btnName=document.getElementById('btn-name');
var q2=document.getElementById('q2');
nameInput.addEventListener('input',function(){
  ownerName=this.value.trim().split(' ')[0];
  q2.innerHTML=ownerName?'Good morning, <em>'+esc(ownerName)+'</em>.':'And your name?';
  btnName.classList.toggle('ready',ownerName.length>0);
});
nameInput.addEventListener('keydown',function(e){if(e.key==='Enter')goStep3();});

function goStep3(skip){
  if(!skip&&ownerName){
    document.getElementById('f-name').value=ownerName;
    var q3=document.getElementById('q3');
    q3.innerHTML=esc(ownerName)+', how do you<br>want to explore?';
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
  document.getElementById('demo-form').submit();
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
