<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Stoka</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{
  --espresso:#1C1814;--terracotta:#C17F4A;--parchment:#FAF7F2;
  --forest:#4A6741;--clay:#B85C38;--surface:#F2EDE6;
  --muted:#8C8279;--border:#E8E2DA;--bg:#EDEAE5;
  --active:#4A6741;--drifting:#C17F4A;--cold:#B85C38;
}
html{scroll-behavior:smooth;}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--espresso);min-height:100vh;-webkit-font-smoothing:antialiased;}
a{text-decoration:none;color:inherit;}

/* NAV */
.nav{background:var(--espresso);height:56px;display:flex;align-items:center;justify-content:space-between;padding:0 28px;position:sticky;top:0;z-index:100;}
.nav-logo{font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:600;color:var(--parchment);letter-spacing:-0.01em;display:flex;align-items:center;gap:8px;}
.nav-logo-tag{font-family:'Plus Jakarta Sans',sans-serif;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;color:rgba(250,247,242,0.35);border:1px solid rgba(250,247,242,0.15);padding:2px 7px;border-radius:20px;}
.nav-links{display:flex;align-items:center;gap:4px;}
.nav-link{font-size:12px;font-weight:500;color:rgba(250,247,242,0.5);padding:6px 12px;border-radius:6px;transition:color 0.15s,background 0.15s;}
.nav-link:hover{color:var(--parchment);background:rgba(250,247,242,0.07);}
.nav-link.active{color:var(--parchment);background:rgba(250,247,242,0.1);}
.nav-right{display:flex;align-items:center;gap:10px;}
.btn-new{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;background:var(--terracotta);color:white;border:none;border-radius:7px;font-family:inherit;font-size:12px;font-weight:600;cursor:pointer;text-decoration:none;transition:opacity 0.15s;}
.btn-new:hover{opacity:0.88;}
.btn-logout{font-size:12px;color:rgba(250,247,242,0.4);padding:6px 10px;border-radius:6px;transition:color 0.15s;}
.btn-logout:hover{color:var(--parchment);}

/* MAIN */
.main{max-width:1200px;margin:0 auto;padding:28px 20px 60px;}

/* HEALTH STRIP */
.health-strip{display:grid;grid-template-columns:repeat(5,1fr);gap:10px;margin-bottom:14px;}
@media(max-width:800px){.health-strip{grid-template-columns:repeat(3,1fr);}}
@media(max-width:480px){.health-strip{grid-template-columns:repeat(2,1fr);}}
.hcard{background:var(--parchment);border-radius:12px;padding:16px 18px;border:1px solid var(--border);}
.hcard-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:6px;}
.hcard-value{font-family:'DM Mono',monospace;font-size:28px;font-weight:500;color:var(--espresso);line-height:1;}
.hcard-value.c-active{color:var(--active);}
.hcard-value.c-drifting{color:var(--drifting);}
.hcard-value.c-cold{color:var(--cold);}
.hcard-value.c-terra{color:var(--terracotta);}

/* DEMO PULSE */
.demo-pulse{background:var(--espresso);border-radius:10px;padding:11px 18px;margin-bottom:24px;display:flex;align-items:center;gap:20px;flex-wrap:wrap;}
.dp-item{font-size:12px;color:rgba(250,247,242,0.5);display:flex;align-items:center;gap:6px;}
.dp-val{font-family:'DM Mono',monospace;font-size:13px;color:var(--parchment);font-weight:500;}
.dp-dot{width:5px;height:5px;border-radius:50%;background:#4A6741;animation:dpulse 2s ease-in-out infinite;}
@keyframes dpulse{0%,100%{opacity:1;}50%{opacity:0.3;}}
.dp-divider{width:1px;height:14px;background:rgba(250,247,242,0.1);}

/* FLASH CARDS */
.flash{border-radius:12px;padding:16px 18px;margin-bottom:18px;display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;}
.flash.success{background:#DFF0DD;border-left:3px solid var(--forest);}
.flash.error{background:#F8E8E4;border-left:3px solid var(--clay);}
.flash-title{font-size:13px;font-weight:700;margin-bottom:8px;}
.flash.success .flash-title{color:var(--forest);}
.flash.error .flash-title{color:var(--clay);}
.flash-meta{font-size:12px;color:var(--espresso);line-height:1.8;}
.flash-meta strong{font-weight:600;}
.wa-box{background:white;border-radius:8px;padding:12px 14px;font-size:11px;font-family:'DM Mono',monospace;line-height:1.65;color:var(--espresso);border:1px solid #C6E0C2;margin-top:10px;white-space:pre-wrap;max-width:480px;}
.btn-copy{display:inline-flex;align-items:center;gap:5px;padding:7px 13px;background:var(--forest);color:white;border:none;border-radius:7px;font-family:inherit;font-size:11px;font-weight:600;cursor:pointer;margin-top:8px;transition:opacity 0.15s;}
.btn-copy:hover{opacity:0.85;}

/* SECTION HEADER */
.section-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;}
.section-head h2{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);}
.section-head-right{font-size:11px;color:var(--muted);}

/* TABLE */
.table-wrap{background:var(--parchment);border-radius:14px;overflow:hidden;border:1px solid var(--border);}
.table-head{display:grid;grid-template-columns:24px 1fr 160px 70px 110px 100px 70px 120px;gap:0;padding:9px 16px;background:#EAE6DF;font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.07em;border-bottom:1px solid var(--border);}
.table-row{display:grid;grid-template-columns:24px 1fr 160px 70px 110px 100px 70px 120px;gap:0;padding:13px 16px;align-items:center;border-bottom:1px solid var(--border);transition:background 0.1s;}
.table-row:last-child{border-bottom:none;}
.table-row:hover{background:#F4F0EB;}
@media(max-width:900px){
  .table-head{display:none;}
  .table-row{grid-template-columns:24px 1fr auto;grid-template-rows:auto auto;gap:4px 8px;padding:12px 14px;}
  .col-owner,.col-plan,.col-shift,.col-sales,.col-shop{display:none;}
  .col-name{grid-column:2;grid-row:1;}
  .col-actions{grid-column:3;grid-row:1 / span 2;align-self:center;}
}

/* HEALTH DOT */
.hdot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}
.hdot.active{background:var(--active);}
.hdot.drifting{background:var(--drifting);}
.hdot.cold{background:var(--cold);}

/* CELL STYLES */
.cell-name{font-size:13px;font-weight:600;color:var(--espresso);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.cell-domain{font-size:10px;color:var(--muted);font-family:'DM Mono',monospace;margin-top:1px;}
.cell-owner{font-size:12px;color:var(--espresso);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.cell-owner small{display:block;font-size:10px;color:var(--muted);}
.cell-mono{font-family:'DM Mono',monospace;font-size:12px;color:var(--espresso);}
.cell-muted{font-size:11px;color:var(--muted);}
.badge{font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;display:inline-block;text-transform:uppercase;letter-spacing:0.04em;}
.badge-basic{background:var(--bg);color:var(--muted);}
.badge-pro{background:#F5E0D8;color:var(--clay);}
.badge-active{background:#DFF0DD;color:var(--forest);}
.badge-suspended{background:#F8E8E4;color:var(--clay);}
.badge-shop-on{background:#DFF0DD;color:var(--forest);}
.badge-shop-off{background:var(--bg);color:var(--muted);}

/* ACTION BUTTONS */
.actions{display:flex;align-items:center;gap:4px;}
.abtn{width:28px;height:28px;border-radius:6px;border:1px solid var(--border);background:white;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:border-color 0.15s,background 0.15s;text-decoration:none;color:var(--muted);}
.abtn:hover{border-color:var(--terracotta);color:var(--terracotta);background:#FDF6EE;}
.abtn.danger:hover{border-color:var(--clay);color:var(--clay);background:#FDF0ED;}
.abtn svg{width:13px;height:13px;}
</style>
</head>
<body>

<nav class="nav">
  <div class="nav-logo">
    stoka <span class="nav-logo-tag">Admin</span>
  </div>
  <div class="nav-links">
    <a href="/admin" class="nav-link active">Tenants</a>
    <a href="/admin/inquiries" class="nav-link">Inquiries</a>
    <a href="/admin/demo-visits" class="nav-link">Demo Visits</a>
  </div>
  <div class="nav-right">
    <a href="{{ route('admin.tenants.create') }}" class="btn-new">
      <svg width="11" height="11" viewBox="0 0 11 11" fill="none"><path d="M5.5 1v9M1 5.5h9" stroke="white" stroke-width="1.7" stroke-linecap="round"/></svg>
      New Tenant
    </a>
    <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">
      @csrf
      <button type="submit" class="btn-logout nav-link">Logout</button>
    </form>
  </div>
</nav>

<div class="main">

  {{-- Health strip --}}
  <div class="health-strip">
    <div class="hcard">
      <div class="hcard-label">Total</div>
      <div class="hcard-value">{{ $stats['total'] }}</div>
    </div>
    <div class="hcard">
      <div class="hcard-label">Active</div>
      <div class="hcard-value c-active">{{ $stats['active'] }}</div>
    </div>
    <div class="hcard">
      <div class="hcard-label">Drifting</div>
      <div class="hcard-value c-drifting">{{ $stats['drifting'] }}</div>
    </div>
    <div class="hcard">
      <div class="hcard-label">Cold</div>
      <div class="hcard-value c-cold">{{ $stats['cold'] }}</div>
    </div>
    <div class="hcard">
      <div class="hcard-label">Inquiries</div>
      <div class="hcard-value c-terra">{{ $stats['inquiries'] }}</div>
    </div>
  </div>

  {{-- Demo pulse --}}
  <div class="demo-pulse">
    <div class="dp-item"><span class="dp-dot"></span> Demo visits today: <span class="dp-val">{{ $stats['demo_today'] }}</span></div>
    <div class="dp-divider"></div>
    <div class="dp-item">This week: <span class="dp-val">{{ $stats['demo_week'] }}</span></div>
    <div class="dp-divider"></div>
    <div class="dp-item">Last visit:
      <span class="dp-val">
        @if($stats['demo_last_visit'])
          {{ \Carbon\Carbon::parse($stats['demo_last_visit'])->diffForHumans() }}
        @else
          —
        @endif
      </span>
    </div>
    <div style="margin-left:auto;">
      <a href="/admin/demo-visits" class="nav-link" style="color:rgba(250,247,242,0.5);font-size:11px;">View all →</a>
    </div>
  </div>

  {{-- Flash messages --}}
  @if(session('error'))
  <div class="flash error"><div><div class="flash-title">Error</div><div class="flash-meta">{{ session('error') }}</div></div></div>
  @endif

  @if(session('created'))
  @php $cr = session('created'); @endphp
  <div class="flash success">
    <div>
      <div class="flash-title">✓ {{ $cr['shop_name'] }} is live</div>
      <div class="flash-meta">
        <strong>URL</strong> {{ $cr['shop_url'] }} &nbsp;·&nbsp;
        <strong>Owner</strong> {{ $cr['owner_name'] }} &nbsp;·&nbsp;
        <strong>Phone</strong> {{ $cr['owner_phone'] }} &nbsp;·&nbsp;
        <strong>Password</strong> <code>{{ $cr['password'] }}</code>
      </div>
      <div class="wa-box" id="waMsg">{{ $cr['wa_message'] }}</div>
      <button class="btn-copy" onclick="copyText('waMsg', this)">
        <svg width="11" height="11" viewBox="0 0 14 14" fill="none"><rect x="4" y="4" width="8" height="8" rx="2" stroke="white" stroke-width="1.4"/><path d="M3 10H2a1 1 0 01-1-1V2a1 1 0 011-1h7a1 1 0 011 1v1" stroke="white" stroke-width="1.4" stroke-linecap="round"/></svg>
        Copy WhatsApp message
      </button>
    </div>
  </div>
  @endif

  @if(session('reset_password'))
  @php $rp = session('reset_password'); @endphp
  <div class="flash success">
    <div>
      <div class="flash-title">✓ Password reset for {{ $rp['shop'] }}</div>
      <div class="flash-meta"><strong>New password:</strong> <code>{{ $rp['password'] }}</code></div>
      <div class="wa-box" id="rpMsg">{{ $rp['wa_message'] }}</div>
      <button class="btn-copy" onclick="copyText('rpMsg', this)">Copy WhatsApp message</button>
    </div>
  </div>
  @endif

  @if(session('toggled'))
  <div class="flash success"><div><div class="flash-title">✓ {{ session('toggled') }} status updated</div></div></div>
  @endif

  @if(session('toggled_shop'))
  @php $ts = session('toggled_shop'); @endphp
  <div class="flash success"><div><div class="flash-title">✓ Shop page {{ $ts['enabled'] ? 'enabled' : 'disabled' }} for {{ $ts['name'] }}</div></div></div>
  @endif

  {{-- Tenant table --}}
  <div class="section-head">
    <h2>All Tenants ({{ $enriched->count() }})</h2>
    <span class="section-head-right">
      Active = shift in last 7 days &nbsp;·&nbsp; Drifting = 8–30 days &nbsp;·&nbsp; Cold = 30+ days or never
    </span>
  </div>

  <div class="table-wrap">
    <div class="table-head">
      <div></div>
      <div>Shop</div>
      <div>Owner</div>
      <div>Plan</div>
      <div>Last shift</div>
      <div>Sales / month</div>
      <div>Shop</div>
      <div>Actions</div>
    </div>

    @forelse($enriched as $t)
    <div class="table-row">
      <div><span class="hdot {{ $t->health }}"></span></div>

      <div class="col-name">
        <div class="cell-name">{{ $t->name }}</div>
        @if($t->domain)
        <div class="cell-domain">{{ $t->domain }}.stoka.co.ke</div>
        @endif
      </div>

      <div class="col-owner cell-owner">
        {{ $t->owner_name }}
        <small>{{ $t->owner_phone }}</small>
      </div>

      <div class="col-plan">
        <span class="badge badge-{{ $t->plan }}">{{ $t->plan }}</span>
      </div>

      <div class="col-shift cell-mono">
        @if($t->last_shift_at)
          {{ $t->last_shift_at->format('d M') }}
          <div class="cell-muted">{{ $t->last_shift_at->diffForHumans() }}</div>
        @else
          <span class="cell-muted">—</span>
        @endif
      </div>

      <div class="col-sales cell-mono">
        @if($t->sales_this_month > 0)
          {{ number_format($t->sales_this_month) }}
        @else
          <span class="cell-muted">—</span>
        @endif
      </div>

      <div class="col-shop">
        <span class="badge {{ $t->shop_enabled ? 'badge-shop-on' : 'badge-shop-off' }}">
          {{ $t->shop_enabled ? 'Live' : 'Off' }}
        </span>
      </div>

      <div class="col-actions">
        <div class="actions">
          {{-- View detail --}}
          <a href="/admin/tenants/{{ $t->id }}/detail" class="abtn" title="View detail">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </a>

          {{-- Open dashboard --}}
          @if($t->domain)
          <a href="https://{{ $t->domain }}.stoka.co.ke" target="_blank" class="abtn" title="Open dashboard">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
          </a>
          @endif

          {{-- View shop --}}
          @if($t->shop_enabled && $t->domain)
          <a href="https://{{ $t->domain }}.stoka.co.ke/shop" target="_blank" class="abtn" title="View shop">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
          </a>
          @endif

          {{-- WhatsApp owner --}}
          @if($t->owner_phone)
          <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $t->owner_phone) }}?text={{ urlencode('Hi ' . $t->owner_name . ', checking in from Stoka — how is everything going at ' . $t->name . '?') }}" target="_blank" class="abtn" title="WhatsApp owner">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
          </a>
          @endif

          {{-- Toggle shop --}}
          @if($t->id !== 'demo')
          <form method="POST" action="/admin/tenants/{{ $t->id }}/shop-toggle" style="display:inline">
            @csrf
            <button type="submit" class="abtn" title="{{ $t->shop_enabled ? 'Disable shop' : 'Enable shop' }}">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            </button>
          </form>

          {{-- Reset password --}}
          <form method="POST" action="/admin/tenants/{{ $t->id }}/reset-password" style="display:inline" onsubmit="return confirm('Reset password for {{ $t->name }}?')">
            @csrf
            <button type="submit" class="abtn danger" title="Reset password">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            </button>
          </form>

          {{-- Suspend/Activate --}}
          <form method="POST" action="{{ route('admin.tenants.toggle', $t->id) }}" style="display:inline" onsubmit="return confirm('{{ $t->status === 'active' ? 'Suspend' : 'Activate' }} {{ $t->name }}?')">
            @csrf
            <button type="submit" class="abtn danger" title="{{ $t->status === 'active' ? 'Suspend' : 'Activate' }}">
              @if($t->status === 'active')
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
              @else
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="9 11 12 14 22 4"/></svg>
              @endif
            </button>
          </form>
          @endif
        </div>
      </div>
    </div>
    @empty
    <div style="padding:48px;text-align:center;color:var(--muted);font-size:14px;">No tenants yet.</div>
    @endforelse
  </div>

</div>

<script>
function copyText(id, btn) {
  var text = document.getElementById(id).innerText;
  navigator.clipboard.writeText(text).then(function() {
    var orig = btn.innerHTML;
    btn.innerHTML = '✓ Copied!';
    setTimeout(function() { btn.innerHTML = orig; }, 2500);
  });
}
</script>
</body>
</html>
