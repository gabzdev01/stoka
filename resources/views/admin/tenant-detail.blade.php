<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $tenant->name }} — Stoka Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{--espresso:#1C1814;--terracotta:#C17F4A;--parchment:#FAF7F2;--forest:#4A6741;--clay:#B85C38;--surface:#F2EDE6;--muted:#8C8279;--border:#E8E2DA;--bg:#EDEAE5;}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--espresso);min-height:100vh;-webkit-font-smoothing:antialiased;}
a{text-decoration:none;color:inherit;}
.nav{background:var(--espresso);height:56px;display:flex;align-items:center;justify-content:space-between;padding:0 28px;position:sticky;top:0;z-index:100;}
.nav-logo{font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:600;color:var(--parchment);letter-spacing:-0.01em;display:flex;align-items:center;gap:8px;}
.nav-logo-tag{font-family:'Plus Jakarta Sans',sans-serif;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;color:rgba(250,247,242,0.35);border:1px solid rgba(250,247,242,0.15);padding:2px 7px;border-radius:20px;}
.nav-back{font-size:12px;color:rgba(250,247,242,0.5);padding:6px 12px;border-radius:6px;transition:color 0.15s;}
.nav-back:hover{color:var(--parchment);}
.btn-logout{font-size:12px;color:rgba(250,247,242,0.4);padding:6px 10px;border-radius:6px;}
.btn-logout:hover{color:var(--parchment);}
.main{max-width:1100px;margin:0 auto;padding:28px 20px 60px;}
.page-header{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:28px;flex-wrap:wrap;}
.page-title{font-family:'Cormorant Garamond',serif;font-size:32px;font-weight:500;color:var(--espresso);}
.page-domain{font-family:'DM Mono',monospace;font-size:12px;color:var(--muted);margin-top:4px;}
.header-actions{display:flex;gap:8px;flex-wrap:wrap;align-items:center;}
.flash{border-radius:12px;padding:14px 18px;margin-bottom:20px;}
.flash.success{background:#DFF0DD;border-left:3px solid var(--forest);}
.flash.error{background:#F8E8E4;border-left:3px solid var(--clay);}
.flash-title{font-size:13px;font-weight:600;margin-bottom:6px;}
.flash.success .flash-title{color:var(--forest);}
.flash.error .flash-title{color:var(--clay);}
.flash-meta{font-size:12px;color:var(--espresso);}
.wa-box{background:white;border-radius:8px;padding:11px 14px;font-size:11px;font-family:'DM Mono',monospace;line-height:1.65;color:var(--espresso);border:1px solid #C6E0C2;margin-top:8px;white-space:pre-wrap;}
.btn-copy{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:var(--forest);color:white;border:none;border-radius:7px;font-family:inherit;font-size:11px;font-weight:600;cursor:pointer;margin-top:8px;}
.grid-3{display:grid;grid-template-columns:280px 1fr;gap:20px;}
@media(max-width:800px){.grid-3{grid-template-columns:1fr;}}
.card{background:var(--parchment);border:1px solid var(--border);border-radius:14px;overflow:hidden;margin-bottom:16px;}
.card-head{padding:13px 18px;border-bottom:1px solid var(--border);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);display:flex;align-items:center;justify-content:space-between;}
.card-body{padding:18px;}
.info-row{display:flex;justify-content:space-between;align-items:baseline;padding:7px 0;border-bottom:1px solid var(--border);}
.info-row:last-child{border-bottom:none;}
.info-label{font-size:11px;color:var(--muted);}
.info-value{font-size:12px;font-weight:500;color:var(--espresso);text-align:right;}
.info-mono{font-family:'DM Mono',monospace;}
.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:7px;font-family:inherit;font-size:12px;font-weight:600;cursor:pointer;border:none;transition:opacity 0.15s;text-decoration:none;}
.btn:hover{opacity:0.85;}
.btn-primary{background:var(--terracotta);color:white;}
.btn-ghost{background:white;color:var(--espresso);border:1px solid var(--border);}
.btn-ghost:hover{border-color:var(--terracotta);color:var(--terracotta);}
.btn-danger{background:#F8E8E4;color:var(--clay);border:1px solid #E8C8C0;}
.btn-danger:hover{background:#F0D8D4;}
.btn-wa{background:#25D366;color:white;}
.quick-actions{display:flex;flex-direction:column;gap:8px;}
.badge{font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;display:inline-block;text-transform:uppercase;}
.badge-active{background:#DFF0DD;color:var(--forest);}
.badge-suspended{background:#F8E8E4;color:var(--clay);}
.badge-basic{background:var(--bg);color:var(--muted);}
.badge-pro{background:#F5E0D8;color:var(--clay);}
.chart-wrap{padding:18px;}
.chart-bars{display:flex;align-items:flex-end;gap:8px;height:120px;}
.bar-col{flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;height:100%;}
.bar-track{flex:1;width:100%;display:flex;align-items:flex-end;}
.bar-fill{width:100%;background:var(--terracotta);border-radius:4px 4px 0 0;opacity:0.75;min-height:2px;transition:opacity 0.15s;}
.bar-fill:hover{opacity:1;}
.bar-label{font-size:9px;color:var(--muted);white-space:nowrap;}
.bar-value{font-family:'DM Mono',monospace;font-size:9px;color:var(--espresso);}
.shift-row{display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);gap:12px;}
.shift-row:last-child{border-bottom:none;}
.shift-staff{font-size:13px;font-weight:600;color:var(--espresso);}
.shift-meta{font-size:11px;color:var(--muted);}
.shift-amount{font-family:'DM Mono',monospace;font-size:13px;color:var(--espresso);text-align:right;}
.shift-disc{font-size:11px;text-align:right;}
.disc-ok{color:var(--forest);}
.disc-bad{color:var(--clay);}
.stat-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
.mini-stat{background:var(--bg);border-radius:8px;padding:12px 14px;}
.mini-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:var(--muted);margin-bottom:4px;}
.mini-value{font-family:'DM Mono',monospace;font-size:20px;font-weight:500;color:var(--espresso);}
.staff-row{display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);}
.staff-row:last-child{border-bottom:none;}
.staff-name{font-size:13px;font-weight:500;color:var(--espresso);}
.staff-phone{font-size:11px;color:var(--muted);}
.empty-state{padding:24px;text-align:center;color:var(--muted);font-size:13px;}
</style>
</head>
<body>
<nav class="nav">
  <div class="nav-logo">stoka <span class="nav-logo-tag">Admin</span></div>
  <a href="/admin" class="nav-back">← All tenants</a>
  <div>
    <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">
      @csrf
      <button type="submit" class="btn-logout">Logout</button>
    </form>
  </div>
</nav>

<div class="main">

  {{-- Flash --}}
  @if(session('error'))
  <div class="flash error"><div class="flash-title">{{ session('error') }}</div></div>
  @endif
  @if(session('reset_password'))
  @php $rp = session('reset_password'); @endphp
  <div class="flash success">
    <div class="flash-title">✓ Password reset</div>
    <div class="flash-meta"><strong>New password:</strong> <code>{{ $rp['password'] }}</code></div>
    <div class="wa-box" id="rpMsg">{{ $rp['wa_message'] }}</div>
    <button class="btn-copy" onclick="copyText('rpMsg',this)">Copy WhatsApp message</button>
  </div>
  @endif
  @if(session('toggled_shop'))
  @php $ts = session('toggled_shop'); @endphp
  <div class="flash success"><div class="flash-title">✓ Shop {{ $ts['enabled'] ? 'enabled' : 'disabled' }} for {{ $ts['name'] }}</div></div>
  @endif

  {{-- Page header --}}
  <div class="page-header">
    <div>
      <h1 class="page-title">{{ $tenant->name }}</h1>
      @if($tenant->domains->first())
      <div class="page-domain">{{ $tenant->domains->first()->domain }}.stoka.co.ke</div>
      @endif
    </div>
    <div class="header-actions">
      @if($tenant->domains->first())
      <a href="https://{{ $tenant->domains->first()->domain }}.stoka.co.ke" target="_blank" class="btn btn-ghost">Open dashboard →</a>
      @endif
      @if($tenant->shop_enabled && $tenant->domains->first())
      <a href="https://{{ $tenant->domains->first()->domain }}.stoka.co.ke/shop" target="_blank" class="btn btn-ghost">View shop →</a>
      @endif
    </div>
  </div>

  <div class="grid-3">

    {{-- LEFT COLUMN --}}
    <div>
      {{-- Shop info --}}
      <div class="card">
        <div class="card-head">Shop info</div>
        <div class="card-body">
          <div class="info-row"><span class="info-label">Owner</span><span class="info-value">{{ $tenant->owner_name }}</span></div>
          <div class="info-row"><span class="info-label">Phone</span><span class="info-value info-mono">{{ $tenant->owner_phone }}</span></div>
          <div class="info-row"><span class="info-label">Plan</span><span class="info-value"><span class="badge badge-{{ $tenant->plan }}">{{ $tenant->plan }}</span></span></div>
          <div class="info-row"><span class="info-label">Status</span><span class="info-value"><span class="badge badge-{{ $tenant->status }}">{{ $tenant->status }}</span></span></div>
          <div class="info-row"><span class="info-label">Currency</span><span class="info-value info-mono">{{ $tenant->currency }}</span></div>
          <div class="info-row"><span class="info-label">Shop page</span><span class="info-value">{{ $tenant->shop_enabled ? 'Live' : 'Off' }}</span></div>
          <div class="info-row"><span class="info-label">Owner last seen</span>
            <span class="info-value info-mono">
              @if($detail['owner_last_seen'])
                {{ \Carbon\Carbon::parse($detail['owner_last_seen'])->diffForHumans() }}
              @else —
              @endif
            </span>
          </div>
          <div class="info-row"><span class="info-label">Created</span><span class="info-value info-mono">{{ \Carbon\Carbon::parse($tenant->created_at)->format('d M Y') }}</span></div>
        </div>
      </div>

      {{-- Quick actions --}}
      @if($tenant->id !== 'demo')
      <div class="card">
        <div class="card-head">Quick actions</div>
        <div class="card-body">
          <div class="quick-actions">
            {{-- WhatsApp --}}
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->owner_phone) }}?text={{ urlencode('Hi ' . $tenant->owner_name . ', checking in from Stoka — how is everything going at ' . $tenant->name . '?') }}"
               target="_blank" class="btn btn-wa">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
              WhatsApp owner
            </a>

            {{-- Reset password --}}
            <form method="POST" action="/admin/tenants/{{ $tenant->id }}/reset-password" onsubmit="return confirm('Reset password for {{ $tenant->name }}?')">
              @csrf
              <button type="submit" class="btn btn-ghost" style="width:100%;">Reset owner password</button>
            </form>

            {{-- Toggle shop --}}
            <form method="POST" action="/admin/tenants/{{ $tenant->id }}/shop-toggle">
              @csrf
              <button type="submit" class="btn btn-ghost" style="width:100%;">
                {{ $tenant->shop_enabled ? 'Disable shop page' : 'Enable shop page' }}
              </button>
            </form>

            {{-- Suspend/Activate --}}
            <form method="POST" action="{{ route('admin.tenants.toggle', $tenant->id) }}" onsubmit="return confirm('{{ $tenant->status === 'active' ? 'Suspend' : 'Activate' }} {{ $tenant->name }}?')">
              @csrf
              <button type="submit" class="btn {{ $tenant->status === 'active' ? 'btn-danger' : 'btn-ghost' }}" style="width:100%;">
                {{ $tenant->status === 'active' ? 'Suspend tenant' : 'Activate tenant' }}
              </button>
            </form>
          </div>
        </div>
      </div>
      @endif

      {{-- Stock & credit --}}
      <div class="card">
        <div class="card-head">At a glance</div>
        <div class="card-body">
          <div class="stat-grid">
            <div class="mini-stat"><div class="mini-label">Active products</div><div class="mini-value">{{ $detail['products']['active'] }}</div></div>
            <div class="mini-stat"><div class="mini-label">Inactive</div><div class="mini-value">{{ $detail['products']['inactive'] }}</div></div>
            <div class="mini-stat"><div class="mini-label">Open credit</div><div class="mini-value" style="font-size:15px;">{{ number_format($detail['open_credit']) }}</div></div>
            <div class="mini-stat"><div class="mini-label">Total shifts</div><div class="mini-value">{{ $detail['total_shifts'] }}</div></div>
          </div>
        </div>
      </div>

      {{-- Staff --}}
      <div class="card">
        <div class="card-head">Staff ({{ $detail['staff']->count() }})</div>
        <div class="card-body" style="padding:0 18px;">
          @forelse($detail['staff'] as $s)
          <div class="staff-row">
            <div>
              <div class="staff-name">{{ $s->name }}</div>
              <div class="staff-phone">{{ $s->phone }}</div>
            </div>
            <span style="font-size:10px;color:{{ $s->active ? 'var(--forest)' : 'var(--muted)' }};">{{ $s->active ? 'Active' : 'Inactive' }}</span>
          </div>
          @empty
          <div class="empty-state">No staff added yet.</div>
          @endforelse
        </div>
      </div>
    </div>

    {{-- RIGHT COLUMN --}}
    <div>
      {{-- Sales chart --}}
      <div class="card">
        <div class="card-head">
          <span>Sales — last 6 months</span>
          <span style="font-family:'DM Mono',monospace;font-size:12px;color:var(--espresso);">{{ $tenant->currency }} {{ number_format($detail['total_sales']) }} total</span>
        </div>
        <div class="chart-wrap">
          <div class="chart-bars">
            @foreach($detail['monthly_sales'] as $ms)
            @php $pct = $maxSale > 0 ? round($ms->total / $maxSale * 100) : 0; @endphp
            <div class="bar-col">
              <div class="bar-value">{{ $ms->total > 0 ? number_format($ms->total / 1000, 0) . 'k' : '' }}</div>
              <div class="bar-track">
                <div class="bar-fill" style="height:{{ max($pct, $ms->total > 0 ? 3 : 0) }}%" title="{{ $tenant->currency }} {{ number_format($ms->total) }}"></div>
              </div>
              <div class="bar-label">{{ substr($ms->month, 0, 3) }}</div>
            </div>
            @endforeach
          </div>
        </div>
      </div>

      {{-- Recent shifts --}}
      <div class="card">
        <div class="card-head">Recent shifts (last 30)</div>
        <div class="card-body" style="padding:0 18px;">
          @forelse($detail['shifts'] as $sh)
          @php
            $shTotal = 0; // we don't have sales sum here — show discrepancy only
            $disc = (float) $sh->cash_discrepancy;
          @endphp
          <div class="shift-row">
            <div>
              <div class="shift-staff">{{ $sh->staff_name }}</div>
              <div class="shift-meta">
                {{ \Carbon\Carbon::parse($sh->opened_at)->format('d M') }}
                @if($sh->closed_at)
                  · {{ \Carbon\Carbon::parse($sh->opened_at)->diffInHours(\Carbon\Carbon::parse($sh->closed_at)) }}h
                @endif
              </div>
            </div>
            <div>
              @if($disc == 0)
                <div class="shift-disc disc-ok">Balanced</div>
              @elseif($disc < 0)
                <div class="shift-disc disc-bad">{{ number_format(abs($disc), 0) }} short</div>
              @else
                <div class="shift-disc" style="color:var(--terracotta);">{{ number_format($disc, 0) }} over</div>
              @endif
            </div>
          </div>
          @empty
          <div class="empty-state">No shifts recorded yet.</div>
          @endforelse
        </div>
      </div>
    </div>

  </div>
</div>

<script>
function copyText(id, btn) {
  navigator.clipboard.writeText(document.getElementById(id).innerText).then(function() {
    var orig = btn.innerHTML;
    btn.innerHTML = '✓ Copied!';
    setTimeout(function() { btn.innerHTML = orig; }, 2500);
  });
}
</script>
</body>
</html>
