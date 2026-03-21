<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $article['title'] }} — Stoka Insights</title>
<meta name="description" content="{{ $article['preview'] }}">
<meta property="og:title" content="{{ $article['title'] }}">
<meta property="og:description" content="{{ $article['preview'] }}">
<meta property="og:type" content="article">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="Stoka">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $article['title'] }}">
<meta name="twitter:description" content="{{ $article['preview'] }}">
<link rel="canonical" href="{{ url()->current() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500&family=DM+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{
  --espresso:#1C1814;--terracotta:#C17F4A;--parchment:#FAF7F2;
  --forest:#4A6741;--clay:#B85C38;--surface:#F2EDE6;
  --muted:#8C8279;--border:#E8E2DA;
}
html{scroll-behavior:smooth;}
body{background:var(--parchment);color:var(--espresso);font-family:'Plus Jakarta Sans',sans-serif;-webkit-font-smoothing:antialiased;}
a{text-decoration:none;color:inherit;}
.nav{position:fixed;top:0;left:0;width:100%;background:var(--parchment);border-bottom:1px solid transparent;display:flex;align-items:center;justify-content:space-between;padding:0 48px;height:60px;z-index:200;transition:border-color 0.3s,box-shadow 0.3s;}
.nav.scrolled{border-bottom-color:var(--border);box-shadow:0 1px 12px rgba(28,24,20,0.06);}
.nav-logo{font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;color:var(--espresso);letter-spacing:-0.02em;}
.nav-logo-dot{color:var(--terracotta);}
.nav-right{display:flex;align-items:center;gap:24px;}
.nav-quiet{font-size:13px;font-weight:500;color:var(--muted);transition:color 0.15s;}
.nav-quiet:hover{color:var(--espresso);}
.nav-cta{display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--parchment);background:var(--espresso);padding:8px 18px;border-radius:7px;transition:opacity 0.15s;}
.nav-cta:hover{opacity:0.82;}
@media(max-width:640px){.nav{padding:0 20px;}.nav-quiet{display:none;}}
.progress-bar{position:fixed;top:0;left:0;height:2px;width:0%;background:linear-gradient(90deg,var(--terracotta),#E8A56A);z-index:300;transition:width 0.08s linear;}
.article-hero{padding-top:60px;background:var(--espresso);position:relative;overflow:hidden;}
.article-hero::after{content:'';position:absolute;bottom:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(193,127,74,0.5),transparent);}
.hero-inner{max-width:780px;margin:0 auto;padding:60px 32px 52px;position:relative;z-index:1;}
.hero-crumb{display:flex;align-items:center;gap:8px;margin-bottom:28px;}
.hero-crumb a{font-size:11px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:rgba(250,247,242,0.35);transition:color 0.15s;}
.hero-crumb a:hover{color:var(--terracotta);}
.hero-crumb-sep{font-size:11px;color:rgba(250,247,242,0.15);}
.hero-crumb-cur{font-size:11px;color:rgba(250,247,242,0.25);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:220px;}
.hero-title{font-family:'Cormorant Garamond',serif;font-size:clamp(30px,5vw,50px);font-weight:400;line-height:1.1;color:var(--parchment);letter-spacing:-0.01em;margin-bottom:24px;max-width:640px;}
.hero-meta{display:flex;align-items:center;gap:14px;flex-wrap:wrap;}
.hero-pill{display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:var(--terracotta);background:rgba(193,127,74,0.1);border:1px solid rgba(193,127,74,0.2);padding:5px 12px;border-radius:20px;}
.hero-pill-dot{width:4px;height:4px;border-radius:50%;background:var(--terracotta);}
.hero-byline{font-size:12px;color:rgba(250,247,242,0.3);letter-spacing:0.04em;}
.article-layout{max-width:780px;margin:0 auto;padding:0 32px;display:grid;grid-template-columns:1fr 52px;gap:0 36px;align-items:start;}
@media(max-width:768px){.article-layout{grid-template-columns:1fr;padding:0 20px;}.article-sidebar{display:none;}}
.article-body{padding:60px 0 72px;}
.share-row{display:none;align-items:center;gap:10px;padding:18px 0;border-top:1px solid var(--border);border-bottom:1px solid var(--border);margin-bottom:44px;}
@media(max-width:768px){.share-row{display:flex;}}
.share-label{font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:var(--muted);flex-shrink:0;}
.share-btn{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:20px;font-size:12px;font-weight:600;border:1px solid var(--border);color:var(--espresso);background:white;cursor:pointer;transition:background 0.15s;white-space:nowrap;}
.share-btn:hover{background:var(--surface);}
.like-mobile{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:20px;font-size:12px;font-weight:600;border:1px solid var(--border);color:var(--espresso);background:white;cursor:pointer;transition:all 0.15s;margin-left:auto;white-space:nowrap;}
.like-mobile.liked{background:#FFF0E8;border-color:var(--terracotta);color:var(--terracotta);}
.article-body p{font-family:'Cormorant Garamond',serif;font-size:21px;line-height:1.78;color:#2A201A;margin-bottom:30px;font-weight:400;}
.article-body p:last-child{margin-bottom:0;}
.article-body p:first-child::first-letter{font-family:'Cormorant Garamond',serif;font-size:84px;font-weight:300;line-height:0.78;float:left;margin-right:6px;margin-top:10px;color:var(--terracotta);letter-spacing:-0.02em;}
.article-body p:nth-child(3){padding-left:20px;border-left:2px solid rgba(193,127,74,0.5);font-style:italic;color:var(--espresso);}
.article-sidebar{position:sticky;top:80px;padding-top:60px;display:flex;flex-direction:column;align-items:center;gap:16px;}
.sa{display:flex;flex-direction:column;align-items:center;gap:5px;cursor:pointer;opacity:0.45;transition:opacity 0.2s;}
.sa:hover{opacity:1;}
.sa.liked{opacity:1;}
.sa-icon{width:40px;height:40px;border-radius:50%;background:white;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;transition:background 0.2s,border-color 0.2s,transform 0.15s;box-shadow:0 1px 4px rgba(28,24,20,0.06);}
.sa:hover .sa-icon{transform:scale(1.1);}
.sa.liked .sa-icon{background:#FFF0E8;border-color:var(--terracotta);}
.sa-label{font-family:'DM Mono',monospace;font-size:10px;color:var(--muted);letter-spacing:0.03em;}
.sa-divider{width:1px;height:16px;background:var(--border);}
.article-end{max-width:780px;margin:0 auto;padding:0 32px 72px;}
@media(max-width:768px){.article-end{padding:0 20px 56px;}}
.end-rule{height:1px;background:linear-gradient(90deg,var(--terracotta),transparent);margin-bottom:44px;opacity:0.35;}
.end-cta{background:var(--espresso);border-radius:20px;padding:44px 48px;position:relative;overflow:hidden;}
.end-cta::before{content:'';position:absolute;top:0;right:0;width:280px;height:280px;background:radial-gradient(circle at top right,rgba(193,127,74,0.18),transparent 65%);pointer-events:none;}
.end-eyebrow{font-size:10px;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:var(--terracotta);margin-bottom:14px;display:block;}
.end-headline{font-family:'Cormorant Garamond',serif;font-size:clamp(24px,4vw,36px);font-weight:400;line-height:1.15;color:var(--parchment);margin-bottom:12px;max-width:460px;}
.end-sub{font-size:14px;line-height:1.7;color:rgba(250,247,242,0.48);margin-bottom:30px;max-width:400px;}
.end-actions{display:flex;gap:10px;flex-wrap:wrap;align-items:center;}
.end-btn-primary{display:inline-flex;align-items:center;gap:7px;padding:12px 22px;background:var(--terracotta);color:var(--parchment);border-radius:9px;font-size:13px;font-weight:600;letter-spacing:0.02em;transition:opacity 0.15s,transform 0.15s;}
.end-btn-primary:hover{opacity:0.88;transform:translateY(-1px);}
.end-btn-ghost{display:inline-flex;align-items:center;gap:7px;padding:11px 18px;border:1px solid rgba(250,247,242,0.15);color:rgba(250,247,242,0.55);border-radius:9px;font-size:13px;font-weight:500;transition:border-color 0.15s,color 0.15s;}
.end-btn-ghost:hover{border-color:rgba(250,247,242,0.35);color:var(--parchment);}
@media(max-width:600px){.end-cta{padding:30px 22px;}}
.recommended{background:var(--surface);padding:60px 32px;border-top:1px solid var(--border);}
@media(max-width:768px){.recommended{padding:48px 20px;}}
.rec-inner{max-width:780px;margin:0 auto;}
.rec-label{font-size:10px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:var(--terracotta);margin-bottom:28px;display:block;}
.rec-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:14px;}
.rec-card{background:white;border:1px solid var(--border);border-radius:14px;padding:22px;display:block;transition:transform 0.2s,box-shadow 0.2s;}
.rec-card:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(28,24,20,0.08);}
.rec-title{font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:500;color:var(--espresso);line-height:1.25;margin-bottom:9px;}
.rec-preview{font-size:12px;color:var(--muted);line-height:1.65;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;}
.rec-read{display:inline-block;margin-top:12px;font-size:11px;font-weight:600;color:var(--terracotta);}
.wa-float{position:fixed;bottom:28px;right:28px;z-index:150;display:flex;align-items:center;gap:10px;background:var(--espresso);color:var(--parchment);padding:12px 20px;border-radius:50px;font-size:13px;font-weight:600;box-shadow:0 4px 20px rgba(28,24,20,0.22);transition:transform 0.25s,box-shadow 0.25s,opacity 0.3s;transform:translateY(80px);opacity:0;}
.wa-float.visible{transform:translateY(0);opacity:1;}
.wa-float:hover{transform:translateY(-2px);box-shadow:0 8px 32px rgba(28,24,20,0.3);}
.wa-icon{width:22px;height:22px;background:#25D366;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.wa-icon svg{width:13px;height:13px;}
@media(max-width:480px){.wa-float{bottom:18px;right:14px;padding:11px 15px;font-size:12px;}}
.footer{background:var(--espresso);padding:52px 28px 36px;}
.footer-inner{max-width:1080px;margin:0 auto;display:flex;justify-content:space-between;gap:48px;flex-wrap:wrap;align-items:flex-start;}
.footer-logo{font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;color:var(--parchment);letter-spacing:-0.02em;}
.footer-logo-dot{color:var(--terracotta);}
.footer-tagline{font-size:12px;color:rgba(250,247,242,0.35);margin-top:8px;max-width:200px;line-height:1.6;}
.footer-col{display:flex;flex-direction:column;gap:5px;}
.footer-col-label{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(250,247,242,0.3);margin-bottom:8px;}
.footer-link{font-size:13px;color:rgba(250,247,242,0.6);line-height:2.0;transition:color 0.15s;}
.footer-link:hover{color:var(--parchment);}
.footer-bottom{max-width:1080px;margin:36px auto 0;padding:20px 28px 0;border-top:1px solid rgba(250,247,242,0.08);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;}
.footer-copy{font-size:11px;color:rgba(250,247,242,0.25);}
.footer-wa{font-size:12px;font-weight:600;color:var(--terracotta);}
.fade-in{opacity:0;transform:translateY(14px);transition:opacity 0.55s ease,transform 0.55s ease;}
.fade-in.visible{opacity:1;transform:translateY(0);}
</style>
</head>
<body>
<div class="progress-bar" id="progress"></div>
<nav class="nav" id="main-nav">
  <a href="/" class="nav-logo">stoka<span class="nav-logo-dot">&middot;</span></a>
  <div class="nav-right">
    <a href="/insights" class="nav-quiet">Insights</a>
    <a href="https://demo.tempforest.com/demo" class="nav-cta">Try the demo &rarr;</a>
  </div>
</nav>
<div class="article-hero">
  <div class="hero-inner">
    <div class="hero-crumb">
      <a href="/">stoka</a>
      <span class="hero-crumb-sep">/</span>
      <a href="/insights">insights</a>
      <span class="hero-crumb-sep">/</span>
      <span class="hero-crumb-cur">{{ Str::limit($article['title'], 36) }}</span>
    </div>
    <h1 class="hero-title">{{ $article['title'] }}</h1>
    <div class="hero-meta">
      <span class="hero-pill"><span class="hero-pill-dot"></span>Boutique Intelligence</span>
      <span class="hero-byline">Stoka &middot; 2026 &middot; 4 min read</span>
    </div>
  </div>
</div>
<div class="article-layout">
  <div class="article-body" id="article-body">
    <div class="share-row">
      <span class="share-label">Share</span>
      <a href="https://twitter.com/intent/tweet?text={{ urlencode($article['title']) }}&url={{ urlencode(url()->current()) }}&via=stokaapp" target="_blank" rel="noopener" class="share-btn">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.748l7.73-8.835L1.254 2.25H8.08l4.259 5.622 5.9-5.622Zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        Share on X
      </a>
      <button class="share-btn" onclick="copyLink(this)">Copy link</button>
      <button class="like-mobile" id="like-mobile" onclick="toggleLike()">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" id="heart-mobile"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        <span id="count-mobile">24</span>
      </button>
    </div>
    @php $paragraphs = array_values(array_filter(explode("\n\n", $article['body']))); @endphp
    @foreach($paragraphs as $para)
      <p>{{ $para }}</p>
    @endforeach
  </div>
  <div class="article-sidebar">
    <div class="sa" id="like-btn" onclick="toggleLike()">
      <div class="sa-icon">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" id="heart-icon">
          <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
        </svg>
      </div>
      <span class="sa-label" id="like-count">24</span>
    </div>
    <div class="sa-divider"></div>
    <a href="https://twitter.com/intent/tweet?text={{ urlencode($article['title']) }}&url={{ urlencode(url()->current()) }}&via=stokaapp" target="_blank" rel="noopener" class="sa">
      <div class="sa-icon">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.748l7.73-8.835L1.254 2.25H8.08l4.259 5.622 5.9-5.622Zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
      </div>
      <span class="sa-label">X</span>
    </a>
    <div class="sa-divider"></div>
    <div class="sa" onclick="copyLink()">
      <div class="sa-icon">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
      </div>
      <span class="sa-label" id="copy-label">Link</span>
    </div>
  </div>
</div>
<div class="article-end fade-in">
  <div class="end-rule"></div>
  <div class="end-cta">
    <span class="end-eyebrow">This is what Stoka was built to solve</span>
    <h2 class="end-headline">See it in your shop &mdash; before you decide anything.</h2>
    <p class="end-sub">Type your shop name. We&rsquo;ll show you what the dashboard looks like for yours. No account. No card. Nothing to install.</p>
    <div class="end-actions">
      <a href="https://demo.tempforest.com/demo" class="end-btn-primary">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
        See it in your shop &rarr;
      </a>
      <a href="https://wa.me/254741641925?text={{ urlencode('Hi! I just read ' . $article['title'] . ' on Stoka and have questions.') }}" target="_blank" class="end-btn-ghost">
        Ask on WhatsApp
      </a>
    </div>
  </div>
</div>
@if(!empty($others))
<div class="recommended">
  <div class="rec-inner">
    <span class="rec-label">Continue reading</span>
    <div class="rec-grid">
      @foreach(array_slice($others, 0, 3) as $other)
      <a href="/insights/{{ $other['slug'] }}" class="rec-card fade-in">
        <div class="rec-title">{{ $other['title'] }}</div>
        <div class="rec-preview">{{ $other['preview'] }}</div>
        <span class="rec-read">Read &rarr;</span>
      </a>
      @endforeach
    </div>
  </div>
</div>
@endif
<footer class="footer">
  <div class="footer-inner">
    <div>
      <div class="footer-logo">stoka<span class="footer-logo-dot">&middot;</span></div>
      <div class="footer-tagline">Boutique intelligence.<br>Built in Kenya.</div>
    </div>
    <div class="footer-col">
      <div class="footer-col-label">Product</div>
      <a href="https://tempforest.com/#section-pricing" class="footer-link">Pricing</a>
      <a href="https://demo.tempforest.com/demo" class="footer-link">Live Demo</a>
      <a href="https://tempforest.com/register" class="footer-link">Get Started</a>
      <a href="/insights" class="footer-link">Insights</a>
    </div>
    <div class="footer-col">
      <div class="footer-col-label">Contact</div>
      <a href="https://wa.me/254741641925" class="footer-link">WhatsApp &rarr;</a>
      <a href="https://wa.me/254741641925?text=Hi%2C+I+have+a+question+about+Stoka." class="footer-link">Ask a question</a>
    </div>
  </div>
  <div class="footer-bottom">
    <span class="footer-copy">stoka.co.ke &middot; 2026</span>
    <a href="https://wa.me/254741641925" class="footer-wa">WhatsApp us &rarr;</a>
  </div>
</footer>
<a href="https://wa.me/254741641925?text={{ urlencode('Hi! I just read ' . $article['title'] . ' on Stoka and have a question.') }}" target="_blank" class="wa-float" id="wa-float">
  <div class="wa-icon">
    <svg viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
  </div>
  Ask about this &rarr;
</a>
<script>
const progress=document.getElementById('progress'),nav=document.getElementById('main-nav'),waFloat=document.getElementById('wa-float');
let liked=false,count=24;
window.addEventListener('scroll',()=>{
  const total=document.documentElement.scrollHeight-document.documentElement.clientHeight;
  progress.style.width=(window.scrollY/total*100)+'%';
  nav.classList.toggle('scrolled',window.scrollY>20);
  const heroH=document.querySelector('.article-hero').offsetHeight;
  waFloat.classList.toggle('visible',window.scrollY>heroH+80);
},{passive:true});
const obs=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)e.target.classList.add('visible');}),{threshold:0.08});
document.querySelectorAll('.fade-in').forEach(el=>obs.observe(el));
window.toggleLike=function(){
  liked=!liked;count=liked?25:24;
  const btn=document.getElementById('like-btn'),icon=document.getElementById('heart-icon'),mob=document.getElementById('like-mobile'),hm=document.getElementById('heart-mobile');
  btn.classList.toggle('liked',liked);if(mob)mob.classList.toggle('liked',liked);
  document.getElementById('like-count').textContent=count;const mc=document.getElementById('count-mobile');if(mc)mc.textContent=count;
  if(liked){icon.setAttribute('fill','var(--terracotta)');icon.setAttribute('stroke','var(--terracotta)');if(hm){hm.setAttribute('fill','var(--terracotta)');hm.setAttribute('stroke','var(--terracotta)');}const si=btn.querySelector('.sa-icon');si.style.transform='scale(1.25)';setTimeout(()=>si.style.transform='',200);}
  else{icon.setAttribute('fill','none');icon.setAttribute('stroke','currentColor');if(hm){hm.setAttribute('fill','none');hm.setAttribute('stroke','currentColor');}}
};
window.copyLink=function(el){
  navigator.clipboard.writeText(window.location.href).then(()=>{
    const lbl=document.getElementById('copy-label');if(lbl){lbl.textContent='Copied!';setTimeout(()=>lbl.textContent='Link',2000);}
    if(el&&el.tagName==='BUTTON'){const t=el.textContent;el.textContent='Copied!';setTimeout(()=>el.textContent=t,2000);}
  });
};
</script>
</body>
</html>
