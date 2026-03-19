<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#1A120B">
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

            --top-h:  calc(108px + env(safe-area-inset-top, 0px));
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

        a { color: inherit; text-decoration: none; }

        /* ── TOP BAR ────────────────────────────────── */
        .top-bar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 200;
            overflow: hidden;
            background: var(--darker-wood);
            padding: 0 16px;
            padding-top: env(safe-area-inset-top, 0px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        @media (min-width: 1024px) { .top-bar { padding-left: 24px; padding-right: 24px; } }

        .top-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 44px;
        }

        .top-shop {
            font-family: "Cormorant Garamond", serif;
            font-size: 17px;
            font-weight: 600;
            color: rgba(255,255,255,0.92);
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
            font-weight: 600;
            color: rgba(255,255,255,0.45);
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        @media (max-width: 479px) { .top-user { display: none; } }

        .top-user-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--terracotta);
            flex-shrink: 0;
        }

        /* Shift badge */
        .shift-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 600;
            color: var(--forest);
            background: rgba(74,103,65,0.18);
            border: 1px solid rgba(74,103,65,0.3);
            border-radius: var(--radius-full);
            padding: 2px 8px 2px 6px;
        }
        .shift-badge-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #6AB65A;
            animation: pulse 2s infinite;
        }
        .shift-badge.closed {
            color: var(--muted);
            background: rgba(122,106,92,0.15);
            border-color: rgba(122,106,92,0.2);
        }
        .shift-badge.closed .shift-badge-dot {
            background: var(--muted);
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
            color: rgba(255,255,255,0.42);
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
        .cart-btn.has-items { color: rgba(255,255,255,0.82); }

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
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.38);
            pointer-events: none;
            line-height: 0;
        }

        .search-input {
            width: 100%;
            height: 52px;
            background: rgba(255,255,255,0.09);
            border: 1.5px solid rgba(255,255,255,0.12);
            border-radius: var(--radius-md);
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 16px;
            font-weight: 500;
            color: rgba(255,255,255,0.9);
            padding: 0 16px 0 46px;
            outline: none;
            transition: border-color 0.15s, background 0.15s;
            -webkit-appearance: none;
        }
        .search-input::placeholder { color: rgba(255,255,255,0.35); font-weight: 400; }
        .search-input:focus { border-color: var(--terracotta); background: rgba(255,255,255,0.13); }

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
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 20px rgba(28,24,20,0.35);
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
            background: #C17F4A;
            color: white;
            border: none;
            border-radius: var(--radius-md);
            padding: 8px 14px;
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            -webkit-tap-highlight-color: transparent;
        }

    </style>
    @yield('styles')
    @yield('head')
</head>
<body>

{{-- ── TOP BAR ──────────────────────────────────── --}}
<header class="top-bar">
    <div class="top-info">
        <span class="top-shop">{{ tenant('name') }}</span>

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
        </div>
    </div>
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

</body>
</html>
