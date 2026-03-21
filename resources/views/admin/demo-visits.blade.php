<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Demo Visits — Stoka Admin</title>
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
.nav-links{display:flex;align-items:center;gap:4px;}
.nav-link{font-size:12px;font-weight:500;color:rgba(250,247,242,0.5);padding:6px 12px;border-radius:6px;transition:color 0.15s,background 0.15s;}
.nav-link:hover{color:var(--parchment);background:rgba(250,247,242,0.07);}
.nav-link.active{color:var(--parchment);background:rgba(250,247,242,0.1);}
.btn-logout{font-size:12px;color:rgba(250,247,242,0.4);padding:6px 10px;border-radius:6px;transition:color 0.15s;}
.btn-logout:hover{color:var(--parchment);}
.main{max-width:1100px;margin:0 auto;padding:28px 20px 60px;}
.page-title{font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:500;color:var(--espresso);margin-bottom:4px;}
.page-sub{font-size:13px;color:var(--muted);margin-bottom:24px;}
.stats-row{display:grid;grid-template-columns:repeat(5,1fr);gap:10px;margin-bottom:28px;}
@media(max-width:600px){.stats-row{grid-template-columns:repeat(3,1fr);}}
.scard{background:var(--parchment);border:1px solid var(--border);border-radius:10px;padding:14px 18px;}
.scard-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:4px;}
.scard-value{font-family:'DM Mono',monospace;font-size:24px;font-weight:500;color:var(--espresso);}
.scard-value.c-terra{color:var(--terracotta);}
.scard-value.c-forest{color:var(--forest);}
.two-col{display:grid;grid-template-columns:1fr 320px;gap:20px;margin-bottom:28px;}
@media(max-width:760px){.two-col{grid-template-columns:1fr;}}
.card{background:var(--parchment);border:1px solid var(--border);border-radius:14px;overflow:hidden;}
.card-head{padding:14px 18px;border-bottom:1px solid var(--border);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);}
.card-body{padding:16px 18px;}
.shop-pills{display:flex;flex-wrap:wrap;gap:7px;}
.shop-pill{display:inline-flex;align-items:center;gap:5px;padding:5px 11px;background:var(--bg);border:1px solid var(--border);border-radius:20px;font-size:12px;color:var(--espresso);}
.shop-pill-count{font-family:'DM Mono',monospace;font-size:10px;color:var(--muted);}
.role-bar{display:flex;gap:12px;align-items:center;}
.role-item{display:flex;flex-direction:column;gap:3px;flex:1;}
.role-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:var(--muted);}
.role-track{height:6px;background:var(--bg);border-radius:3px;overflow:hidden;}
.role-fill{height:100%;border-radius:3px;}
.role-fill.owner{background:var(--terracotta);}
.role-fill.staff{background:var(--forest);}
.role-count{font-family:'DM Mono',monospace;font-size:13px;font-weight:500;color:var(--espresso);}
.table-wrap{background:var(--parchment);border-radius:14px;overflow:hidden;border:1px solid var(--border);}
.table-head{display:grid;grid-template-columns:160px 1fr 1fr 80px 130px;padding:9px 16px;background:#EAE6DF;font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.07em;border-bottom:1px solid var(--border);}
.table-row{display:grid;grid-template-columns:160px 1fr 1fr 80px 130px;padding:11px 16px;align-items:center;border-bottom:1px solid var(--border);transition:background 0.1s;}
.table-row:last-child{border-bottom:none;}
.table-row:hover{background:#F4F0EB;}
@media(max-width:700px){
  .table-head{display:none;}
  .table-row{grid-template-columns:1fr auto;gap:2px 8px;}
  .col-owner,.col-ref{display:none;}
}
.cell-mono{font-family:'DM Mono',monospace;font-size:11px;color:var(--muted);}
.cell-name{font-size:13px;font-weight:500;color:var(--espresso);}
.cell-muted{font-size:12px;color:var(--muted);}
.badge{font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;display:inline-block;}
.badge-owner{background:#FEF3E4;color:var(--terracotta);}
.badge-staff{background:#EDF2EC;color:var(--forest);}
.empty{padding:48px;text-align:center;color:var(--muted);font-size:14px;}
</style>
</head>
<body>
<nav class="nav">
  <div class="nav-logo">stoka <span class="nav-logo-tag">Admin</span></div>
  <div class="nav-links">
    <a href="/admin" class="nav-link">Tenants</a>
    <a href="/admin/inquiries" class="nav-link">Inquiries</a>
    <a href="/admin/demo-visits" class="nav-link active">Demo Visits</a>
  </div>
  <div>
    <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">
      @csrf
      <button type="submit" class="btn-logout nav-link">Logout</button>
    </form>
  </div>
</nav>

<div class="main">
  <h1 class="page-title">Demo Visits</h1>
  <p class="page-sub">Every person who entered the demo. The shop names they typed are your warmest leads.</p>

  <div class="stats-row">
    <div class="scard"><div class="scard-label">Today</div><div class="scard-value c-terra">{{ $stats['today'] }}</div></div>
    <div class="scard"><div class="scard-label">This week</div><div class="scard-value">{{ $stats['week'] }}</div></div>
    <div class="scard"><div class="scard-label">Total</div><div class="scard-value">{{ $stats['total'] }}</div></div>
    <div class="scard"><div class="scard-label">As owner</div><div class="scard-value c-terra">{{ $stats['owners'] }}</div></div>
    <div class="scard"><div class="scard-label">As staff</div><div class="scard-value c-forest">{{ $stats['staff'] }}</div></div>
  </div>

  <div class="two-col">
    {{-- Shop name cloud --}}
    <div class="card">
      <div class="card-head">Shop names typed (top {{ $topShopNames->count() }})</div>
      <div class="card-body">
        @if($topShopNames->isEmpty())
          <p style="color:var(--muted);font-size:13px;">No data yet.</p>
        @else
        <div class="shop-pills">
          @foreach($topShopNames as $s)
          <span class="shop-pill">
            {{ $s->shop_name }}
            <span class="shop-pill-count">{{ $s->count }}</span>
          </span>
          @endforeach
        </div>
        @endif
      </div>
    </div>

    {{-- Role split --}}
    <div class="card">
      <div class="card-head">Owner vs Staff split</div>
      <div class="card-body">
        @php
          $total = max($stats['owners'] + $stats['staff'], 1);
          $ownerPct = round($stats['owners'] / $total * 100);
          $staffPct = 100 - $ownerPct;
        @endphp
        <div class="role-bar">
          <div class="role-item">
            <div class="role-label">Owner</div>
            <div class="role-track"><div class="role-fill owner" style="width:{{ $ownerPct }}%"></div></div>
            <div class="role-count">{{ $stats['owners'] }} <span style="font-size:11px;color:var(--muted);">({{ $ownerPct }}%)</span></div>
          </div>
          <div class="role-item">
            <div class="role-label">Staff</div>
            <div class="role-track"><div class="role-fill staff" style="width:{{ $staffPct }}%"></div></div>
            <div class="role-count">{{ $stats['staff'] }} <span style="font-size:11px;color:var(--muted);">({{ $staffPct }}%)</span></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Visit log --}}
  <div class="table-wrap">
    <div class="table-head">
      <div>Time</div>
      <div>Shop name</div>
      <div>Owner name</div>
      <div>Role</div>
      <div>Ref source</div>
    </div>
    @forelse($visits as $v)
    <div class="table-row">
      <div class="cell-mono">{{ \Carbon\Carbon::parse($v->created_at)->format('d M · g:ia') }}</div>
      <div class="col-shop cell-name">{{ $v->shop_name ?: '—' }}</div>
      <div class="col-owner cell-name">{{ $v->owner_name ?: '—' }}</div>
      <div>
        <span class="badge badge-{{ $v->role }}">{{ $v->role }}</span>
      </div>
      <div class="col-ref cell-muted">{{ $v->ref ?: '—' }}</div>
    </div>
    @empty
    <div class="empty">No demo visits recorded yet. They will appear here after the first form submission.</div>
    @endforelse
  </div>
</div>
</body>
</html>
