<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="alternate icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="192x192" href="/icons/icon-192.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Stoka') — {{ shop_name() }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=DM+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* ── Reset ────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* ── Tokens ───────────────────────────────────────────── */
        :root {
            --parchment:  #FAF7F2;
            --surface:    #F2EDE6;
            --border:     #E8E0D6;
            --espresso:   #1C1814;
            --mid:        #4A3728;
            --muted:      #8C7B6E;
            --terracotta: #C17F4A;
            --forest:     #4A6741;
            --clay:       #B85C38;
            --dark-wood:  #2C1F14;
            --sidebar-w:  240px;

            --radius-sm:      6px;
            --radius-md:      10px;
            --radius-default: 14px;
            --radius-lg:      20px;
            --radius-full:    999px;
        }

        /* ── Base ─────────────────────────────────────────────── */
        body {
            font-family: "Plus Jakarta Sans", sans-serif;
            background: var(--parchment);
            color: var(--espresso);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }
        body.threshold-enter::before {
            content: '';
            position: fixed;
            inset: 0;
            background: #1C1814;
            opacity: 1;
            z-index: 9999;
            pointer-events: none;
            animation: dawnIn 0.7s cubic-bezier(0.4, 0, 0.2, 1) 0.05s forwards;
        }
        @keyframes dawnIn {
            0%   { opacity: 1; }
            100% { opacity: 0; visibility: hidden; }
        }
            100% { opacity: 0; visibility: hidden; }
        }

        a { color: inherit; text-decoration: none; }
        .nav-link, .nav-link * { text-decoration: none !important; }

        /* ── Shell ────────────────────────────────────────────── */
        .shell {
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ──────────────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            flex-shrink: 0;
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            height: 100dvh;
            z-index: 100;
            transition: transform 0.22s ease;
            overflow: hidden;
        }

        /* Sidebar — header */
        .sidebar-header {
            padding: 26px 22px 18px;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }

        .sidebar-logo {
            font-family: "Cormorant Garamond", serif;
            font-size: 27px;
            font-weight: 600;
            color: var(--espresso);
            letter-spacing: 0.02em;
            display: block;
            line-height: 1.15;
            margin-bottom: 7px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-logo.name-medium { font-size: 21px; }
        .sidebar-logo.name-long   { font-size: 16px; letter-spacing: 0; }

        .sidebar-shop {
            font-size: 11px;
            font-weight: 600;
            color: var(--terracotta);
            text-transform: uppercase;
            letter-spacing: 0.09em;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Sidebar — nav */
        .sidebar-nav {
            flex: 1;
            padding: 14px 10px;
            overflow-y: auto;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 14px 12px 5px;
            display: block;
        }

        .nav-section-label:first-child {
            padding-top: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 9px 12px;
            border-radius: var(--radius-md);
            font-size: 13.5px;
            font-weight: 400;
            color: var(--mid);
            transition: background 0.13s, color 0.13s, font-weight 0.1s;
            margin-bottom: 1px;
        }

        .nav-link:hover {
            background: #EBE3D8;
            color: var(--espresso);
        }

        .nav-link.active {
            background: var(--terracotta);
            color: #fff;
            font-weight: 500;
        }

        /* Attention signal — fractionally bolder, no badge, no colour */
        .nav-link.nav-attention {
            font-weight: 500;
            color: var(--espresso);
        }

        .nav-link.active .nav-icon { opacity: 1; }

        .nav-icon {
            width: 15px;
            height: 15px;
            flex-shrink: 0;
            opacity: 0.65;
        }

        .nav-link.active .nav-icon { opacity: 1; }
        .nav-link.nav-attention .nav-icon { opacity: 0.85; }

        /* Sidebar — footer */
        .sidebar-footer {
            flex-shrink: 0;
            padding: 14px 18px 18px;
            border-top: 1px solid var(--border);
        }

        .user-block {
            margin-bottom: 10px;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--espresso);
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 11px;
            color: var(--muted);
            text-transform: capitalize;
            display: block;
            margin-top: 2px;
        }

        .logout-btn {
            width: 100%;
            padding: 8px 12px;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 12.5px;
            font-weight: 500;
            color: var(--muted);
            cursor: pointer;
            text-align: left;
            transition: background 0.13s, color 0.13s, border-color 0.13s;
        }

        .logout-btn:hover {
            background: var(--clay);
            border-color: var(--clay);
            color: #fff;
        }

        /* ── Main ─────────────────────────────────────────────── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
        }

        /* ── Topbar (mobile only) ─────────────────────────────── */
        .topbar {
            display: none;
            align-items: center;
            justify-content: space-between;
            padding: 13px 20px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 50;
            flex-shrink: 0;
        }

        .topbar-logo {
            font-family: "Cormorant Garamond", serif;
            font-size: 23px;
            font-weight: 600;
            color: var(--espresso);
        }

        .topbar-shop {
            font-size: 11px;
            color: var(--terracotta);
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .hamburger {
            width: 40px;
            height: 40px;
            background: var(--parchment);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 5px;
            padding: 0;
            flex-shrink: 0;
            transition: background 0.15s, border-color 0.15s;
            -webkit-tap-highlight-color: transparent;
        }
        .hamburger:active { background: #EBE3D8; }
        .ham-line {
            width: 18px;
            height: 1.75px;
            background: var(--espresso);
            border-radius: 2px;
            transition: transform 0.22s ease, opacity 0.18s ease;
            transform-origin: center;
        }
        .hamburger.active { background: #EBE3D8; border-color: #D5CEC5; }
        .hamburger.active .ham-line:nth-child(1) { transform: translateY(6.75px) rotate(45deg); }
        .hamburger.active .ham-line:nth-child(2) { opacity: 0; transform: scaleX(0); }
        .hamburger.active .ham-line:nth-child(3) { transform: translateY(-6.75px) rotate(-45deg); }

        /* ── Page header ──────────────────────────────────────── */
        .page-header {
            padding: 32px 36px 20px;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }

        .page-title {
            font-family: "Cormorant Garamond", serif;
            font-size: 30px;
            font-weight: 600;
            color: var(--espresso);
            line-height: 1.1;
        }

        .page-subtitle {
            font-size: 13px;
            color: var(--muted);
            margin-top: 5px;
        }

        /* ── Content ──────────────────────────────────────────── */
        .content {
            padding: 32px 36px 56px;
            flex: 1;
        }

        /* ── Overlay (mobile) ─────────────────────────────────── */
        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(28, 24, 20, 0.45);
            z-index: 90;
            backdrop-filter: blur(1px);
        }

        /* ── Mobile ───────────────────────────────────────────── */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .topbar {
                display: flex;
            }

            .main {
                margin-left: 0;
            }

            .page-header {
                padding: 22px 20px 16px;
            }

            .content {
                padding: 22px 20px 56px;
            }

            .overlay.visible {
                display: block;
            }
        }

        /* ── Utility classes ──────────────────────────────────── */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: var(--radius-full);
            font-size: 11px;
            font-weight: 600;
        }

        .badge-green  { background: #DFF0DD; color: var(--forest); }
        .badge-clay   { background: #F5DDD8; color: var(--clay); }
        .badge-tan    { background: #EEE4D5; color: var(--muted); }
        .badge-gold   { background: #F5EADB; color: var(--terracotta); }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-default);
            padding: 24px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: var(--radius-md);
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: opacity 0.13s, background 0.13s;
            text-decoration: none;
        }

        .btn:hover { opacity: 0.88; }

        .btn-primary {
            background: var(--espresso);
            color: #fff;
        }

        .btn-secondary {
            background: var(--surface);
            color: var(--mid);
            border: 1px solid var(--border);
        }

        .btn-danger {
            background: var(--clay);
            color: #fff;
        }
    </style>
    @yield('styles')
</head>
<body>

<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<div class="shell">

    {{-- ── Sidebar ──────────────────────────────────────────────── --}}
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-header">
            @php
                $__sn = shop_name();
                $__snClass = strlen($__sn) > 25 ? 'name-long' : (strlen($__sn) > 15 ? 'name-medium' : '');
            @endphp
            <span class="sidebar-logo {{ $__snClass }}">{{ $__sn }}</span>
        </div>

        @php
            $navAlerts = session('nav_alerts', []);
        @endphp

        <nav class="sidebar-nav">

            {{-- Dashboard — always first, no section label --}}
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="nav-icon" viewBox="0 0 16 16" fill="none">
                    <rect x="1.5" y="1.5" width="5.5" height="5.5" rx="1.25" fill="currentColor"/>
                    <rect x="9"   y="1.5" width="5.5" height="5.5" rx="1.25" fill="currentColor"/>
                    <rect x="1.5" y="9"   width="5.5" height="5.5" rx="1.25" fill="currentColor"/>
                    <rect x="9"   y="9"   width="5.5" height="5.5" rx="1.25" fill="currentColor"/>
                </svg>
                Dashboard
            </a>

            {{-- ── TODAY ──────────────────────────────────────────── --}}
            <span class="nav-section-label">Today</span>

            <a href="/shifts"
               class="nav-link {{ request()->is('shifts*') ? 'active' : '' }} {{ in_array('shifts', $navAlerts) && !request()->is('shifts*') ? 'nav-attention' : '' }}">
                <svg class="nav-icon" viewBox="0 0 16 16" fill="none">
                    <circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.4"/>
                    <path d="M8 5v3.2l2.2 1.8" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Shifts
            </a>


            <a href="/credit"
               class="nav-link {{ request()->is('credit*') ? 'active' : '' }} {{ in_array('credit', $navAlerts) && !request()->is('credit*') ? 'nav-attention' : '' }}"
               >
                <svg class="nav-icon" viewBox="0 0 16 16" fill="none">
                    <rect x="1.5" y="4" width="13" height="8.5" rx="1.5" stroke="currentColor" stroke-width="1.4"/>
                    <path d="M1.5 7h13" stroke="currentColor" stroke-width="1.3"/>
                    <path d="M4.5 10h3.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                </svg>
                Deposits
            </a>

            {{-- ── STOCK ───────────────────────────────────────────── --}}
            <span class="nav-section-label">Stock</span>

            <a href="/shopping-list"
               class="nav-link {{ request()->is('shopping-list*') ? 'active' : '' }} {{ in_array('shopping-list', $navAlerts) && !request()->is('shopping-list*') ? 'nav-attention' : '' }}">
                <svg class="nav-icon" viewBox="0 0 16 16" fill="none">
                    <rect x="2" y="2" width="12" height="12" rx="1.5" stroke="currentColor" stroke-width="1.4"/>
                    <path d="M5 5.5h6M5 8h6M5 10.5h3.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                    <circle cx="3.8" cy="5.5" r="0.8" fill="currentColor"/>
                    <circle cx="3.8" cy="8"   r="0.8" fill="currentColor"/>
                    <circle cx="3.8" cy="10.5" r="0.8" fill="currentColor"/>
                </svg>
                Shopping list
            </a>

            <a href="/restocks"
               class="nav-link {{ request()->is('restocks*') ? 'active' : '' }}">
                <svg class="nav-icon" viewBox="0 0 16 16" fill="none">
                    <path d="M2.5 13.5V6l5.5-4 5.5 4v7.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M5.5 13.5V10h5v3.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                    <path d="M8 3v2M6.5 4H9.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                </svg>
                Restocks
            </a>

            <a href="/products"
               class="nav-link {{ request()->is('products*') ? 'active' : '' }}">
                <svg class="nav-icon" viewBox="0 0 16 16" fill="none">
                    <rect x="1.75" y="1.75" width="12.5" height="12.5" rx="1.5" stroke="currentColor" stroke-width="1.4"/>
                    <path d="M4.5 5.5h7M4.5 8h7M4.5 10.5h4.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                </svg>
                Products
            </a>

            {{-- ── MONEY ───────────────────────────────────────────── --}}
            <span class="nav-section-label">Money</span>

            <a href="/supplier-balances"
               class="nav-link {{ request()->is('supplier-balances*') ? 'active' : '' }} {{ in_array('supplier-pay', $navAlerts) && !request()->is('supplier-balances*') ? 'nav-attention' : '' }}">
                <svg class="nav-icon" viewBox="0 0 16 16" fill="none">
                    <path d="M8 2v12M5 5h4.5a2 2 0 0 1 0 4H5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                    <path d="M5 9h5a2 2 0 0 1 0 4H5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                </svg>
                Supplier pay
            </a>

            <a href="/suppliers"
               class="nav-link {{ request()->is('suppliers*') ? 'active' : '' }}">
                <svg class="nav-icon" viewBox="0 0 16 16" fill="none">
                    <path d="M2 12V6.5l6-4 6 4V12a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1Z" stroke="currentColor" stroke-width="1.4"/>
                    <path d="M6 13V9h4v4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Suppliers
            </a>

            {{-- ── MANAGE ──────────────────────────────────────────── --}}
            @if(session('auth_role') === 'owner')
            <span class="nav-section-label">Manage</span>

            <a href="/staff"
               class="nav-link {{ request()->is('staff*') ? 'active' : '' }}">
                <svg class="nav-icon" viewBox="0 0 16 16" fill="none">
                    <circle cx="6" cy="4.5" r="2.2" stroke="currentColor" stroke-width="1.4"/>
                    <path d="M1.5 13.5c0-2.49 2.02-4.5 4.5-4.5s4.5 2.01 4.5 4.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                    <path d="M10.5 7l1.5 1.5L15 5.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Staff
            </a>

            <a href="/reports"
               class="nav-link {{ request()->is('reports*') ? 'active' : '' }}">
                <svg class="nav-icon" viewBox="0 0 16 16" fill="none">
                    <path d="M3 2h10v12H3z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                    <path d="M5.5 5.5h5M5.5 8h5M5.5 10.5h3" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                    <path d="M11 8v4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                    <path d="M9.5 9.5L11 8l1.5 1.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Reports
            </a>

            <a href="/settings"
               class="nav-link {{ request()->is('settings*') ? 'active' : '' }}">
                <svg class="nav-icon" viewBox="0 0 16 16" fill="none">
                    <circle cx="8" cy="8" r="2" stroke="currentColor" stroke-width="1.3"/>
                    <path d="M8 1.5v1M8 13.5v1M1.5 8h1M13.5 8h1M3.4 3.4l.7.7M11.9 11.9l.7.7M3.4 12.6l.7-.7M11.9 4.1l.7-.7"
                          stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                </svg>
                Settings
            </a>
            @endif

        </nav>

        <div class="sidebar-footer">
            <a href="https://tempforest.com" target="_blank" style="display:block;font-size:10px;color:var(--muted);letter-spacing:0.06em;margin-bottom:12px;opacity:0.55;text-decoration:none;transition:opacity 0.15s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.55'">Powered by Stoka</a>
            <a href="https://tempforest.com" target="_blank" style="display:block;font-size:10px;color:var(--muted);letter-spacing:0.06em;margin-bottom:12px;opacity:0.6;text-decoration:none;transition:opacity 0.15s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.6'">Powered by Stoka</a>
            <div class="user-block">
                <span class="user-name">{{ session('auth_name') }}</span>
                <span class="user-role">{{ session('auth_role') }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Log out</button>
            </form>
        </div>

    </aside>

    {{-- ── Main ──────────────────────────────────────────────────── --}}
    <div class="main">

        {{-- Mobile topbar --}}
        <div class="topbar">
            <div>
                <div class="topbar-logo">{{ shop_name() }}</div>
            </div>
            <button class="hamburger" id="ham-btn" onclick="toggleSidebar()" aria-label="Toggle menu">
                <span class="ham-line"></span>
                <span class="ham-line"></span>
                <span class="ham-line"></span>
            </button>
        </div>

        {{-- Page header (optional) --}}
        @hasSection('header')
        <div class="page-header">
            @yield('header')
        </div>
        @endif

        {{-- Content --}}
        <div class="content">
            @yield('content')
        </div>

    </div>

</div>

<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('overlay').classList.add('visible');
        document.getElementById('ham-btn').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('overlay').classList.remove('visible');
        document.getElementById('ham-btn').classList.remove('active');
        document.body.style.overflow = '';
    }
    function toggleSidebar() {
        var open = document.getElementById('sidebar').classList.contains('open');
        open ? closeSidebar() : openSidebar();
    }
    document.querySelectorAll('.nav-link').forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth < 768) { closeSidebar(); }
        });
    });
</script>


@yield('scripts')

<script>
(function(){
    @if(session('threshold_enter'))
    sessionStorage.setItem('stoka_threshold','1');
    @endif
    if(sessionStorage.getItem('stoka_threshold')){
        sessionStorage.removeItem('stoka_threshold');
        document.body.classList.add('threshold-enter');
    }
    window.addEventListener('pageshow',function(e){
        if(e.persisted){ document.body.classList.remove('threshold-enter'); }
    });
})();
</script>

</body>
</html>
