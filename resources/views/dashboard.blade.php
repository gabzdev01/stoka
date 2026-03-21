@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
<div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap;">
    <div>
        <p class="dash-greeting">{{ $greeting }}, {{ session('demo_owner_name') ?: explode(' ', trim(session('auth_name')))[0] }}.</p>
        @if($insight)
            <p class="dash-insight">{{ $insight }}</p>
        @else
            <p class="dash-date">{{ $today->isoFormat('dddd, D MMMM YYYY') }}</p>
        @endif
    </div>
    @if(session('auth_role') === 'owner')
    @php
        // Always use shop.index route - no preview parameter needed
        $goOnlineUrl = route('shop.index');
    @endphp
    <div class="header-actions">
        <div class="header-btns">
            <a href="{{ route('sales.index') }}" class="btn-start-selling">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                    <line x1="7" y1="7" x2="7.01" y2="7"/>
                </svg>
                Start Selling
            </a>
            <a href="{{ $goOnlineUrl }}" target="_blank" class="btn-go-online">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="2" y1="12" x2="22" y2="12"/>
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                </svg>
                See it Live
            </a>
        </div>
        <p class="btn-preview-hint">
            @if(!$shopEnabled)
                See how your boutique looks online &middot;
                <a href="https://wa.me/254741641925?text={{ urlencode('Hi! I just saw my shop preview on Stoka — ' . shop_name() . '. I would like to get started.') }}" target="_blank" class="btn-preview-hint-link">Chat to get started →</a>
            @else
                <span class="live-dot"></span>
                <span class="live-count">{{ $shopVisibleCount }}</span> product{{ $shopVisibleCount === 1 ? '' : 's' }} live in your shop
            @endif
        </p>
    </div>
    @endif
</div>
@endsection

@section('styles')
<style>
/* ── Wrap ────────────────────────────────────────────── */
.dash-wrap {
    max-width: 1100px;
    margin: 0 auto;
}

/* ── Zone 1: Greeting ─────────────────────────────────── */
.dash-greeting {
    font-family: "Cormorant Garamond", serif;
    font-size: 28px;
    font-weight: 600;
    color: var(--espresso);
    line-height: 1.1;
}
.dash-date {
    font-size: 13px;
    color: var(--muted);
    margin-top: 5px;
}
.dash-insight {
    font-size: 13px;
    color: var(--forest);
    margin-top: 6px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
}
.dash-insight::before {
    content: '';
    display: inline-block;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--forest);
    flex-shrink: 0;
    opacity: 0.7;
}

/* ── Header action buttons ────────────────────────────── */
.header-actions {
    display: flex;
    align-items: flex-start;
    flex-direction: column;
    gap: 6px;
    flex-shrink: 0;
    align-self: center;
    margin-top: 4px;
}
.header-btns {
    display: flex;
    align-items: center;
    gap: 10px;
}
.btn-start-selling {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 15px;
    background: var(--espresso);
    color: var(--parchment);
    border-radius: var(--radius-md);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.01em;
    text-decoration: none;
    white-space: nowrap;
    transition: opacity 0.13s;
    flex-shrink: 0;
}
.btn-start-selling:hover { opacity: 0.82; }
.btn-go-online {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: transparent;
    color: var(--terracotta);
    border: 1.5px solid var(--terracotta);
    border-radius: var(--radius-md);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.01em;
    text-decoration: none;
    white-space: nowrap;
    transition: background 0.13s, opacity 0.13s;
    opacity: 0.85;
    flex-shrink: 0;
}
.btn-go-online:hover { background: #FDF3E8; opacity: 1; }
.btn-preview-hint {
    font-size: 10px;
    color: var(--muted);
    letter-spacing: 0.02em;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 5px;
    padding-left: 1px;
}
.btn-preview-hint-link {
    color: var(--terracotta);
    text-decoration: none;
    font-weight: 600;
    opacity: 0.85;
}
.btn-preview-hint-link:hover { opacity: 1; }
.live-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--forest);
    flex-shrink: 0;
    animation: live-pulse 2.8s ease-in-out infinite;
}
@keyframes live-pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.35; transform: scale(0.85); }
}
.live-count {
    font-family: 'DM Mono', monospace;
    font-size: 12px;
    font-weight: 500;
    color: var(--espresso);
    letter-spacing: 0;
}

/* ── Yesterday comparison ─────────────────────────────── */
.stat-compare {
    font-size: 12px;
    margin-top: 5px;
    font-family: "Plus Jakarta Sans", sans-serif;
}
.stat-compare .cmp-num { font-family: "DM Mono", monospace; }
.stat-compare.cmp-up   { color: #4A6741; }
.stat-compare.cmp-down { color: var(--muted); }

/* ── Zone spacing ─────────────────────────────────────── */
.zone-gap-sm  { margin-bottom: 24px; }
.zone-gap-md  { margin-bottom: 32px; }
.zone-gap-lg  { margin-bottom: 40px; }
.section-title {
    font-family: "Cormorant Garamond", serif;
    font-size: 22px;
    font-weight: 600;
    color: var(--espresso);
    margin-bottom: 14px;
}

/* ── While you were away card ─────────────────────────── */
.away-card {
    background: #fff;
    border: 1px solid #EDE8E0;
    border-radius: var(--radius-default);
    padding: 22px 24px;
    margin-bottom: 28px;
    box-shadow: 0 1px 3px rgba(28,24,20,0.05);
}
.away-label {
    font-size: 10.5px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--muted);
    margin-bottom: 8px;
    display: block;
}
.away-text {
    font-size: 15px;
    color: var(--espresso);
    line-height: 1.7;
}
.away-text strong { font-weight: 600; }
.away-mono { font-family: "DM Mono", monospace; }

/* ── Monthly story card ───────────────────────────────── */
.story-card {
    background: var(--espresso);
    border-radius: var(--radius-default);
    padding: 28px 30px;
    margin-bottom: 32px;
    color: #FAF7F2;
}
.story-month {
    font-size: 10.5px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: rgba(250,247,242,0.5);
    margin-bottom: 14px;
    display: block;
}
.story-para {
    font-size: 15.5px;
    line-height: 1.75;
    color: rgba(250,247,242,0.92);
}
.story-para strong { color: #FAF7F2; font-weight: 600; }
.story-mono { font-family: "DM Mono", monospace; }
.story-para + .story-para { margin-top: 10px; }

/* ── Zone 2: Right Now ────────────────────────────────── */
/* Single shift: compact card. 2+ shifts: horizontal chip strip */
.now-wrap {
    background: #F5F0E8;
    border: 1px solid #EDE8E0;
    border-radius: 14px;
    overflow: hidden;
}
/* Single-shift row */
.now-card {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 18px;
    text-decoration: none;
    color: inherit;
    transition: background 0.13s;
}
.now-card:hover { background: #EDE8DC; }
/* Multi-shift: chips in a row */
.now-chips {
    display: flex;
    align-items: center;
    gap: 0;
    flex-wrap: nowrap;
    overflow-x: auto;
    scrollbar-width: none;
}
.now-chips::-webkit-scrollbar { display: none; }
.now-chip {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 13px 16px;
    text-decoration: none;
    color: inherit;
    flex: 1;
    min-width: 0;
    transition: background 0.13s;
    border-right: 1px solid #EDE8E0;
}
.now-chip:last-child { border-right: none; }
.now-chip:hover { background: #EDE8DC; }
.now-chip-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--espresso);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.now-chip-stats {
    font-family: "DM Mono", monospace;
    font-size: 11px;
    color: var(--muted);
    white-space: nowrap;
}
.now-chip-arrow {
    font-size: 11px;
    color: var(--muted);
    flex-shrink: 0;
    margin-left: auto;
}
.pulse-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--forest);
    flex-shrink: 0;
    animation: pdot 2s ease-in-out infinite;
}
@keyframes pdot {
    0%, 100% { transform: scale(1);    box-shadow: 0 0 0 0   rgba(74,103,65,0.5); }
    50%       { transform: scale(1.35); box-shadow: 0 0 0 5px rgba(74,103,65,0);   }
}
.now-body {
    flex: 1;
    min-width: 0;
    font-size: 14px;
    color: var(--espresso);
    line-height: 1.5;
}
.now-name strong { font-weight: 600; }
.now-dot-sep { color: var(--muted); padding: 0 1px; }
.now-stats {
    font-family: "DM Mono", monospace;
    font-size: 13px;
    color: var(--muted);
}

/* ── Summary cards (Status) ───────────────────────────── */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}
.summary-card {
    background: #FFFFFF;
    border: 1px solid #EDE8E0;
    border-radius: 14px;
    padding: 20px 22px;
    box-shadow: 0 1px 4px rgba(28,24,20,0.05);
    min-height: 140px;
    display: flex;
    flex-direction: column;
}
.sc-header {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 12px;
}
.sc-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}
.sc-dot-green { background: #4A6741; }
.sc-dot-amber { background: #C17F4A; }
.sc-dot-red   { background: #B85C38; }
.sc-title {
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #8C7B6E;
    line-height: 1;
}
.sc-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.sc-line {
    font-size: 14px;
    color: var(--espresso);
    line-height: 1.6;
    margin: 0;
}
.sc-line strong { font-weight: 600; }
.sc-mono { font-family: "DM Mono", monospace; }
.sc-positive { color: #4A6741; font-weight: 500; }
.sc-footer {
    margin-top: auto;
    padding-top: 12px;
}
.sc-link {
    font-size: 12px;
    color: #C17F4A;
    font-weight: 500;
    text-decoration: none;
    transition: opacity 0.13s;
}
.sc-link:hover { opacity: 0.75; }

/* ── Zone 3: Hero stat cards ──────────────────────────── */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
}
.stat-card {
    background: #fff;
    border: 1px solid #EDE8E0;
    border-radius: var(--radius-default);
    padding: 20px 22px;
    box-shadow: 0 1px 3px rgba(28,24,20,0.06), 0 4px 16px rgba(28,24,20,0.04);
    display: block;
    color: inherit;
    text-decoration: none;
    cursor: pointer;
    position: relative;
}
.stat-eye {
    position: absolute;
    top: 12px;
    right: 14px;
    color: #8C7B6E;
    line-height: 0;
}
.eye-closed { display: inline-block; }
.eye-open   { display: none; }
body.hero-revealed .eye-closed { display: none; }
body.hero-revealed .eye-open   { display: inline-block; }
.stat-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--muted);
    line-height: 1;
    display: block;
    margin-bottom: 10px;
}
.stat-value {
    font-family: "DM Mono", monospace;
    font-size: 32px;
    font-weight: 500;
    color: var(--espresso);
    line-height: 1;
    margin-bottom: 8px;
    letter-spacing: -0.01em;
}
.stat-value.c-forest { color: var(--forest); }
.stat-value.c-clay   { color: var(--clay); }
.stat-value.c-muted  { color: var(--muted); }
.stat-sub { font-size: 13px; color: var(--muted); }
.stat-credit-link {
    font-size: 12px;
    color: var(--terracotta);
    font-weight: 500;
    text-decoration: none;
}
/* Hero cards blur */
.stat-amount {
    filter: blur(8px);
    transition: filter 300ms ease;
    user-select: none;
    display: inline-block;
}
body.hero-revealed .stat-amount {
    filter: blur(0);
}

/* ── Zone 5: Recent Shifts ────────────────────────────── */
.shift-list {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(28,24,20,0.06);
    margin-bottom: 10px;
}
.shift-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 18px;
    border-bottom: 1px solid #F5F0EB;
    text-decoration: none;
    color: inherit;
    transition: background 0.12s;
    min-height: 56px;
    border-left: 3px solid transparent;
}
.shift-row:last-child { border-bottom: none; }
.shift-row:hover { background: #FDFAF7; }
.sr-open { border-left-color: var(--forest); }
.shift-staff { font-weight: 600; font-size: 15px; color: var(--espresso); min-width: 120px; flex-shrink: 0; }
.shift-center { flex: 1; min-width: 0; }
.shift-meta {
    font-family: "DM Mono", monospace;
    font-size: 13px;
    color: var(--espresso);
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.shift-note {
    font-size: 11.5px;
    color: var(--muted);
    margin-top: 3px;
    font-style: italic;
}
.shift-split { font-size: 11.5px; color: var(--muted); margin-top: 3px; }
.shift-right { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; flex-shrink: 0; }
.shift-date { font-size: 12px; color: var(--muted); }
.shift-balanced {
    display: flex; align-items: center; gap: 4px;
    font-size: 12.5px; color: var(--forest);
}
.shift-balanced .dot { width: 6px; height: 6px; background: var(--forest); border-radius: 50%; flex-shrink: 0; }
.shift-disc { font-size: 15px; font-weight: 700; color: var(--clay); }
.shift-disc.over { color: var(--terracotta); }

/* ── Below-floor alert card ───────────────────────────── */
.floor-card {
    border: 1px solid #E8D0C0;
    border-radius: var(--radius-default);
    overflow: hidden;
    margin-bottom: 40px;
}
.floor-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 13px 20px;
    background: #FDF0E8;
    border-bottom: 1px solid #E8D0C0;
}
.floor-card-title {
    display: flex;
    align-items: center;
    gap: 9px;
    font-size: 12.5px;
    font-weight: 700;
    color: var(--clay);
    text-transform: uppercase;
    letter-spacing: 0.06em;
}
.floor-card-title-icon {
    width: 18px; height: 18px;
    background: var(--clay);
    border-radius: 5px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    color: #fff;
}
.floor-card-count {
    font-size: 12px;
    font-weight: 600;
    color: var(--clay);
    opacity: 0.7;
}
.floor-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 20px;
    border-bottom: 1px solid #F5F0EB;
    background: #fff;
}
.floor-row:last-child { border-bottom: none; }
.floor-staff {
    font-size: 13.5px;
    font-weight: 700;
    color: var(--espresso);
    flex-shrink: 0;
    min-width: 90px;
}
.floor-product {
    flex: 1;
    min-width: 0;
}
.floor-product-name {
    font-size: 13.5px;
    color: var(--espresso);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.floor-product-variant {
    font-size: 11.5px;
    color: var(--muted);
    margin-top: 1px;
}
.floor-prices {
    text-align: right;
    flex-shrink: 0;
}
.floor-sold {
    font-family: "DM Mono", monospace;
    font-size: 14px;
    font-weight: 600;
    color: var(--clay);
}
.floor-gap {
    font-size: 11.5px;
    color: var(--muted);
    margin-top: 1px;
}
.floor-time {
    font-size: 11.5px;
    color: var(--muted);
    flex-shrink: 0;
    text-align: right;
    min-width: 46px;
}
@media (max-width: 600px) {
    .floor-row { gap: 10px; padding: 11px 16px; }
    .floor-staff { min-width: 70px; font-size: 12.5px; }
    .floor-time { display: none; }
}
.badge-open {
    display: inline-flex;
    align-items: center;
    padding: 2px 8px;
    background: #DFF0DD;
    color: var(--forest);
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    font-family: "Plus Jakarta Sans", sans-serif;
}
.see-all-link {
    display: block;
    text-align: right;
    font-size: 13px;
    color: var(--terracotta);
    font-weight: 600;
    padding: 4px 0;
    transition: opacity 0.13s;
}
.see-all-link:hover { opacity: 0.75; }

/* ── Mobile ───────────────────────────────────────────── */
@media (max-width: 767px) {
    .dash-greeting { font-size: 22px; }
    .btn-start-selling, .btn-go-online { font-size: 11px; padding: 7px 13px; }
    .header-btns { gap: 8px; }
    .btn-preview-hint { font-size: 10px; }

    /* Right Now — two lines */
    .now-dot-sep { display: none; }
    .now-stats   { display: block; }
    
    /* Open shift chips — more compact on mobile */
    .now-chip {
        padding: 10px 12px;
        gap: 6px;
    }
    .now-chip-name {
        font-size: 12px;
    }
    .now-chip-stats {
        font-size: 10px;
        white-space: normal;
        line-height: 1.3;
    }
    .now-chip-arrow {
        font-size: 10px;
    }

    /* Stat grid — 2x2 */
    .stat-grid  { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .stat-value { font-size: 24px; }

    /* Summary cards — stack on mobile */
    .summary-grid { grid-template-columns: 1fr; gap: 12px; }

    /* Shifts */
    .shift-split { display: none; }
    .shift-staff { min-width: 90px; }
    .shift-disc  { font-size: 13px; }
}

/* ── Shop teaser card ─────────────────────────────────── */
.teaser-card {
    background: #1C1814;
    border-radius: 16px;
    padding: 28px 24px 24px;
    margin-bottom: 40px;
    position: relative;
    overflow: hidden;
}
.teaser-eyebrow {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: #C17F4A;
    margin-bottom: 16px;
}
.teaser-body {
    display: flex;
    gap: 20px;
    align-items: flex-start;
}
.teaser-mock {
    flex-shrink: 0;
    width: 100px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3px;
}
.teaser-mock-cell {
    background: #2C1F14;
    aspect-ratio: 3/4;
    border-radius: 2px;
}
.teaser-mock-cell:nth-child(1) { background: #3A2A1A; }
.teaser-mock-cell:nth-child(4) { background: #2E2010; }
.teaser-text {
    flex: 1;
}
.teaser-shop-name {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(20px, 5vw, 26px);
    font-weight: 400;
    color: #FAF7F2;
    line-height: 1.1;
    letter-spacing: 0.03em;
    margin-bottom: 8px;
}
.teaser-tagline {
    font-size: 12px;
    color: rgba(250,247,242,0.45);
    line-height: 1.5;
    margin-bottom: 20px;
    letter-spacing: 0.01em;
}
.teaser-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.teaser-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 16px;
    background: #C17F4A;
    color: #FAF7F2;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.02em;
    text-decoration: none;
    transition: opacity 0.15s;
    white-space: nowrap;
}
.teaser-btn-primary:hover { opacity: 0.88; }
.teaser-btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 16px;
    border: 1px solid rgba(255,255,255,0.12);
    color: rgba(255,255,255,0.55);
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
    letter-spacing: 0.02em;
    text-decoration: none;
    transition: border-color 0.15s, color 0.15s;
    white-space: nowrap;
}
.teaser-btn-secondary:hover { border-color: rgba(255,255,255,0.3); color: rgba(255,255,255,0.85); }
@media (min-width: 480px) {
    .teaser-mock { width: 120px; }
    .teaser-shop-name { font-size: 28px; }
}
</style>

@endsection

@section('content')

@php
/* ─── Status card dots ────────────────────────────────── */
$shopDot   = 'green';
$shopLines = [];

// Previous closed shift for Your Shop card
$prevShift = $recentShifts->first();
if ($prevShift) {
    $pvSales = $prevShift->activeSales;
    $pvTotal = (int) $pvSales->sum('total');
    $pvCount = $pvSales->count();
    $pvDisc  = (float) $prevShift->cash_discrepancy;
    $pvDate  = $prevShift->closed_at->isYesterday() ? 'Yesterday'
             : ($prevShift->closed_at->isToday() ? 'Today'
             : $prevShift->closed_at->format('d M'));
    $shopLines[] = [
        'type'  => 'prev',
        'staff' => $prevShift->staff->name,
        'total' => $pvTotal,
        'count' => $pvCount,
        'disc'  => $pvDisc,
        'date'  => $pvDate,
    ];
}

if ($discrepancyShifts->isNotEmpty()) {
    $shopDot = 'red';
} elseif ($openShifts->isNotEmpty()) {
    if ($openShifts->first()->opened_at->diffInHours(now()) >= 10) {
        $shopDot = 'amber';
    }
}


if ($discrepancyShifts->isNotEmpty()) {
    $dsI   = $discrepancyShifts->first();
    $disc  = abs((float)$dsI->cash_discrepancy);
    $dtype = (float)$dsI->cash_discrepancy < 0 ? 'short' : 'over';
    $when  = $dsI->closed_at->isYesterday() ? 'yesterday'
           : 'on ' . $dsI->closed_at->format('d M');
    $shopLines[] = ['type' => 'disc', 'staff' => $dsI->staff->name,
                    'disc' => $disc, 'dtype' => $dtype, 'when' => $when];
}

$supplierTotal = $supplierBalances->sum('total');
$maxCreditDays = 0;
foreach ($overdueCredit as $ocl) {
    $d = (int)$ocl->created_at->diffInDays(now());
    if ($d > $maxCreditDays) $maxCreditDays = $d;
}
$moneyDot = 'green';
if ($creditOwed > 0 || $supplierTotal > 0) {
    $moneyDot = $maxCreditDays >= 60 ? 'red' : 'amber';
}

$oosProductIds = collect()
    ->merge($outOfStock->pluck('id'))
    ->merge($outVariants->pluck('product_id'))
    ->unique();

$stockDot = 'green';
if ($oosCount > 0)     $stockDot = 'red';
elseif ($lowCount > 0) $stockDot = 'amber';

/* Best shift this month per staff (for footnote) */
$bestShiftPerStaff = \App\Models\Shift::where('status', 'closed')
    ->where('closed_at', '>=', now()->startOfMonth())
    ->with(['staff', 'activeSales'])
    ->get()
    ->groupBy('staff_id')
    ->map(fn($shifts) => $shifts->sortByDesc(fn($s) => $s->activeSales->sum('total'))->first());
@endphp

<div class="dash-wrap">

{{-- ── Monthly story (1st of month only) ──────────────── --}}
@if($monthlyStory)
<div class="story-card">
    <span class="story-month">{{ $monthlyStory['month'] }} at {{ shop_name() }}</span>
    <p class="story-para">
        <strong class="story-mono">{{ tenant('currency_symbol') }} {{ number_format($monthlyStory['total']) }}</strong> in sales
        @if($monthlyStory['top_product'])
        — <strong>{{ $monthlyStory['top_product']->name }}</strong> carried the month.
        @else
        across <strong class="story-mono">{{ $monthlyStory['count'] }}</strong> transactions.
        @endif
    </p>
    @if($monthlyStory['credit_owed'] > 0 || $monthlyStory['best_staff'])
    <p class="story-para">
        @if($monthlyStory['best_staff'])
        <strong>{{ $monthlyStory['best_staff']['name'] }}</strong> worked {{ $monthlyStory['best_staff']['shifts'] }} {{ $monthlyStory['best_staff']['shifts'] === 1 ? 'shift' : 'shifts' }}.
        @endif
        @if($monthlyStory['credit_owed'] > 0)
        Customers are carrying <strong class="story-mono">{{ tenant('currency_symbol') }} {{ number_format($monthlyStory['credit_owed']) }}</strong> in open credit.
        @endif
    </p>
    @endif
</div>
@endif

{{-- ── While you were away ──────────────────────────────── --}}
@if($awayData)
<div class="away-card">
    <span class="away-label">Welcome back</span>
    <p class="away-text">
        Since <strong>{{ $awayData['since_label'] }}</strong>:
        <span class="away-mono">{{ $awayData['count'] }}</span> {{ $awayData['count'] === 1 ? 'sale' : 'sales' }},
        <span class="away-mono">{{ tenant('currency_symbol') }} {{ number_format($awayData['total']) }}</span> in revenue.
        @if($awayData['best_shift'])
            <strong>{{ $awayData['best_shift']->staff->name }}</strong> had
            {{ tenant('currency_symbol') }} {{ number_format((int)$awayData['best_shift']->activeSales->sum('total')) }} in one shift.
        @endif
        @if($awayData['low_item'])
            <strong>{{ $awayData['low_item']->name }}</strong> is getting low.
        @endif
    </p>
</div>
@endif

{{-- ── Zone 2: Right Now (open shift pulse) ────────────── --}}
@if($openShifts->isNotEmpty())
<div class="zone-gap-sm">
<div class="now-wrap">
@if($openShifts->count() === 1)
    @php
        $os = $openShifts->first();
        $osSales = $os->activeSales;
        $osTotal = $osSales->sum('total');
        $osCount = $osSales->count();
        $since   = $os->opened_at->format('g:ia');
    @endphp
    <a href="/shifts/{{ $os->id }}" class="now-card">
        <span class="pulse-dot"></span>
        <div class="now-body">
            <span class="now-name"><strong>{{ $os->staff->name }}</strong> is selling</span><span class="now-dot-sep"> · </span><span class="now-stats">{{ $osCount }} {{ $osCount === 1 ? 'sale' : 'sales' }} · {{ tenant('currency_symbol') }} {{ number_format((int)$osTotal) }} so far · Since {{ $since }}</span>
        </div>
    </a>
@else
    <div class="now-chips">
    @foreach($openShifts as $os)
    @php
        $osSales = $os->activeSales;
        $osTotal = $osSales->sum('total');
        $osCount = $osSales->count();
        $since   = $os->opened_at->format('g:ia');
        $firstName = explode(' ', $os->staff->name)[0];
    @endphp
    <a href="/shifts/{{ $os->id }}" class="now-chip">
        <span class="pulse-dot"></span>
        <div style="min-width:0;">
            <div class="now-chip-name">{{ $firstName }}</div>
            <div class="now-chip-stats">{{ $osCount }} {{ $osCount === 1 ? 'sale' : 'sales' }} · {{ tenant('currency_symbol') }} {{ number_format((int)$osTotal) }} · {{ $since }}</div>
        </div>
        <span class="now-chip-arrow">›</span>
    </a>
    @endforeach
    </div>
@endif
</div>
</div>
@endif

{{-- ── Zone 3: Hero stat cards ───────────────────────────── --}}
@php
    $cmpDiff  = (int)$todayTotal - (int)$yesterdayTotal;
    $cmpClass = $cmpDiff > 0 ? 'cmp-up' : 'cmp-down';
    $cmpArrow = $cmpDiff > 0 ? '↑' : '↓';
    $cmpLabel = $cmpDiff > 0 ? 'more' : 'less';
    $showCmp  = $yesterdayTotal > 0 && $cmpDiff !== 0;
@endphp
<div class="stat-grid zone-gap-md">

    <div class="stat-card" onclick="toggleHero()">
        <div class="stat-eye">
            <svg class="eye-closed" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            <svg class="eye-open" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>
        <span class="stat-label">Today's sales</span>
        <div class="stat-value stat-amount">{{ tenant('currency_symbol') }}&nbsp;{{ number_format((int)$todayTotal) }}</div>
        <div class="stat-sub">{{ $todayCount }} {{ $todayCount === 1 ? 'transaction' : 'transactions' }}</div>
        @if($showCmp)
        <div class="stat-compare {{ $cmpClass }}">{{ $cmpArrow }} <span class="cmp-num">{{ tenant('currency_symbol') }} {{ number_format(abs($cmpDiff)) }}</span> {{ $cmpLabel }} than yesterday</div>
        @endif
    </div>

    <div class="stat-card" onclick="toggleHero()">
        <div class="stat-eye">
            <svg class="eye-closed" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            <svg class="eye-open" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>
        <span class="stat-label">Cash collected</span>
        <div class="stat-value c-forest stat-amount">{{ tenant('currency_symbol') }}&nbsp;{{ number_format((int)$todayCash) }}</div>
    </div>

    <div class="stat-card" onclick="toggleHero()">
        <div class="stat-eye">
            <svg class="eye-closed" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            <svg class="eye-open" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>
        <span class="stat-label">M-Pesa received</span>
        <div class="stat-value c-forest stat-amount">{{ tenant('currency_symbol') }}&nbsp;{{ number_format((int)$todayMpesa) }}</div>
    </div>

    <div class="stat-card" onclick="toggleHero()">
        <div class="stat-eye">
            <svg class="eye-closed" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            <svg class="eye-open" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>
        <span class="stat-label">Customers owe you</span>
        <div class="stat-value {{ $creditOwed > 0 ? 'c-clay' : 'c-muted' }} stat-amount">{{ tenant('currency_symbol') }}&nbsp;{{ number_format((int)$creditOwed) }}</div>
        @if($creditOwed > 0)
        <div class="stat-sub"><a href="/credit" class="stat-credit-link">View deposits →</a></div>
        @endif
    </div>

</div>
<p id="hero-hint" style="text-align:center; font-size:11px; color:#8C7B6E; margin-top:6px; margin-bottom:32px;">Tap any card to show amounts</p>

{{-- ── Status cards (Shop / Money / Stock) ─────────────── --}}
<div class="summary-grid zone-gap-lg">

    {{-- Card 1: Your Shop --}}
    <div class="summary-card">
        <div class="sc-header">
            <span class="sc-dot sc-dot-{{ $shopDot }}"></span>
            <span class="sc-title">Your shop</span>
        </div>
        <div class="sc-body">
            @if(empty($shopLines))
                <p class="sc-line sc-positive">All recent shifts balanced</p>
            @else
                @foreach($shopLines as $sl)
                    @if($sl['type'] === 'prev')
                    <p class="sc-line">
                        <strong>{{ $sl['staff'] }}</strong>
                        · {{ $sl['date'] }}
                        · <span class="sc-mono">{{ tenant('currency_symbol') }} {{ number_format($sl['total']) }}</span>
                    </p>
                    @if($sl['disc'] != 0)
                    <p class="sc-line" style="color:{{ $sl['disc'] < 0 ? '#B85C38' : '#C17F4A' }}; font-size:13px;">
                        {{ tenant('currency_symbol') }} {{ number_format(abs($sl['disc']), 0) }} {{ $sl['disc'] < 0 ? 'short' : 'over' }}
                    </p>
                    @else
                    <p class="sc-line" style="color:#4A6741; font-size:13px;">Balanced</p>
                    @endif
                    @elseif($sl['type'] === 'disc')
                    <p class="sc-line">
                        <strong>{{ $sl['staff'] }}</strong> was
                        <span style="color:#B85C38;">{{ tenant('currency_symbol') }} {{ number_format($sl['disc'], 0) }} {{ $sl['dtype'] }}</span>
                        {{ $sl['when'] }}
                    </p>
                    @endif
                @endforeach
            @endif
        </div>
        <div class="sc-footer">
            <a href="/shifts" class="sc-link">See all shifts →</a>
        </div>
    </div>

    {{-- Card 2: Money --}}
    <div class="summary-card">
        <div class="sc-header">
            <span class="sc-dot sc-dot-{{ $moneyDot }}"></span>
            <span class="sc-title">Money</span>
        </div>
        <div class="sc-body">
            @if($creditOwed == 0 && $supplierTotal == 0)
                <p class="sc-line sc-positive">No outstanding balances</p>
            @else
                @if($creditOwed > 0)
                <p class="sc-line" style="color:#B85C38;">
                    {{ tenant('currency_symbol') }} <span class="sc-mono">{{ number_format((int)$creditOwed) }}</span>
                    in deposit balances
                </p>
                @endif
                @if($supplierTotal > 0)
                <p class="sc-line" style="color:#C17F4A;">
                    {{ tenant('currency_symbol') }} <span class="sc-mono">{{ number_format((int)$supplierTotal) }}</span>
                    owed to suppliers
                </p>
                @endif
            @endif
        </div>
        <div class="sc-footer">
            <a href="/credit" class="sc-link">See all credit →</a>
        </div>
    </div>

    {{-- Card 3: Stock --}}
    <div class="summary-card">
        <div class="sc-header">
            <span class="sc-dot sc-dot-{{ $stockDot }}"></span>
            <span class="sc-title">Stock</span>
        </div>
        <div class="sc-body">
            @if($oosCount == 0 && $lowCount == 0)
                <p class="sc-line sc-positive">Stock levels looking good</p>
            @elseif($oosCount > 0)
                <p class="sc-line">
                    <span style="color:#B85C38;">{{ $oosCount }} {{ $oosCount === 1 ? 'item' : 'items' }} sold out</span>
                    — restock needed
                </p>
                @if($lowCount > 0)
                <p class="sc-line" style="color:#C17F4A;">{{ $lowCount }} more running low</p>
                @endif
            @else
                <p class="sc-line" style="color:#C17F4A;">{{ $lowCount }} {{ $lowCount === 1 ? 'item' : 'items' }} running low</p>
            @endif
        </div>
        <div class="sc-footer">
            <a href="/shopping-list" class="sc-link">See shopping list →</a>
        </div>
    </div>

</div>

{{-- ── Zone 5: Recent Shifts ──────────────────────────── --}}
@php
    // Find the single best shift this month per staff member for footnote
    $bestShiftIds = $bestShiftPerStaff->pluck('id')->toArray();
@endphp
@if($belowFloorSales->isNotEmpty())
<div class="floor-card">
    <div class="floor-card-header">
        <div class="floor-card-title">
            <div class="floor-card-title-icon">
                <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
                    <path d="M5 1v4M5 7.5v.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
            </div>
            Sales below floor price
        </div>
        <span class="floor-card-count">{{ $belowFloorSales->count() }} {{ $belowFloorSales->count() === 1 ? 'sale' : 'sales' }} today</span>
    </div>
    @foreach($belowFloorSales as $fs)
    @php
        $gap = (float)$fs->floor_price - (float)$fs->actual_price;
        $fsTime = \Carbon\Carbon::parse($fs->created_at)->format('g:ia');
    @endphp
    <div class="floor-row">
        <span class="floor-staff">{{ explode(' ', trim($fs->staff_name))[0] }}</span>
        <div class="floor-product">
            <div class="floor-product-name">{{ $fs->product_name }}</div>
            @if($fs->variant_size)
            <div class="floor-product-variant">Size {{ $fs->variant_size }}</div>
            @endif
        </div>
        <div class="floor-prices">
            <div class="floor-sold">{{ tenant('currency_symbol') }} {{ number_format((int)$fs->actual_price) }}</div>
            <div class="floor-gap">{{ tenant('currency_symbol') }} {{ number_format((int)$gap) }} below floor</div>
        </div>
        <span class="floor-time">{{ $fsTime }}</span>
    </div>
    @endforeach
</div>
@endif

<section class="zone-gap-lg">
    <h2 class="section-title">Recent shifts</h2>
    <div class="shift-list">

        @foreach($openShifts as $os2)
        @php
            $os2Sales = $os2->activeSales;
            $os2Total = $os2Sales->sum('total');
        @endphp
        <a href="/shifts/{{ $os2->id }}" class="shift-row sr-open">
            <div class="shift-staff">{{ $os2->staff->name }}</div>
            <div class="shift-center">
                <div class="shift-meta">
                    <span class="badge-open">Open now</span>
                    <span>{{ $os2Sales->count() }} {{ $os2Sales->count() === 1 ? 'sale' : 'sales' }}</span>
                    <span style="color:var(--border);">·</span>
                    <span>{{ tenant('currency_symbol') }} {{ number_format((int)$os2Total) }}</span>
                </div>
            </div>
            <div class="shift-right">
                <span class="shift-date" style="color:var(--forest);font-weight:500;">Since {{ $os2->opened_at->format('g:ia') }}</span>
            </div>
        </a>
        @endforeach

        @foreach($recentShifts->take(3) as $shift)
        @php
            $ss     = $shift->activeSales;
            $sTotal = $ss->sum('total');
            $sCash  = $ss->where('payment_type', 'cash')->sum('total');
            $sMpesa = $ss->where('payment_type', 'mpesa')->sum('total');
            $sCount = $ss->count();
            $disc   = (float)$shift->cash_discrepancy;
            $sDate  = $shift->closed_at->isYesterday() ? 'Yesterday'
                    : ($shift->closed_at->isToday()    ? 'Today'
                    : $shift->closed_at->format('d M'));
            $isBest = in_array($shift->id, $bestShiftIds);
        @endphp
        <a href="/shifts/{{ $shift->id }}" class="shift-row">
            <div class="shift-staff">{{ $shift->staff->name }}</div>
            <div class="shift-center">
                <div class="shift-meta">
                    <span>{{ $sCount }} {{ $sCount === 1 ? 'sale' : 'sales' }}</span>
                    <span style="color:var(--border);">·</span>
                    <span>{{ tenant('currency_symbol') }} {{ number_format((int)$sTotal) }}</span>
                </div>
                @if($isBest)
                <div class="shift-note">their best shift this month</div>
                @else
                <div class="shift-split">
                    Cash {{ tenant('currency_symbol') }} {{ number_format((int)$sCash) }}
                    &nbsp;·&nbsp;M-Pesa {{ tenant('currency_symbol') }} {{ number_format((int)$sMpesa) }}
                </div>
                @endif
            </div>
            <div class="shift-right">
                <span class="shift-date">{{ $sDate }}</span>
                @if($disc == 0)
                    <span class="shift-balanced"><span class="dot"></span>Balanced</span>
                @elseif($disc < 0)
                    <span class="shift-disc">{{ tenant('currency_symbol') }} {{ number_format(abs($disc), 0) }} short</span>
                @else
                    <span class="shift-disc over">{{ tenant('currency_symbol') }} {{ number_format(abs($disc), 0) }} over</span>
                @endif
            </div>
        </a>
        @endforeach

    </div>
    <a href="/shifts" class="see-all-link">See all shifts →</a>
</section>

</div>{{-- /dash-wrap --}}

@endsection

@section('scripts')
<script>
(function () {
    // Warm exit flash when navigating to the sales floor
    var startBtn = document.querySelector('.btn-start-selling');
    if (startBtn) {
        startBtn.addEventListener('click', function(e) {
            e.preventDefault();
            var dest = this.href;
            var flash = document.createElement('div');
            flash.style.cssText = 'position:fixed;inset:0;background:#F5F0E8;opacity:0;z-index:9999;pointer-events:none;transition:opacity 0.3s ease;';
            document.body.appendChild(flash);
            requestAnimationFrame(function(){
                requestAnimationFrame(function(){
                    flash.style.opacity = '1';
                    setTimeout(function(){ window.location = dest; }, 280);
                });
            });
        });
    }
})();

(function () {
    window.toggleHero = function () {
        document.body.classList.toggle('hero-revealed');
        var hint = document.getElementById('hero-hint');
        if (hint) {
            hint.style.display = 'none';
            sessionStorage.setItem('stoka_hero_hint', '1');
        }
    };
    if (sessionStorage.getItem('stoka_hero_hint') === '1') {
        var hint = document.getElementById('hero-hint');
        if (hint) hint.style.display = 'none';
    }
})();
</script>
@endsection
