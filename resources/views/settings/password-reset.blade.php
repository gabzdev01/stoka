<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — Stoka</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: #FAF7F2;
            font-family: "Plus Jakarta Sans", sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            background: #F2EDE6;
            border-radius: 16px;
            padding: 40px 36px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 2px 24px rgba(28,24,20,0.07);
        }

        .logo {
            font-family: "Cormorant Garamond", serif;
            font-size: 32px;
            font-weight: 600;
            color: #1C1814;
            margin-bottom: 4px;
        }

        .tagline {
            font-size: 13px;
            color: #8C7B6E;
            margin-bottom: 32px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #1C1814;
            margin-bottom: 6px;
        }

        .section-sub {
            font-size: 13px;
            color: #8C7B6E;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #8C7B6E;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 7px;
        }

        input[type="tel"],
        input[type="text"],
        input[type="password"],
        input[type="number"] {
            width: 100%;
            height: 48px;
            background: #FAF7F2;
            border: 1.5px solid #DDD5C8;
            border-radius: 10px;
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 15px;
            color: #1C1814;
            padding: 0 16px;
            outline: none;
            transition: border-color 0.15s;
            margin-bottom: 18px;
        }
        input:focus { border-color: #1C1814; }

        input.code-input {
            font-family: "DM Mono", monospace;
            font-size: 22px;
            letter-spacing: 0.2em;
            text-align: center;
        }

        .btn-primary {
            width: 100%;
            height: 50px;
            background: #1C1814;
            color: #FAF7F2;
            border: none;
            border-radius: 10px;
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 6px;
            transition: opacity 0.15s;
        }
        .btn-primary:hover { opacity: 0.85; }

        .wa-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            height: 52px;
            background: #25D366;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            margin-bottom: 28px;
            transition: opacity 0.15s;
        }
        .wa-btn:hover { opacity: 0.9; }

        .step-label {
            font-size: 11px;
            font-weight: 700;
            color: #8C7B6E;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 10px;
        }

        .divider {
            height: 1px;
            background: #DDD5C8;
            margin: 28px 0;
        }

        .flash-err {
            background: #F5DDD8;
            color: #B85C38;
            border-radius: 8px;
            padding: 11px 14px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 18px;
        }

        .flash-ok {
            background: #DFF0DD;
            color: #4A6741;
            border-radius: 8px;
            padding: 11px 14px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 18px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: #8C7B6E;
            text-decoration: underline;
            text-underline-offset: 2px;
        }
        .back-link:hover { color: #1C1814; }
    </style>
</head>
<body>
<div class="card">
    <div class="logo">Stoka</div>
    <div class="tagline">{{ tenant('name') }}</div>

    @if(session('err_reset'))
        <div class="flash-err">{{ session('err_reset') }}</div>
    @endif

    @if(session('wa_url'))
        {{-- ── STATE 2: WhatsApp + code entry ── --}}
        <div class="section-title">Reset your password</div>
        <div class="section-sub">Follow the two steps below to reset your password.</div>

        <div class="step-label">Step 1 — Send the code to yourself</div>
        <a href="{{ session('wa_url') }}" target="_blank" class="wa-btn">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M10 1.667A8.333 8.333 0 0 0 2.5 13.91L1.667 18.333l4.545-.834A8.333 8.333 0 1 0 10 1.667zm0 1.666a6.667 6.667 0 1 1-3.43 12.39l-.246-.146-2.677.49.5-2.617-.157-.254A6.667 6.667 0 0 1 10 3.333zm-2.083 3.75c-.209 0-.547.079-.833.417-.286.337-1.084 1.083-1.084 2.583 0 1.5 1.084 2.917 1.25 3.167.167.25 2.084 3.25 5.084 4.417.708.292 1.25.458 1.708.583.709.209 1.375.172 1.875.104.583-.083 1.75-.709 2-1.417.25-.708.25-1.291.167-1.416-.083-.125-.292-.209-.625-.375s-1.958-.959-2.25-1.084c-.292-.125-.5-.167-.708.167-.208.333-.834 1.083-1.042 1.291-.208.209-.375.25-.666.084-.292-.167-1.25-.459-2.375-1.459-.875-.792-1.459-1.75-1.625-2.041-.167-.292-.017-.459.125-.584.125-.125.292-.333.417-.5.125-.166.167-.291.25-.5.084-.208.042-.375-.042-.542-.083-.166-.708-1.708-.979-2.333-.25-.583-.5-.5-.708-.5z" fill="currentColor"/>
            </svg>
            Open WhatsApp to send your code
        </a>

        <div class="step-label">Step 2 — Enter the 6-digit code</div>

        @if(session('err_confirm'))
            <div class="flash-err">{{ session('err_confirm') }}</div>
        @endif

        <form method="POST" action="{{ route('password-reset.confirm') }}">
            @csrf
            <label>Reset code</label>
            <input type="text" name="code" class="code-input"
                   maxlength="6" inputmode="numeric"
                   placeholder="000000"
                   value="{{ old('code') }}" required>

            <label>New password</label>
            <input type="password" name="new_password"
                   autocomplete="new-password"
                   placeholder="At least 6 characters" required>

            <label>Confirm new password</label>
            <input type="password" name="new_password_confirmation"
                   autocomplete="new-password"
                   placeholder="Repeat new password" required>

            <button type="submit" class="btn-primary">Set new password</button>
        </form>

        <div class="divider"></div>
        <p style="font-size:12px; color:#8C7B6E; text-align:center; line-height:1.5;">
            Code not received? <a href="{{ route('password-reset.show') }}" style="color:#1C1814; text-decoration:underline;">Request a new one</a>
        </p>

    @else
        {{-- ── STATE 1: Phone entry ── --}}
        <div class="section-title">Forgot your password?</div>
        <div class="section-sub">Enter your owner phone number and we'll send a reset code via WhatsApp.</div>

        <form method="POST" action="{{ route('password-reset.request') }}">
            @csrf
            <label>Phone number</label>
            <input type="tel" name="phone"
                   value="{{ old('phone') }}"
                   placeholder="e.g. 0712 345 678" required
                   autofocus>

            <button type="submit" class="btn-primary">Send reset code</button>
        </form>
    @endif

    <a href="{{ route('login') }}" class="back-link">&larr; Back to login</a>
</div>
</body>
</html>
