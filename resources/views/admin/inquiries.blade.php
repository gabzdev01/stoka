<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inquiries — Stoka Admin</title>
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
.page-header{margin-bottom:24px;}
.page-title{font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:500;color:var(--espresso);}
.page-sub{font-size:13px;color:var(--muted);margin-top:4px;}
.stats-row{display:flex;gap:10px;margin-bottom:24px;flex-wrap:wrap;}
.scard{background:var(--parchment);border:1px solid var(--border);border-radius:10px;padding:14px 18px;min-width:120px;}
.scard-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:4px;}
.scard-value{font-family:'DM Mono',monospace;font-size:24px;font-weight:500;color:var(--espresso);}
.scard-value.c-forest{color:var(--forest);}
.scard-value.c-terra{color:var(--terracotta);}
.table-wrap{background:var(--parchment);border-radius:14px;overflow:hidden;border:1px solid var(--border);}
.table-head{display:grid;grid-template-columns:1fr 1fr 130px 90px 100px 80px 44px;padding:9px 16px;background:#EAE6DF;font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.07em;border-bottom:1px solid var(--border);}
.table-row{display:grid;grid-template-columns:1fr 1fr 130px 90px 100px 80px 44px;padding:13px 16px;align-items:center;border-bottom:1px solid var(--border);transition:background 0.1s;}
.table-row:last-child{border-bottom:none;}
.table-row:hover{background:#F4F0EB;}
@media(max-width:760px){
  .table-head{display:none;}
  .table-row{grid-template-columns:1fr auto;grid-template-rows:auto auto;gap:3px 8px;padding:12px 14px;}
  .col-shop,.col-city,.col-date{grid-column:1;}
  .col-status{grid-column:2;grid-row:1;}
  .col-wa{grid-column:2;grid-row:2;}
  .col-phone{display:none;}
}
.cell-name{font-size:13px;font-weight:600;color:var(--espresso);}
.cell-sub{font-size:11px;color:var(--muted);margin-top:1px;}
.cell-mono{font-family:'DM Mono',monospace;font-size:12px;color:var(--espresso);}
.cell-muted{font-size:12px;color:var(--muted);}
.badge{font-size:10px;font-weight:700;padding:3px 9px;border-radius:20px;display:inline-block;text-transform:uppercase;letter-spacing:0.04em;}
.badge-converted{background:#DFF0DD;color:var(--forest);}
.badge-pending{background:#FEF3E4;color:var(--terracotta);}
.abtn{width:28px;height:28px;border-radius:6px;border:1px solid var(--border);background:white;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:border-color 0.15s;text-decoration:none;color:var(--muted);}
.abtn:hover{border-color:#25D366;color:#25D366;}
.abtn svg{width:13px;height:13px;}
.empty{padding:48px;text-align:center;color:var(--muted);font-size:14px;}
</style>
</head>
<body>
<nav class="nav">
  <div class="nav-logo">stoka <span class="nav-logo-tag">Admin</span></div>
  <div class="nav-links">
    <a href="/admin" class="nav-link">Tenants</a>
    <a href="/admin/inquiries" class="nav-link active">Inquiries</a>
    <a href="/admin/demo-visits" class="nav-link">Demo Visits</a>
  </div>
  <div>
    <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">
      @csrf
      <button type="submit" class="btn-logout nav-link">Logout</button>
    </form>
  </div>
</nav>

<div class="main">
  <div class="page-header">
    <h1 class="page-title">Registration Inquiries</h1>
    <p class="page-sub">Everyone who filled the registration form. Converted = a tenant account exists for that phone number.</p>
  </div>

  <div class="stats-row">
    <div class="scard"><div class="scard-label">Total</div><div class="scard-value">{{ $stats['total'] }}</div></div>
    <div class="scard"><div class="scard-label">Converted</div><div class="scard-value c-forest">{{ $stats['converted'] }}</div></div>
    <div class="scard"><div class="scard-label">Pending</div><div class="scard-value c-terra">{{ $stats['pending'] }}</div></div>
  </div>

  <div class="table-wrap">
    <div class="table-head">
      <div>Name</div>
      <div>Shop</div>
      <div>Phone</div>
      <div>City</div>
      <div>Date</div>
      <div>Status</div>
      <div></div>
    </div>
    @forelse($inquiries as $inq)
    <div class="table-row">
      <div class="col-name">
        <div class="cell-name">{{ $inq->owner_name }}</div>
        @if($inq->email)<div class="cell-sub">{{ $inq->email }}</div>@endif
      </div>
      <div class="col-shop cell-name">{{ $inq->shop_name }}</div>
      <div class="col-phone cell-mono">{{ $inq->phone }}</div>
      <div class="col-city cell-muted">{{ $inq->city ?: '—' }}</div>
      <div class="col-date cell-muted">{{ \Carbon\Carbon::parse($inq->created_at)->format('d M Y') }}</div>
      <div class="col-status">
        <span class="badge {{ $inq->converted ? 'badge-converted' : 'badge-pending' }}">
          {{ $inq->converted ? 'Converted' : 'Pending' }}
        </span>
      </div>
      <div class="col-wa">
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $inq->phone) }}?text={{ urlencode('Hi ' . $inq->owner_name . ', you expressed interest in Stoka for ' . $inq->shop_name . '. I\'d love to get you set up — takes about 15 minutes. When\'s a good time?') }}"
           target="_blank" class="abtn" title="WhatsApp follow-up">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
        </a>
      </div>
    </div>
    @empty
    <div class="empty">No inquiries yet.</div>
    @endforelse
  </div>
</div>
</body>
</html>
