<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Insights — Stoka</title>
<meta name="description" content="Honest examinations of the real economics of running a boutique in East Africa.">
<meta property="og:title" content="Insights — Stoka">
<meta property="og:description" content="Honest examinations of the real economics of running a boutique in East Africa.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="Stoka">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Insights — Stoka">
<meta name="twitter:description" content="Honest examinations of the real economics of running a boutique.">
<link rel="canonical" href="{{ url()->current() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500&family=DM+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{--espresso:#1C1814;--terracotta:#C17F4A;--parchment:#FAF7F2;--forest:#4A6741;--clay:#B85C38;--surface:#F2EDE6;--muted:#8C8279;--border:#E8E2DA;}
html{scroll-behavior:smooth;}
body{background:var(--parchment);color:var(--espresso);font-family:'Plus Jakarta Sans',sans-serif;-webkit-font-smoothing:antialiased;}
a{text-decoration:none;color:inherit;}

/* NAV */
.nav{position:fixed;top:0;left:0;width:100%;background:var(--parchment);border-bottom:1px solid transparent;display:flex;align-items:center;justify-content:space-between;padding:0 48px;height:60px;z-index:200;transition:border-color 0.3s,box-shadow 0.3s;}
.nav.scrolled{border-bottom-color:var(--border);box-shadow:0 1px 12px rgba(28,24,20,0.06);}
.nav-logo{font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;color:var(--espresso);letter-spacing:-0.02em;}
.nav-logo-dot{color:var(--terracotta);}
.nav-right{display:flex;align-items:center;gap:24px;}
.nav-quiet{font-size:13px;font-weight:500;color:var(--muted);transition:color 0.15s;}
.nav-quiet:hover{color:var(--espresso);}
.nav-quiet.active{color:var(--espresso);font-weight:600;}
.nav-cta{display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--parchment);background:var(--espresso);padding:8px 18px;border-radius:7px;transition:opacity 0.15s;}
.nav-cta:hover{opacity:0.82;}
@media(max-width:640px){.nav{padding:0 20px;}.nav-quiet{display:none;}}

/* HERO */
.page-hero{padding-top:60px;background:var(--espresso);position:relative;overflow:hidden;}
.page-hero::after{content:'';position:absolute;bottom:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(193,127,74,0.5),transparent);}
.hero-inner{max-width:880px;margin:0 auto;padding:64px 32px 56px;position:relative;z-index:1;}
.hero-crumb{display:flex;align-items:center;gap:8px;margin-bottom:28px;}
.hero-crumb a{font-size:11px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:rgba(250,247,242,0.35);transition:color 0.15s;}
.hero-crumb a:hover{color:var(--terracotta);}
.hero-crumb-sep{font-size:11px;color:rgba(250,247,242,0.15);}
.hero-crumb-cur{font-size:11px;color:rgba(250,247,242,0.25);}
.hero-title{font-family:'Cormorant Garamond',serif;font-size:clamp(32px,5vw,54px);font-weight:400;line-height:1.08;color:var(--parchment);letter-spacing:-0.01em;margin-bottom:18px;max-width:620px;}
.hero-sub{font-size:15px;line-height:1.7;color:rgba(250,247,242,0.45);max-width:480px;}

/* ARTICLES */
.articles-section{background:var(--parchment);padding:64px 32px 100px;}
@media(max-width:640px){.articles-section{padding:48px 20px 80px;}}
.articles-inner{max-width:880px;margin:0 auto;}
.articles-count{font-size:10px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:var(--terracotta);margin-bottom:32px;display:block;}

/* Featured first card — full width, different treatment */
.article-card-featured{
  display:block;
  background:white;
  border:1px solid var(--border);
  border-radius:16px;
  padding:36px;
  margin-bottom:16px;
  position:relative;
  overflow:hidden;
  transition:transform 0.2s,box-shadow 0.2s;
}
.article-card-featured::before{
  content:'';position:absolute;top:0;right:0;
  width:200px;height:200px;
  background:radial-gradient(circle at top right,rgba(193,127,74,0.07),transparent 65%);
  pointer-events:none;
}
.article-card-featured:hover{transform:translateY(-2px);box-shadow:0 8px 32px rgba(28,24,20,0.08);}
.card-num{font-family:'DM Mono',monospace;font-size:11px;color:var(--terracotta);letter-spacing:0.08em;margin-bottom:12px;display:block;}
.featured-title{font-family:'Cormorant Garamond',serif;font-size:clamp(22px,3vw,30px);font-weight:400;color:var(--espresso);line-height:1.15;margin-bottom:12px;}
.featured-preview{font-size:14px;color:var(--muted);line-height:1.7;max-width:560px;}
.card-read{display:inline-flex;align-items:center;gap:5px;margin-top:20px;font-size:12px;font-weight:600;color:var(--terracotta);}

/* Grid for remaining cards */
.articles-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px;}
@media(max-width:640px){.articles-grid{grid-template-columns:1fr;}}
.article-card{
  display:block;background:white;border:1px solid var(--border);
  border-radius:14px;padding:26px;
  transition:transform 0.2s,box-shadow 0.2s;
}
.article-card:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(28,24,20,0.08);}
.card-title{font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:400;color:var(--espresso);line-height:1.2;margin-bottom:10px;}
.card-preview{font-size:12px;color:var(--muted);line-height:1.65;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;}

/* BOTTOM CTA BAND */
.cta-band{background:var(--espresso);padding:64px 32px;text-align:center;}
@media(max-width:640px){.cta-band{padding:48px 20px;}}
.cta-band-inner{max-width:560px;margin:0 auto;}
.cta-eyebrow{font-size:10px;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:var(--terracotta);margin-bottom:16px;display:block;}
.cta-headline{font-family:'Cormorant Garamond',serif;font-size:clamp(26px,4vw,40px);font-weight:400;line-height:1.1;color:var(--parchment);margin-bottom:12px;}
.cta-sub{font-size:14px;color:rgba(250,247,242,0.45);margin-bottom:32px;line-height:1.7;}
.cta-actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;}
.cta-btn-primary{display:inline-flex;align-items:center;gap:7px;padding:13px 26px;background:var(--terracotta);color:var(--parchment);border-radius:9px;font-size:13px;font-weight:600;transition:opacity 0.15s,transform 0.15s;}
.cta-btn-primary:hover{opacity:0.88;transform:translateY(-1px);}
.cta-btn-ghost{display:inline-flex;align-items:center;gap:7px;padding:12px 22px;border:1px solid rgba(250,247,242,0.15);color:rgba(250,247,242,0.55);border-radius:9px;font-size:13px;font-weight:500;transition:border-color 0.15s,color 0.15s;}
.cta-btn-ghost:hover{border-color:rgba(250,247,242,0.35);color:var(--parchment);}

/* FLOATING WA */
.wa-float{position:fixed;bottom:28px;right:28px;z-index:150;display:flex;align-items:center;gap:10px;background:var(--espresso);color:var(--parchment);padding:12px 20px;border-radius:50px;font-size:13px;font-weight:600;box-shadow:0 4px 20px rgba(28,24,20,0.22);transition:transform 0.25s,box-shadow 0.25s,opacity 0.3s;transform:translateY(80px);opacity:0;}
.wa-float.visible{transform:translateY(0);opacity:1;}
.wa-float:hover{transform:translateY(-2px);box-shadow:0 8px 32px rgba(28,24,20,0.3);}
.wa-icon{width:22px;height:22px;background:#25D366;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.wa-icon svg{width:13px;height:13px;}
@media(max-width:480px){.wa-float{bottom:18px;right:14px;padding:11px 15px;font-size:12px;}}

/* FOOTER */
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

/* FADE IN */
.fade-in{opacity:0;transform:translateY(14px);transition:opacity 0.55s ease,transform 0.55s ease;}
.fade-in.visible{opacity:1;transform:translateY(0);}
</style>
</head>
<body>

<nav class="nav" id="main-nav">
  <a href="/" class="nav-logo">stoka<span class="nav-logo-dot">&middot;</span></a>
  <div class="nav-right">
    <a href="/insights" class="nav-quiet active">Insights</a>
    <a href="https://demo.tempforest.com/demo" class="nav-cta">Try the demo &rarr;</a>
  </div>
</nav>

<div class="page-hero">
  <div class="hero-inner">
    <div class="hero-crumb">
      <a href="/">stoka</a>
      <span class="hero-crumb-sep">/</span>
      <span class="hero-crumb-cur">insights</span>
    </div>
    <h1 class="hero-title">What nobody tells you about running a boutique.</h1>
    <p class="hero-sub">Honest examinations of the real economics &mdash; shifts, stock, credit, and what it actually means to know your numbers.</p>
  </div>
</div>

<section class="articles-section">
  <div class="articles-inner">
    <span class="articles-count fade-in">{{ count($articles) }} pieces</span>

    @php $first = $articles[0]; $rest = array_slice($articles, 1); @endphp

    {{-- Featured first article --}}
    <a href="/insights/{{ $first['slug'] }}" class="article-card-featured fade-in">
      <span class="card-num">01 &mdash; Featured</span>
      <div class="featured-title">{{ $first['title'] }}</div>
      <div class="featured-preview">{{ $first['preview'] }}</div>
      <span class="card-read">Read &rarr;</span>
    </a>

    {{-- Remaining articles in 2-col grid --}}
    <div class="articles-grid">
      @foreach($rest as $i => $article)
      <a href="/insights/{{ $article['slug'] }}" class="article-card fade-in">
        <span class="card-num">0{{ $i + 2 }}</span>
        <div class="card-title">{{ $article['title'] }}</div>
        <div class="card-preview">{{ $article['preview'] }}</div>
        <span class="card-read">Read &rarr;</span>
      </a>
      @endforeach
    </div>

  </div>
</section>

<div class="cta-band">
  <div class="cta-band-inner">
    <span class="cta-eyebrow">See it working</span>
    <h2 class="cta-headline">Your shop. Your numbers. No guesswork.</h2>
    <p class="cta-sub">Type your shop name and see what the dashboard looks like for yours. No account, no card, nothing to install.</p>
    <div class="cta-actions">
      <a href="https://demo.tempforest.com/demo" class="cta-btn-primary">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
        Try the demo &rarr;
      </a>
      <a href="https://wa.me/254741641925?text={{ urlencode('Hi! I read the Stoka insights and have a question.') }}" target="_blank" class="cta-btn-ghost">
        Ask on WhatsApp
      </a>
    </div>
  </div>
</div>

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

<a href="https://wa.me/254741641925?text={{ urlencode('Hi! I was reading the Stoka insights and have a question.') }}" target="_blank" class="wa-float" id="wa-float">
  <div class="wa-icon">
    <svg viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
  </div>
  Ask on WhatsApp &rarr;
</a>

<script>
const nav=document.getElementById('main-nav'),waFloat=document.getElementById('wa-float');
window.addEventListener('scroll',()=>{
  nav.classList.toggle('scrolled',window.scrollY>20);
  waFloat.classList.toggle('visible',window.scrollY>300);
},{passive:true});
const obs=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)e.target.classList.add('visible');}),{threshold:0.08});
document.querySelectorAll('.fade-in').forEach(el=>obs.observe(el));
</script>
</body>
</html>
