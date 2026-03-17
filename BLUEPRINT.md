# Stoka — Project Blueprint v3

## 1. Vision & Purpose

Stoka is a multi-tenant SaaS boutique management system built for Kenyan small business owners who currently run their shops with notebooks. The owner cannot see what is actually happening when they are not there. Stoka makes every stock movement and every sale visible — tied to a named person, during a defined shift. The truth is in the system. Nobody needs to be accused. The numbers speak.

**The soul:** Accountability through visibility, not surveillance. The system is so transparent that the right behaviour becomes the path of least resistance.

Target market: boutique owners across Kenya, single or multi-location shops with 1–10 staff, currently managing inventory and sales manually.

## 2. Problems Being Solved

- Missing stock: Staff sold without recording and pocketed the cash
- Cash shortfall: No sale record means no number to compare against
- No visibility: Owner not at shop, staff summarise verbally
- No data: Every decision made by feel
- Untracked credit: Notebook gets lost, money unrecovered

## 3. Product Types

- **Unit items:** Clothes, shoes, bags, caps, jewelry. Tracked by quantity. Optional size/colour variants.
- **Measured items:** Perfumes sold by ml. Tracked by volume remaining in bottle. Price per ml. Each sale deducts ml.
- **Variant items:** Clothes with sizes — S/M/L/XL each tracked independently.

## 4. Pricing Model

- **Fixed price:** One price. Staff cannot change it.
- **Bargainable:** Shelf price (opening ask) + floor price (bei ya mwisho). Staff sells between floor and shelf. Owner sees every below-shelf sale.
- **Price override:** Go below floor with mandatory reason. Logged and visible to owner.
- **Floor price display:** Visible to staff but NOT obvious to customer looking across the counter. Subtle indicator only.

## 5. Staff Sale Flow — THE 3-TAP RULE

Tap product → confirm quantity + price → select payment type → done.
Every common action in the staff interface must be reachable in THREE TAPS OR FEWER. This is a HARD constraint. If slower than a notebook, staff will not use it.

Payment types:
- **Cash:** Against shift. In end-of-shift reconciliation.
- **M-Pesa:** Recorded separately. Cannot be pocketed.
- **Credit:** Customer name and phone required. Auto WhatsApp confirmation sent to customer. Prevents fake entries.

Void: Any sale can be voided within same shift with mandatory reason. Visible to owner, not deleted.

## 6. Shift System

Every sale tied to a named staff member during a defined shift.
- Open shift: Staff logs in, records opening float
- During shift: All sales, exchanges, credit, cashouts recorded in real time
- Close shift: Staff counts till. System shows expected vs counted. Discrepancy visible before anyone leaves.
- WhatsApp report sent automatically on close: staff name, duration, sales, M-Pesa, cash expected, cash counted, discrepancy
- Footer on every report: "Powered by Stoka · stoka.co.ke"

## 7. Inventory

- Real-time stock updated on every sale and restock
- Low stock alerts — one WhatsApp alert when stock crosses threshold (not on every sale)
- Restock flow: Select supplier → see their products → enter quantities and cost price

## 8. Pre-Trip Shopping List

Before owner travels to Eastleigh or wholesale market:
- Current stock per product/variant — red (out), amber (low), green (healthy)
- Fast movers from last 30 days highlighted
- Suggested restock quantities based on sales rate
- List sent to owner via WhatsApp before they leave
- Filterable by supplier — at Ali's stall, see only Ali's products

## 9. Supplier Balance Tracking

- Per restock: enter total cost + amount paid. Difference = outstanding balance.
- Running balance per supplier on dashboard
- Owner logs payments any time
- Not accounts payable — just "how much do I still owe Ali"

## 10. Exchanges

Exchange only — no cash refunds.
- Even exchange: Return A (Ksh 800), pick B (Ksh 800). No cash movement.
- Uneven exchange: Return A (Ksh 800), pick B (Ksh 1,200). Customer owes Ksh 400 — recorded as credit.

## 11. Outstanding Credit

- Customer name and phone required at credit sale
- Age visible: 7 days, 30 days, 60 days
- WhatsApp alert to owner when credit crosses age threshold
- Full credit ledger per customer

## 12. Owner Dashboard

Today: Total sales, transactions, cash collected, M-Pesa received, open shifts, items that ran out.
Health: Low stock (amber), out of stock (red), top sellers, slow movers, below-floor sales flagged, supplier balances outstanding.

## 13. Reports — Three only in v1

- Shift summary: WhatsApp on close
- Stock report: Dashboard, always current
- Sales history: Dashboard, filterable by day/week/month, below-floor highlighted

## 14. What Is NOT in v1

No barcode scanning, no online store, no accounts payable, no payroll, no tax reporting, no P&L, no layaway, no loyalty programme, no multi-currency.

## 15. Design System

### Two Audiences

**Staff interface:**
- Used standing at counter, one hand, time pressure
- May not be tech-comfortable — learnable in under 10 minutes
- Primary device: mobile phone
- Large tap targets, minimal text
- BOTTOM tab navigation — thumb reach. Four tabs: Products · Active Shift · History · Close Shift
- Search is PRIMARY — customers ask verbally, staff fetch. Product grid is secondary.
- 3 taps max for any common action

**Owner interface:**
- Desktop-first
- Left sidebar navigation
- Information dense but breathable
- Primary alert: WhatsApp — does not need to open dashboard daily
- No complex charts in v1 — numbers and simple bar indicators

### Colour Palette

- Parchment #FAF7F2 — Background
- Surface #F2EDE6 — Cards/panels
- Espresso #1C1814 — Primary text
- Warm mid #8C7B6E — Secondary text
- Terracotta #C17F4A — Primary accent
- Forest #4A6741 — Positive/success
- Clay #B85C38 — Alert/warning
- Dark wood #2C1F14 — Dark surfaces

Nothing in this palette is corporate or cold. Every colour exists in the natural world. The system should feel like it was made by someone who understands what a boutique feels like — not built by an engineer who chose from a default chart.

### Typography

- Display headings: Cormorant Garamond — elegant, literary. Whispers rather than announces.
- UI & body: Plus Jakarta Sans — warm, humanist, highly legible on mobile
- Numbers & data: DM Mono — monospace for all figures, prices, quantities, timestamps

### Design Standard

Stoka feels like the same hand that built PsTally — but more refined. More warmth, more restraint, more intention. Every element earns its place or it is removed. Complexity is not elegance. Precision is elegance. Feel: warm, minimal, elegant — MORNING not midnight.

## 16. Build Phases

- Phase 1 — Foundation: Laravel, tenancy, auth, migrations ✅ COMPLETE
- Phase 2 — Products: Suppliers CRUD, Products CRUD (all 3 types), variants, bottles
- Phase 3 — Sales flow: Staff interface, product grid, 3-tap sale, payment types, void, exchange
- Phase 4 — Shifts: Open/close, reconciliation, WhatsApp report. Sister's boutique goes live.
- Phase 5 — Dashboard: Owner view, stock report, sales history, below-floor flagging, credit view
- Phase 6 — Alerts: Low stock WhatsApp, credit age alerts, automated triggers
- Phase 7 — Polish: Mobile optimisation, onboarding flow, stoka.co.ke marketing site, demo (Zuri Boutique)

## 17. The Demo — Zuri Boutique

Pre-loaded believable Kenyan boutique. Makes visitor think: this could be mine.
- 25–30 products: dresses S/M/L/XL, shirts, caps, perfumes by ml, body sprays, jewelry, handbags
- Two staff: Amina (mid-shift, 6 sales, Ksh 3,400) and James (yesterday, balanced)
- Two entry points: Try as Staff / Try as Owner
- Smart reset: each session restores to pre-loaded state. Daily reset at midnight.

## 18. Go-To-Market

Zero budget. Direct WhatsApp outreach to boutique owners.
Message: "My sister runs a boutique. I built a system after she showed me her notebook. 15 minutes to show you — no obligation."
First client: sister's boutique, client zero, goes live Phase 4.
