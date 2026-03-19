@extends('layouts.staff')

@section('title', 'Open Shift')

@section('styles')
<style>
    .open-shift-page {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: calc(100dvh - var(--top-h) - var(--tab-h));
        padding: 0 28px 32px;
    }

    .os-inner {
        width: 100%;
        max-width: 360px;
        display: flex;
        flex-direction: column;
        gap: 0;
        animation: osIn 0.5s cubic-bezier(0.22, 1, 0.36, 1) both;
    }

    @keyframes osIn {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Clock ── */
    .os-clock {
        font-family: "DM Mono", monospace;
        font-size: 13px;
        font-weight: 400;
        color: var(--muted);
        letter-spacing: 0.04em;
        margin-bottom: 20px;
    }

    /* ── Greeting + name ── */
    .os-greeting {
        font-size: 12px;
        font-weight: 600;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 6px;
    }

    .os-name {
        font-family: "Cormorant Garamond", Georgia, serif;
        font-size: 46px;
        font-weight: 600;
        color: var(--espresso);
        line-height: 1.0;
        letter-spacing: -0.01em;
        margin-bottom: 36px;
    }

    /* ── Divider ── */
    .os-rule {
        border: none;
        border-top: 1px solid var(--border);
        margin-bottom: 32px;
    }

    /* ── Float section ── */
    .os-float-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 12px;
        display: block;
    }

    .os-amount-row {
        display: flex;
        align-items: baseline;
        gap: 10px;
        margin-bottom: 8px;
    }

    .os-currency {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 18px;
        font-weight: 600;
        color: var(--muted);
        flex-shrink: 0;
        padding-bottom: 6px;
    }

    .os-amount-input {
        font-family: "DM Mono", monospace;
        font-size: 52px;
        font-weight: 400;
        color: var(--espresso);
        border: none;
        border-bottom: 2px solid var(--border);
        background: transparent;
        outline: none;
        width: 100%;
        padding: 0 0 6px;
        line-height: 1;
        -webkit-appearance: none;
        appearance: none;
        transition: border-color 0.2s;
    }
    .os-amount-input:focus { border-bottom-color: var(--espresso); }
    .os-amount-input::placeholder { color: var(--border); }
    .os-amount-input::-webkit-outer-spin-button,
    .os-amount-input::-webkit-inner-spin-button { -webkit-appearance: none; }
    .os-amount-input[type=number] { -moz-appearance: textfield; }

    .os-hint {
        font-size: 12px;
        color: var(--muted);
        line-height: 1.5;
        margin-bottom: 36px;
    }

    /* ── Start button ── */
    .os-start-btn {
        width: 100%;
        height: 58px;
        background: var(--espresso);
        color: rgba(255,255,255,0.92);
        border: none;
        border-radius: 14px;
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 15px;
        font-weight: 600;
        letter-spacing: 0.02em;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: opacity 0.13s;
        -webkit-tap-highlight-color: transparent;
    }
    .os-start-btn:active { opacity: 0.78; }
    .os-start-arrow {
        font-size: 18px;
        opacity: 0.6;
        transition: transform 0.2s;
    }
    .os-start-btn:active .os-start-arrow { transform: translateX(4px); }
</style>
@endsection

@section('content')
<div class="open-shift-page">
    <div class="os-inner">

        <div class="os-clock" id="os-clock"></div>

        <div class="os-greeting">{{ $greeting }},</div>
        <div class="os-name">{{ explode(' ', trim(session('auth_name')))[0] }}</div>

        <hr class="os-rule">

        <form method="POST" action="{{ route('shifts.open') }}">
            @csrf

            <label class="os-float-label" for="opening_float">Opening float</label>

            <div class="os-amount-row">
                <span class="os-currency">{{ tenant('currency_symbol') }}</span>
                <input
                    class="os-amount-input"
                    type="number"
                    id="opening_float"
                    name="opening_float"
                    value="{{ old('opening_float', '0') }}"
                    min="0"
                    step="1"
                    inputmode="numeric"
                    autocomplete="off"
                    autofocus
                >
            </div>

            @error('opening_float')
                <p style="color:var(--clay);font-size:12.5px;margin:4px 0 8px;">{{ $message }}</p>
            @enderror

            <p class="os-hint">Count the notes in the till. Type 0 if it's empty — that's fine.</p>

            <button type="submit" class="os-start-btn">
                Open shift
                <span class="os-start-arrow">&#8594;</span>
            </button>
        </form>

    </div>
</div>

<script>
(function () {
    var el = document.getElementById('os-clock');
    if (!el) return;
    function tick() {
        var now = new Date();
        var h   = now.getHours();
        var m   = now.getMinutes().toString().padStart(2, '0');
        var ampm = h >= 12 ? 'PM' : 'AM';
        var h12  = h % 12 || 12;
        var days  = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        el.textContent = days[now.getDay()] + ' ' + now.getDate() + ' ' + months[now.getMonth()]
            + '  \u00b7  ' + h12 + ':' + m + ' ' + ampm;
    }
    tick();
    setInterval(tick, 10000);
}());
</script>
@endsection
