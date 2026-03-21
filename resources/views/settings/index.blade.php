@extends('layouts.app')

@section('title', 'Settings')

@section('header')
<h1 class="page-title">Settings</h1>
<p class="page-subtitle">Manage your shop, staff, and how Stoka works for you</p>
@endsection

@section('styles')
<style>
/* ── Settings layout ───────────────────────────────────── */
.settings-wrap {
    max-width: 680px;
    display: flex;
    flex-direction: column;
    gap: 28px;
}

/* ── Section card ──────────────────────────────────────── */
.s-card {
    background: #fff;
    border: 1px solid #EDE8E0;
    border-radius: var(--radius-default);
    padding: 28px;
}
.s-card-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--espresso);
    margin-bottom: 22px;
    letter-spacing: 0;
}
.s-divider {
    height: 1px;
    background: #F5F0EB;
    margin: 24px 0;
}

/* ── Form fields ───────────────────────────────────────── */
.s-field { margin-bottom: 18px; }
.s-field:last-child { margin-bottom: 0; }
.s-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--espresso);
    margin-bottom: 6px;
}
.s-helper {
    font-size: 12px;
    color: var(--muted);
    margin-top: 5px;
    line-height: 1.4;
}
.s-input {
    width: 100%;
    height: 44px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-md);
    background: var(--parchment);
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 14px;
    color: var(--espresso);
    padding: 0 14px;
    transition: border-color 0.15s;
    outline: none;
    box-sizing: border-box;
}
.s-input:focus { border-color: var(--espresso); }
.s-textarea {
    width: 100%;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-md);
    background: var(--parchment);
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 14px;
    color: var(--espresso);
    padding: 12px 14px;
    transition: border-color 0.15s;
    outline: none;
    resize: vertical;
    min-height: 80px;
    box-sizing: border-box;
}
.s-textarea:focus { border-color: var(--espresso); }
.s-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
@media (max-width: 520px) { .s-grid-2 { grid-template-columns: 1fr; } }

/* ── Save button ───────────────────────────────────────── */
.s-save {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 42px;
    padding: 0 22px;
    background: var(--espresso);
    color: #fff;
    border: none;
    border-radius: var(--radius-md);
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
    transition: opacity 0.13s;
}
.s-save:hover { opacity: 0.85; }

/* ── Flash messages ────────────────────────────────────── */
.flash-ok {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #DFF0DD;
    color: var(--forest);
    border-radius: var(--radius-md);
    padding: 11px 15px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 18px;
}
.flash-err {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #F5DDD8;
    color: var(--clay);
    border-radius: var(--radius-md);
    padding: 11px 15px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 18px;
}

/* ── Subsection title ──────────────────────────────────── */
.s-sub-title {
    font-size: 13px;
    font-weight: 700;
    color: var(--espresso);
    margin-bottom: 14px;
}

/* ── Toggle switch ─────────────────────────────────────── */
.toggle-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    padding: 12px 0;
}
.toggle-row + .toggle-row { border-top: 1px solid #F5F0EB; }
.toggle-text { flex: 1; min-width: 0; }
.toggle-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--espresso);
    display: block;
}
.toggle-helper {
    font-size: 12px;
    color: var(--muted);
    margin-top: 3px;
    line-height: 1.4;
    display: block;
}
.toggle-switch {
    position: relative;
    width: 46px;
    height: 26px;
    flex-shrink: 0;
    margin-top: 2px;
}
.toggle-switch input { opacity: 0; width: 0; height: 0; position: absolute; }
.toggle-slider {
    position: absolute;
    inset: 0;
    background: #D5CEC5;
    border-radius: 13px;
    cursor: pointer;
    transition: background 0.2s;
}
.toggle-slider::before {
    content: '';
    position: absolute;
    width: 20px; height: 20px;
    left: 3px; top: 3px;
    background: white;
    border-radius: 50%;
    transition: transform 0.2s;
    box-shadow: 0 1px 3px rgba(0,0,0,0.15);
}
input:checked + .toggle-slider { background: var(--forest); }
input:checked + .toggle-slider::before { transform: translateX(20px); }

/* ── Staff list ────────────────────────────────────────── */
.staff-list { display: flex; flex-direction: column; gap: 0; }
.staff-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 0;
    border-bottom: 1px solid #F5F0EB;
}
.staff-row:last-child { border-bottom: none; }
.staff-info { flex: 1; min-width: 0; }
.staff-name {
    font-size: 14.5px;
    font-weight: 600;
    color: var(--espresso);
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.staff-phone {
    font-family: "DM Mono", monospace;
    font-size: 12px;
    color: var(--muted);
    display: block;
    margin-top: 2px;
}
.staff-pin-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
}
.staff-pin {
    font-family: "DM Mono", monospace;
    font-size: 13px;
    color: var(--espresso);
    letter-spacing: 0.12em;
}
.pin-show-btn {
    background: none;
    border: none;
    cursor: pointer;
    color: var(--muted);
    font-size: 11px;
    font-weight: 600;
    font-family: "Plus Jakarta Sans", sans-serif;
    padding: 2px 4px;
    transition: color 0.13s;
}
.pin-show-btn:hover { color: var(--terracotta); }
.status-pill {
    display: inline-flex;
    align-items: center;
    padding: 3px 10px;
    border-radius: var(--radius-full);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
    cursor: pointer;
    border: none;
    font-family: "Plus Jakarta Sans", sans-serif;
}
.status-pill.active   { background: #DFF0DD; color: var(--forest); }
.status-pill.inactive { background: #F0EDE8; color: var(--muted); }
.staff-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}
.s-link {
    background: none;
    border: none;
    cursor: pointer;
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 12px;
    font-weight: 500;
    color: var(--muted);
    padding: 0;
    text-decoration: underline;
    text-underline-offset: 2px;
    transition: color 0.13s;
}
.s-link:hover { color: var(--espresso); }
.s-link-danger { color: var(--clay); }
.s-link-danger:hover { color: var(--clay); opacity: 0.75; }

/* ── New PIN card ──────────────────────────────────────── */
.new-pin-card {
    background: #DFF0DD;
    border-radius: var(--radius-md);
    padding: 14px 18px;
    margin-top: 10px;
}
.new-pin-name { font-size: 13px; color: var(--forest); font-weight: 600; }
.new-pin-number {
    font-family: "DM Mono", monospace;
    font-size: 32px;
    font-weight: 700;
    color: var(--forest);
    letter-spacing: 0.15em;
    display: block;
    margin-top: 4px;
}

/* ── Remove confirm inline ─────────────────────────────── */
.remove-confirm {
    display: none;
    align-items: center;
    gap: 10px;
    background: #FDF5EC;
    border: 1px solid #E8D8C0;
    border-radius: var(--radius-md);
    padding: 10px 14px;
    margin-top: 8px;
    font-size: 13px;
    color: var(--espresso);
    flex-wrap: wrap;
    gap: 8px;
}
.remove-confirm.visible { display: flex; }
.confirm-text { flex: 1; min-width: 160px; line-height: 1.4; }

/* ── Add staff expand ──────────────────────────────────── */
.add-staff-toggle {
    background: none;
    border: 1.5px dashed var(--border);
    border-radius: var(--radius-md);
    width: 100%;
    padding: 12px;
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 13.5px;
    font-weight: 600;
    color: var(--muted);
    cursor: pointer;
    margin-top: 16px;
    transition: border-color 0.15s, color 0.15s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
}
.add-staff-toggle:hover { border-color: var(--terracotta); color: var(--terracotta); }
.add-staff-form {
    display: none;
    background: var(--parchment);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 20px;
    margin-top: 12px;
}
.add-staff-form.visible { display: block; }

/* ── Hours select ──────────────────────────────────────── */
.hours-select-row {
    display: flex;
    align-items: flex-end;
    gap: 14px;
}
.hours-select-wrap {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.hours-mini-label {
    font-size: 11px;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.08em;
}
.hours-arrow {
    font-size: 18px;
    color: var(--border);
    padding-bottom: 11px;
    flex-shrink: 0;
    line-height: 1;
}
@media (max-width: 480px) {
    .hours-select-row { flex-direction: column; gap: 10px; }
    .hours-arrow { display: none; }
}

/* ── Currency select ───────────────────────────────────── */
.s-select {
    width: 100%;
    height: 44px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-md);
    background: var(--parchment);
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 14px;
    color: var(--espresso);
    padding: 0 14px;
    outline: none;
    cursor: pointer;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M3 5l4 4 4-4' stroke='%238C7B6E' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 36px;
    transition: border-color 0.15s;
    -webkit-text-fill-color: var(--espresso);
}
.s-select:focus { border-color: var(--espresso); outline: none; }
.s-select option { background: #FAF7F2; color: #1C1814; }

/* ── Plan line ─────────────────────────────────────────── */
.plan-line {
    font-size: 12px;
    color: var(--muted);
    margin-top: 14px;
}

/* ── Threshold input row ───────────────────────────────── */
.threshold-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 14px;
    padding: 14px;
    background: var(--parchment);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
}
.threshold-label {
    flex: 1;
    font-size: 13px;
    font-weight: 500;
    color: var(--espresso);
}
.threshold-input {
    width: 72px;
    height: 38px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-md);
    background: #fff;
    font-family: "DM Mono", monospace;
    font-size: 16px;
    color: var(--espresso);
    text-align: center;
    outline: none;
    transition: border-color 0.15s;
}
.threshold-input:focus { border-color: var(--espresso); }
.threshold-unit {
    font-size: 12px;
    color: var(--muted);
    white-space: nowrap;
}

/* ── Export card ───────────────────────────────────────── */
.export-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 18px;
    background: var(--parchment);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
}
.export-label {
    font-size: 14px;
    font-weight: 600;
    color: var(--espresso);
}
.export-sub {
    font-size: 12px;
    color: var(--muted);
    margin-top: 2px;
}
.export-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 38px;
    padding: 0 18px;
    background: var(--espresso);
    color: #fff;
    border: none;
    border-radius: var(--radius-md);
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
    flex-shrink: 0;
    transition: opacity 0.13s;
}
.export-btn:hover { opacity: 0.85; }

.data-assurance {
    font-size: 12.5px;
    color: var(--muted);
    line-height: 1.6;
    margin-top: 16px;
}

/* ── Mobile ────────────────────────────────────────────── */
@media (max-width: 600px) {
    .s-card { padding: 20px 18px; }
    .s-save { width: 100%; justify-content: center; }
    .export-card { flex-direction: column; align-items: flex-start; }
    .export-btn { width: 100%; justify-content: center; }
}
@media (max-width: 520px) {
    .s-grid-2 { grid-template-columns: 1fr; }
}
</style>
@endsection

@section('content')
<div class="settings-wrap">

{{-- ════════════════════════════════════════════════════════
     SECTION 1 — MY ACCOUNT
     ════════════════════════════════════════════════════════ --}}
<div class="s-card" id="account">
    <div class="s-card-title">My account</div>

    @if(session('ok_account'))
        <div class="flash-ok">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2.5 7l3.5 3.5 5.5-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ session('ok_account') }}
        </div>
    @endif

    <form method="POST" action="{{ route('settings.account') }}">
        @csrf
        <div class="s-field">
            <label class="s-label">Full name</label>
            <input type="text" name="owner_name" class="s-input"
                   value="{{ old('owner_name', $t->owner_name) }}" required>
        </div>
        <div class="s-grid-2">
            <div class="s-field">
                <label class="s-label">Phone number</label>
                <input type="tel" name="owner_phone" class="s-input"
                       value="{{ old('owner_phone', $t->owner_phone) }}" required>
            </div>
            <div class="s-field">
                <label class="s-label">WhatsApp number</label>
                <input type="tel" name="owner_whatsapp" class="s-input"
                       value="{{ old('owner_whatsapp', $t->owner_whatsapp) }}"
                       placeholder="Same as phone">
                <span class="s-helper">Shift reports and alerts go to this number</span>
            </div>
        </div>
        <button type="submit" class="s-save">Save account details</button>
    </form>

    <div class="s-divider"></div>

    {{-- Password change --}}
    <div class="s-sub-title">Change password</div>

    @if(session('ok_password'))
        <div class="flash-ok">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2.5 7l3.5 3.5 5.5-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ session('ok_password') }}
        </div>
    @endif
    @if(session('err_password'))
        <div class="flash-err">{{ session('err_password') }}</div>
    @endif

    <form method="POST" action="{{ route('settings.password') }}">
        @csrf
        <div class="s-grid-2">
            <div class="s-field">
                <label class="s-label">Current password</label>
                <input type="password" name="current_password" class="s-input" autocomplete="current-password">
            </div>
            <div class="s-field"></div>
            <div class="s-field">
                <label class="s-label">New password</label>
                <input type="password" name="new_password" class="s-input" autocomplete="new-password">
            </div>
            <div class="s-field">
                <label class="s-label">Confirm new password</label>
                <input type="password" name="new_password_confirmation" class="s-input" autocomplete="new-password">
            </div>
        </div>
        <button type="submit" class="s-save">Change password</button>
    </form>

    <div style="margin-top:16px;">
        <a href="{{ route('password-reset.show') }}" style="font-size:13px; color:var(--muted); text-decoration:underline; text-underline-offset:2px;">
            Forgot your password? Reset via WhatsApp &rarr;
        </a>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════
     SECTION 2 — MY SHOP
     ════════════════════════════════════════════════════════ --}}
<div class="s-card" id="shop">
    <div class="s-card-title">My shop</div>

    @if(session('ok_shop'))
        <div class="flash-ok">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2.5 7l3.5 3.5 5.5-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ session('ok_shop') }}
        </div>
    @endif

    <form method="POST" action="{{ route('settings.shop') }}">
        @csrf
        <div class="s-field">
            <label class="s-label">Shop name</label>
            <input type="text" name="name" class="s-input"
                   value="{{ old('name', $t->name) }}" required>
        </div>
        <div class="s-field">
            <label class="s-label">Location</label>
            <input type="text" name="shop_location" class="s-input"
                   value="{{ old('shop_location', $t->shop_location) }}"
                   placeholder="e.g. Westlands, Nairobi">
            <span class="s-helper">Shown on your public shop page</span>
        </div>
        <div class="s-field">
            <label class="s-label">About your shop <span style="color:var(--muted);font-weight:400;font-size:11px;">(optional)</span></label>
            <textarea name="shop_description" class="s-textarea" id="shop-desc-input"
                      maxlength="200"
                      placeholder="e.g. Nairobi's favourite stop for African prints, occasion wear and accessories. We ship nationwide.">{{ old('shop_description', $t->shop_description) }}</textarea>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:5px;">
                <span class="s-helper" style="margin-top:0;">A line your customers see at the top of your shop</span>
                <span id="shop-desc-count" style="font-size:11px;color:var(--muted);font-family:'DM Mono',monospace;">{{ strlen(old('shop_description', $t->shop_description ?? '')) }}/200</span>
            </div>
        </div>

        <div class="s-field">
            <label class="s-label">Opening hours</label>
            @php
                $openVal  = old('operating_hours_open',  $t->operating_hours_open  ?? '09:00');
                $closeVal = old('operating_hours_close', $t->operating_hours_close ?? '18:00');
                $times = [];
                for ($h = 5; $h <= 23; $h++) {
                    foreach ([0, 30] as $m) {
                        $times[] = sprintf('%02d:%02d', $h, $m);
                    }
                }
            @endphp
            <div class="hours-select-row">
                <div class="hours-select-wrap">
                    <span class="hours-mini-label">Opens</span>
                    <select name="operating_hours_open" class="s-select">
                        @foreach($times as $time)
                            @php
                                [$hh, $mm] = explode(':', $time);
                                $h12 = (int)$hh % 12 ?: 12;
                                $ampm = (int)$hh < 12 ? 'AM' : 'PM';
                            @endphp
                            <option value="{{ $time }}" {{ $openVal === $time ? 'selected' : '' }}>
                                {{ $h12 }}:{{ $mm }} {{ $ampm }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="hours-arrow">&#8594;</div>
                <div class="hours-select-wrap">
                    <span class="hours-mini-label">Closes</span>
                    <select name="operating_hours_close" class="s-select">
                        @foreach($times as $time)
                            @php
                                [$hh, $mm] = explode(':', $time);
                                $h12 = (int)$hh % 12 ?: 12;
                                $ampm = (int)$hh < 12 ? 'AM' : 'PM';
                            @endphp
                            <option value="{{ $time }}" {{ $closeVal === $time ? 'selected' : '' }}>
                                {{ $h12 }}:{{ $mm }} {{ $ampm }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <span class="s-helper">Used in shift reports to flag unusual hours</span>
        </div>

        <div class="s-divider"></div>
        <div class="s-sub-title">Currency</div>
        <div class="s-field">
            @php
                $currencies = [
                    'KES' => 'KES — Kenyan Shilling (Ksh)',
                    'UGX' => 'UGX — Ugandan Shilling (USh)',
                    'TZS' => 'TZS — Tanzanian Shilling (TSh)',
                    'RWF' => 'RWF — Rwandan Franc (RWF)',
                    'ETB' => 'ETB — Ethiopian Birr (ETB)',
                ];
                $currentCurrency = old('currency', $t->currency ?? 'KES');
            @endphp
            <select name="currency" class="s-select">
                @foreach($currencies as $code => $label)
                    <option value="{{ $code }}" {{ $currentCurrency === $code ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <span class="s-helper">All prices in your shop will use this currency</span>
        </div>

        <button type="submit" class="s-save">Save shop details</button>
    </form>

    {{-- ── My Shop link ──────────────────────────────── --}}
    @php
        $appHost = parse_url(config('app.url'), PHP_URL_HOST);
        $shopUrl = request()->getScheme() . '://' . tenant('id') . '.' . $appHost . '/shop';
    @endphp
    <div style="margin-top:20px; padding:16px; background:var(--surface); border-radius:12px; border:1px solid var(--border);">
        <div style="font-size:12px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:0.06em; margin-bottom:10px;">My Shop Link</div>
        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
            <div style="flex:1; min-width:0; font-size:12px; font-family:'DM Mono',monospace; color:var(--espresso); background:white; border:1px solid var(--border); border-radius:8px; padding:9px 12px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $shopUrl }}</div>
            <button onclick="navigator.clipboard.writeText('{{ $shopUrl }}').then(function(){var b=document.getElementById('copy-shop-btn');b.textContent='Copied!';setTimeout(function(){b.textContent='Copy';},2000);})" id="copy-shop-btn" style="padding:9px 14px; background:var(--espresso); color:white; border:none; border-radius:8px; font-family:inherit; font-size:12px; font-weight:600; cursor:pointer; white-space:nowrap;">Copy</button>
            <a href="{{ $shopUrl }}" target="_blank" style="padding:9px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:12px; font-weight:600; color:var(--muted); text-decoration:none; white-space:nowrap;">Preview</a>
        </div>
        <div style="margin-top:14px; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div>
                <div style="font-size:14px; font-weight:600; color:var(--espresso);">Shop is {{ tenant()->shop_enabled ? 'live' : 'not yet active' }}</div>
                <div style="font-size:12px; color:var(--muted); margin-top:2px;">{{ tenant()->shop_enabled ? 'Customers can browse your shop.' : 'Your shop will be activated by the Stoka team.' }}</div>
            </div>
            @if(tenant()->id === 'demo')
            <form method="POST" action="{{ route('settings.shop.toggle') }}" style="display:inline; flex-shrink:0;">
                @csrf
                <button type="submit" style="padding:9px 16px; background:{{ tenant()->shop_enabled ? '#F8E8E4' : '#DFF0DD' }}; color:{{ tenant()->shop_enabled ? '#B85C38' : '#4A6741' }}; border:none; border-radius:8px; font-family:inherit; font-size:12px; font-weight:700; cursor:pointer;">
                    {{ tenant()->shop_enabled ? 'Pause shop' : 'Go live' }}
                </button>
            </form>
            @else
            @if(!tenant()->shop_enabled)
            <a href="https://wa.me/254741641925?text={{ urlencode('Hi! I would like to activate my shop on Stoka. My shop is ' . tenant('name') . '.') }}" target="_blank" style="padding:9px 16px; background:#FDF3E8; color:var(--terracotta); border-radius:8px; font-size:12px; font-weight:600; text-decoration:none; white-space:nowrap; flex-shrink:0;">Activate my shop →</a>
            @endif
            @endif
        </div>
    </div>

    <div class="plan-line">
        You are on the <strong>{{ ucfirst($t->plan ?? 'Basic') }}</strong> plan &middot;
        <a href="https://stoka.co.ke" target="_blank" style="color:var(--terracotta);">stoka.co.ke</a>
    </div>
</div>


{{-- ════════════════════════════════════════════════════════
     SECTION 4 — RECEIPTS
     ════════════════════════════════════════════════════════ --}}
<div class="s-card" id="receipts">
    <div class="s-card-title">Receipts</div>

    @if(session('ok_receipts'))
        <div class="flash-ok">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2.5 7l3.5 3.5 5.5-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ session('ok_receipts') }}
        </div>
    @endif

    <form method="POST" action="{{ route('settings.receipts') }}">
        @csrf
        <div class="toggle-row">
            <div class="toggle-text">
                <span class="toggle-name">Send receipts to customers</span>
                <span class="toggle-helper">Opens WhatsApp with a receipt after each sale</span>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" name="receipt_digital" value="1"
                       {{ ($t->receipt_digital ?? true) ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
            </label>
        </div>
        <div class="toggle-row">
            <div class="toggle-text">
                <span class="toggle-name">Allow printing receipts</span>
                <span class="toggle-helper">Shows a print button after each sale</span>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" name="receipt_print" value="1"
                       {{ ($t->receipt_print ?? false) ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="s-divider"></div>
        <div class="s-field">
            <label class="s-label">Footer message</label>
            <input type="text" name="receipt_footer" class="s-input"
                   maxlength="100"
                   value="{{ old('receipt_footer', $t->receipt_footer) }}"
                   placeholder="Thank you for shopping with us!">
            <span class="s-helper">Appears at the bottom of every receipt &middot; Max 100 characters</span>
        </div>

        <button type="submit" class="s-save">Save receipt settings</button>
    </form>
</div>

{{-- ════════════════════════════════════════════════════════
     SECTION 5 — ALERTS
     ════════════════════════════════════════════════════════ --}}
<div class="s-card" id="alerts">
    <div class="s-card-title">Alerts</div>
    <p style="font-size:13px; color:var(--muted); margin-bottom:20px; margin-top:-10px;">
        Choose what Stoka tells you about
    </p>

    @if(session('ok_alerts'))
        <div class="flash-ok">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2.5 7l3.5 3.5 5.5-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ session('ok_alerts') }}
        </div>
    @endif

    <form method="POST" action="{{ route('settings.alerts') }}">
        @csrf
        <div class="toggle-row">
            <div class="toggle-text">
                <span class="toggle-name">Send me a shift report when staff close</span>
                <span class="toggle-helper">Sent to your WhatsApp number above</span>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" name="notify_shift_close" value="1"
                       {{ ($t->notify_shift_close ?? true) ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
            </label>
        </div>
        <div class="toggle-row">
            <div class="toggle-text">
                <span class="toggle-name">Warn me when items are running low</span>
                <div class="threshold-row" style="margin-top:10px; margin-bottom:0;">
                    <span class="threshold-label">Alert me when stock falls below</span>
                    <input type="number" name="default_low_stock_threshold"
                           class="threshold-input"
                           value="{{ old('default_low_stock_threshold', $t->default_low_stock_threshold ?? 3) }}"
                           min="1" max="100">
                    <span class="threshold-unit">units</span>
                </div>
                <span class="toggle-helper" style="margin-top:8px; display:block;">You can also set this per product</span>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" name="notify_low_stock" value="1"
                       {{ ($t->notify_low_stock ?? true) ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
            </label>
        </div>
        <div class="toggle-row">
            <div class="toggle-text">
                <span class="toggle-name">Remind me about unpaid credit</span>
                <span class="toggle-helper">At 30 days and 60 days</span>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" name="notify_credit_overdue" value="1"
                       {{ ($t->notify_credit_overdue ?? true) ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div style="margin-top:20px;">
            <button type="submit" class="s-save">Save alert settings</button>
        </div>
    </form>
</div>

{{-- ════════════════════════════════════════════════════════
     SECTION 6 — MY DATA
     ════════════════════════════════════════════════════════ --}}
<div class="s-card" id="data">
    <div class="s-card-title">My data</div>
    <p style="font-size:13px; color:var(--muted); margin-top:-10px; margin-bottom:20px;">
        Your business data belongs to you
    </p>

    <div class="export-card">
        <div>
            <div class="export-label">Export this month's data</div>
            <div class="export-sub">Sales, credit balances, and stock levels as CSV</div>
        </div>
        <a href="{{ route('settings.export') }}" class="export-btn">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M7 1v8M4 6l3 3 3-3M2 11h10" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Export &rarr;
        </a>
    </div>

    <p class="data-assurance">
        Your data is stored securely and never shared. You can export it any time.
    </p>
</div>

</div>{{-- end settings-wrap --}}
@endsection

@section('scripts')
<script>
// ── Shop description character counter ────────────────────────────────────
(function() {
    var ta = document.getElementById('shop-desc-input');
    var ct = document.getElementById('shop-desc-count');
    if (!ta || !ct) return;
    ta.addEventListener('input', function() {
        ct.textContent = ta.value.length + '/200';
        ct.style.color = ta.value.length > 180 ? '#B85C38' : '';
    });
})();

// ── PIN show/hide ──────────────────────────────────────────────────────────
function togglePin(id, pin) {
    var el  = document.getElementById('pin-' + id);
    var btn = document.getElementById('pin-btn-' + id);
    if (btn.textContent === 'Show') {
        el.textContent  = pin;
        btn.textContent = 'Hide';
    } else {
        el.textContent  = '\u25CF\u25CF\u25CF\u25CF';
        btn.textContent = 'Show';
    }
}

// ── Remove confirm ─────────────────────────────────────────────────────────
function showRemoveConfirm(id) {
    document.getElementById('remove-' + id).classList.add('visible');
}
function hideRemoveConfirm(id) {
    document.getElementById('remove-' + id).classList.remove('visible');
}

// ── Add staff expand ───────────────────────────────────────────────────────
function toggleAddStaff() {
    var form = document.getElementById('add-staff-form');
    var btn  = document.getElementById('add-staff-btn');
    var open = form.classList.toggle('visible');
    btn.style.display = open ? 'none' : '';
}

// If there were validation errors on add staff, keep form open
@if($errors->has('name') || $errors->has('phone') || $errors->has('pin'))
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('add-staff-form');
        var btn  = document.getElementById('add-staff-btn');
        form.classList.add('visible');
        btn.style.display = 'none';
    });
@endif

// ── Scroll to section after save ───────────────────────────────────────────
@if(session('ok_account') || session('ok_password') || session('err_password'))
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('account')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
@endif
@if(session('ok_shop'))
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('shop')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
@endif
@if(session('ok_receipts'))
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('receipts')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
@endif
@if(session('ok_alerts'))
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('alerts')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
@endif
</script>
@endsection
