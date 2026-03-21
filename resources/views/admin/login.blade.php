<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Stoka Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
    --espresso: #1C1814;
    --terracotta: #C17F4A;
    --parchment: #FAF7F2;
    --muted: #8C8279;
    --border: #E8E2DA;
}
body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--espresso);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
}
.card {
    background: var(--parchment);
    border-radius: 20px;
    padding: 40px 36px;
    width: 100%;
    max-width: 380px;
}
.logo {
    font-family: 'Cormorant Garamond', serif;
    font-size: 28px;
    font-weight: 600;
    color: var(--espresso);
    text-align: center;
    letter-spacing: 0.04em;
    margin-bottom: 4px;
}
.sub {
    text-align: center;
    font-size: 12px;
    color: var(--muted);
    margin-bottom: 32px;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}
label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 6px;
}
input[type=password] {
    width: 100%;
    padding: 12px 14px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-family: inherit;
    font-size: 15px;
    color: var(--espresso);
    background: white;
    outline: none;
    transition: border-color 0.15s;
}
input[type=password]:focus { border-color: var(--terracotta); }
.error {
    margin-top: 8px;
    font-size: 12px;
    color: #B85C38;
    font-weight: 500;
}
button {
    margin-top: 20px;
    width: 100%;
    padding: 13px;
    background: var(--espresso);
    color: white;
    border: none;
    border-radius: 10px;
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    letter-spacing: 0.02em;
    transition: opacity 0.15s;
}
button:active { opacity: 0.8; }
</style>
</head>
<body>
<div class="card">
    <div class="logo">Stoka</div>
    <div class="sub">Super Admin</div>

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf
        <label>Password</label>
        <input type="password" name="password" autofocus autocomplete="current-password">
        @if($errors->has('password'))
            <div class="error">{{ $errors->first('password') }}</div>
        @endif
        <button type="submit">Sign in</button>
    </form>
</div>
</body>
</html>
