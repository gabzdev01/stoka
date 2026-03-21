<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="alternate icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="192x192" href="/icons/icon-192.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#F5F0E8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Stoka')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&family=DM+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:          #F5F0E8;
            --surface:     #EDE6DA;
            --surface-2:   #E4DCD0;
            --border:      #D9CEBC;
            --espresso:    #1C1814;
            --mid:         #4A3728;
            --muted:       #7A6A5C;
            --terracotta:  #C17F4A;
            --terracotta-d:#A86A38;
            --forest:      #4A6741;
            --clay:        #B85C38;
            --dark-wood:   #2C1F14;
            --darker-wood: #1A120B;

            --top-h:  calc(106px + env(safe-area-inset-top, 0px));
            --tab-h:  64px;

            --radius-sm:      6px;
            --radius-md:      10px;
            --radius-default: 14px;
            --radius-lg:      20px;
            --radius-full:    999px;
        }

        html { height: 100%; }

        body {
            font-family: "Plus Jakarta Sans", sans-serif;
            background: var(--bg);
            color: var(--espresso);
            height: 100%;
            min-height: 100dvh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -webkit-tap-highlight-color: transparent;
        }
            100% { opacity: 0; visibility: hidden; }
        }
        /* Espresso-to-parchment transition */
        body::before {
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

        a { color: inherit; text-decoration: none; }

        /* ── TOP BAR ────────────────────────────────── */
        .top-bar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 200;
            overflow: hidden;
            background: #FAF7F2;
            padding: 0 16px;
            padding-top: env(safe-area-inset-top, 0px);
            border-bottom: 1px solid rgba(28,24,20,0.08);
        }
        @media (min-width: 1024px) { .top-bar { padding-left: 24px; padding-right: 24px; } }

        .top-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 50px;
        }

        .top-shop {
            font-family: "Cormorant Garamond", serif;
            font-size: 21px;
            font-weight: 600;
            color: #1C1814;
            letter-spacing: 0.01em;
            flex: 1;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .top-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .top-user {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 500;
            color: rgba(28,24,20,0.4);
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        @media (max-width: 479px) { .top-user { display: none; } }

        .top-user-dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            background: rgba(28,24,20,0.25);
            flex-shrink: 0;
        }

        /* Shift badge */
        .shift-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 500;
            color: #4A6741;
            background: rgba(74,103,65,0.1);
            border: 1px solid rgba(74,103,65,0.18);
            border-radius: var(--radius-full);
            padding: 3px 9px 3px 7px;
        }
        .shift-badge-dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            background: #6AB65A;
            animation: pulse 2s infinite;
        }
        .shift-badge.closed {
            color: rgba(28,24,20,0.35);
            background: rgba(28,24,20,0.05);
            border-color: rgba(28,24,20,0.1);
        }
        .shift-badge.closed .shift-badge-dot {
            background: rgba(28,24,20,0.25);
            animation: none;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.4; }
        }

        /* Cart button + badge */
        .cart-btn {
            position: relative;
            background: none;
            border: none;
            color: rgba(28,24,20,0.35);
            cursor: pointer;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-md);
            -webkit-tap-highlight-color: transparent;
            transition: color 0.15s;
        }
        .cart-btn:active { opacity: 0.7; }
        .cart-btn.has-items { color: rgba(28,24,20,0.8); }

        .cart-badge {
            position: absolute;
            top: 0; right: 0;
            background: var(--terracotta);
            color: #fff;
            font-family: "DM Mono", monospace;
            font-size: 9px;
            font-weight: 500;
            min-width: 16px;
            height: 16px;
            border-radius: var(--radius-full);
            padding: 0 3px;
            display: none;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        /* Search bar */
        .top-search { padding-bottom: 12px; }

        .search-wrap { position: relative; }

        .search-icon {
            position: absolute;
            left: 13px; top: 50%;
            transform: translateY(-50%);
            color: rgba(28,24,20,0.35);
            pointer-events: none;
            line-height: 0;
        }

        .search-input {
            width: 100%;
            height: 46px;
            background: rgba(28,24,20,0.05);
            border: 1.5px solid rgba(28,24,20,0.1);
            border-radius: var(--radius-md);
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 15px;
            font-weight: 400;
            color: #1C1814;
            padding: 0 16px 0 44px;
            outline: none;
            transition: border-color 0.18s, background 0.18s;
            -webkit-appearance: none;
        }
        .search-input::placeholder { color: rgba(28,24,20,0.35); font-weight: 400; }
        .search-input:focus { border-color: rgba(28,24,20,0.28); background: rgba(28,24,20,0.07); }

        /* ── SCROLLABLE CONTENT ─────────────────────── */
        .staff-content {
            padding-top: var(--top-h);
            padding-bottom: calc(var(--tab-h) + env(safe-area-inset-bottom, 12px));
            min-height: 100dvh;
        }

        .content-inner { padding: 20px 16px 8px; }
        @media (min-width: 1024px) { .content-inner { padding: 20px 24px 8px; } }

        /* ── BOTTOM TAB BAR (3 tabs) ────────────────── */
        .tab-bar {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            z-index: 200;
            height: calc(var(--tab-h) + env(safe-area-inset-bottom, 0px));
            background: var(--darker-wood);
            border-top: 1px solid rgba(255,255,255,0.07);
            display: flex;
            align-items: flex-start;
            padding-top: 6px;
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }

        .tab-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            min-height: 48px;
            padding: 4px 2px;
            cursor: pointer;
            border: none;
            background: none;
            text-decoration: none;
            color: rgba(255,255,255,0.35);
            transition: color 0.15s;
            -webkit-tap-highlight-color: transparent;
        }
        .tab-item:active { opacity: 0.7; }
        .tab-item.active { color: var(--terracotta); }

        .tab-icon-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .tab-item.active .tab-icon-wrap::before {
            content: '';
            display: block;
            width: 3px; height: 3px;
            background: var(--terracotta);
            border-radius: 50%;
            margin: 0 auto 4px;
        }

        .tab-icon { width: 22px; height: 22px; flex-shrink: 0; line-height: 0; }

        .tab-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.03em;
            white-space: nowrap;
        }

        /* ── Flash messages ─────────────────────────── */
        .flash {
            margin: 0 16px 12px;
            padding: 12px 16px;
            border-radius: var(--radius-md);
            font-size: 13.5px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .flash-success {
            background: rgba(74,103,65,0.15);
            border: 1px solid rgba(74,103,65,0.28);
            color: #3D6B34;
        }
        .flash-error {
            background: rgba(184,92,56,0.12);
            border: 1px solid rgba(184,92,56,0.25);
            color: var(--clay);
        }

        .mono { font-family: "DM Mono", monospace; }

        /* ── Floating cart bar ──────────────────────────── */
        #cart-float {
            position: fixed;
            bottom: calc(var(--tab-h) + env(safe-area-inset-bottom, 0px) + 8px);
            left: 16px; right: 16px;
            z-index: 80;
        }
        #cart-float.visible { animation: cfSlideUp 0.25s ease; }
        @keyframes cfSlideUp {
            from { transform: translateY(20px); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }
        .cart-float-inner {
            background: #1C1814;
            border-radius: var(--radius-default);
            padding: 13px 14px 13px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 24px rgba(28,24,20,0.4), 0 1px 4px rgba(28,24,20,0.25);
            border: 1px solid rgba(255,255,255,0.06);
        }
        .cart-float-count {
            color: #FAF7F2;
            font-size: 14px;
            font-weight: 600;
            display: flex; align-items: center; gap: 6px;
            flex: 1;
            min-width: 0;
        }
        .cart-float-total {
            font-family: "DM Mono", monospace;
            color: #C17F4A;
            font-size: 15px;
            font-weight: 500;
            white-space: nowrap;
        }
        .cart-float-btn {
            background: rgba(193,127,74,0.18);
            color: #C17F4A;
            border: 1px solid rgba(193,127,74,0.3);
            border-radius: var(--radius-md);
            padding: 8px 14px;
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            -webkit-tap-highlight-color: transparent;
            transition: background 0.12s;
        }
        .cart-float-btn:active { background: rgba(193,127,74,0.28); }

    </style>
    @yield('styles')
    @yield('head')
</head>
<body>
<div id="page-curtain" style="position:fixed;inset:0;background:#1C1814;opacity:1;z-index:9998;pointer-events:none;transition:opacity 0.55s ease;"></div>

{{-- ── TOP BAR ──────────────────────────────────── --}}
<header class="top-bar">
    <div class="top-info">
        <a href="{{ route('sales.index') }}" class="top-shop" style="text-decoration:none;">{{ shop_name() }}</a>

        <div class="top-right">
            @if(session('shift_id'))
                <span class="shift-badge">
                    <span class="shift-badge-dot"></span>
                    Shift open
                </span>
            @else
                <span class="shift-badge closed">
                    <span class="shift-badge-dot"></span>
                    No shift
                </span>
            @endif

            {{-- Cart icon --}}
            <button class="cart-btn" id="cart-btn"
                    onclick="window.openCartSheet ? window.openCartSheet() : (window.location = '{{ route('sales.index') }}')"
                    aria-label="Cart">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M6.5 7.5V5.5a3.5 3.5 0 0 1 7 0v2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    <rect x="2" y="7.5" width="16" height="10" rx="2" stroke="currentColor" stroke-width="1.6"/>
                    <circle cx="7.5" cy="13" r="1" fill="currentColor"/>
                    <circle cx="12.5" cy="13" r="1" fill="currentColor"/>
                </svg>
                <span class="cart-badge" id="cart-badge"></span>
            </button>

            <a href="{{ route('profile.index') }}" class="top-user" style="text-decoration:none;">
                <span class="top-user-dot"></span>
                {{ session('auth_name') }}
            </a>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;display:flex;">
                @csrf
                <button type="submit" class="top-logout" title="Log out">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
    @unless(View::hasSection('no-search'))
    <div class="top-search">
        <div class="search-wrap">
            <span class="search-icon">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <circle cx="7.8" cy="7.8" r="5.3" stroke="currentColor" stroke-width="1.6"/>
                    <path d="M11.8 11.8L15.5 15.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                </svg>
            </span>
            <input
                class="search-input"
                type="search"
                id="staff-search"
                placeholder="Search products…"
                autocomplete="off"
                autocorrect="off"
                spellcheck="false"
                inputmode="search"
                @yield('search-attrs')
            >
        </div>
    </div>
    @endunless
</header>

{{-- ── MAIN CONTENT ─────────────────────────────── --}}
<main class="staff-content">
    @yield('above-content')
    <div class="content-inner">

        @if(session('success'))
            <div class="flash flash-success">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" style="flex-shrink:0">
                    <circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.4"/>
                    <path d="M5 8l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="flash flash-error">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" style="flex-shrink:0">
                    <circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.4"/>
                    <path d="M8 5v3.5M8 10.5v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')

    </div>
</main>

{{-- ── FLOATING CART BAR ─────────────────────────── --}}
<div id="cart-float" style="display:none">
    <div class="cart-float-inner">
        <span class="cart-float-count">
            <svg width="16" height="16" viewBox="0 0 20 20" fill="none" style="flex-shrink:0">
                <path d="M6.5 7.5V5.5a3.5 3.5 0 0 1 7 0v2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                <rect x="2" y="7.5" width="16" height="10" rx="2" stroke="currentColor" stroke-width="1.6"/>
            </svg>
            <span id="cf-count">0</span> items
        </span>
        <span class="cart-float-total" id="cf-total">{{ tenant('currency_symbol') }} 0</span>
        <button class="cart-float-btn" onclick="window.openCartSheet && window.openCartSheet()">View cart &rarr;</button>
    </div>
</div>

{{-- ── BOTTOM TAB BAR (3 tabs) ──────────────────── --}}
<nav class="tab-bar">

    {{-- Sell --}}
    <a href="{{ route('sales.index') }}"
       class="tab-item {{ request()->routeIs('sales.index') ? 'active' : '' }}">
        <div class="tab-icon-wrap">
            <span class="tab-icon" style="position:relative;display:inline-block;">
                <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <path d="M7.5 9V6.5a3.5 3.5 0 0 1 7 0V9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    <rect x="3" y="9" width="16" height="10.5" rx="2" stroke="currentColor" stroke-width="1.6"/>
                    <circle cx="8.5" cy="14.5" r="1.1" fill="currentColor"/>
                    <circle cx="13.5" cy="14.5" r="1.1" fill="currentColor"/>
                </svg>
                <span id="tab-cart-badge" style="display:none;position:absolute;top:-4px;right:-6px;background:#C17F4A;color:white;border-radius:var(--radius-full);font-size:10px;font-weight:700;padding:1px 5px;font-family:'Plus Jakarta Sans',sans-serif;line-height:1.4;"></span>
            </span>
        </div>
        <span class="tab-label">Sell</span>
    </a>

    {{-- My Shift --}}
    <a href="{{ route('sales.shift') }}"
       class="tab-item {{ request()->routeIs('sales.shift') ? 'active' : '' }}">
        <div class="tab-icon-wrap">
            <span class="tab-icon">
                <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="1.6"/>
                    <path d="M11 7v4.5l3 2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
        </div>
        <span class="tab-label">My Shift</span>
    </a>

    {{-- Deposits --}}
    <a href="{{ route('sales.deposits') }}"
       class="tab-item {{ request()->routeIs('sales.deposits') ? 'active' : '' }}">
        <div class="tab-icon-wrap">
            <span class="tab-icon">
                <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <rect x="2" y="5" width="18" height="13" rx="2" stroke="currentColor" stroke-width="1.6"/>
                    <path d="M2 9h18" stroke="currentColor" stroke-width="1.4"/>
                    <path d="M6 14h4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                    <circle cx="16" cy="14" r="1.2" fill="currentColor"/>
                </svg>
            </span>
        </div>
        <span class="tab-label">Deposits</span>
    </a>

    {{-- Close Shift --}}
    <a href="{{ route('shifts.close') }}"
       class="tab-item {{ request()->routeIs('shifts.close') ? 'active' : '' }}">
        <div class="tab-icon-wrap">
            <span class="tab-icon">
                <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <rect x="5" y="10" width="12" height="9" rx="1.8" stroke="currentColor" stroke-width="1.6"/>
                    <path d="M8 10V7a3 3 0 0 1 6 0v3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    <circle cx="11" cy="14.5" r="1.2" fill="currentColor"/>
                </svg>
            </span>
        </div>
        <span class="tab-label">Close Shift</span>
    </a>

</nav>

@yield('scripts')

{{-- Cart badge init from sessionStorage --}}
<script>
(function () {
    try {
        var cart = JSON.parse(sessionStorage.getItem('stoka_cart') || '[]');
        var count = cart.reduce(function (s, i) { return s + (i.quantity || 1); }, 0);
        var badge = document.getElementById('cart-badge');
        var btn   = document.getElementById('cart-btn');
        if (badge && count > 0) {
            badge.textContent = count;
            badge.style.display = 'flex';
            if (btn) btn.classList.add('has-items');
        }
    } catch (e) {}
}());
</script>
<script>
(function(){
  var curtain=document.getElementById('page-curtain');
  if(!curtain)return;
  requestAnimationFrame(function(){
    requestAnimationFrame(function(){
      curtain.style.opacity='0';
      setTimeout(function(){curtain.remove();},600);
    });
  });
}());
</script>
<script>
(function(){
    @if(session('threshold_enter'))
    sessionStorage.setItem('stoka_threshold','1');
    @endif
    if(sessionStorage.getItem('stoka_threshold')){
        sessionStorage.removeItem('stoka_threshold');
        document.body.classList.add('threshold-enter');
    }
})();
</script>

</body>
</html>
