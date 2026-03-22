<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Demo Visits — Stoka Admin</title><link rel="preconnect" href="https://fonts.googleapis.com"><link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"><style>*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{--parchment:#FAF7F2;--surface:#F2EDE6;--border:#E8E0D6;--espresso:#1C1814;--mid:#4A3728;--muted:#8C7B6E;--terracotta:#C17F4A;--forest:#4A6741;--clay:#B85C38;--sidebar-w:220px;--radius-sm:6px;--radius-md:10px;--radius-default:14px;}
html{scroll-behavior:smooth;}
body{font-family:"Plus Jakarta Sans",sans-serif;background:var(--parchment);color:var(--espresso);min-height:100vh;-webkit-font-smoothing:antialiased;}
a{color:inherit;text-decoration:none;}
.shell{display:flex;min-height:100vh;}
.sidebar{width:var(--sidebar-w);flex-shrink:0;background:var(--surface);border-right:1px solid var(--border);display:flex;flex-direction:column;position:fixed;top:0;left:0;height:100vh;z-index:100;overflow:hidden;}
.sidebar-header{padding:24px 20px 16px;border-bottom:1px solid var(--border);flex-shrink:0;}
.sidebar-logo{font-family:"Cormorant Garamond",serif;font-size:26px;font-weight:600;color:var(--espresso);letter-spacing:0.02em;display:block;line-height:1;margin-bottom:5px;}
.sidebar-tag{font-size:10px;font-weight:700;color:var(--terracotta);text-transform:uppercase;letter-spacing:0.1em;display:block;}
.sidebar-nav{flex:1;padding:12px 10px;overflow-y:auto;}
.nav-section-label{font-size:10px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:0.1em;padding:12px 12px 4px;display:block;}
.nav-section-label:first-child{padding-top:4px;}
.nav-link{display:flex;align-items:center;gap:9px;padding:9px 12px;border-radius:var(--radius-md);font-size:13px;font-weight:400;color:var(--mid);transition:background 0.13s,color 0.13s;margin-bottom:1px;}
.nav-link:hover{background:#EBE3D8;color:var(--espresso);}
.nav-link.active{background:var(--terracotta);color:#fff;font-weight:500;}
.nav-icon{width:15px;height:15px;flex-shrink:0;opacity:0.65;}
.nav-link.active .nav-icon,.nav-link:hover .nav-icon{opacity:1;}
.sidebar-footer{flex-shrink:0;padding:14px 18px 18px;border-top:1px solid var(--border);}
.sidebar-admin{font-size:12px;font-weight:600;color:var(--espresso);display:block;margin-bottom:2px;}
.sidebar-role{font-size:11px;color:var(--muted);display:block;margin-bottom:10px;}
.logout-btn{width:100%;padding:8px 12px;background:transparent;border:1px solid var(--border);border-radius:var(--radius-md);font-family:"Plus Jakarta Sans",sans-serif;font-size:12px;font-weight:500;color:var(--muted);cursor:pointer;text-align:left;transition:background 0.13s,color 0.13s,border-color 0.13s;}
.logout-btn:hover{background:var(--clay);border-color:var(--clay);color:#fff;}
.main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh;min-width:0;}
.page-header{padding:30px 36px 22px;border-bottom:1px solid var(--border);flex-shrink:0;display:flex;align-items:flex-start;justify-content:space-between;gap:16px;}
.page-title{font-family:"Cormorant Garamond",serif;font-size:30px;font-weight:600;color:var(--espresso);line-height:1.1;}
.page-sub{font-size:13px;color:var(--muted);margin-top:5px;}
.page-content{padding:28px 36px;flex:1;}
.health-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:16px;}
.hcard{background:white;border:1px solid var(--border);border-radius:var(--radius-default);padding:18px 20px;box-shadow:0 1px 3px rgba(28,24,20,0.04);}
.hcard-label{font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:6px;}
.hcard-val{font-family:"DM Mono",monospace;font-size:30px;font-weight:500;color:var(--espresso);line-height:1;}
.c-active{color:var(--forest)!important;}
.c-drifting{color:var(--terracotta)!important;}
.c-cold{color:var(--clay)!important;}
.demo-pulse{background:var(--espresso);border-radius:var(--radius-default);padding:12px 20px;margin-bottom:24px;display:flex;align-items:center;gap:20px;flex-wrap:wrap;}
.dp-item{font-size:12px;color:rgba(250,247,242,0.45);display:flex;align-items:center;gap:6px;}
.dp-val{font-family:"DM Mono",monospace;font-size:13px;color:var(--parchment);font-weight:500;}
.dp-dot{width:5px;height:5px;border-radius:50%;background:var(--forest);animation:dpulse 2s ease-in-out infinite;}
@keyframes dpulse{0%,100%{opacity:1;}50%{opacity:0.3;}}
.dp-div{width:1px;height:14px;background:rgba(250,247,242,0.12);}
.dp-link{font-size:11px;color:rgba(250,247,242,0.35);margin-left:auto;transition:color 0.15s;}
.dp-link:hover{color:var(--parchment);}
.section-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;}
.section-head h2{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);}
.section-note{font-size:11px;color:var(--muted);}
.table-card{background:white;border:1px solid var(--border);border-radius:var(--radius-default);overflow:hidden;box-shadow:0 1px 3px rgba(28,24,20,0.04);}
.thead{display:grid;grid-template-columns:20px 1fr 160px 70px 120px 110px 70px 130px;padding:10px 18px;background:var(--surface);font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.07em;border-bottom:1px solid var(--border);}
.trow{display:grid;grid-template-columns:20px 1fr 160px 70px 120px 110px 70px 130px;padding:14px 18px;align-items:center;border-bottom:1px solid var(--border);transition:background 0.1s;}
.trow:last-child{border-bottom:none;}
.trow:hover{background:var(--parchment);}
.hdot{width:7px;height:7px;border-radius:50%;flex-shrink:0;}
.hdot.active{background:var(--forest);}
.hdot.drifting{background:var(--terracotta);}
.hdot.cold{background:var(--clay);}
.cn{font-size:13px;font-weight:600;color:var(--espresso);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.cd{font-size:10px;color:var(--muted);font-family:"DM Mono",monospace;margin-top:2px;}
.co{font-size:12px;color:var(--espresso);}
.co small{display:block;font-size:10px;color:var(--muted);margin-top:1px;}
.cm{font-family:"DM Mono",monospace;font-size:12px;color:var(--espresso);}
.cmuted{font-size:11px;color:var(--muted);}
.badge{font-size:10px;font-weight:600;padding:2px 8px;border-radius:20px;display:inline-block;text-transform:uppercase;letter-spacing:0.03em;}
.b-basic{background:var(--surface);color:var(--muted);}
.b-pro{background:#F5E0D8;color:var(--clay);}
.b-on{background:#DFF0DD;color:var(--forest);}
.b-off{background:var(--surface);color:var(--muted);}
.b-suspended{background:#F8E8E4;color:var(--clay);}
.b-converted{background:#DFF0DD;color:var(--forest);}
.b-pending{background:#FEF3E4;color:var(--terracotta);}
.b-owner{background:#FEF3E4;color:var(--terracotta);}
.b-staff{background:#EDF2EC;color:var(--forest);}
.actions{display:flex;align-items:center;gap:3px;}
.abtn{width:28px;height:28px;border-radius:var(--radius-sm);border:1px solid var(--border);background:white;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:border-color 0.13s,color 0.13s,background 0.13s;text-decoration:none;color:var(--muted);flex-shrink:0;}
.abtn:hover{border-color:var(--terracotta);color:var(--terracotta);background:#FDF6EE;}
.abtn.red:hover{border-color:var(--clay);color:var(--clay);background:#FDF0ED;}
.abtn svg{width:13px;height:13px;}
.flash{border-radius:var(--radius-default);padding:14px 18px;margin-bottom:20px;border-left:3px solid;}
.flash.ok{background:#DFF0DD;border-color:var(--forest);}
.flash.err{background:#F8E8E4;border-color:var(--clay);}
.flash-t{font-size:13px;font-weight:700;margin-bottom:4px;}
.flash.ok .flash-t{color:var(--forest);}
.flash.err .flash-t{color:var(--clay);}
.flash-m{font-size:12px;color:var(--espresso);line-height:1.7;}
.wa-box{background:white;border-radius:var(--radius-sm);padding:11px 14px;font-size:11px;font-family:"DM Mono",monospace;line-height:1.65;color:var(--espresso);border:1px solid #C6E0C2;margin-top:8px;white-space:pre-wrap;max-width:480px;}
.btn-copy{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:var(--forest);color:white;border:none;border-radius:var(--radius-sm);font-family:inherit;font-size:11px;font-weight:600;cursor:pointer;margin-top:8px;transition:opacity 0.15s;}
.btn-copy:hover{opacity:0.85;}
.btn-new{display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:var(--terracotta);color:white;border:none;border-radius:var(--radius-md);font-family:"Plus Jakarta Sans",sans-serif;font-size:12px;font-weight:600;cursor:pointer;text-decoration:none;transition:opacity 0.15s;flex-shrink:0;}
.btn-new:hover{opacity:0.88;}
.stats-row{display:flex;gap:10px;margin-bottom:24px;flex-wrap:wrap;}
.scard{background:white;border:1px solid var(--border);border-radius:var(--radius-default);padding:16px 20px;box-shadow:0 1px 3px rgba(28,24,20,0.04);}
.scard-label{font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:4px;}
.scard-val{font-family:"DM Mono",monospace;font-size:26px;font-weight:500;color:var(--espresso);}
.empty{padding:48px;text-align:center;color:var(--muted);font-size:14px;}
@media(max-width:900px){.health-grid{grid-template-columns:repeat(3,1fr);}.thead{display:none;}.trow{grid-template-columns:20px 1fr auto;grid-template-rows:auto auto;gap:4px 8px;padding:12px 16px;}.col-owner,.col-plan,.col-shift,.col-sales,.col-shop{display:none;}.page-content{padding:20px;}.page-header{padding:20px;}}</style></head><body><div class="shell"><aside class="sidebar"><div class="sidebar-header"><span class="sidebar-logo">Stoka</span><span class="sidebar-tag">Super Admin</span></div><nav class="sidebar-nav"><span class="nav-section-label">Tenants</span><a href="/admin" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>Tenants</a><a href="/admin/inquiries" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M12 7a4 4 0 110 8 4 4 0 010-8z"/></svg>Inquiries</a><a href="/admin/demo-visits" class="nav-link active"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8zM12 9a3 3 0 110 6 3 3 0 010-6z"/></svg>Demo Visits</a><span class="nav-section-label">Content</span><a href="/admin/articles" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8zM14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>Articles</a><a href="/admin/testimonials" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>Testimonials</a><a href="/admin/demo-products" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>Demo Products</a><span class="nav-section-label">Actions</span><a href="/admin/tenants/create" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>New Tenant</a></nav><div class="sidebar-footer"><span class="sidebar-role">stoka.co.ke</span><form method="POST" action="/admin/logout">@csrf<button type="submit" class="logout-btn">Log out</button></form></div></aside>
<div class="main">
  <div class="page-header"><div><h1 class="page-title">Demo Visits</h1><p class="page-sub">Every person who entered the demo. The shop names they typed are your warmest leads.</p></div></div>
  <div class="page-content">
    <div class="stats-row">
      <div class="scard"><div class="scard-label">Today</div><div class="scard-val c-drifting">{{ $stats['today'] }}</div></div>
      <div class="scard"><div class="scard-label">This week</div><div class="scard-val">{{ $stats['week'] }}</div></div>
      <div class="scard"><div class="scard-label">Total</div><div class="scard-val">{{ $stats['total'] }}</div></div>
      <div class="scard"><div class="scard-label">As owner</div><div class="scard-val c-drifting">{{ $stats['owners'] }}</div></div>
      <div class="scard"><div class="scard-label">As staff</div><div class="scard-val c-active">{{ $stats['staff'] }}</div></div>
    </div>
    @if($topShopNames->isNotEmpty())
    <div style="background:white;border:1px solid var(--border);border-radius:var(--radius-default);padding:18px 20px;margin-bottom:20px;box-shadow:0 1px 3px rgba(28,24,20,0.04);">
      <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:12px;">Shop names typed</div>
      <div style="display:flex;flex-wrap:wrap;gap:7px;">
        @foreach($topShopNames as $s)<span style="display:inline-flex;align-items:center;gap:5px;padding:5px 11px;background:var(--surface);border:1px solid var(--border);border-radius:20px;font-size:12px;">{{ $s->shop_name }}<span style="font-family:DM Mono,monospace;font-size:10px;color:var(--muted);">{{ $s->count }}</span></span>@endforeach
      </div>
    </div>
    @endif
    <div class="table-card">
      <div class="thead" style="grid-template-columns:150px 1fr 1fr 80px 120px"><div>Time</div><div>Shop name</div><div>Owner name</div><div>Role</div><div>Ref</div></div>
      @forelse($visits as $v)
      <div class="trow" style="grid-template-columns:150px 1fr 1fr 80px 120px">
        <div class="cm" style="font-size:11px;">{{ \Carbon\Carbon::parse($v->created_at)->format('d M · g:ia') }}</div>
        <div class="cn">{{ $v->shop_name ?: '—' }}</div>
        <div class="co">{{ $v->owner_name ?: '—' }}</div>
        <div><span class="badge b-{{ $v->role }}">{{ $v->role }}</span></div>
        <div class="cmuted">{{ $v->ref ?: '—' }}</div>
      </div>
      @empty<div class="empty">No demo visits yet.</div>@endforelse
    </div>
  </div>
</div></div><script>function copyText(id,btn){navigator.clipboard.writeText(document.getElementById(id).innerText).then(function(){var o=btn.innerHTML;btn.innerHTML="Copied!";setTimeout(function(){btn.innerHTML=o;},2500);});}</script></body></html>