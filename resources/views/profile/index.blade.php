@extends('layouts.staff')

@section('title', 'My Profile')

@section('styles')
<style>
.profile-wrap {
    max-width: 480px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.p-card {
    background: #fff;
    border: 1px solid #DDD5C8;
    border-radius: 14px;
    padding: 24px;
}

.p-card-title {
    font-size: 11px;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 18px;
}

.p-name {
    font-family: "Cormorant Garamond", serif;
    font-size: 28px;
    font-weight: 600;
    color: var(--espresso);
    margin-bottom: 6px;
}

.p-phone {
    font-family: "DM Mono", monospace;
    font-size: 14px;
    color: var(--muted);
}

.p-role {
    display: inline-flex;
    align-items: center;
    margin-top: 10px;
    background: var(--surface);
    border-radius: var(--radius-full);
    padding: 3px 12px;
    font-size: 11px;
    font-weight: 700;
    color: var(--mid);
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.p-field { margin-bottom: 16px; }
.p-field:last-child { margin-bottom: 0; }
.p-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 6px;
}
.p-input {
    width: 100%;
    height: 48px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-md);
    background: var(--surface);
    font-family: "DM Mono", monospace;
    font-size: 20px;
    letter-spacing: 0.2em;
    color: var(--espresso);
    padding: 0 14px;
    outline: none;
    transition: border-color 0.15s;
    box-sizing: border-box;
}
.p-input:focus { border-color: var(--espresso); }

.p-save {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 48px;
    padding: 0 24px;
    background: var(--espresso);
    color: #fff;
    border: none;
    border-radius: var(--radius-md);
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    width: 100%;
    justify-content: center;
    transition: opacity 0.13s;
    margin-top: 4px;
}
.p-save:hover { opacity: 0.85; }

.flash-ok {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(74,103,65,0.12);
    color: var(--forest);
    border-radius: var(--radius-md);
    padding: 11px 14px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 16px;
}
.p-logout {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    height: 48px;
    background: transparent;
    border: 1.5px solid rgba(184,92,56,0.25);
    border-radius: var(--radius-md);
    color: var(--clay);
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.13s, border-color 0.13s;
    -webkit-tap-highlight-color: transparent;
}
.p-logout:active { background: rgba(184,92,56,0.07); border-color: rgba(184,92,56,0.4); }

.flash-err {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(184,92,56,0.1);
    color: var(--clay);
    border-radius: var(--radius-md);
    padding: 11px 14px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 16px;
}
</style>
@endsection

@section('content')
<div class="profile-wrap">

    {{-- Identity card --}}
    <div class="p-card">
        <div class="p-card-title">My profile</div>
        <div class="p-name">{{ $user->name }}</div>
        <div class="p-phone">{{ $user->phone }}</div>
        <div class="p-role">{{ ucfirst($user->role) }}</div>
    </div>

    {{-- Change PIN --}}
    <div class="p-card">
        <div class="p-card-title">Change PIN</div>

        @if(session('ok_pin'))
            <div class="flash-ok">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2.5 7l3.5 3.5 5.5-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                {{ session('ok_pin') }}
            </div>
        @endif
        @if(session('err_pin'))
            <div class="flash-err">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M7 4v4M7 9.5v.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                {{ session('err_pin') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.pin') }}">
            @csrf
            <div class="p-field">
                <label class="p-label">Current PIN</label>
                <input type="password" name="current_pin" class="p-input"
                       inputmode="numeric" maxlength="6"
                       autocomplete="current-password"
                       placeholder="&#9679;&#9679;&#9679;&#9679;" required>
            </div>
            <div class="p-field">
                <label class="p-label">New PIN</label>
                <input type="password" name="new_pin" class="p-input"
                       inputmode="numeric" maxlength="6"
                       autocomplete="new-password"
                       placeholder="&#9679;&#9679;&#9679;&#9679;" required>
            </div>
            <div class="p-field">
                <label class="p-label">Confirm new PIN</label>
                <input type="password" name="new_pin_confirmation" class="p-input"
                       inputmode="numeric" maxlength="6"
                       autocomplete="new-password"
                       placeholder="&#9679;&#9679;&#9679;&#9679;" required>
            </div>
            <button type="submit" class="p-save">Change PIN</button>
        </form>
    </div>

    {{-- Logout --}}
    <div class="p-card" style="padding:20px 24px;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="p-logout">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2H3a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h3"/>
                    <polyline points="11 11 14 8 11 5"/>
                    <line x1="14" y1="8" x2="6" y2="8"/>
                </svg>
                Log out
            </button>
        </form>
    </div>

</div>
@endsection
