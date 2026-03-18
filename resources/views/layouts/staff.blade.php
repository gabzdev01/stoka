<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#1A120B">
    <title>@yield('title', 'Stoka')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&family=DM+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ── Reset ─────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* ── Tokens ─────────────────────────────────────── */
        :root {
            --bg:          #F5F0E8;       /* slightly warmer/richer than owner parchment */
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
            --darker-wood: #1A120B;       /* tab bar bg */

            /* fixed heights */
            --top-h:       108px;         /* info bar + search */
            --tab-h:       64px;
        }

        /* ── Base ───────────────────────────────────────── */
        html { height: 100%; }

        body {
            font-family: "Plus Jakarta Sans", sans-serif;
            background: var(--bg);
            color: var(--espresso);
            height: 100%;
            min-height: 100vh;
            min-height: 100dvh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -webkit-tap-highlight-color: transparent;
        }

        a { color: inherit; text-decoration: none; }

        /* ── TOP HEADER (fixed) ─────────────────────────── */
        /*
         *  ┌──────────────────────────────────┐
         *  │ Zuri Boutique          James  ▸  │  ← info strip
         *  │ ┌────────────────────────────┐   │
         *  │ │  🔍  Search products…      │   │  ← search
         *  │ └────────────────────────────┘   │
         *  └──────────────────────────────────┘
         */
        .top-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 200;
            background: var(--darker-wood);
            padding: 0 16px;
            padding-top: env(safe-area-inset-top, 0px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        /* Info strip */
        .top-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 40px;
        }

        .top-shop {
            font-family: "Cormorant Garamond", serif;
            font-size: 17px;
            font-weight: 600;
            color: rgba(255,255,255,0.92);
            letter-spacing: 0.01em;
            line-height: 1;
        }

        .top-user {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255,255,255,0.55);
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .top-user-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--terracotta);
            flex-shrink: 0;
        }

        /* Search bar */
        .top-search {
            padding-bottom: 12px;
        }

        .search-wrap {
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
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
            border-radius: 12px;
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 16px;          /* ≥16px prevents iOS zoom on focus */
            font-weight: 500;
            color: rgba(255,255,255,0.9);
            padding: 0 16px 0 46px;
            outline: none;
            transition: border-color 0.15s, background 0.15s;
            -webkit-appearance: none;
        }

        .search-input::placeholder {
            color: rgba(255,255,255,0.35);
            font-weight: 400;
        }

        .search-input:focus {
            border-color: var(--terracotta);
            background: rgba(255,255,255,0.13);
        }

        /* ── CONTENT (scrollable middle) ────────────────── */
        .staff-content {
            /* sits between top bar and tab bar */
            padding-top: var(--top-h);
            padding-bottom: calc(var(--tab-h) + env(safe-area-inset-bottom, 12px));
            min-height: 100dvh;
        }

        .content-inner {
            padding: 20px 16px 8px;
        }

        /* ── BOTTOM TAB BAR (fixed) ─────────────────────── */
        /*
         *  ┌────────┬────────┬────────┬────────┐
         *  │  grid  │  cart  │ clock  │  lock  │
         *  │Products│ Active │History │ Close  │
         *  └────────┴────────┴────────┴────────┘
         */
        .tab-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
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
            color: rgba(255,255,255,0.38);
            transition: color 0.15s;
            -webkit-tap-highlight-color: transparent;
        }

        .tab-item:active {
            opacity: 0.7;
        }

        .tab-item.active {
            color: var(--terracotta);
        }

        .tab-icon {
            width: 22px;
            height: 22px;
            flex-shrink: 0;
            line-height: 0;
        }

        .tab-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.03em;
            white-space: nowrap;
        }

        /* Active tab — subtle glow dot above icon */
        .tab-item.active .tab-icon-wrap::before {
            content: '';
            display: block;
            width: 3px;
            height: 3px;
            background: var(--terracotta);
            border-radius: 50%;
            margin: 0 auto 4px;
        }

        .tab-icon-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ── Shift status pill (in top bar, optional) ───── */
        .shift-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 600;
            color: var(--forest);
            background: rgba(74,103,65,0.18);
            border: 1px solid rgba(74,103,65,0.3);
            border-radius: 100px;
            padding: 2px 8px 2px 6px;
            letter-spacing: 0.02em;
        }

        .shift-badge-dot {
            width: 6px;
            height: 6px;
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

        /* ── Flash messages ─────────────────────────────── */
        .flash {
            margin: 0 16px 12px;
            padding: 12px 16px;
            border-radius: 10px;
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

        /* ── Utility ─────────────────────────────────────── */
        .mono {
            font-family: "DM Mono", monospace;
        }

    </style>
    @yield('styles')
    @yield('head')
</head>
<body>

{{-- ── TOP BAR ───────────────────────────────────────── --}}
<header class="top-bar">
    <div class="top-info">
        <span class="top-shop">{{ tenant('name') }}</span>
        <div style="display:flex;align-items:center;gap:10px;">
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
            <span class="top-user">
                <span class="top-user-dot"></span>
                {{ session('auth_name') }}
            </span>
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

{{-- ── MAIN CONTENT ──────────────────────────────────── --}}
<main class="staff-content">
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

{{-- ── BOTTOM TAB BAR ────────────────────────────────── --}}
<nav class="tab-bar">

    {{-- Products --}}
    <a href="{{ route('sales.index') }}"
       class="tab-item {{ request()->routeIs('sales.index') ? 'active' : '' }}">
        <div class="tab-icon-wrap">
            <span class="tab-icon">
                <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <rect x="2.5" y="2.5" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.6"/>
                    <rect x="12.5" y="2.5" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.6"/>
                    <rect x="2.5" y="12.5" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.6"/>
                    <rect x="12.5" y="12.5" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.6"/>
                </svg>
            </span>
        </div>
        <span class="tab-label">Products</span>
    </a>

    {{-- Active Shift --}}
    <a href="{{ route('sales.shift') }}"
       class="tab-item {{ request()->routeIs('sales.shift') ? 'active' : '' }}">
        <div class="tab-icon-wrap">
            <span class="tab-icon">
                <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <path d="M3 5.5h1.5l2.3 9h9.4l2-6.5H7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="9" cy="17" r="1.3" fill="currentColor"/>
                    <circle cx="14.5" cy="17" r="1.3" fill="currentColor"/>
                </svg>
            </span>
        </div>
        <span class="tab-label">Active Shift</span>
    </a>

    {{-- History --}}
    <a href="{{ route('sales.history') }}"
       class="tab-item {{ request()->routeIs('sales.history') ? 'active' : '' }}">
        <div class="tab-icon-wrap">
            <span class="tab-icon">
                <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="1.6"/>
                    <path d="M11 7v4.5l3 2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
        </div>
        <span class="tab-label">History</span>
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

</body>
</html>
