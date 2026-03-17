<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stoka — Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
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
            max-width: 400px;
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
            margin-bottom: 36px;
        }

        .shop-name {
            font-size: 15px;
            font-weight: 600;
            color: #1C1814;
            margin-bottom: 28px;
            padding: 10px 14px;
            background: #FAF7F2;
            border-radius: 8px;
            border: 1px solid #E8E0D6;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #8C7B6E;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            background: #FAF7F2;
            border: 1px solid #E8E0D6;
            border-radius: 8px;
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 15px;
            color: #1C1814;
            outline: none;
            transition: border-color 0.2s;
            margin-bottom: 6px;
        }

        input:focus {
            border-color: #C17F4A;
        }

        input.error {
            border-color: #B85C38;
        }

        .error-msg {
            font-size: 12px;
            color: #B85C38;
            margin-bottom: 16px;
        }

        .field {
            margin-bottom: 20px;
        }

        .hint {
            font-size: 12px;
            color: #8C7B6E;
            margin-bottom: 28px;
        }

        .btn {
            width: 100%;
            padding: 14px;
            background: #1C1814;
            color: #FAF7F2;
            border: none;
            border-radius: 8px;
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn:hover {
            background: #2C1F14;
        }

        .footer {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: #8C7B6E;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">Stoka</div>
        <div class="tagline">Boutique management, simplified.</div>

        <div class="shop-name">{{ tenant("name") }}</div>

        <form method="POST" action="/login">
            @csrf

            <div class="field">
                <label>Phone Number</label>
                <input
                    type="tel"
                    name="phone"
                    value="{{ old("phone") }}"
                    placeholder="07XX XXX XXX"
                    class="{{ $errors->has("phone") ? "error" : "" }}"
                    autocomplete="tel"
                >
                @if($errors->has("phone"))
                    <div class="error-msg">{{ $errors->first("phone") }}</div>
                @endif
            </div>

            <div class="field">
                <label>Password or PIN</label>
                <input
                    type="password"
                    name="credential"
                    placeholder="Password (owner) or 4-digit PIN (staff)"
                    class="{{ $errors->has("credential") ? "error" : "" }}"
                    autocomplete="current-password"
                >
                @if($errors->has("credential"))
                    <div class="error-msg">{{ $errors->first("credential") }}</div>
                @endif
            </div>

            <div class="hint">Owners use their password. Staff use their 4-digit PIN.</div>

            <button type="submit" class="btn">Sign In</button>
        </form>

        <div class="footer">Stoka &copy; {{ date("Y") }}</div>
    </div>
</body>
</html>
