# Stoka — CLAUDE.md

This file is the persistent memory for all Claude Code sessions working on this project.
Read it at the start of every session before touching any code.

---

## Project Overview

**Stoka** is a multi-tenant SaaS boutique management system built for Kenyan small business owners.
It gives each shop its own isolated database, subdomain, and staff login.

- **Production domain:** stoka.co.ke (not yet pointed — in use)
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
- **Central migrations path:** `database/migrations/` (standard Laravel path)

### Adding a new tenant

Insert into the `tenants` table, then insert into `domains`. The `TenancyServiceProvider` handles
DB creation and migration automatically via the `TenantCreated` event.

Example (via tinker or a controller):
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
| Owner password | `demo1234` |
| Plan | basic |
| Status | active |

Login URL: `http://demo.tempforest.com/login`

---

## Authentication System

Custom session-based auth. No Laravel Sanctum or Breeze.

**Owner** — logs in with phone + password (bcrypt).
**Staff** — logs in with phone + 6-digit PIN (plain string comparison). Deactivated staff are blocked.

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

## Directory Structure

```
/var/www/stoka/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/LoginController.php     # Login, logout
│   │   │   └── Controller.php
│   │   └── Middleware/
│   │       ├── Auth.php                     # auth.custom
│   │       └── OwnerOnly.php                # owner.only
│   ├── Models/
│   │   ├── Tenant.php                       # Central — custom columns defined here
│   │   └── User.php                         # Tenant — phone/role/pin/active
│   └── Providers/
│       ├── AppServiceProvider.php
│       └── TenancyServiceProvider.php       # Events, route mapping
├── bootstrap/
│   ├── app.php                              # Middleware aliases registered here
│   └── providers.php
├── config/
│   └── tenancy.php                          # Full tenancy config
├── database/
│   ├── migrations/                          # Central migrations (users, cache, jobs, tenants, domains)
│   └── migrations/tenant/                   # Per-tenant migrations (all schema below)
├── resources/views/
│   ├── auth/login.blade.php                 # Styled login page (inline CSS, no Vite)
│   └── welcome.blade.php                    # Central domain landing (default Laravel)
└── routes/
    ├── web.php                              # Central routes (just welcome view)
    └── tenant.php                           # All tenant routes
```

---

## Tenant Database Schema

All 15 tables below live in each tenant's isolated database.

### `users`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| name | string | |
| phone | string unique | Used as login identifier |
| role | enum(owner, staff) | default: staff |
| password | string nullable | Owner only |
| pin | string(6) nullable | Staff only |
| active | boolean | default: true |

### `suppliers`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| name | string | |
| phone | string nullable | |
| category | string nullable | |
| notes | text nullable | |

### `products`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| supplier_id | FK → suppliers nullable | nullOnDelete |
| name | string | |
| category | string nullable | |
| type | enum(unit, measured, variant) | default: unit |
| shelf_price | decimal(10,2) | Displayed selling price |
| floor_price | decimal(10,2) nullable | Minimum bargain price |
| is_bargainable | boolean | default: false |
| track_stock | boolean | default: true |
| stock | integer | default: 0 |
| low_stock_threshold | integer | default: 3 |
| low_stock_alert_sent | boolean | default: false |
| status | enum(active, inactive) | default: active |

**Product types:**
- `unit` — sold as whole units (e.g. a bag, a dress)
- `measured` — sold by ml from a bottle (linked to `product_bottles`)
- `variant` — has size/colour variants (linked to `product_variants`)

### `product_variants`
| Column | Type | Notes |
|---|---|---|
| product_id | FK → products | cascadeOnDelete |
| size | string nullable | |
| colour | string nullable | |
| stock | integer | default: 0 |

### `product_bottles`
| Column | Type | Notes |
|---|---|---|
| product_id | FK → products | cascadeOnDelete |
| total_ml | decimal(8,2) | |
| remaining_ml | decimal(8,2) | |
| price_per_ml | decimal(8,2) | |
| active | boolean | |

### `customers`
| Column | Type | Notes |
|---|---|---|
| name | string | |
| phone | string nullable | |
| total_outstanding | decimal(10,2) | default: 0 (denormalized running balance) |

### `shifts`
| Column | Type | Notes |
|---|---|---|
| staff_id | FK → users | |
| opened_at | timestamp | |
| closed_at | timestamp nullable | |
| opening_float | decimal(10,2) | Cash in till at open |
| cash_counted | decimal(10,2) nullable | Counted at close |
| mpesa_total | decimal(10,2) | Accumulated during shift |
| expected_cash | decimal(10,2) nullable | Calculated at close |
| cash_discrepancy | decimal(10,2) nullable | expected − counted |
| status | enum(open, closed) | |
| wa_sent_at | timestamp nullable | WhatsApp summary sent time |

### `sales`
| Column | Type | Notes |
|---|---|---|
| shift_id | FK → shifts | |
| staff_id | FK → users | |
| product_id | FK → products | |
| variant_id | FK → product_variants nullable | |
| bottle_id | FK → product_bottles nullable | |
| customer_id | FK → customers nullable | For credit sales |
| quantity_or_ml | decimal(10,2) | default: 1 |
| unit_price | decimal(10,2) | Listed price at time of sale |
| actual_price | decimal(10,2) | Price after bargaining |
| total | decimal(10,2) | actual_price × quantity_or_ml |
| payment_type | enum(cash, mpesa, credit) | |
| voided_at | timestamp nullable | |
| void_reason | string nullable | |

### `credit_ledger`
| Column | Type | Notes |
|---|---|---|
| customer_id | FK → customers | |
| sale_id | FK → sales nullable | |
| amount | decimal(10,2) | Original credit amount |
| paid | decimal(10,2) | Amount paid back so far |
| balance | decimal(10,2) | amount − paid |
| last_payment_at | timestamp nullable | |
| status | enum(open, settled) | |

### `restocks`
| Column | Type | Notes |
|---|---|---|
| supplier_id | FK → suppliers nullable | |
| staff_id | FK → users | Who received the stock |
| notes | text nullable | |

### `restock_items`
| Column | Type | Notes |
|---|---|---|
| restock_id | FK → restocks | |
| product_id | FK → products | |
| variant_id | FK → product_variants nullable | |
| quantity | decimal(10,2) | |
| cost_price | decimal(10,2) nullable | |

### `supplier_balances`
| Column | Type | Notes |
|---|---|---|
| supplier_id | FK → suppliers | |
| restock_id | FK → restocks nullable | |
| total_cost | decimal(10,2) | |
| amount_paid | decimal(10,2) | default: 0 |
| balance | decimal(10,2) | |
| settled_at | timestamp nullable | |

### `supplier_payments`
| Column | Type | Notes |
|---|---|---|
| supplier_id | FK → suppliers | |
| amount | decimal(10,2) | |
| notes | text nullable | |
| recorded_by | FK → users | |

### `shopping_lists`
| Column | Type | Notes |
|---|---|---|
| owner_id | FK → users | |
| sent_via_whatsapp_at | timestamp nullable | |

### `shopping_list_items`
| Column | Type | Notes |
|---|---|---|
| list_id | FK → shopping_lists | |
| product_id | FK → products | |
| variant_id | FK → product_variants nullable | |
| current_stock | integer | Snapshot at time of list creation |
| suggested_qty | integer | Auto-calculated |
| target_qty | integer | Owner-editable target |

---

## Routes

### Central (`routes/web.php`)
```
GET /   → welcome view
```

### Tenant (`routes/tenant.php`)
All tenant routes run through `InitializeTenancyBySubdomain` + `PreventAccessFromCentralDomains`.

```
GET  /login    → LoginController@showLogin
POST /login    → LoginController@login
POST /logout   → LoginController@logout

[auth.custom]
  [owner.only]
    GET /      → owner dashboard (stub)
  GET /sales   → sales screen (stub)
```

---

## Views

| File | Status | Notes |
|---|---|---|
| `resources/views/auth/login.blade.php` | Complete | Inline CSS, warm cream palette, Cormorant Garamond + Plus Jakarta Sans, mobile-friendly |
| `resources/views/welcome.blade.php` | Default | Laravel default, only shown on central domain |

**No base layout exists yet.** `layouts/app.blade.php` must be created before any Phase 2 views are built.

**No Vite build.** `public/build/` does not exist. The login page uses inline CSS so it works without Vite.
If Vite is needed later: `npm install && npm run build` from `/var/www/stoka`.

---

## Design System

The login page establishes the visual language. Use these values consistently:

| Token | Value |
|---|---|
| Background | `#FAF7F2` (warm off-white) |
| Card/surface | `#F2EDE6` |
| Border | `#E8E0D6` |
| Primary text | `#1C1814` |
| Muted text | `#8C7B6E` |
| Accent/button | `#5C3D2E` (dark brown) |
| Font — headings | Cormorant Garamond (serif), 600 |
| Font — body/UI | Plus Jakarta Sans (sans-serif), 400/500/600 |
| Border radius | 8px (inputs), 16px (cards) |

---

## Nginx Config

Stoka is served at `tempforest.com` on **port 80 only** (no SSL yet).
The config block lives in `/etc/nginx/sites-enabled/` alongside pstally and quietawareness.

```nginx
server {
    listen 80;
    server_name tempforest.com www.tempforest.com;
    root /var/www/stoka/public;
    ...
}
```

**Wildcard subdomains are not explicitly listed** — Nginx will match them via the catch-all for `tempforest.com`.
DNS must have a wildcard `*.tempforest.com` A record pointing to `187.124.32.36` for subdomain tenancy to work.

---

## Known Issues / Tech Debt

- Two stray files in project root (tinker paste artifacts): `User::create([` and `sword' => bcrypt('password123'),` — safe to delete
- No SSL on Stoka/tempforest.com
- `APP_URL` is `http://tempforest.com` — needs to become `https://stoka.co.ke` before go-live
- No Vite build compiled

---

## Build Phases

### Phase 1 — Foundation (COMPLETE)
- [x] Laravel 12 + stancl/tenancy v3.9 installed and configured
- [x] Subdomain tenancy working (one DB per tenant, auto-created on TenantCreated)
- [x] Custom session auth (owner: password, staff: PIN)
- [x] Auth + OwnerOnly middleware
- [x] Full tenant DB schema — all 15 tables migrated
- [x] Login view (styled)
- [x] Demo tenant: Zuri Boutique (`demo.tempforest.com`)
- [x] Demo owner account seeded (Amina Wanjiru / 0712345678 / demo1234)

### Phase 2 — Products (NEXT)

**Goal:** Owner can manage their product catalogue.

**Prerequisites before any Phase 2 code:**
- [ ] Create `resources/views/layouts/app.blade.php` — base layout with nav

**Features to build:**
- [ ] Products list — table of all products, filterable by category/status, with stock badge
- [ ] Add product form — name, category, type (unit/measured/variant), supplier, shelf price, floor price, bargainable, stock, low-stock threshold
- [ ] Edit product
- [ ] Toggle product active/inactive (soft disable, not delete)
- [ ] Variant management — add/remove size+colour variants per product (type=variant)
- [ ] Bottle management — add a new bottle for a measured product (type=measured)
- [ ] Supplier list + add supplier (needed before products can be linked)

**Routes to add (all owner-only):**
```
GET    /products               → index
GET    /products/create        → create form
POST   /products               → store
GET    /products/{id}/edit     → edit form
PUT    /products/{id}          → update
POST   /products/{id}/toggle   → toggle active/inactive
GET    /suppliers              → index
POST   /suppliers              → store
```

### Phase 3 — Sales (PLANNED)
Staff shift open/close, point-of-sale screen, payment types (cash/M-Pesa/credit), void a sale.

### Phase 4 — Customers & Credit (PLANNED)
Customer registry, credit ledger, record repayments.

### Phase 5 — Stock & Restocks (PLANNED)
Receive stock from supplier, update quantities, record cost, supplier balance tracking.

### Phase 6 — Reports & Dashboard (PLANNED)
Owner dashboard: daily sales totals, top products, low stock alerts, shift summaries.

### Phase 7 — WhatsApp Integration (PLANNED)
Shift close summary → WhatsApp, low-stock alerts, shopping list send.

### Phase 8 — Onboarding & Billing (PLANNED)
Self-serve tenant signup, plan limits, subscription management.


---

## Full Product Blueprint

See /var/www/stoka/BLUEPRINT.md for the complete product vision, design philosophy, all features, and build rules. Read it alongside this file at the start of every session.
