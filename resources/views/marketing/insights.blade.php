<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Insights — Stoka</title>
<meta name="description" content="Honest examinations of the real economics of running a boutique.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400;1,600&family=DM+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --espresso: #1C1814;
    --terracotta: #C17F4A;
    --parchment: #FAF7F2;
    --forest: #4A6741;
    --clay: #B85C38;
    --surface: #F2EDE6;
    --muted: #8C8279;
    --border: #E8E2DA;
  }
  html { scroll-behavior: smooth; }
  body { background: var(--parchment); color: var(--espresso); font-family: 'Plus Jakarta Sans', sans-serif; }
  a { text-decoration: none; color: inherit; }

  .nav {
    position: fixed;
    top: 0; left: 0;
    width: 100%;
    height: 56px;
    background: var(--espresso);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 24px;
    z-index: 100;
  }
  .nav-logo {
    font-family: 'Cormorant Garamond', serif;
    font-size: 22px;
    font-weight: 600;
    color: var(--parchment);
    letter-spacing: -0.02em;
    display: flex;
    align-items: center;
    gap: 3px;
  }
  .nav-logo-dot { color: var(--terracotta); font-size: 24px; line-height: 1; }
  .nav-right { display: flex; align-items: center; gap: 24px; }
  .nav-insights {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13px;
    font-weight: 500;
    color: var(--parchment);
    opacity: 0.7;
    display: none;
  }
  @media (min-width: 640px) { .nav-insights { display: inline; } }
  .nav-cta {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13px;
    font-weight: 600;
    color: var(--terracotta);
  }

  .page-hero {
    padding-top: 56px;
    background: var(--parchment);
    padding-bottom: 0;
  }
  .hero-inner {
    max-width: 800px;
    margin: 0 auto;
    padding: 72px 24px 56px;
    border-bottom: 1px solid var(--border);
  }
  .hero-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 48px;
    font-weight: 600;
    color: var(--espresso);
    line-height: 1.1;
    margin-bottom: 14px;
  }
  .hero-sub {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 16px;
    color: var(--muted);
    line-height: 1.5;
  }

  .articles-section {
    background: var(--parchment);
    padding: 56px 24px 100px;
  }
  .articles-inner {
    max-width: 1000px;
    margin: 0 auto;
  }
  .articles-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
  }
  @media (min-width: 680px) {
    .articles-grid { grid-template-columns: repeat(2, 1fr); }
  }
  .article-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 28px;
    display: block;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .article-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 32px rgba(28, 24, 20, 0.08);
  }
  .article-card-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 22px;
    font-weight: 600;
    color: var(--espresso);
    line-height: 1.2;
    margin-bottom: 12px;
  }
  .article-card-preview {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13px;
    color: #5A524C;
    line-height: 1.65;
  }
  .article-card-read {
    display: block;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 12px;
    font-weight: 600;
    color: var(--terracotta);
    margin-top: 16px;
  }

  .footer {
    background: var(--espresso);
    padding: 60px 24px 40px;
  }
  .footer .inner {
    max-width: 1100px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 48px;
  }
  @media (min-width: 768px) { .footer .inner { flex-direction: row; } }
  .footer-col { flex: 1; }
  .footer-logo {
    font-family: 'Cormorant Garamond', serif;
    font-size: 22px;
    font-weight: 600;
    color: var(--parchment);
    letter-spacing: -0.02em;
    display: flex;
    align-items: center;
    gap: 3px;
  }
  .footer-logo-dot { color: var(--terracotta); }
  .footer-built { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 12px; color: rgba(250,247,242,0.40); margin-top: 8px; }
  .footer-col-label { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(250,247,242,0.40); margin-bottom: 12px; }
  .footer-links { display: flex; flex-direction: column; }
  .footer-links a { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; color: rgba(250,247,242,0.65); line-height: 2.2; transition: color 0.15s; }
  .footer-links a:hover { color: var(--parchment); }
  .footer-contact a { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; color: var(--terracotta); display: block; margin-bottom: 8px; }
  .footer-privacy { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; color: rgba(250,247,242,0.40); margin-top: 8px; display: block; }
  .footer-domain { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 12px; color: rgba(250,247,242,0.25); margin-top: 16px; display: block; }
  @media (max-width: 767px) { .footer-col-center { order: 3; } }
</style>
</head>
<body>

<nav class="nav">
  <a href="/" class="nav-logo">stoka<span class="nav-logo-dot">&middot;</span></a>
  <div class="nav-right">
    <a href="/insights" class="nav-insights">Insights</a>
    <a href="https://demo.tempforest.com/quick-login/owner" class="nav-cta">Start free trial &rarr;</a>
  </div>
</nav>

<div class="page-hero">
  <div class="hero-inner">
    <h1 class="hero-title">Guides for boutiques that run tight.</h1>
    <p class="hero-sub">Practical examinations of how boutiques track stock, manage staff, and keep their numbers honest.</p>
  </div>
</div>

<section class="articles-section">
  <div class="articles-inner">
    <div class="articles-grid">
      @foreach($articles as $article)
      <a href="/insights/{{ $article['slug'] }}" class="article-card">
        <div class="article-card-title">{{ $article['title'] }}</div>
        <div class="article-card-preview">{{ $article['preview'] }}</div>
        <span class="article-card-read">Read &rarr;</span>
      </a>
      @endforeach
    </div>
  </div>
</section>

<footer class="footer">
  <div class="inner">
    <div class="footer-col">
      <div class="footer-logo">stoka<span class="footer-logo-dot">&middot;</span></div>
      <div class="footer-built">Built in Kenya &middot; 2026</div>
    </div>
    <div class="footer-col footer-col-center">
      <div class="footer-col-label">Insights</div>
      <div class="footer-links">
        <a href="/insights/the-notebook">Your notebook isn&rsquo;t lying to you. It just can&rsquo;t tell you the truth.</a>
        <a href="/insights/last-tuesday">How much did your shop make last Thursday?</a>
        <a href="/insights/buying-on-instinct">The real cost of buying on instinct</a>
        <a href="/insights/the-staff-problem">The staff problem that is not a staff problem</a>
        <a href="/insights/end-of-day-summary">What your end-of-day summary is not telling you</a>
        
      </div>
    </div>
    <div class="footer-col footer-contact">
      <a href="https://wa.me/254741641925">WhatsApp us &rarr;</a>
      <span class="footer-privacy">Privacy policy</span>
      <span class="footer-domain">stoka.co.ke</span>
    </div>
  </div>
</footer>

</body>
</html>
