<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $article['title'] }} — Stoka</title>
<meta name="description" content="{{ $article['preview'] }}">
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

  .article-wrap {
    padding-top: 56px;
    min-height: 100vh;
  }
  .article-inner {
    max-width: 680px;
    margin: 0 auto;
    padding: 72px 24px 100px;
  }
  .article-back {
    display: inline-block;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
    margin-bottom: 40px;
    letter-spacing: 0.02em;
    transition: color 0.15s;
  }
  .article-back:hover { color: var(--espresso); }
  .article-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 36px;
    font-weight: 600;
    color: var(--espresso);
    line-height: 1.15;
    margin-bottom: 16px;
  }
  .article-byline {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 12px;
    color: var(--muted);
    margin-bottom: 52px;
    letter-spacing: 0.02em;
  }
  .article-body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 16px;
    line-height: 1.85;
    color: #3D3530;
  }
  .article-body p {
    margin-bottom: 28px;
  }
  .article-body p:last-child { margin-bottom: 0; }
  .article-cta {
    margin-top: 56px;
    padding-top: 32px;
    border-top: 1px solid var(--border);
  }
  .article-cta a {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 15px;
    font-weight: 600;
    color: var(--terracotta);
    transition: opacity 0.15s;
  }
  .article-cta a:hover { opacity: 0.75; }

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

<div class="article-wrap">
  <div class="article-inner">
    <a href="/insights" class="article-back">&larr; All insights</a>
    <h1 class="article-title">{{ $article['title'] }}</h1>
    <div class="article-byline">Stoka &middot; 2026</div>
    <div class="article-body">
      @foreach(explode("\n\n", $article['body']) as $paragraph)
        <p>{{ $paragraph }}</p>
      @endforeach
    </div>
    <div class="article-cta">
      <a href="https://demo.tempforest.com/quick-login/owner">This is what Stoka was built to solve. See it working &rarr;</a>
    </div>
  </div>
</div>

<footer class="footer">
  <div class="inner">
    <div class="footer-col">
      <div class="footer-logo">stoka<span class="footer-logo-dot">&middot;</span></div>
      <div class="footer-built">Built in Kenya &middot; 2026</div>
    </div>
    <div class="footer-col footer-col-center">
      <div class="footer-col-label">Insights</div>
      <div class="footer-links">
        <a href="/insights/the-notebook">The notebook is not the problem</a>
        <a href="/insights/last-tuesday">How much did you actually make last Tuesday?</a>
        <a href="/insights/buying-on-instinct">The real cost of buying on instinct</a>
        <a href="/insights/the-staff-problem">The staff problem that is not a staff problem</a>
        <a href="/insights/end-of-day-summary">What your end-of-day summary is not telling you</a>
        <a href="/insights/credit">The credit that never comes back</a>
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
