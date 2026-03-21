<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Dashboard — Stoka Admin</title><link rel="preconnect" href="https://fonts.googleapis.com"><link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"><style>*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
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
@media(max-width:900px){.health-grid{grid-template-columns:repeat(3,1fr);}.thead{display:none;}.trow{grid-template-columns:20px 1fr auto;grid-template-rows:auto auto;gap:4px 8px;padding:12px 16px;}.col-owner,.col-plan,.col-shift,.col-sales,.col-shop{display:none;}.page-content{padding:20px;}.page-header{padding:20px;}}</style></head><body><div class="shell"><aside class="sidebar"><div class="sidebar-header"><span class="sidebar-logo">Stoka</span><span class="sidebar-tag">Super Admin</span></div><nav class="sidebar-nav"><span class="nav-section-label">Management</span><a href="/admin" class="nav-link active"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>Tenants</a><a href="/admin/inquiries" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M12 7a4 4 0 110 8 4 4 0 010-8z"/></svg>Inquiries</a><a href="/admin/demo-visits" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8zM12 9a3 3 0 110 6 3 3 0 010-6z"/></svg>Demo Visits</a><a href="/admin/tenants/create" class="nav-link"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>New Tenant</a></nav><div class="sidebar-footer"><span class="sidebar-admin">Super Admin</span><span class="sidebar-role">stoka.co.ke</span><form method="POST" action="/admin/logout">@csrf<button type="submit" class="logout-btn">Log out</button></form></div></aside>
<div class="main">
  <div class="page-header">
    <div><h1 class="page-title">Dashboard</h1><p class="page-sub">Green = active this week &nbsp;·&nbsp; Amber = drifting &nbsp;·&nbsp; Red = cold or never opened</p></div>
    <a href="{{ route('admin.tenants.create') }}" class="btn-new"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>New Tenant</a>
  </div>
  <div class="page-content">
    <div class="health-grid">
      <div class="hcard"><div class="hcard-label">Total</div><div class="hcard-val">{{ $stats['total'] }}</div></div>
      <div class="hcard"><div class="hcard-label">Active</div><div class="hcard-val c-active">{{ $stats['active'] }}</div></div>
      <div class="hcard"><div class="hcard-label">Drifting</div><div class="hcard-val c-drifting">{{ $stats['drifting'] }}</div></div>
      <div class="hcard"><div class="hcard-label">Cold</div><div class="hcard-val c-cold">{{ $stats['cold'] }}</div></div>
      <div class="hcard"><div class="hcard-label">Inquiries</div><div class="hcard-val c-drifting">{{ $stats['inquiries'] }}</div></div>
    </div>
    <div class="demo-pulse">
      <div class="dp-item"><span class="dp-dot"></span>Demo today&nbsp;<span class="dp-val">{{ $stats['demo_today'] }}</span></div>
      <div class="dp-div"></div>
      <div class="dp-item">This week&nbsp;<span class="dp-val">{{ $stats['demo_week'] }}</span></div>
      <div class="dp-div"></div>
      <div class="dp-item">Last visit&nbsp;<span class="dp-val">@if($stats['demo_last_visit']){{ \Carbon\Carbon::parse($stats['demo_last_visit'])->diffForHumans() }}@else—@endif</span></div>
      <a href="/admin/demo-visits" class="dp-link" style="margin-left:auto;">View all →</a>
    </div>
    @if(session('error'))<div class="flash err"><div class="flash-t">{{ session('error') }}</div></div>@endif
    @if(session('toggled'))<div class="flash ok"><div class="flash-t">✓ {{ session('toggled') }} updated</div></div>@endif
    @if(session('toggled_shop'))@php $ts=session('toggled_shop');@endphp<div class="flash ok"><div class="flash-t">✓ Shop {{ $ts['enabled']?'enabled':'disabled' }} for {{ $ts['name'] }}</div></div>@endif
    @if(session('reset_password'))@php $rp=session('reset_password');@endphp<div class="flash ok"><div class="flash-t">✓ Password reset for {{ $rp['shop'] }}</div><div class="flash-m">New password: <code>{{ $rp['password'] }}</code></div><div class="wa-box" id="rpMsg">{{ $rp['wa_message'] }}</div><button class="btn-copy" onclick="copyText('rpMsg',this)">Copy WhatsApp message</button></div>@endif
    @if(session('created'))@php $cr=session('created');@endphp<div class="flash ok"><div class="flash-t">✓ {{ $cr['shop_name'] }} is live</div><div class="flash-m"><strong>URL</strong> {{ $cr['shop_url'] }} &nbsp;·&nbsp; <strong>Owner</strong> {{ $cr['owner_name'] }} &nbsp;·&nbsp; <strong>Password</strong> <code>{{ $cr['password'] }}</code></div><div class="wa-box" id="waMsg">{{ $cr['wa_message'] }}</div><button class="btn-copy" onclick="copyText('waMsg',this)">Copy WhatsApp message</button></div>@endif
    <div class="section-head"><h2>All Tenants ({{ $enriched->count() }})</h2><span class="section-note">Active = last 7 days &nbsp;·&nbsp; Drifting = 8–30 days &nbsp;·&nbsp; Cold = 30+ or never</span></div>
    <div class="table-card">
      <div class="thead"><div></div><div>Shop</div><div>Owner</div><div>Plan</div><div>Last shift</div><div>Sales / month</div><div>Shop</div><div>Actions</div></div>
      @forelse($enriched as $t)
      <div class="trow">
        <div><span class="hdot {{ $t->health }}"></span></div>
        <div><div class="cn">{{ $t->name }}</div>@if($t->domain)<div class="cd">{{ $t->domain }}.stoka.co.ke</div>@endif</div>
        <div class="col-owner co">{{ $t->owner_name }}<small>{{ $t->owner_phone }}</small></div>
        <div class="col-plan"><span class="badge b-{{ $t->plan }}">{{ $t->plan }}</span></div>
        <div class="col-shift cm">@if($t->last_shift_at){{ $t->last_shift_at->format('d M') }}<div class="cmuted">{{ $t->last_shift_at->diffForHumans() }}</div>@else<span class="cmuted">—</span>@endif</div>
        <div class="col-sales cm">@if($t->sales_this_month>0){{ number_format($t->sales_this_month) }}@else<span class="cmuted">—</span>@endif</div>
        <div class="col-shop"><span class="badge {{ $t->shop_enabled?'b-on':'b-off' }}">{{ $t->shop_enabled?'Live':'Off' }}</span></div>
        <div class="actions">
          <a href="/admin/tenants/{{ $t->id }}/detail" class="abtn" title="Detail"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
          @if($t->domain)<a href="https://{{ $t->domain }}.stoka.co.ke" target="_blank" class="abtn" title="Open dashboard"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg></a>@endif
          @if($t->shop_enabled && $t->domain)<a href="https://{{ $t->domain }}.stoka.co.ke/shop" target="_blank" class="abtn" title="Shop"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg></a>@endif
          @if($t->owner_phone)<a href="https://wa.me/{{ preg_replace('/[^0-9]/','', $t->owner_phone) }}?text={{ urlencode('Hi '.$t->owner_name).',+checking+in+from+Stoka' }}" target="_blank" class="abtn" title="WhatsApp"><svg viewBox="0 0 24 24" fill="currentColor" width="13" height="13"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg></a>@endif
          @if($t->id !== 'demo')
          <form method="POST" action="/admin/tenants/{{ $t->id }}/shop-toggle" style="display:inline">@csrf<button type="submit" class="abtn" title="{{ $t->shop_enabled?'Disable shop':'Enable shop' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></button></form>
          <form method="POST" action="/admin/tenants/{{ $t->id }}/reset-password" style="display:inline" onsubmit="return confirm('Reset?')">@csrf<button type="submit" class="abtn red" title="Reset password"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg></button></form>
          <form method="POST" action="{{ route('admin.tenants.toggle', $t->id) }}" style="display:inline" onsubmit="return confirm('{{ $t->status==='active'?'Suspend':'Activate' }}?')">@csrf<button type="submit" class="abtn red" title="{{ $t->status==='active'?'Suspend':'Activate' }}">@if($t->status==='active')<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>@else<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>@endif</button></form>
          @endif
        </div>
      </div>
      @empty<div class="empty">No tenants yet.</div>@endforelse
    </div>
  </div>
</div></div><script>function copyText(id,btn){navigator.clipboard.writeText(document.getElementById(id).innerText).then(function(){var o=btn.innerHTML;btn.innerHTML="Copied!";setTimeout(function(){btn.innerHTML=o;},2500);});}</script></body></html>