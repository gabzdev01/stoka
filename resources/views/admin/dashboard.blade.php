<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Stoka</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
    --espresso: #1C1814;
    --terracotta: #C17F4A;
    --parchment: #FAF7F2;
    --muted: #8C8279;
    --border: #E8E2DA;
    --forest: #4A6741;
    --clay: #B85C38;
    --bg: #EDEAE5;
}
body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--bg);
    min-height: 100vh;
    color: var(--espresso);
}

/* ── Top bar ────────────────────────────────────────────────────────── */
.topbar {
    background: var(--espresso);
    padding: 0 24px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 10;
}
.topbar-logo {
    font-family: 'Cormorant Garamond', serif;
    font-size: 22px;
    font-weight: 600;
    color: var(--parchment);
    letter-spacing: 0.05em;
}
.topbar-logo span {
    font-size: 11px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--muted);
    margin-left: 8px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    vertical-align: middle;
}
.topbar-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}
.btn-new {
    padding: 8px 16px;
    background: var(--terracotta);
    color: white;
    border: none;
    border-radius: 8px;
    font-family: inherit;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: opacity 0.15s;
}
.btn-new:hover { opacity: 0.88; }
.btn-logout {
    font-size: 12px;
    color: var(--muted);
    text-decoration: none;
    padding: 6px 10px;
    border-radius: 6px;
    transition: background 0.12s;
}
.btn-logout:hover { background: rgba(255,255,255,0.07); color: var(--parchment); }

/* ── Main layout ────────────────────────────────────────────────────── */
.main {
    max-width: 1100px;
    margin: 0 auto;
    padding: 28px 20px 60px;
}

/* ── Stats strip ────────────────────────────────────────────────────── */
.stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 24px;
}
.stat-card {
    background: var(--parchment);
    border-radius: 14px;
    padding: 18px 20px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.stat-label {
    font-size: 11px;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.07em;
}
.stat-value {
    font-family: 'DM Mono', monospace;
    font-size: 30px;
    font-weight: 500;
    color: var(--espresso);
    line-height: 1;
}
.stat-value.active { color: var(--forest); }
.stat-value.suspended { color: var(--clay); }

/* ── Success card (after create) ────────────────────────────────────── */
.success-card {
    background: #DFF0DD;
    border-radius: 14px;
    padding: 20px 22px;
    margin-bottom: 20px;
    border-left: 4px solid var(--forest);
}
.success-card h3 {
    font-size: 14px;
    font-weight: 700;
    color: var(--forest);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 7px;
}
.success-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 6px 20px;
    margin-bottom: 14px;
}
.success-meta-row { font-size: 13px; color: var(--espresso); }
.success-meta-row strong { font-weight: 600; }
.wa-box {
    background: white;
    border-radius: 10px;
    padding: 13px 15px;
    font-size: 12px;
    line-height: 1.65;
    color: var(--espresso);
    white-space: pre-wrap;
    font-family: 'DM Mono', monospace;
    border: 1.5px solid #C6E0C2;
    margin-bottom: 12px;
}
.btn-copy {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    background: var(--forest);
    color: white;
    border: none;
    border-radius: 8px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: opacity 0.15s;
}
.btn-copy:hover { opacity: 0.85; }
.btn-copy.copied { background: #5A8750; }

/* ── Error flash ────────────────────────────────────────────────────── */
.error-flash {
    background: #F8E8E4;
    border-radius: 14px;
    padding: 14px 18px;
    margin-bottom: 20px;
    font-size: 13px;
    color: var(--clay);
    font-weight: 500;
    border-left: 4px solid var(--clay);
}

/* ── Section header ─────────────────────────────────────────────────── */
.section-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}
.section-head h2 {
    font-size: 13px;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.07em;
}

/* ── Tenant table ───────────────────────────────────────────────────── */
.tenant-table {
    background: var(--parchment);
    border-radius: 16px;
    overflow: hidden;
}
.tenant-thead {
    display: grid;
    grid-template-columns: 1fr 1fr 80px 90px 100px 80px;
    gap: 0;
    padding: 10px 20px;
    background: #EAE6DF;
    font-size: 10px;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    border-bottom: 1px solid var(--border);
}
.tenant-row {
    display: grid;
    grid-template-columns: 1fr 1fr 80px 90px 100px 80px;
    gap: 0;
    padding: 14px 20px;
    align-items: center;
    border-bottom: 1px solid var(--border);
    transition: background 0.1s;
}
.tenant-row:last-child { border-bottom: none; }
.tenant-row:hover { background: #F4F0EB; }

.tenant-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--espresso);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding-right: 8px;
}
.tenant-domain {
    font-size: 11px;
    color: var(--muted);
    font-family: 'DM Mono', monospace;
    margin-top: 2px;
}
.tenant-owner {
    font-size: 13px;
    color: var(--espresso);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding-right: 8px;
}
.tenant-owner small {
    display: block;
    font-size: 11px;
    color: var(--muted);
}
.tenant-plan {
    font-size: 11px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    display: inline-block;
}
.plan-basic { background: #EBE3D8; color: var(--muted); }
.plan-pro   { background: #F5E0D8; color: var(--clay); }

.status-badge {
    font-size: 11px;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 20px;
    display: inline-block;
}
.status-active    { background: #DFF0DD; color: var(--forest); }
.status-suspended { background: #F8E8E4; color: var(--clay); }

.tenant-date {
    font-size: 11px;
    color: var(--muted);
    font-family: 'DM Mono', monospace;
}

.toggle-form { display: inline; }
.btn-toggle {
    font-size: 11px;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 7px;
    border: 1.5px solid var(--border);
    background: white;
    cursor: pointer;
    font-family: inherit;
    color: var(--muted);
    transition: border-color 0.12s, color 0.12s;
}
.btn-toggle:hover { border-color: var(--terracotta); color: var(--terracotta); }
.btn-toggle.suspend:hover { border-color: var(--clay); color: var(--clay); }

/* ── Empty state ────────────────────────────────────────────────────── */
.empty {
    padding: 48px 20px;
    text-align: center;
    color: var(--muted);
    font-size: 14px;
}

/* ── Mobile ─────────────────────────────────────────────────────────── */
@media (max-width: 700px) {
    .stats { grid-template-columns: repeat(3, 1fr); gap: 8px; }
    .stat-card { padding: 14px 12px; }
    .stat-value { font-size: 24px; }
    .topbar { padding: 0 16px; }
    .main { padding: 16px 12px 48px; }
    .tenant-thead { display: none; }
    .tenant-row {
        grid-template-columns: 1fr auto;
        grid-template-rows: auto auto auto;
        gap: 4px 8px;
        padding: 14px 16px;
    }
    .col-name  { grid-column: 1; grid-row: 1; }
    .col-owner { grid-column: 1; grid-row: 2; }
    .col-date  { grid-column: 1; grid-row: 3; }
    .col-plan   { grid-column: 2; grid-row: 1; justify-self: end; }
    .col-status { grid-column: 2; grid-row: 2; justify-self: end; }
    .col-action { grid-column: 2; grid-row: 3; justify-self: end; }
    .success-meta { grid-template-columns: 1fr; }
    .tenant-name, .tenant-owner { font-size: 13px; }
}
</style>
</head>
<body>

{{-- ── Top bar ──────────────────────────────────────────────────────── --}}
<div class="topbar">
    <div class="topbar-logo">
        Stoka <span>Super Admin</span>
    </div>
    <div class="topbar-actions">
        <a href="{{ route('admin.tenants.create') }}" class="btn-new">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M6 1v10M1 6h10" stroke="white" stroke-width="1.8" stroke-linecap="round"/></svg>
            New Tenant
        </a>
        <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</div>

<div class="main">

    {{-- ── Stats ──────────────────────────────────────────────────────── --}}
    <div class="stats">
        <div class="stat-card">
            <div class="stat-label">Total</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Active</div>
            <div class="stat-value active">{{ $stats['active'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Suspended</div>
            <div class="stat-value suspended">{{ $stats['suspended'] }}</div>
        </div>
    </div>

    {{-- ── Error flash ─────────────────────────────────────────────────── --}}
    @if(session('error'))
    <div class="error-flash">{{ session('error') }}</div>
    @endif

    {{-- ── Created success card ────────────────────────────────────────── --}}
    @if(session('created'))
    @php $c = session('created'); @endphp
    <div class="success-card">
        <h3>
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2.5 7l3.5 3.5 5.5-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ $c['shop_name'] }} is live
        </h3>
        <div class="success-meta">
            <div class="success-meta-row"><strong>URL</strong> &nbsp;{{ $c['shop_url'] }}</div>
            <div class="success-meta-row"><strong>Owner</strong> &nbsp;{{ $c['owner_name'] }}</div>
            <div class="success-meta-row"><strong>Phone</strong> &nbsp;{{ $c['owner_phone'] }}</div>
            <div class="success-meta-row"><strong>Password</strong> &nbsp;<code>{{ $c['password'] }}</code></div>
        </div>
        <div class="wa-box" id="waMsg">{{ $c['wa_message'] }}</div>
        <button class="btn-copy" id="copyBtn" onclick="copyWA()">
            <svg width="12" height="12" viewBox="0 0 14 14" fill="none"><rect x="4" y="4" width="8" height="8" rx="2" stroke="white" stroke-width="1.4"/><path d="M3 10H2a1 1 0 01-1-1V2a1 1 0 011-1h7a1 1 0 011 1v1" stroke="white" stroke-width="1.4" stroke-linecap="round"/></svg>
            Copy WhatsApp message
        </button>
    </div>
    @endif

    {{-- ── Tenant list ─────────────────────────────────────────────────── --}}
    <div class="section-head">
        <h2>All Tenants</h2>
    </div>

    <div class="tenant-table">
        <div class="tenant-thead">
            <div>Shop</div>
            <div>Owner</div>
            <div>Plan</div>
            <div>Status</div>
            <div>Created</div>
            <div></div>
        </div>

        @forelse($tenants as $t)
        <div class="tenant-row">
            <div class="col-name">
                <div class="tenant-name">{{ $t->name }}</div>
                @if($t->domain)
                <div class="tenant-domain">{{ $t->domain }}.stoka.co.ke</div>
                @endif
            </div>
            <div class="col-owner tenant-owner">
                {{ $t->owner_name }}
                <small>{{ $t->owner_phone }}</small>
            </div>
            <div class="col-plan">
                <span class="tenant-plan plan-{{ $t->plan }}">{{ $t->plan }}</span>
            </div>
            <div class="col-status">
                <span class="status-badge status-{{ $t->status }}">{{ $t->status }}</span>
            </div>
            <div class="col-date tenant-date">{{ \Carbon\Carbon::parse($t->created_at)->format('d M Y') }}</div>
            <div class="col-action">
                @if($t->id !== 'demo')
                <form method="POST" action="{{ route('admin.tenants.toggle', $t->id) }}" class="toggle-form">
                    @csrf
                    <button type="submit" class="btn-toggle {{ $t->status === 'active' ? 'suspend' : '' }}">
                        {{ $t->status === 'active' ? 'Suspend' : 'Activate' }}
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="empty">No tenants yet. Create your first one.</div>
        @endforelse
    </div>

</div>

<script>
function copyWA() {
    var msg = document.getElementById('waMsg').innerText;
    navigator.clipboard.writeText(msg).then(function() {
        var btn = document.getElementById('copyBtn');
        btn.classList.add('copied');
        btn.innerHTML = '<svg width="12" height="12" viewBox="0 0 14 14" fill="none"><path d="M2.5 7l3.5 3.5 5.5-6" stroke="white" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg> Copied!';
        setTimeout(function() {
            btn.classList.remove('copied');
            btn.innerHTML = '<svg width="12" height="12" viewBox="0 0 14 14" fill="none"><rect x="4" y="4" width="8" height="8" rx="2" stroke="white" stroke-width="1.4"/><path d="M3 10H2a1 1 0 01-1-1V2a1 1 0 011-1h7a1 1 0 011 1v1" stroke="white" stroke-width="1.4" stroke-linecap="round"/></svg> Copy WhatsApp message';
        }, 2500);
    });
}
</script>

</body>
</html>
