<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Stoka — Boutique management built for Kenyan shops</title>
<meta name="description" content="Every shift. Every shilling. Tied to a name. Stoka gives Kenyan boutique owners full visibility without being in the shop.">
<meta name="keywords" content="boutique management Kenya, POS system Kenya, boutique software, inventory management Kenya, shop management system">
<meta name="robots" content="index, follow">
<link rel="canonical" href="https://stoka.co.ke/">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:url" content="https://stoka.co.ke/">
<meta property="og:title" content="Stoka — See exactly what's happening in your shop.">
<meta property="og:description" content="Every sale, every shift, every shilling — visible from your phone. Boutique management that works while you're away.">
<meta property="og:image" content="https://stoka.co.ke/og-homepage.png">
<meta property="og:site_name" content="Stoka">
<meta property="og:locale" content="en_KE">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Stoka — Boutique management for Kenyan shops">
<meta name="twitter:description" content="Every shift. Every shilling. Tied to a name. Stoka gives Kenyan boutique owners full visibility without being in the shop.">
<meta name="twitter:image" content="https://stoka.co.ke/og-homepage.png">

<!-- Favicon -->
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<link rel="alternate icon" href="/favicon.ico">
<link rel="apple-touch-icon" sizes="192x192" href="/icons/icon-192.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=DM+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{
  --espresso:#1C1814;--terracotta:#C17F4A;--parchment:#FAF7F2;
  --forest:#4A6741;--clay:#B85C38;--surface:#F2EDE6;
  --muted:#6B5D51;--border:#E5DDD4;--dark-wood:#2C1F14;
  --forest-bg:#EDF2EC;--clay-bg:#F6EDE8;--terra-bg:#F8F1E8;
}
html{scroll-behavior:smooth;}
body{background:var(--parchment);color:var(--espresso);font-family:'Plus Jakarta Sans',sans-serif;-webkit-font-smoothing:antialiased;}
a{text-decoration:none;color:inherit;}
.fade-up{opacity:0;transform:translateY(18px);transition:opacity 0.55s ease,transform 0.55s ease;}
.fade-up.visible{opacity:1;transform:translateY(0);}

/* ── NAV ──────────────────────────────────────────────────── */
.nav{
  position:fixed;top:0;left:0;width:100%;background:var(--parchment);
  border-bottom:1px solid transparent;display:flex;align-items:center;
  justify-content:space-between;padding:0 48px;height:60px;z-index:100;
  transition:border-color 0.2s;
}
.nav.scrolled{border-bottom-color:var(--border);}
.nav-logo{font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;color:var(--espresso);letter-spacing:-0.02em;}
.nav-logo-dot{color:var(--terracotta);}
.nav-links{display:flex;align-items:center;gap:8px;}
.nav-link{font-size:13px;font-weight:500;color:var(--muted);padding:7px 12px;border-radius:6px;transition:color 0.15s,background 0.15s;}
.nav-link:hover{color:var(--espresso);background:var(--surface);}
/* Live Demo pill — pulse dot + text */
.nav-demo{display:inline-flex;align-items:center;gap:7px;font-size:13px;font-weight:600;color:var(--forest);background:var(--forest-bg);padding:8px 14px;border-radius:20px;transition:background 0.15s;}
.nav-demo:hover{background:#ddeadc;}
.nav-demo-dot{width:6px;height:6px;border-radius:50%;background:var(--forest);flex-shrink:0;animation:live-pulse 2s ease-in-out infinite;}
/* Primary CTA */
.nav-cta{font-size:13px;font-weight:600;color:var(--parchment);background:var(--espresso);padding:9px 20px;border-radius:7px;transition:opacity 0.15s;}
.nav-cta:hover{opacity:0.82;}
@media(max-width:768px){.nav{padding:0 20px;}.nav-link.hide-sm{display:none;}}
@media(max-width:480px){.nav-link{display:none;}.nav-demo{display:inline-flex;}.nav-link.nav-cta{display:inline-block;}}

/* ── HERO ─────────────────────────────────────────────────── */
@keyframes live-pulse{0%,100%{opacity:1;}50%{opacity:0.35;}}

.hero{
  display:grid;grid-template-columns:1fr 1fr;
  min-height:100vh;max-width:1280px;margin:0 auto;
  padding:100px 56px 80px;gap:40px;align-items:center;
}
@media(max-width:900px){
  .hero{grid-template-columns:1fr;padding:80px 28px 56px;min-height:auto;gap:48px;}
}

/* left */
.hero-left{display:flex;flex-direction:column;gap:26px;max-width:480px;}
.hero-eyebrow{font-size:10px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:var(--terracotta);}
.hero-heading{font-family:'Cormorant Garamond',serif;font-size:clamp(40px,4vw,60px);font-weight:500;line-height:1.04;color:var(--espresso);letter-spacing:-0.01em;}
.hero-heading em{font-style:normal;color:var(--terracotta);}
.hero-sub{font-size:15px;line-height:1.75;color:var(--muted);max-width:380px;}
.hero-ctas{display:flex;gap:10px;flex-wrap:wrap;}
.btn-primary{display:inline-flex;align-items:center;gap:6px;padding:13px 26px;background:var(--espresso);color:var(--parchment);font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:600;letter-spacing:0.02em;border:none;border-radius:8px;cursor:pointer;text-decoration:none;transition:opacity 0.2s;}
.btn-primary:hover{opacity:0.84;}
.btn-ghost{display:inline-flex;align-items:center;gap:6px;padding:12px 26px;background:transparent;color:var(--espresso);font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:600;letter-spacing:0.02em;border:1.5px solid var(--border);border-radius:8px;cursor:pointer;text-decoration:none;transition:border-color 0.2s;}
.btn-ghost:hover{border-color:rgba(28,24,20,0.35);}
.trial-note{display:flex;align-items:center;gap:7px;font-size:12px;color:var(--forest);font-weight:600;}
.trial-dot{width:6px;height:6px;border-radius:50%;background:var(--forest);flex-shrink:0;animation:live-pulse 2s ease-in-out infinite;}
.hero-stats{display:flex;align-items:stretch;gap:28px;}
.stat-item{display:flex;flex-direction:column;gap:4px;}
.stat-number{font-family:'DM Mono',monospace;font-size:21px;font-weight:500;color:var(--espresso);letter-spacing:-0.02em;}
.stat-label{font-size:10px;color:var(--muted);letter-spacing:0.06em;text-transform:uppercase;}
.stat-rule{width:1px;background:var(--border);align-self:stretch;}
@media(max-width:900px){.hero-left{max-width:100%;}.hero-heading{font-size:40px;}.hero-right{order:-1;}}
@media(max-width:480px){.hero{padding:70px 20px 44px;}.hero-stats{gap:18px;}.stat-number{font-size:18px;}.btn-primary,.btn-ghost{font-size:12px;padding:11px 20px;}}

/* right — stage */
.hero-right{display:flex;align-items:center;justify-content:center;position:relative;}
.hero-stage{position:relative;width:540px;height:480px;}

/* dashboard card */
.dashboard-card{
  position:absolute;top:44px;left:56px;width:430px;
  background:var(--parchment);border:1px solid var(--border);
  border-radius:16px;padding:22px 22px 18px;transform:rotate(2deg);z-index:1;
}
.dash-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;}
.dash-store{font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--muted);}
.dash-period{font-size:10px;color:var(--muted);background:var(--surface);padding:3px 10px;border-radius:20px;letter-spacing:0.04em;}
.dash-total{font-family:'DM Mono',monospace;font-size:34px;font-weight:500;color:var(--espresso);letter-spacing:-0.025em;margin-bottom:5px;}
.dash-compare{font-size:11px;font-weight:600;color:var(--forest);display:flex;align-items:center;gap:4px;margin-bottom:18px;}
.dash-split{display:grid;grid-template-columns:1fr 1fr;gap:7px;margin-bottom:18px;}
.split-cell{background:var(--surface);border-radius:9px;padding:10px 12px;}
.split-label{font-size:9px;text-transform:uppercase;letter-spacing:0.1em;color:var(--muted);font-weight:700;margin-bottom:3px;}
.split-value{font-family:'DM Mono',monospace;font-size:17px;font-weight:500;color:var(--espresso);letter-spacing:-0.02em;}
.dash-rule{height:1px;background:var(--border);margin:0 -22px 14px;}
.dash-section-label{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--muted);margin-bottom:9px;}
.shift-row{display:flex;align-items:center;justify-content:space-between;}
.shift-left{display:flex;align-items:center;gap:7px;}
.shift-dot{width:6px;height:6px;border-radius:50%;background:var(--forest);flex-shrink:0;animation:live-pulse 2s ease-in-out infinite;}
.shift-name{font-size:13px;font-weight:600;color:var(--espresso);}
.shift-dur{font-size:12px;color:var(--muted);}
.shift-amount{font-family:'DM Mono',monospace;font-size:13px;color:var(--espresso);}
.recent-rows{margin-top:14px;}
.recent-row{display:flex;align-items:center;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border);}
.recent-row:last-child{border-bottom:none;}
.recent-name{font-size:12px;color:var(--espresso);font-weight:500;}
.recent-right{display:flex;align-items:center;gap:7px;}
.recent-amount{font-family:'DM Mono',monospace;font-size:12px;color:var(--espresso);}
.pay-badge{font-size:8px;font-weight:700;letter-spacing:0.07em;text-transform:uppercase;padding:2px 5px;border-radius:4px;}
.pay-cash{background:var(--surface);color:var(--muted);}
.pay-mpesa{background:var(--forest-bg);color:var(--forest);}

/* float cards */
.float-card{position:absolute;border-radius:11px;border:1px solid var(--border);background:#fff;opacity:0;z-index:10;}
.card-recon{top:-4px;left:-14px;width:198px;padding:13px 14px;border-color:var(--forest-bg);animation:anim-card1 20s ease-in-out infinite;animation-fill-mode:both;}
.card-stock{bottom:32px;right:-18px;width:206px;padding:12px 14px;border-color:var(--terra-bg);animation:anim-card2 20s ease-in-out infinite;animation-fill-mode:both;transition:border-color 0.5s ease,background 0.5s ease;}
.card-stock.stock-pulse{border-color:var(--terracotta);background:var(--terra-bg);}
.card-credit{bottom:44px;left:-16px;width:182px;padding:11px 13px;border-color:var(--clay-bg);animation:anim-card3 20s ease-in-out infinite;animation-fill-mode:both;}
.card-whatsapp{top:90px;right:-54px;width:224px;padding:12px 14px;background:var(--espresso);border-color:transparent;animation:anim-card4 20s ease-in-out infinite;animation-fill-mode:both;}
.card-pulse{top:54px;left:130px;width:178px;padding:11px 13px;border-color:var(--forest-bg);animation:anim-card5 20s ease-in-out infinite;animation-fill-mode:both;}

@keyframes anim-card1{0%{opacity:0;transform:translateY(10px);}4%{opacity:1;transform:translateY(0);}38%{transform:translateY(-3px);}65%{transform:translateY(0);}72%{opacity:1;transform:translateY(0);}80%{opacity:0.4;transform:translateY(2px);}94%{opacity:0.4;}100%{opacity:0;transform:translateY(10px);}}
@keyframes anim-card2{0%{opacity:0;transform:translateY(10px);}15%{opacity:0;transform:translateY(10px);}19%{opacity:1;transform:translateY(0);}50%{transform:translateY(-4px);}72%{transform:translateY(0);}81%{opacity:1;transform:translateY(0);}89%{opacity:0.4;transform:translateY(2px);}97%{opacity:0.4;}100%{opacity:0;transform:translateY(10px);}}
@keyframes anim-card3{0%{opacity:0;transform:translateY(10px);}30%{opacity:0;transform:translateY(10px);}34%{opacity:1;transform:translateY(0);}55%{transform:translateY(-2px);}70%{transform:translateY(0);}76%{opacity:1;transform:translateY(0);}83%{opacity:0.4;transform:translateY(2px);}96%{opacity:0.4;}100%{opacity:0;transform:translateY(10px);}}
@keyframes anim-card4{0%{opacity:0;transform:translateY(10px);}46%{opacity:0;transform:translateY(10px);}50%{opacity:1;transform:translateY(0);}86%{opacity:1;transform:translateY(0);}93%{opacity:0.4;}99%{opacity:0.4;}100%{opacity:0;transform:translateY(10px);}}
@keyframes anim-card5{0%{opacity:0;transform:translateY(10px);}60%{opacity:0;transform:translateY(10px);}64%{opacity:1;transform:translateY(0);}66%{transform:translateY(-5px);}68%{opacity:1;transform:translateY(-3px);}76%{opacity:0.4;transform:translateY(0);}95%{opacity:0.4;}100%{opacity:0;transform:translateY(10px);}}

.fc-tag{font-size:9px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;margin-bottom:7px;}
.fc-tag.green{color:var(--forest);}
.fc-tag.terra{color:var(--terracotta);}
.fc-tag.clay{color:var(--clay);}
.fc-row{display:flex;justify-content:space-between;align-items:baseline;margin-bottom:3px;}
.fc-key{font-size:11px;color:var(--muted);}
.fc-val{font-family:'DM Mono',monospace;font-size:11px;color:var(--espresso);}
.fc-balanced{display:flex;align-items:center;gap:6px;margin-top:9px;font-size:12px;font-weight:600;color:var(--forest);}
.fc-check{width:17px;height:17px;border-radius:50%;background:var(--forest);display:flex;align-items:center;justify-content:center;color:#fff;font-size:9px;flex-shrink:0;}
.fc-product-name{font-size:12px;font-weight:600;color:var(--espresso);line-height:1.4;margin-bottom:2px;}
.fc-product-sub{font-size:11px;color:var(--muted);margin-bottom:7px;}
.fc-stock-stat{font-size:12px;font-weight:600;color:var(--terracotta);}
.wa-header{display:flex;align-items:center;gap:7px;margin-bottom:8px;}
.wa-icon{width:18px;height:18px;border-radius:50%;background:#25D366;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:800;color:#fff;flex-shrink:0;font-family:'Plus Jakarta Sans',sans-serif;}
.wa-source{font-size:10px;font-weight:600;color:rgba(250,247,242,0.65);letter-spacing:0.06em;text-transform:uppercase;}
.wa-time{font-size:10px;color:rgba(250,247,242,0.35);margin-left:auto;}
.wa-body{font-size:12px;line-height:1.55;color:rgba(250,247,242,0.92);}
.wa-body-sub{font-size:11px;color:rgba(250,247,242,0.5);margin-top:2px;}
.pulse-row{display:flex;align-items:center;gap:7px;margin-bottom:4px;}
.pulse-dot{width:7px;height:7px;border-radius:50%;background:var(--forest);flex-shrink:0;animation:live-pulse 1.8s ease-in-out infinite;}
.pulse-name{font-size:13px;font-weight:600;color:var(--espresso);}
.pulse-stats{font-size:11px;color:var(--muted);padding-left:14px;}

@media(max-width:900px){
  .hero-stage{width:340px;height:300px;margin:0 auto;}
  .dashboard-card{width:270px;left:35px;top:28px;padding:16px 16px 12px;}
  .dash-total{font-size:24px;}.split-value{font-size:14px;}
  .recent-rows{display:none;}.dash-rule.second{display:none;}
  .card-stock,.card-credit,.card-pulse{display:none;}
  .card-recon{top:-8px;left:-8px;width:170px;}
  .card-whatsapp{top:auto;right:-8px;bottom:0;width:200px;}
}

/* ── SHARED SECTION STYLES ────────────────────────────────── */
.section-inner{max-width:1080px;margin:0 auto;padding:0 28px;}
.section-label{font-size:10px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:var(--terracotta);display:block;margin-bottom:14px;}
.section-headline{font-family:'Cormorant Garamond',serif;font-size:clamp(28px,3.8vw,46px);font-weight:500;color:var(--espresso);line-height:1.1;letter-spacing:-0.01em;}
.section-sub{font-size:15px;line-height:1.75;color:var(--muted);max-width:500px;margin-top:14px;}
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center;}
@media(max-width:860px){.two-col{grid-template-columns:1fr;gap:48px;}}

/* ── SYSTEM OVERVIEW ──────────────────────────────────────── */
.section-system{background:var(--surface);padding:96px 28px;}
.sys-dash{background:white;border:1px solid var(--border);border-radius:18px;overflow:hidden;max-width:660px;margin:48px auto 0;}
.sys-dash-top{background:var(--espresso);padding:18px 22px;display:flex;justify-content:space-between;align-items:flex-start;}
.sys-name{font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:500;color:var(--parchment);}
.sys-insight-row-top{font-size:11px;font-weight:600;color:var(--forest);display:flex;align-items:center;gap:6px;margin-top:6px;}
.sys-idot{width:5px;height:5px;border-radius:50%;background:var(--forest);flex-shrink:0;}
.sys-grid{display:grid;grid-template-columns:repeat(3,1fr);}
.sys-cell{padding:16px 18px;border-right:1px solid var(--border);border-bottom:1px solid var(--border);}
.sys-cell:last-child{border-right:none;}
.sys-clabel{font-size:9px;text-transform:uppercase;letter-spacing:0.09em;color:var(--muted);font-weight:700;margin-bottom:5px;}
.sys-cval{font-family:'DM Mono',monospace;font-size:20px;color:var(--espresso);}
.sys-cval.up{color:var(--forest);}
.sys-csub{font-size:10px;color:var(--muted);margin-top:3px;}
.sys-shift{padding:12px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px;}
.sys-spulse{width:7px;height:7px;border-radius:50%;background:var(--forest);animation:live-pulse 2s ease-in-out infinite;flex-shrink:0;}
.sys-stext{font-size:12px;font-weight:600;color:var(--espresso);}
.sys-sdetail{font-size:11px;color:var(--muted);}
.sys-insight-bar{padding:10px 18px;background:var(--terra-bg);display:flex;align-items:center;gap:8px;}
.sys-ibar-dot{width:5px;height:5px;border-radius:50%;background:var(--terracotta);flex-shrink:0;}
.sys-ibar-text{font-size:11px;color:var(--terracotta);font-weight:600;}
.sys-callouts{display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin-top:48px;}
@media(max-width:580px){.sys-callouts{grid-template-columns:1fr;}.sys-grid{grid-template-columns:repeat(2,1fr);}}
.sys-co{display:flex;align-items:flex-start;gap:10px;}
.sys-co-dot{width:5px;height:5px;border-radius:50%;background:var(--terracotta);flex-shrink:0;margin-top:6px;}
.sys-co-title{font-size:13px;font-weight:600;color:var(--espresso);margin-bottom:3px;}
.sys-co-body{font-size:12px;color:var(--muted);line-height:1.6;}

/* ── SELL SECTION ─────────────────────────────────────────── */
.section-sell{background:var(--parchment);padding:96px 28px;}
.phone-outer{background:var(--espresso);border-radius:30px;padding:12px;width:230px;flex-shrink:0;}
.phone-inner{background:var(--parchment);border-radius:20px;overflow:hidden;}
.phone-bar{background:var(--espresso);padding:9px 14px;display:flex;justify-content:space-between;align-items:center;}
.phone-bar-title{font-family:'Cormorant Garamond',serif;font-size:13px;font-weight:600;color:var(--parchment);}
.phone-bar-val{font-family:'DM Mono',monospace;font-size:11px;color:var(--terracotta);}
.phone-grid{padding:10px;display:grid;grid-template-columns:1fr 1fr;gap:8px;}
.pg-card{border:1px solid var(--border);border-radius:9px;padding:8px;}
.pg-card.active{border-color:var(--terracotta);background:white;}
.pg-img{height:44px;background:var(--surface);border-radius:6px;margin-bottom:5px;}
.pg-name{font-size:10px;font-weight:600;color:var(--espresso);line-height:1.3;}
.pg-price{font-family:'DM Mono',monospace;font-size:9px;color:var(--terracotta);margin-top:1px;}
.phone-sale{padding:10px 12px;border-top:1px solid var(--border);background:white;}
.ps-name{font-size:11px;font-weight:600;color:var(--espresso);}
.ps-price{font-family:'DM Mono',monospace;font-size:18px;color:var(--terracotta);margin:3px 0;}
.ps-floor{font-size:9px;color:var(--muted);}
.ps-btns{display:grid;grid-template-columns:repeat(3,1fr);gap:5px;margin-top:7px;}
.psb{border-radius:5px;font-size:9px;font-weight:700;padding:6px 0;text-align:center;}
.psb-cash{background:var(--forest-bg);color:var(--forest);}
.psb-mpesa{background:var(--terra-bg);color:var(--terracotta);}
.psb-credit{background:var(--surface);color:var(--muted);border:1px solid var(--border);}
.sell-phone-col{position:relative;display:flex;align-items:center;justify-content:center;padding:40px 80px 40px 40px;}
.sf-card{position:absolute;border-radius:10px;border:1px solid var(--border);background:white;padding:11px 13px;}
.sf-recon{top:-10px;right:-50px;width:175px;border-color:var(--forest-bg);}
.sf-wa{bottom:-10px;right:-60px;width:200px;background:var(--espresso);border-color:transparent;}
.sf-tag{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:6px;}
.sf-tag.green{color:var(--forest);}
.sf-row{display:flex;justify-content:space-between;margin-bottom:3px;}
.sf-key{font-size:10px;color:var(--muted);}
.sf-val{font-family:'DM Mono',monospace;font-size:10px;color:var(--espresso);}
.sf-balanced{display:flex;align-items:center;gap:5px;margin-top:7px;}
.sf-check{width:14px;height:14px;border-radius:50%;background:var(--forest);display:flex;align-items:center;justify-content:center;font-size:8px;color:white;flex-shrink:0;}
.sf-balanced-text{font-size:11px;font-weight:600;color:var(--forest);}
.sf-wa-head{display:flex;align-items:center;gap:6px;margin-bottom:7px;}
.sf-wa-icon{width:16px;height:16px;border-radius:50%;background:#25D366;display:flex;align-items:center;justify-content:center;font-size:8px;font-weight:800;color:white;flex-shrink:0;}
.sf-wa-src{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:rgba(250,247,242,0.6);}
.sf-wa-time{font-size:9px;color:rgba(250,247,242,0.35);margin-left:auto;}
.sf-wa-body{font-size:11px;color:rgba(250,247,242,0.9);line-height:1.5;}
.sf-wa-sub{font-size:10px;color:rgba(250,247,242,0.5);margin-top:2px;}
.sell-bullets{margin-top:24px;display:flex;flex-direction:column;gap:12px;}
.sell-bullet{display:flex;align-items:flex-start;gap:10px;}
.sell-bdot{width:5px;height:5px;border-radius:50%;background:var(--terracotta);flex-shrink:0;margin-top:7px;}
.sell-btext{font-size:13px;color:var(--muted);line-height:1.65;}
.sell-btext strong{color:var(--espresso);font-weight:600;}
@media(max-width:860px){.sell-phone-col{padding:20px;}.sf-card{display:none;}}

/* ── TRANSACTIONS ─────────────────────────────────────────── */
.section-tx{background:var(--surface);padding:96px 28px;}
.tx-cards{display:flex;flex-direction:column;gap:12px;}
.tx-card{background:white;border:1px solid var(--border);border-radius:12px;overflow:hidden;}
.tx-card-head{padding:12px 14px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;}
.tx-ctag{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:0.09em;}
.tx-ctag.clay{color:var(--clay);}
.tx-ctag.terra{color:var(--terracotta);}
.tx-ctitle{font-size:13px;font-weight:600;color:var(--espresso);}
.tx-body{padding:10px 14px;}
.tx-row{display:flex;justify-content:space-between;align-items:baseline;padding:5px 0;border-bottom:1px solid var(--border);}
.tx-row:last-child{border-bottom:none;}
.tx-rl{font-size:11px;color:var(--muted);}
.tx-rv{font-family:'DM Mono',monospace;font-size:11px;color:var(--espresso);}
.tx-rv.clay{color:var(--clay);}
.sl-row{display:flex;align-items:center;gap:8px;padding:7px 14px;border-bottom:1px solid var(--border);}
.sl-row:last-child{border-bottom:none;}
.sl-dot{width:7px;height:7px;border-radius:50%;flex-shrink:0;}
.sl-name{font-size:12px;color:var(--espresso);flex:1;}
.sl-qty{font-size:10px;color:var(--muted);white-space:nowrap;}
.sl-price{font-family:'DM Mono',monospace;font-size:11px;color:var(--muted);}
.tx-bullets{margin-top:28px;display:flex;flex-direction:column;gap:11px;}
.tx-bullet{display:flex;align-items:flex-start;gap:9px;}
.tx-bdot{width:5px;height:5px;border-radius:50%;background:var(--terracotta);flex-shrink:0;margin-top:6px;}
.tx-btext{font-size:13px;color:var(--muted);line-height:1.65;}
.tx-btext strong{color:var(--espresso);font-weight:600;}

/* ── ONLINE ───────────────────────────────────────────────── */
.section-online{background:var(--parchment);padding:96px 28px;}
.shop-frame{background:white;border:1px solid var(--border);border-radius:16px;overflow:hidden;max-width:380px;width:100%;}
.shop-top{background:var(--espresso);padding:14px 18px;}
.shop-sname{font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:500;color:var(--parchment);}
.shop-sloc{font-size:10px;color:rgba(250,247,242,0.45);margin-top:2px;}
.shop-cats{display:flex;gap:7px;padding:12px 14px;border-bottom:1px solid var(--border);}
.shop-cat{font-size:10px;font-weight:600;padding:4px 10px;border-radius:20px;}
.shop-cat.active{background:var(--espresso);color:var(--parchment);}
.shop-cat.inactive{color:var(--muted);}
.shop-grid{display:grid;grid-template-columns:1fr 1fr;}
.shop-item{border-right:1px solid var(--border);border-bottom:1px solid var(--border);padding:12px;}
.shop-item:nth-child(2n){border-right:none;}
.shop-item:nth-last-child(-n+2){border-bottom:none;}
.shop-item-img{height:80px;background:var(--surface);border-radius:7px;margin-bottom:8px;position:relative;}
.shop-badge-new{position:absolute;top:5px;left:5px;background:var(--espresso);color:var(--parchment);font-size:8px;font-weight:700;padding:2px 6px;border-radius:4px;}
.shop-iname{font-size:11px;font-weight:600;color:var(--espresso);}
.shop-iprice{font-family:'DM Mono',monospace;font-size:11px;color:var(--terracotta);margin-top:2px;}
.shop-istock{font-size:9px;color:var(--muted);}
.shop-wa{padding:11px 14px;background:var(--forest-bg);display:flex;align-items:center;gap:8px;border-top:1px solid var(--border);}
.shop-wa-icon{width:16px;height:16px;border-radius:50%;background:#25D366;display:flex;align-items:center;justify-content:center;font-size:8px;font-weight:800;color:white;flex-shrink:0;}
.shop-wa-text{font-size:11px;font-weight:600;color:var(--forest);}
.online-bullets{margin-top:28px;display:flex;flex-direction:column;gap:11px;}
.online-bullet{display:flex;align-items:flex-start;gap:9px;}
.online-bdot{width:5px;height:5px;border-radius:50%;background:var(--terracotta);flex-shrink:0;margin-top:6px;}
.online-btext{font-size:13px;color:var(--muted);line-height:1.65;}
.online-btext strong{color:var(--espresso);font-weight:600;}

/* ── DEMO ─────────────────────────────────────────────────── */
.section-demo{background:var(--espresso);padding:80px 28px;text-align:center;}
.demo-eye{font-size:10px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:var(--terracotta);margin-bottom:16px;}
.demo-head{font-family:'Cormorant Garamond',serif;font-size:clamp(28px,3.8vw,46px);font-weight:500;color:var(--parchment);line-height:1.1;}
.demo-sub{font-size:14px;color:rgba(250,247,242,0.5);margin-top:10px;}
.demo-btns{display:flex;gap:12px;justify-content:center;margin-top:36px;flex-wrap:wrap;}
.demo-btn-s{padding:13px 28px;border-radius:8px;font-size:13px;font-weight:600;color:var(--espresso);background:var(--parchment);display:inline-block;transition:opacity 0.2s;}
.demo-btn-o{padding:12px 28px;border-radius:8px;font-size:13px;font-weight:600;color:var(--parchment);border:1.5px solid rgba(250,247,242,0.2);display:inline-block;transition:border-color 0.2s;}
.demo-note{font-size:12px;color:rgba(250,247,242,0.3);margin-top:24px;}

/* ── TESTIMONIAL ──────────────────────────────────────────── */
.section-testimonial{background:var(--surface);padding:96px 28px;}
.t-wrap{max-width:720px;margin:0 auto;}
.t-pull{font-family:'Cormorant Garamond',serif;font-size:clamp(22px,3vw,32px);font-weight:500;color:var(--espresso);line-height:1.3;margin-bottom:28px;}
.t-body{font-size:14px;color:var(--muted);line-height:1.8;margin-bottom:28px;}
.t-footer{display:flex;align-items:center;gap:14px;padding-top:24px;border-top:1px solid var(--border);}
.t-avatar{width:42px;height:42px;border-radius:50%;background:var(--espresso);display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:600;color:var(--parchment);flex-shrink:0;}
.t-name{font-size:13px;font-weight:600;color:var(--espresso);}
.t-loc{font-size:11px;color:var(--muted);margin-top:2px;}

/* ── PRICING ──────────────────────────────────────────────── */
.section-pricing{background:var(--parchment);padding:96px 28px;}
.pricing-inner{max-width:680px;margin:0 auto;text-align:center;}
.pricing-cards{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:48px;}
@media(max-width:520px){.pricing-cards{grid-template-columns:1fr;}}
.price-card{border:1px solid var(--border);border-radius:14px;padding:28px 24px;background:white;text-align:left;}
.price-card.featured{border-color:var(--espresso);}
.price-best{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--terracotta);margin-bottom:12px;}
.price-amount{font-family:'DM Mono',monospace;font-size:32px;font-weight:500;color:var(--espresso);letter-spacing:-0.02em;}
.price-period{font-size:12px;color:var(--muted);margin-left:4px;}
.price-trial{font-size:13px;font-weight:600;color:var(--forest);margin-top:10px;}
.price-note{font-size:11px;color:var(--muted);margin-top:4px;}
.pricing-action{margin-top:40px;display:flex;flex-direction:column;align-items:center;gap:10px;}
.pricing-confidence{font-size:12px;color:var(--muted);}
.btn-dark{background:var(--espresso);color:var(--parchment);padding:13px 28px;border-radius:8px;font-size:13px;font-weight:600;display:inline-block;transition:opacity 0.2s;}
.btn-dark:hover{opacity:0.84;}

/* ── FAQ ──────────────────────────────────────────────────── */
.section-faq{background:var(--surface);padding:80px 28px;}
.faq-inner{max-width:740px;margin:0 auto;}
.faq-head{display:flex;justify-content:space-between;align-items:baseline;margin-bottom:36px;}
.faq-wa{font-size:12px;font-weight:600;color:var(--terracotta);}
details{border-bottom:1px solid var(--border);}
summary{font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:500;color:var(--espresso);cursor:pointer;list-style:none;display:flex;justify-content:space-between;align-items:center;padding:18px 0;gap:16px;}
summary::-webkit-details-marker{display:none;}
details[open] summary{color:var(--terracotta);}
.faq-icon{color:var(--terracotta);font-size:17px;flex-shrink:0;}
.faq-answer{font-size:13px;color:var(--muted);line-height:1.75;padding-bottom:18px;}

/* ── INSIGHTS ─────────────────────────────────────────────── */
.section-insights{background:var(--parchment);padding:80px 28px;border-top:1px solid var(--border);}
.insights-inner{max-width:680px;margin:0 auto;}
.insights-head{display:flex;justify-content:space-between;align-items:baseline;margin-bottom:36px;}
.insights-all{font-size:12px;font-weight:600;color:var(--terracotta);}
.insight-item{padding:26px 0;border-bottom:1px solid var(--border);}
.insight-item:last-child{border-bottom:none;}
.insight-title{font-family:'Cormorant Garamond',serif;font-size:21px;font-weight:500;color:var(--espresso);line-height:1.2;margin-bottom:8px;}
.insight-preview{font-size:13px;color:var(--muted);line-height:1.7;}
.insight-read{font-size:11px;font-weight:600;color:var(--terracotta);margin-top:10px;display:block;}

/* ── FOOTER ───────────────────────────────────────────────── */
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
</style>
</head>
<body>

<!-- NAV -->
<nav class="nav" id="main-nav">
  <a href="/" class="nav-logo">Stoka<span class="nav-logo-dot">&middot;</span></a>
  <div class="nav-links">
    <a href="#section-features" class="nav-link hide-sm">Features</a>
    <a href="#section-pricing" class="nav-link hide-sm">Pricing</a>
    <a href="/insights" class="nav-link hide-sm">Insights</a>
    <a href="https://demo.stoka.co.ke/demo" class="nav-demo hide-sm">
      <span class="nav-demo-dot"></span>Live demo
    </a>
    <a href="/register" class="nav-link nav-cta">Get started</a>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-left">
    <span class="hero-eyebrow">Aware by Design</span>
    <h1 class="hero-heading">
      Run your shop<br>
      like you&rsquo;re<br>
      <em>always there.</em>
    </h1>
    <p class="hero-sub">Every shift, every sale, every number &mdash; visible from anywhere, in real time.</p>
    <div class="hero-ctas">
      <a href="/register" class="btn-primary">Start free &rarr;</a>
      <a href="https://demo.stoka.co.ke/demo" class="btn-ghost">See the demo</a>
    </div>
    <div class="trial-note">
      <span class="trial-dot"></span>
      30 days free. No card required. Live in 24 hours.
    </div>
    <div class="hero-stats">
      <div class="stat-item">
        <span class="stat-number">Ksh 0</span>
        <span class="stat-label">Notebooks required</span>
      </div>
      <div class="stat-rule"></div>
      <div class="stat-item">
        <span class="stat-number">24 hrs</span>
        <span class="stat-label">To first shift report</span>
      </div>
      <div class="stat-rule"></div>
      <div class="stat-item">
        <span class="stat-number">30 min</span>
        <span class="stat-label">Setup</span>
      </div>
    </div>
  </div>

  <div class="hero-right">
    <div class="hero-stage">
      <div class="dashboard-card">
        <div class="dash-header">
          <span class="dash-store">Boutique Maisha</span>
          <span class="dash-period">Today</span>
        </div>
        <div class="dash-total">KES 19,800</div>
        <div class="dash-compare">&#8593; KES 1,550 more than yesterday</div>
        <div class="dash-split">
          <div class="split-cell"><div class="split-label">Cash</div><div class="split-value">12,400</div></div>
          <div class="split-cell"><div class="split-label">M-Pesa</div><div class="split-value">7,400</div></div>
        </div>
        <div class="dash-rule"></div>
        <div class="dash-section-label">Active shift</div>
        <div class="shift-row">
          <div class="shift-left">
            <span class="shift-dot"></span>
            <span class="shift-name">Staff on shift</span>
            <span class="shift-dur">3h 22m</span>
          </div>
          <span class="shift-amount">6,800</span>
        </div>
        <div class="dash-rule second" style="margin-top:14px;"></div>
        <div class="recent-rows">
          <div class="recent-row">
            <span class="recent-name">Khaki Straight Trouser</span>
            <div class="recent-right"><span class="recent-amount">850</span><span class="pay-badge pay-cash">Cash</span></div>
          </div>
          <div class="recent-row">
            <span class="recent-name">Floral Wrap Dress</span>
            <div class="recent-right"><span class="recent-amount">4,500</span><span class="pay-badge pay-mpesa">M-Pesa</span></div>
          </div>
        </div>
      </div>

      <div class="float-card card-recon">
        <div class="fc-tag green">Last shift closed</div>
        <div class="fc-row"><span class="fc-key">Expected</span><span class="fc-val">Ksh 8,400</span></div>
        <div class="fc-row"><span class="fc-key">Counted</span><span class="fc-val">Ksh 8,400</span></div>
        <div class="fc-balanced"><span class="fc-check">&#10003;</span>Balanced</div>
      </div>

      <div class="float-card card-stock">
        <div class="fc-tag terra">Moving fastest this week</div>
        <div class="fc-product-name">Khaki Straight Trouser</div>
        <div class="fc-product-sub">Size 32</div>
        <div class="fc-stock-stat"><span id="stock-sold">14</span> sold &nbsp;&middot;&nbsp; <span id="stock-remaining">2</span> remaining</div>
      </div>

      <div class="float-card card-credit">
        <div class="fc-tag clay">Outstanding credit</div>
        <div class="fc-row"><span class="fc-key">28 days</span><span class="fc-val">Ksh 1,800</span></div>
        <div class="fc-row"><span class="fc-key">Last payment</span><span class="fc-val">Mar 3</span></div>
      </div>

      <div class="float-card card-whatsapp">
        <div class="wa-header">
          <div class="wa-icon">W</div>
          <span class="wa-source">Stoka</span>
          <span class="wa-time">Just now</span>
        </div>
        <div class="wa-body">Shift closed &nbsp;&middot;&nbsp; Ksh 12,200 total</div>
        <div class="wa-body-sub">Cash balanced &nbsp;&middot;&nbsp; M-Pesa Ksh 7,400</div>
      </div>

      <div class="float-card card-pulse">
        <div class="pulse-row"><span class="pulse-dot"></span><span class="pulse-name">Shop is open</span></div>
        <div class="pulse-stats">3h 22m &nbsp;&middot;&nbsp; Ksh 6,800 so far</div>
      </div>
    </div>
  </div>
</section>

<!-- SYSTEM OVERVIEW -->
<section class="section-system" id="section-system">
  <div class="section-inner">
    <div style="text-align:center;max-width:620px;margin:0 auto 48px;" class="fade-up">
      <span class="section-label" style="display:block;text-align:center;">The full picture</span>
      <h2 class="section-headline" style="text-align:center;">Every sale. Every shift. Visible the moment it happens.</h2>
    </div>
    <div class="sys-dash fade-up">
      <div class="sys-dash-top">
        <div>
          <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(250,247,242,0.4);">Good morning</div>
          <div class="sys-name">Maya</div>
          <div class="sys-insight-row-top"><span class="sys-idot"></span>Today is already 25% stronger than yesterday</div>
        </div>
        <div style="font-size:10px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;color:rgba(250,247,242,0.35);margin-top:4px;">Fri 20 Mar</div>
      </div>
      <div class="sys-shift">
        <span class="sys-spulse"></span>
        <span class="sys-stext">James is on shift</span>
        <span class="sys-sdetail">&nbsp;&middot;&nbsp;3h 22m &middot; 4 sales</span>
        <span style="font-family:'DM Mono',monospace;font-size:12px;color:#1C1814;margin-left:auto;">KES 7,850</span>
      </div>
      <div class="sys-grid">
        <div class="sys-cell"><div class="sys-clabel">Today&rsquo;s sales</div><div class="sys-cval">7,850</div><div class="sys-csub">4 transactions</div></div>
        <div class="sys-cell"><div class="sys-clabel">M-Pesa</div><div class="sys-cval">6,700</div><div class="sys-csub">3 payments</div></div>
        <div class="sys-cell" style="border-right:none;"><div class="sys-clabel">vs yesterday</div><div class="sys-cval up">+1,550</div><div class="sys-csub">6,300 yesterday</div></div>
      </div>
      <div class="sys-insight-bar">
        <span class="sys-ibar-dot"></span>
        <span class="sys-ibar-text">Khaki Straight Trouser size 32 &mdash; 2 remaining, 14 sold this week</span>
      </div>
    </div>
    <div class="sys-callouts fade-up">
      <div class="sys-co"><span class="sys-co-dot"></span><div><div class="sys-co-title">See it from anywhere</div><div class="sys-co-body">Real-time dashboard from any phone. No calls needed.</div></div></div>
      <div class="sys-co"><span class="sys-co-dot"></span><div><div class="sys-co-title">Role-based access</div><div class="sys-co-body">Staff manage sales and shifts. You see everything.</div></div></div>
      <div class="sys-co"><span class="sys-co-dot"></span><div><div class="sys-co-title">Nothing to install</div><div class="sys-co-body">Browser-based. Works on any phone, any OS.</div></div></div>
      <div class="sys-co"><span class="sys-co-dot"></span><div><div class="sys-co-title">Reports without asking</div><div class="sys-co-body">When a shift closes, the summary is already on your phone.</div></div></div>
    </div>
  </div>
</section>

<!-- SELL · CLOSE · KNOW -->
<section class="section-sell">
  <div class="section-inner">
    <div class="two-col">
      <div class="fade-up">
        <span class="section-label">Sell &middot; Close &middot; Know</span>
        <h2 class="section-headline">Three taps to close a sale. Everything else is automatic.</h2>
        <p class="section-sub">Your staff work fast. You see everything. When the shift closes, the reconciliation is done and the summary is already on your phone.</p>
        <div class="sell-bullets">
          <div class="sell-bullet"><span class="sell-bdot"></span><span class="sell-btext"><strong>Staff interface built for speed.</strong> Product grid, one tap to select, three payment options. Nothing else.</span></div>
          <div class="sell-bullet"><span class="sell-bdot"></span><span class="sell-btext"><strong>Shift close is automatic.</strong> Expected cash calculated before staff count. The number is the number.</span></div>
          <div class="sell-bullet"><span class="sell-bdot"></span><span class="sell-btext"><strong>Report arrives on your phone.</strong> Sales total, M-Pesa, cash, discrepancy &mdash; within seconds of closing.</span></div>
        </div>
      </div>
      <div class="sell-phone-col fade-up">
        <div class="phone-outer">
          <div class="phone-inner">
            <div class="phone-bar">
              <span class="phone-bar-title">Maisha Boutique</span>
              <span class="phone-bar-val">KES 7,850</span>
            </div>
            <div class="phone-grid">
              <div class="pg-card active"><div class="pg-img"></div><div class="pg-name">Khaki Trouser</div><div class="pg-price">KES 850</div></div>
              <div class="pg-card"><div class="pg-img"></div><div class="pg-name">Floral Dress</div><div class="pg-price">KES 4,500</div></div>
              <div class="pg-card"><div class="pg-img"></div><div class="pg-name">Linen Shirt</div><div class="pg-price">KES 3,200</div></div>
              <div class="pg-card"><div class="pg-img"></div><div class="pg-name">Wrap Dress</div><div class="pg-price">KES 5,800</div></div>
            </div>
            <div class="phone-sale">
              <div class="ps-name">Khaki Straight Trouser &middot; Size 32</div>
              <div class="ps-price">KES 850</div>
              <div class="ps-floor">Floor: KES 700</div>
              <div class="ps-btns">
                <div class="psb psb-cash">Cash</div>
                <div class="psb psb-mpesa">M-Pesa</div>
                <div class="psb psb-credit">Credit</div>
              </div>
            </div>
          </div>
        </div>
        <div class="sf-card sf-recon">
          <div class="sf-tag green">Last shift closed</div>
          <div class="sf-row"><span class="sf-key">Expected</span><span class="sf-val">Ksh 8,400</span></div>
          <div class="sf-row"><span class="sf-key">Counted</span><span class="sf-val">Ksh 8,400</span></div>
          <div class="sf-balanced"><span class="sf-check">&#10003;</span><span class="sf-balanced-text">Balanced</span></div>
        </div>
        <div class="sf-card sf-wa">
          <div class="sf-wa-head"><div class="sf-wa-icon">W</div><span class="sf-wa-src">Stoka</span><span class="sf-wa-time">Just now</span></div>
          <div class="sf-wa-body">Shift closed &middot; Ksh 12,200 total</div>
          <div class="sf-wa-sub">Cash balanced &middot; M-Pesa Ksh 7,400</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TRANSACTIONS + STOCK -->
<section class="section-tx">
  <div class="section-inner">
    <div class="two-col">
      <div class="fade-up" style="order:2;">
        <div class="tx-cards">
          <div class="tx-card">
            <div class="tx-card-head">
              <div><div class="tx-ctag clay">Outstanding credit</div><div class="tx-ctitle">28 days open</div></div>
              <span style="font-family:'DM Mono',monospace;font-size:18px;color:var(--clay);">1,800</span>
            </div>
            <div class="tx-body">
              <div class="tx-row"><span class="tx-rl">Last payment</span><span class="tx-rv">Mar 3</span></div>
              <div class="tx-row"><span class="tx-rl">Original sale</span><span class="tx-rv">Feb 4 &middot; KES 3,600</span></div>
              <div class="tx-row"><span class="tx-rl">Outstanding</span><span class="tx-rv clay">KES 1,800</span></div>
            </div>
          </div>
          <div class="tx-card">
            <div class="tx-card-head">
              <div><div class="tx-ctag terra">Moving fastest this week</div><div class="tx-ctitle">Restock list &middot; 5 items</div></div>
            </div>
            <div class="sl-row"><span class="sl-dot" style="background:var(--clay);"></span><span class="sl-name">Denim Mom Jeans 30</span><span class="sl-qty">0 left</span><span class="sl-price">2,800</span></div>
            <div class="sl-row"><span class="sl-dot" style="background:var(--terracotta);"></span><span class="sl-name">Ankara Floral Dress S</span><span class="sl-qty">2 left</span><span class="sl-price">4,500</span></div>
            <div class="sl-row"><span class="sl-dot" style="background:var(--terracotta);"></span><span class="sl-name">Kitenge Wrap Dress L</span><span class="sl-qty">1 left</span><span class="sl-price">5,800</span></div>
            <div class="sl-row"><span class="sl-dot" style="background:var(--forest);"></span><span class="sl-name">Khaki Straight Trouser</span><span class="sl-qty">2 left</span><span class="sl-price">850</span></div>
          </div>
        </div>
      </div>
      <div class="fade-up" style="order:1;">
        <span class="section-label">Every transaction</span>
        <h2 class="section-headline">Exchanges, credit, returns. All of it. In one place.</h2>
        <p class="section-sub">Most systems stop at the sale. Stoka tracks the partial payment, the credit sitting 28 days open, the stock that needs restocking before the next market trip.</p>
        <div class="tx-bullets">
          <div class="tx-bullet"><span class="tx-bdot"></span><span class="tx-btext"><strong>Credit tracked by customer.</strong> Balances, payment history, days open &mdash; all on the dashboard.</span></div>
          <div class="tx-bullet"><span class="tx-bdot"></span><span class="tx-btext"><strong>Exchanges and returns recorded.</strong> Every movement ties back to a shift and a name.</span></div>
          <div class="tx-bullet"><span class="tx-bdot"></span><span class="tx-btext"><strong>Restock list generated automatically.</strong> Walk into the supplier with data, not instinct.</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ONLINE -->
<section class="section-online">
  <div class="section-inner">
    <div class="two-col">
      <div class="fade-up">
        <span class="section-label">Online presence</span>
        <h2 class="section-headline">A real shop page. Not a price list.</h2>
        <p class="section-sub">Every product, every price, real stock levels. Shareable in one link. Customers browse and enquire directly on WhatsApp &mdash; no app, no DM guesswork.</p>
        <div class="online-bullets">
          <div class="online-bullet"><span class="online-bdot"></span><span class="online-btext"><strong>Your own subdomain.</strong> yourshop.stoka.co.ke. Clean, shareable, professional.</span></div>
          <div class="online-bullet"><span class="online-bdot"></span><span class="online-btext"><strong>Stock levels update in real time.</strong> What you sold today is gone from the page today.</span></div>
          <div class="online-bullet"><span class="online-bdot"></span><span class="online-btext"><strong>WhatsApp enquiry built in.</strong> One tap from product to conversation.</span></div>
        </div>
      </div>
      <div style="display:flex;justify-content:center;" class="fade-up">
        <div class="shop-frame">
          <div class="shop-top"><div class="shop-sname">Zawadi Boutique</div><div class="shop-sloc">Nairobi &middot; Boutique Fashion</div></div>
          <div class="shop-cats">
            <span class="shop-cat active">All</span>
            <span class="shop-cat inactive">Dresses</span>
            <span class="shop-cat inactive">Tops</span>
            <span class="shop-cat inactive">Bottoms</span>
          </div>
          <div class="shop-grid">
            <div class="shop-item"><div class="shop-item-img"><span class="shop-badge-new">NEW</span></div><div class="shop-iname">Ankara Dress</div><div class="shop-iprice">KES 4,500</div><div class="shop-istock">Sizes S, M</div></div>
            <div class="shop-item"><div class="shop-item-img"></div><div class="shop-iname">Kitenge Wrap</div><div class="shop-iprice">KES 5,800</div><div class="shop-istock">Size L only</div></div>
            <div class="shop-item"><div class="shop-item-img"></div><div class="shop-iname">Linen Shirt</div><div class="shop-iprice">KES 3,200</div><div class="shop-istock">M, L, XL</div></div>
            <div class="shop-item"><div class="shop-item-img"></div><div class="shop-iname">Denim Jeans</div><div class="shop-iprice">KES 2,800</div><div class="shop-istock" style="color:var(--clay);">Last piece</div></div>
          </div>
          <div class="shop-wa"><div class="shop-wa-icon">W</div><span class="shop-wa-text">Ask about this item &rarr;</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- DEMO -->
<section class="section-demo">
  <div class="demo-eye">Live demo</div>
  <h2 class="demo-head">See a real boutique running on Stoka.</h2>
  <p class="demo-sub">No account needed. Nothing to install.</p>
  <div class="demo-btns">
    <a href="https://demo.stoka.co.ke/demo?role=staff" class="demo-btn-s">Experience as Staff &rarr;</a>
    <a href="https://demo.stoka.co.ke/demo?role=owner" class="demo-btn-o">Experience as Owner &rarr;</a>
  </div>
  <p class="demo-note">Staff: the till, product grid, shift close. &nbsp;&middot;&nbsp; Owner: dashboard, reconciliation, shift history.</p>
</section>

<!-- TESTIMONIAL -->
<section class="section-testimonial" id="section-testimonial">
  <div class="section-inner">
    <div class="t-wrap fade-up">
      @if($testimonial)
      <div class="t-pull">&ldquo;{{ $testimonial->pull_quote }}&rdquo;</div>
      <p class="t-body">{{ $testimonial->body }}</p>
      <div class="t-footer">
        <div class="t-avatar">{{ substr($testimonial->name, 0, 1) }}</div>
        <div><div class="t-name">{{ $testimonial->name }}</div><div class="t-loc">{{ $testimonial->location }}</div></div>
      </div>
      @endif
    </div>
  </div>
</section>

<!-- PRICING -->
<section class="section-pricing" id="section-pricing">
  <div class="pricing-inner">
    <span class="section-label" style="display:block;text-align:center;">Pricing</span>
    <h2 class="section-headline" style="text-align:center;">Straightforward. No surprises.</h2>
    <div class="pricing-cards">
      <div class="price-card fade-up">
        <div class="price-best" style="color:var(--muted);font-weight:500;letter-spacing:0;font-size:12px;text-transform:none;">Monthly</div>
        <div><span class="price-amount">KES 2,000</span><span class="price-period">/ month</span></div>
        <div class="price-trial">30 days free to start</div>
        <div class="price-note">No card required for the trial.</div>
      </div>
      <div class="price-card featured fade-up">
        <div class="price-best">Best value</div>
        <div><span class="price-amount">KES 18,000</span><span class="price-period">/ year</span></div>
        <div class="price-trial">Three months free.</div>
        <div class="price-note">Pay once. Done for the year.</div>
      </div>
    </div>
    <div class="pricing-action">
      <a href="/register" class="btn-dark">Start your 30 days &rarr;</a>
      <span class="pricing-confidence">Most owners know by the end of the first week.</span>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="section-faq">
  <div class="faq-inner">
    <div class="faq-head">
      <h2 class="section-headline" style="font-size:clamp(22px,2.8vw,34px);">Things people ask before they decide.</h2>
      <a href="https://wa.me/254741641925?text=Hi%2C+I+have+a+question+about+Stoka." class="faq-wa">Ask on WhatsApp &rarr;</a>
    </div>
    <details class="fade-up"><summary>Can my staff use this if they are not comfortable with technology?<span class="faq-icon">+</span></summary><p class="faq-answer">The staff interface is built for speed, not features. Opening a shift, recording a sale, closing the shift &mdash; three taps or fewer each. If they can operate a mobile payment terminal, they can use Stoka.</p></details>
    <details class="fade-up"><summary>What happens when a shift closes and I am not at the shop?<span class="faq-icon">+</span></summary><p class="faq-answer">A summary goes to your WhatsApp automatically. Sales total, M-Pesa collected, cash reconciliation result. Within seconds of the shift closing &mdash; no login required.</p></details>
    <details class="fade-up"><summary>Can I see what is happening in my shop right now?<span class="faq-icon">+</span></summary><p class="faq-answer">Yes. The owner dashboard shows any open shifts, who is selling, how many sales have been made, and the running total &mdash; updated in real time. Accessible from any phone.</p></details>
    <details class="fade-up"><summary>What if a staff member makes a mistake on a sale?<span class="faq-icon">+</span></summary><p class="faq-answer">Sales can be voided from the till during the shift. The record shows the void against the staff member&rsquo;s name, the reason, and the time. Nothing disappears &mdash; it is marked as voided and visible in shift history.</p></details>
    <details class="fade-up"><summary>How does the credit tracking work?<span class="faq-icon">+</span></summary><p class="faq-answer">When a credit sale is recorded, the customer&rsquo;s name and phone number are required. The balance is tracked. Partial payments are logged. The dashboard shows outstanding balances and how long each has been open.</p></details>
    <details class="fade-up"><summary>Does the public shop page replace Instagram?<span class="faq-icon">+</span></summary><p class="faq-answer">No. They serve different moments. Instagram is for discovery. The shop page is for when a customer already knows you &mdash; and wants to see what you have at what price, right now. Most boutiques use both.</p></details>
    <details class="fade-up"><summary>How quickly can we go live?<span class="faq-icon">+</span></summary><p class="faq-answer">The setup conversation takes about fifteen minutes. Once we have your product list, staff names, and shop details, the system is live within 24 hours. First shift can open the same day.</p></details>
    <details class="fade-up"><summary>Is there a contract?<span class="faq-icon">+</span></summary><p class="faq-answer">No. Month-to-month on the monthly plan. The annual plan is a one-time payment &mdash; no recurring charges, no cancellation fees. If you stop, you stop.</p></details>
  </div>
</section>

<!-- INSIGHTS -->
<section class="section-insights">
  <div class="insights-inner">
    <div class="insights-head">
      <span class="section-label" style="margin-bottom:0;">Insights</span>
      <a href="/insights" class="insights-all">All five pieces &rarr;</a>
    </div>
    <div class="insight-item fade-up">
      <div class="insight-title">Your notebook isn&rsquo;t lying to you. It just can&rsquo;t tell you the truth.</div>
      <p class="insight-preview">A notebook records what gets written in it. The gap between what moved and what was recorded isn&rsquo;t in the notebook &mdash; it&rsquo;s in everything the notebook never saw.</p>
      <a href="/insights/the-notebook" class="insight-read">Read &rarr;</a>
    </div>
    <div class="insight-item fade-up">
      <div class="insight-title">The staff problem that is not a staff problem.</div>
      <p class="insight-preview">Good people in systems that make honesty hard to prove behave like the system allows. The way to change the outcome is not to change the people.</p>
      <a href="/insights/the-staff-problem" class="insight-read">Read &rarr;</a>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="footer">
  <div class="footer-inner">
    <div>
      <div class="footer-logo">stoka<span class="footer-logo-dot">&middot;</span></div>
      <div class="footer-tagline">Boutique management,<br>aware by design.</div>
    </div>
    <div class="footer-col">
      <div class="footer-col-label">Product</div>
      <a href="#section-pricing" class="footer-link">Pricing</a>
      <a href="https://demo.stoka.co.ke/demo" class="footer-link">Live Demo</a>
      <a href="/register" class="footer-link">Get Started</a>
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

<script>
const nav = document.getElementById('main-nav');
window.addEventListener('scroll',()=>nav.classList.toggle('scrolled',window.scrollY>20),{passive:true});
const obs = new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)e.target.classList.add('visible');}),{threshold:0.1});
document.querySelectorAll('.fade-up').forEach(el=>obs.observe(el));
document.querySelectorAll('details').forEach(d=>{d.addEventListener('toggle',()=>{d.querySelector('.faq-icon').textContent=d.open?'\u2212':'+'});});
// Stock card counter — synced to 20s animation loop
let sold=14,remaining=2;
const soldEl=document.getElementById('stock-sold'),remEl=document.getElementById('stock-remaining'),sc=document.querySelector('.card-stock');
function updateStock(){sold++;remaining=Math.max(0,remaining-1);soldEl.textContent=sold;remEl.textContent=remaining;sc.classList.add('stock-pulse');setTimeout(()=>sc.classList.remove('stock-pulse'),1400);}
setTimeout(()=>{updateStock();setInterval(updateStock,20000);},20000);
</script>
</body>
</html>