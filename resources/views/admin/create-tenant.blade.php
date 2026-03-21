<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Tenant — Stoka Admin</title>
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
    --forest: #4A6741;
}
body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #F0EDE8;
    min-height: 100vh;
    padding: 24px 16px 48px;
}
.top-bar {
    display: flex;
    align-items: center;
    gap: 12px;
    max-width: 560px;
    margin: 0 auto 24px;
}
.top-bar a {
    font-size: 13px;
    color: var(--muted);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 5px;
}
.top-bar a:hover { color: var(--espresso); }
h1 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 24px;
    font-weight: 600;
    color: var(--espresso);
    max-width: 560px;
    margin: 0 auto 20px;
}
.card {
    background: var(--parchment);
    border-radius: 16px;
    padding: 28px 24px;
    max-width: 560px;
    margin: 0 auto;
}
.field { margin-bottom: 18px; }
label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 6px;
}
input, select {
    width: 100%;
    padding: 11px 13px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-family: inherit;
    font-size: 14px;
    color: var(--espresso);
    background: white;
    outline: none;
    transition: border-color 0.15s;
    appearance: none;
}
input:focus, select:focus { border-color: var(--terracotta); }
.row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.hint {
    margin-top: 4px;
    font-size: 11px;
    color: var(--muted);
}
.subdomain-row {
    display: flex;
    align-items: center;
    gap: 0;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
    background: white;
    transition: border-color 0.15s;
}
.subdomain-row:focus-within { border-color: var(--terracotta); }
.subdomain-row input {
    border: none;
    border-radius: 0;
    flex: 1;
    padding: 11px 13px;
    background: transparent;
}
.subdomain-row input:focus { outline: none; }
.subdomain-suffix {
    padding: 11px 13px 11px 0;
    font-size: 13px;
    color: var(--muted);
    white-space: nowrap;
    user-select: none;
}
.field-error {
    margin-top: 5px;
    font-size: 12px;
    color: #B85C38;
    font-weight: 500;
}
.btn-submit {
    margin-top: 8px;
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
.btn-submit:active { opacity: 0.8; }
.btn-submit:disabled { opacity: 0.5; cursor: default; }
@media (max-width: 480px) {
    .row2 { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<div class="top-bar">
    <a href="/admin">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M9 2L4 7l5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Back to dashboard
    </a>
</div>

<h1>New Tenant</h1>

<div class="card">
    <form method="POST" action="{{ route('admin.tenants.store') }}" id="createForm">
        @csrf

        <div class="field">
            <label>Shop Name</label>
            <input type="text" name="shop_name" value="{{ old('shop_name') }}" placeholder="e.g. Zara Boutique Nairobi" maxlength="80" required>
            @error('shop_name')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="row2">
            <div class="field">
                <label>Owner Name</label>
                <input type="text" name="owner_name" value="{{ old('owner_name') }}" placeholder="Full name" maxlength="80" required>
                @error('owner_name')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field">
                <label>Owner Phone</label>
                <input type="tel" name="owner_phone" value="{{ old('owner_phone') }}" placeholder="+254712345678" maxlength="20" required>
                @error('owner_phone')<div class="field-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="field">
            <label>Subdomain</label>
            <div class="subdomain-row">
                <input type="text" name="subdomain" value="{{ old('subdomain') }}"
                       placeholder="zara-boutique"
                       maxlength="30" pattern="[a-z0-9\-]+"
                       title="Lowercase letters, numbers and hyphens only" required>
                <span class="subdomain-suffix">.stoka.co.ke</span>
            </div>
            <div class="hint">Lowercase, numbers and hyphens only. Cannot be changed later.</div>
            @error('subdomain')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="row2">
            <div class="field">
                <label>Currency</label>
                <select name="currency" required>
                    <option value="">Select…</option>
                    @foreach(['KES' => 'KES — Kenya Shilling', 'UGX' => 'UGX — Uganda Shilling', 'TZS' => 'TZS — Tanzania Shilling', 'RWF' => 'RWF — Rwanda Franc', 'ETB' => 'ETB — Ethiopian Birr'] as $code => $label)
                        <option value="{{ $code }}" {{ old('currency') == $code ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('currency')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field">
                <label>Plan</label>
                <select name="plan" required>
                    <option value="">Select…</option>
                    <option value="basic" {{ old('plan') == 'basic' ? 'selected' : '' }}>Basic</option>
                    <option value="pro"   {{ old('plan') == 'pro'   ? 'selected' : '' }}>Pro</option>
                </select>
                @error('plan')<div class="field-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <button type="submit" class="btn-submit" id="submitBtn">Create Tenant &amp; Send Credentials</button>
    </form>
</div>

<script>
document.getElementById('createForm').addEventListener('submit', function() {
    var btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.textContent = 'Creating…';
});
</script>
</body>
</html>
