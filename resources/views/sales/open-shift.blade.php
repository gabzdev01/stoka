@extends('layouts.staff')

@section('title', 'Open Shift')

@section('styles')
<style>
    /* ── Full-height centred layout ─────────────── */
    .open-shift-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: calc(100dvh - var(--top-h) - var(--tab-h) - 40px);
        padding: 8px 0 24px;
    }

    /* ── Card ────────────────────────────────────── */
    .open-shift-card {
        width: 100%;
        max-width: 400px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 36px 28px 32px;
        display: flex;
        flex-direction: column;
        gap: 28px;
    }

    /* ── Header ──────────────────────────────────── */
    .open-shift-header {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .open-shift-greeting {
        font-size: 13px;
        font-weight: 600;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .open-shift-name {
        font-family: "Cormorant Garamond", serif;
        font-size: 32px;
        font-weight: 600;
        color: var(--espresso);
        line-height: 1.1;
    }

    .open-shift-sub {
        font-size: 13.5px;
        color: var(--muted);
        margin-top: 4px;
        line-height: 1.5;
    }

    /* ── Float field ─────────────────────────────── */
    .float-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 10px;
    }

    .float-input-wrap {
        display: flex;
        align-items: center;
        background: var(--bg);
        border: 2px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        transition: border-color 0.15s;
    }

    .float-input-wrap:focus-within {
        border-color: var(--espresso);
    }

    .float-prefix {
        padding: 0 14px 0 16px;
        font-family: "DM Mono", monospace;
        font-size: 15px;
        font-weight: 500;
        color: var(--muted);
        white-space: nowrap;
        border-right: 1px solid var(--border);
        height: 56px;
        display: flex;
        align-items: center;
        flex-shrink: 0;
        background: var(--surface);
    }

    .float-input {
        flex: 1;
        height: 56px;
        border: none;
        background: transparent;
        font-family: "DM Mono", monospace;
        font-size: 22px;
        font-weight: 500;
        color: var(--espresso);
        padding: 0 16px;
        outline: none;
        min-width: 0;
        -webkit-appearance: none;
        appearance: none;
    }

    .float-input::placeholder {
        color: var(--border);
    }

    /* hide browser number spinners */
    .float-input::-webkit-outer-spin-button,
    .float-input::-webkit-inner-spin-button { -webkit-appearance: none; }
    .float-input[type=number] { -moz-appearance: textfield; }

    .float-hint {
        margin-top: 8px;
        font-size: 12px;
        color: var(--muted);
        line-height: 1.4;
    }

    /* ── Start button ────────────────────────────── */
    .start-btn {
        width: 100%;
        height: 56px;
        background: var(--espresso);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 0.02em;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        transition: opacity 0.13s, background 0.13s;
        -webkit-tap-highlight-color: transparent;
    }

    .start-btn:active {
        opacity: 0.82;
    }
</style>
@endsection

@section('content')
<div class="open-shift-wrap">
    <div class="open-shift-card">

        {{-- Header --}}
        <div class="open-shift-header">
            <span class="open-shift-greeting">{{ $greeting }},</span>
            <span class="open-shift-name">{{ session('auth_name') }}</span>
            <p class="open-shift-sub">Count the notes and coins in the till, then type the amount below.</p>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('shifts.open') }}">
            @csrf

            <label class="float-label" for="opening_float">Opening float</label>

            <div class="float-input-wrap">
                <span class="float-prefix">{{ tenant('currency_symbol') }}</span>
                <input
                    class="float-input"
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
                <p style="color:var(--clay);font-size:12.5px;margin-top:7px;">{{ $message }}</p>
            @enderror

            <p class="float-hint">Type 0 if you're starting with an empty till — that's fine.</p>

            <div style="margin-top:24px;">
                <button type="submit" class="start-btn">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <circle cx="9" cy="9" r="7.5" stroke="currentColor" stroke-width="1.6"/>
                        <path d="M7 6l5 3-5 3V6z" fill="currentColor"/>
                    </svg>
                    Open Shift
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
