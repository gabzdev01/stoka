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
    font-size: 11px;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 22px;
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

/* ── Hours row ─────────────────────────────────────────── */
.hours-row {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.hours-sep {
    font-size: 13px;
    color: var(--muted);
    white-space: nowrap;
}
.hours-input {
    width: 120px;
    height: 44px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-md);
    background: var(--parchment);
    font-family: "DM Mono", monospace;
    font-size: 15px;
    color: var(--espresso);
    padding: 0 12px;
    outline: none;
    transition: border-color 0.15s;
}
.hours-input:focus { border-color: var(--espresso); }

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
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M3 5l4 4 4-4' stroke='%238C7B6E' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 36px;
}
.s-select:focus { border-color: var(--espresso); }

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
            <label class="s-label">About your shop</label>
            <textarea name="shop_description" class="s-textarea"
                      maxlength="200"
                      placeholder="e.g. Fashion boutique specialising in African prints and accessories">{{ old('shop_description', $t->shop_description) }}</textarea>
            <span class="s-helper">Shown on your public shop page &middot; Max 200 characters</span>
        </div>

        <div class="s-field">
            <label class="s-label">Opening hours</label>
            <div class="hours-row">
                <span class="hours-sep">Open from</span>
                <input type="time" name="operating_hours_open" class="hours-input"
                       value="{{ old('operating_hours_open', $t->operating_hours_open ?? '09:00') }}">
                <span class="hours-sep">to</span>
                <input type="time" name="operating_hours_close" class="hours-input"
                       value="{{ old('operating_hours_close', $t->operating_hours_close ?? '18:00') }}">
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

    <div class="plan-line">
        You are on the <strong>{{ ucfirst($t->plan ?? 'Basic') }}</strong> plan &middot;
        <a href="https://stoka.co.ke" target="_blank" style="color:var(--terracotta);">stoka.co.ke</a>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════
     SECTION 3 — MY STAFF
     ════════════════════════════════════════════════════════ --}}
<div class="s-card" id="staff">
    <div class="s-card-title" style="display:flex; align-items:center; justify-content:space-between;">
        <span>My staff</span>
        <span style="font-size:11px; font-weight:600; color:var(--muted); text-transform:none; letter-spacing:0;">
            {{ $staff->count() }} {{ $staff->count() === 1 ? 'member' : 'members' }}
        </span>
    </div>

    @if(session('ok_remove'))
        <div class="flash-ok">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2.5 7l3.5 3.5 5.5-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ session('ok_remove') }}
        </div>
    @endif
    @if(session('ok_add_staff'))
        @php $addedStaff = session('ok_add_staff'); @endphp
        <div class="new-pin-card" style="margin-bottom:18px;">
            <div class="new-pin-name">{{ $addedStaff['name'] }} has been added to {{ tenant('name') }}.</div>
            <div style="font-size:12px; color:var(--forest); margin-top:2px;">Their PIN is</div>
            <span class="new-pin-number">{{ $addedStaff['pin'] }}</span>
            <div style="font-size:11px; color:var(--forest); margin-top:6px; opacity:0.75;">Save this — you can always reset it from here.</div>
        </div>
    @endif

    @php $resetPin = session('ok_reset_pin'); @endphp

    <div class="staff-list">
        @forelse($staff as $member)
        <div>
            <div class="staff-row">
                <div class="staff-info">
                    <span class="staff-name">{{ $member->name }}</span>
                    <span class="staff-phone">{{ $member->phone }}</span>
                </div>
                <div class="staff-pin-wrap">
                    <span class="staff-pin" id="pin-{{ $member->id }}">&#9679;&#9679;&#9679;&#9679;</span>
                    <button type="button" class="pin-show-btn"
                            onclick="togglePin({{ $member->id }}, '{{ $member->pin }}')"
                            id="pin-btn-{{ $member->id }}">Show</button>
                </div>
                <form method="POST" action="{{ route('settings.staff.toggle', $member) }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="status-pill {{ $member->active ? 'active' : 'inactive' }}">
                        {{ $member->active ? 'Active' : 'Inactive' }}
                    </button>
                </form>
                <div class="staff-actions">
                    <form method="POST" action="{{ route('settings.staff.reset-pin', $member) }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="s-link">Reset PIN</button>
                    </form>
                    <button type="button" class="s-link s-link-danger"
                            onclick="showRemoveConfirm({{ $member->id }})">Remove</button>
                </div>
            </div>

            {{-- New PIN after reset --}}
            @if($resetPin && $resetPin['user_id'] == $member->id)
            <div class="new-pin-card" style="margin-bottom:12px;">
                <div class="new-pin-name">{{ $resetPin['name'] }}'s new PIN is</div>
                <span class="new-pin-number">{{ $resetPin['pin'] }}</span>
            </div>
            @endif

            {{-- Remove confirm --}}
            <div class="remove-confirm" id="remove-{{ $member->id }}">
                <span class="confirm-text">Remove {{ $member->name }}? Their shift history will be kept.</span>
                <form method="POST" action="{{ route('settings.staff.remove', $member) }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="s-save" style="background:var(--clay); height:34px; padding:0 14px; font-size:12px;">Confirm</button>
                </form>
                <button type="button" class="s-link" onclick="hideRemoveConfirm({{ $member->id }})">Cancel</button>
            </div>
        </div>
        @empty
        <p style="color:var(--muted); font-size:14px; padding:16px 0;">No staff members yet. Add your first one below.</p>
        @endforelse
    </div>

    {{-- Add staff --}}
    <button type="button" class="add-staff-toggle" onclick="toggleAddStaff()" id="add-staff-btn">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
            <path d="M7 1v12M1 7h12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
        Add staff member
    </button>

    <div class="add-staff-form" id="add-staff-form">
        <div class="s-sub-title" style="margin-bottom:16px;">New staff member</div>
        @if($errors->has('name') || $errors->has('phone') || $errors->has('pin'))
            <div class="flash-err" style="margin-bottom:14px;">
                {{ $errors->first('name') ?: ($errors->first('phone') ?: $errors->first('pin')) }}
            </div>
        @endif
        <form method="POST" action="{{ route('settings.staff.add') }}">
            @csrf
            <div class="s-grid-2">
                <div class="s-field">
                    <label class="s-label">Name</label>
                    <input type="text" name="name" class="s-input"
                           value="{{ old('name') }}" required placeholder="e.g. Grace Otieno">
                </div>
                <div class="s-field">
                    <label class="s-label">Phone</label>
                    <input type="tel" name="phone" class="s-input"
                           value="{{ old('phone') }}" required placeholder="0712 345 678">
                </div>
                <div class="s-field">
                    <label class="s-label">PIN <span style="font-weight:400; color:var(--muted);">(optional)</span></label>
                    <input type="text" name="pin" class="s-input" inputmode="numeric"
                           maxlength="6" placeholder="Leave blank to auto-generate">
                    <span class="s-helper">Leave blank to auto-generate a 4-digit PIN</span>
                </div>
            </div>
            <button type="submit" class="s-save">Add staff member</button>
        </form>
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
@if(session('ok_add_staff') || session('ok_reset_pin') || session('ok_remove'))
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('staff')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
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
