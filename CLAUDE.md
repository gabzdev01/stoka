# Stoka — CLAUDE.md

This file is the persistent memory for all Claude Code sessions working on this project.
Read it at the start of every session before touching any code.

---

## Project Overview

**Stoka** is a multi-tenant SaaS boutique management system built for Kenyan small business owners.
It gives each shop its own isolated database, subdomain, and staff login.

- **Production domain:** stoka.co.ke (not yet pointed — pending SSL)
- **Dev/staging domain:** tempforest.com
- **Server:** 187.124.32.36
- **SSH:** `ssh root@187.124.32.36` (passwordless)
- **Project path:** `/var/www/stoka`
- **Other projects on server (do NOT touch):** `/var/www/pstally`, `/var/www/quietawareness`

---

## Stack

| Layer | Detail |
|---|---|
| Framework | Laravel 12 |
| PHP | 8.2 |
| Tenancy | stancl/tenancy v3.9 |
| Database | MySQL — one DB per tenant |
| Auth | Custom session-based (no Sanctum/Breeze) |
| Web server | Nginx |
| Frontend | Blade + inline CSS (no Vite build yet) |
| Queue | Sync (no worker needed for now) |
| Cache | File |
| Sessions | File |

---

## Tenancy Architecture

- **Strategy:** subdomain identification (`InitializeTenancyBySubdomain`)
- **Central domains:** `127.0.0.1`, `localhost`, `tempforest.com`, `www.tempforest.com`
- **Central DB:** `stoka` (MySQL user: `stoka`, password: `stoka1234`)
- **Tenant DB naming:** `tenant{id}` — e.g. tenant with id `demo` → DB `tenantdemo`
- **On TenantCreated:** auto-runs `CreateDatabase` + `MigrateDatabase` (synchronous)
- **On TenantDeleted:** auto-runs `DeleteDatabase`
- **Tenant migrations path:** `database/migrations/tenant/`
- **Central migrations path:** `database/migrations/` (standard Laravel path — run with `php artisan migrate`)

### CRITICAL: `getCustomColumns()` in Tenant.php
ALL non-standard columns on the `tenants` table MUST be listed in `Tenant::getCustomColumns()`.
Without this, stancl/tenancy stores values in the JSON `data` column instead of real DB columns,
breaking `tenant('column_name')` access.

Current custom columns (all must be in `getCustomColumns()`):
`name`, `owner_name`, `owner_phone`, `owner_whatsapp`, `plan`, `status`,
`default_low_stock_threshold`, `currency`, `operating_hours_open`, `operating_hours_close`,
`shop_location`, `shop_description`, `receipt_digital`, `receipt_print`, `receipt_footer`,
`notify_shift_close`, `notify_low_stock`, `notify_credit_overdue`,
`password_reset_token`, `password_reset_expires_at`, `wa_sent_at`

### Adding a new tenant

Insert into the `tenants` table, then insert into `domains`. The `TenancyServiceProvider` handles
DB creation and migration automatically via the `TenantCreated` event.

```php
$tenant = Tenant::create([
    'id'             => 'acme',
    'name'           => 'Acme Boutique',
    'owner_name'     => 'Jane Doe',
    'owner_phone'    => '0700000000',
    'owner_whatsapp' => '0700000000',
    'plan'           => 'basic',
    'status'         => 'active',
]);
$tenant->domains()->create(['domain' => 'acme.tempforest.com']);
```

Then seed the owner user into the tenant DB:
```php
tenancy()->initialize($tenant);
User::create([
    'name'     => 'Jane Doe',
    'phone'    => '0700000000',
    'role'     => 'owner',
    'password' => bcrypt('their_password'),
    'active'   => true,
]);
tenancy()->end();
```

---

## Credentials & Demo Tenant

### Central DB
- Host: `127.0.0.1:3306`
- DB: `stoka`
- User: `stoka`
- Password: `stoka1234`

### Demo Tenant
| Field | Value |
|---|---|
| Tenant ID | `demo` |
| Shop name | Zuri Boutique |
| DB | `tenantdemo` |
| Subdomain | `demo.tempforest.com` |
| Owner name | Amina Wanjiru |
| Owner phone | `0712345678` |
| Owner WhatsApp | `0712345678` |
| Owner password | `demo1234` |
| Plan | basic |
| Status | active |

Login URL: `http://demo.tempforest.com/login`

---

## Authentication System

Custom session-based auth. No Laravel Sanctum or Breeze.

**Owner** — logs in with phone + password (bcrypt).
**Staff** — logs in with phone + 4-digit PIN (plain string comparison). Deactivated staff are blocked.

Session keys stored on login:
- `auth_user` — user ID
- `auth_role` — `owner` or `staff`
- `auth_name` — display name

### Middleware

| Alias | Class | Behaviour |
|---|---|---|
| `auth.custom` | `App\Http\Middleware\Auth` | Redirects unauthenticated → `/login` |
| `owner.only` | `App\Http\Middleware\OwnerOnly` | Redirects non-owner → `/sales` |

### Route protection pattern
```php
Route::middleware('auth.custom')->group(function () {
    Route::middleware('owner.only')->group(function () {
        // owner-only routes
    });
    // staff + owner routes
});
```

---

## What Has Been Built

### Core
- Laravel 12 + stancl/tenancy v3.9, subdomain tenancy, one DB per tenant
- Custom session auth (owner: password, staff: PIN)
- Auth + OwnerOnly middleware
- Full tenant DB schema (15+ tables)
- Demo tenant: Zuri Boutique (demo.tempforest.com)

### Products
- Products list with stock badges, category/status filter
- Add/edit product (unit/measured/variant types)
- Toggle product active/inactive
- Variant management (size + colour)
- Supplier list + add supplier

### Sales (POS)
- Staff opens shift with float
- Point-of-sale screen: search products, cart, bargaining
- Payment types: cash, M-Pesa, credit (with partial payment)
- Cart checkout (multi-item), stock updates
- Receipt generation: digital (WhatsApp wa.me) + print (/sales/receipt/{ids})
- Void a sale (with reason)
- Sales history by shift
- Active shift view

### Customers & Credit
- Customer auto-created on first credit sale
- Credit ledger: outstanding balance tracking
- Record repayments
- `/credit` — credit ledger overview

### Stock & Restocks
- Receive stock from supplier (restock form)
- Stock increments per product/variant
- Supplier balance tracking
- Supplier payment recording
- `/shopping-list` — colour-coded by urgency, supplier filter, WhatsApp send
- `/restocks` — restock history
- `/supplier-balances` — payment recording

### Dashboard
- Owner dashboard: daily totals, top products, low stock alerts, shift summaries
- Open shift status
- Credit outstanding

### Shifts
- Shift history list
- Shift detail view (reconciliation)
- Shift close → summary page → WhatsApp share via wa.me link

### WhatsApp (Phase 6)
- Shift close report: wa.me pre-filled link (no API — user taps to send)
- Message format: time-of-day greeting, first names, Ksh amounts, balanced/short/over
- Receipt sharing: wa.me link after cart checkout (if receipt_digital = true)
- WhatsAppService::send() returns true immediately (no AT API calls)

### Settings Page (/settings — owner only)
- **My Account**: owner_name, owner_phone, owner_whatsapp, password change
- **My Shop**: name, location, description, operating hours, currency dropdown
- **My Staff**: list with PIN show/hide, status toggle, reset PIN, remove, add staff inline
- **Receipts**: digital/print toggles, footer message
- **Alerts**: shift close/low stock/credit overdue toggles, low stock threshold
- **My Data**: CSV export (sales, credits, products)

### Staff Profile (/profile)
- Staff can view their name/phone and change their PIN

### Password Reset via WhatsApp
- Public routes (no auth required)
- Owner enters phone → 6-digit code generated → stored in tenant.password_reset_token
- wa.me link pre-fills code to owner's WhatsApp (manual copy)
- Owner enters code + new password → confirmed

---

## Tenant Columns (Central DB — `tenants` table)

Standard: `id`, `data`, `created_at`, `updated_at`

Custom (all in `getCustomColumns()`):
| Column | Type | Default |
|---|---|---|
| name | string | |
| owner_name | string | |
| owner_phone | string | |
| owner_whatsapp | string | |
| plan | string | basic |
| status | string | active |
| default_low_stock_threshold | integer | 3 |
| currency | string | KES |
| operating_hours_open | string | nullable |
| operating_hours_close | string | nullable |
| shop_location | string | nullable |
| shop_description | text | nullable |
| receipt_digital | boolean | true |
| receipt_print | boolean | false |
| receipt_footer | string | nullable |
| notify_shift_close | boolean | true |
| notify_low_stock | boolean | true |
| notify_credit_overdue | boolean | true |
| password_reset_token | string | nullable |
| password_reset_expires_at | timestamp | nullable |
| wa_sent_at | timestamp | nullable |

**Currency symbol accessor:** `tenant('currency_symbol')` — calls `getCurrencySymbolAttribute()`:
- KES → Ksh, UGX → USh, TZS → TSh, RWF → RWF, ETB → ETB

---

## Routes

### Central (`routes/web.php`)
```
GET /   → welcome view
```

### Tenant (`routes/tenant.php`)

Public (no auth):
```
GET  /login                  → LoginController@showLogin
POST /login                  → LoginController@login
POST /logout                 → LoginController@logout
GET  /password-reset          → PasswordResetController@show
POST /password-reset/request  → PasswordResetController@requestReset
POST /password-reset/confirm  → PasswordResetController@confirm
```

Authenticated (auth.custom):
```
GET  /                        → owner dashboard
GET  /sales                   → SalesController@index (POS screen)
POST /sales                   → SalesController@store (single item)
POST /sales/cart              → SalesController@storeCart (cart checkout)
GET  /sales/shift             → SalesController@activeShift
GET  /sales/history           → SalesController@history
POST /sales/{sale}/void       → SalesController@void
GET  /sales/receipt/{ids}     → SalesController@receipt (print receipt)
GET  /customers/lookup        → SalesController@customerLookup
GET  /credit                  → CreditController@index
POST /credit/{entry}/payment  → CreditController@recordPayment
GET  /products                → ProductController@index
GET  /products/create         → ProductController@create
POST /products                → ProductController@store
GET  /products/{id}/edit      → ProductController@edit
PUT  /products/{id}           → ProductController@update
POST /products/{id}/toggle    → ProductController@toggle
GET  /suppliers               → SupplierController@index
POST /suppliers               → SupplierController@store
GET  /shifts                  → ShiftsController@index
GET  /shifts/open             → ShiftsController@openForm
POST /shifts/open             → ShiftsController@open
GET  /shifts/close            → ShiftsController@closeForm
POST /shifts/close            → ShiftsController@close
GET  /shifts/{shift}/summary  → ShiftsController@summary
GET  /shifts/{shift}          → ShiftsController@show
GET  /restocks                → RestockController@index
GET  /restocks/create         → RestockController@create
POST /restocks                → RestockController@store
GET  /supplier-balances       → SupplierBalanceController@index
POST /supplier-balances/{id}/pay → SupplierBalanceController@recordPayment
GET  /shopping-list           → ShoppingListController@index
POST /shopping-list           → ShoppingListController@store
GET  /profile                 → ProfileController@index
POST /profile/pin             → ProfileController@updatePin
```

Owner only (owner.only within auth.custom):
```
GET  /dashboard               → DashboardController@index
GET  /settings                → SettingsController@index
POST /settings/account        → SettingsController@saveAccount
POST /settings/password       → SettingsController@changePassword
POST /settings/shop           → SettingsController@saveShop
POST /settings/staff          → SettingsController@addStaff
POST /settings/staff/{id}/toggle  → SettingsController@toggleStaffStatus
POST /settings/staff/{id}/pin     → SettingsController@resetStaffPin
POST /settings/staff/{id}/remove  → SettingsController@removeStaff
POST /settings/receipts       → SettingsController@saveReceipts
POST /settings/alerts         → SettingsController@saveAlerts
GET  /settings/export         → SettingsController@export
```

---

## Views

| File | Status |
|---|---|
| `auth/login.blade.php` | Complete |
| `layouts/app.blade.php` | Complete (owner layout with sidebar nav) |
| `layouts/staff.blade.php` | Complete (staff layout with top bar, cart float, bottom sheet) |
| `dashboard.blade.php` | Complete |
| `products/index.blade.php` | Complete |
| `products/form.blade.php` | Complete |
| `suppliers/index.blade.php` | Complete |
| `sales/index.blade.php` | Complete (full POS screen) |
| `sales/shift.blade.php` | Complete |
| `sales/history.blade.php` | Complete |
| `sales/open-shift.blade.php` | Complete |
| `sales/close.blade.php` | Complete |
| `sales/receipt.blade.php` | Complete (print-optimised, auto-print) |
| `shifts/index.blade.php` | Complete |
| `shifts/show.blade.php` | Complete |
| `shifts/summary.blade.php` | Complete (WhatsApp share button) |
| `credit/index.blade.php` | Complete |
| `restocks/create.blade.php` | Complete |
| `restocks/index.blade.php` | Complete |
| `supplier-balances/index.blade.php` | Complete |
| `shopping-list/index.blade.php` | Complete |
| `settings/index.blade.php` | Complete (6 sections) |
| `settings/password-reset.blade.php` | Complete |
| `profile/index.blade.php` | Complete |

---

## Design System

| Token | Value |
|---|---|
| Background | `#FAF7F2` (warm off-white / parchment) |
| Card/surface | `#F2EDE6` |
| Border | `#E8E0D6` |
| Primary text | `#1C1814` |
| Muted text | `#8C7B6E` |
| Accent button | `#5C3D2E` (dark espresso brown) |
| Forest green | `#4A6741` (positive actions, WhatsApp) |
| Clay | `#B85C38` (warnings, credit, short) |
| Font — headings | Cormorant Garamond (serif), 600 |
| Font — body/UI | Plus Jakarta Sans (sans-serif), 400/500/600 |
| Font — numbers | DM Mono (monospace) |
| Border radius | 8px (inputs), 12px (buttons), 16px (cards) |

**Currency display:** Always use `{{ tenant('currency_symbol') }}` — never hardcode "Ksh" or "KSh" in Blade views.

---

## Nginx Config

Stoka is served at `tempforest.com` on **port 80 only** (no SSL yet).

```nginx
server {
    listen 80;
    server_name tempforest.com www.tempforest.com;
    root /var/www/stoka/public;
    ...
}
```

DNS: wildcard `*.tempforest.com` A record → `187.124.32.36`

---

## Known Issues / Tech Debt

- No SSL on Stoka/tempforest.com
- `APP_URL` is `http://tempforest.com` — needs `https://stoka.co.ke` before go-live
- No Vite build compiled (all CSS is inline in Blade)
- Two stray files in project root (tinker paste artifacts): `User::create([` and `sword' => bcrypt(...)` — safe to delete
- `buildCreditSaleMessage()` stub remains in SalesController (dead code, can be removed)

---

## Build Phases

### Phase 1 — Foundation ✅ COMPLETE
Laravel 12 + tenancy, custom auth, full DB schema, demo tenant

### Phase 2 — Products ✅ COMPLETE
Product catalogue, suppliers, variants, bottles

### Phase 3 — Sales ✅ COMPLETE
Shift open/close, POS screen, cart checkout, void

### Phase 4 — Customers & Credit ✅ COMPLETE
Customer registry, credit ledger, repayments

### Phase 5 — Stock & Restocks ✅ COMPLETE
Restock form, supplier balances, shopping list

### Phase 6 — WhatsApp via wa.me ✅ COMPLETE
Shift close report via wa.me (no API), receipt sharing, human Kenyan English copy

### Settings Page ✅ COMPLETE
All 6 sections, staff profile, password reset via WhatsApp

### Remaining

- **Demo reset** — daily midnight artisan command to reset demo tenant data
- **Public shop page** (`/shop`) — browse-only product view for customers
- **SSL** — stoka.co.ke domain + Certbot
- **Exchanges** — even/uneven item exchanges
- **stoka.co.ke marketing site** — landing page
- **Onboarding & billing** — self-serve tenant signup, plan limits, subscriptions

---

## Full Product Blueprint

See `/var/www/stoka/BLUEPRINT.md` for the complete product vision, design philosophy, all features, and build rules. Read it alongside this file at the start of every session.
