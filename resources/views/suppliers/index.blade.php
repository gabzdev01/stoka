@extends('layouts.app')

@section('title', 'Suppliers')

@section('header')
    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap;">
        <div>
            <h1 class="page-title">Suppliers</h1>
            <p class="page-subtitle">
                {{ $suppliers->count() }} {{ $suppliers->count() === 1 ? 'supplier' : 'suppliers' }}
            </p>
        </div>
        <button class="btn btn-primary" onclick="openAddDrawer()">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M7 1v12M1 7h12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
            Add Supplier
        </button>
    </div>
@endsection

@section('styles')
<style>
    /* ── Responsive toggle ───────────────────────────────── */
    .desktop-table { display: block; }
    .mobile-cards  { display: none; }

    @media (max-width: 767px) {
        .desktop-table { display: none; }
        .mobile-cards  { display: block; }
    }

    /* ── Desktop table ───────────────────────────────────── */
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th {
        font-size: 11px;
        font-weight: 600;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.07em;
        padding: 12px 20px;
        text-align: left;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }
    .data-table td {
        padding: 14px 20px;
        font-size: 13.5px;
        color: var(--espresso);
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    .data-table tbody tr:last-child td { border-bottom: none; }
    .data-table tbody tr:hover td { background: #FAF5EF; }
    .supplier-name { font-weight: 600; }
    .td-muted   { color: var(--muted); }
    .td-actions { width: 1%; white-space: nowrap; text-align: right; }

    /* ── Row action buttons ──────────────────────────────── */
    .btn-row {
        background: none;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 5px 11px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        font-family: "Plus Jakarta Sans", sans-serif;
        transition: background 0.13s, border-color 0.13s, color 0.13s;
        line-height: 1.4;
    }
    .btn-row-edit  { color: var(--mid); margin-right: 6px; }
    .btn-row-edit:hover  { background: #F0E8DC; border-color: var(--terracotta); color: var(--terracotta); }
    .btn-row-delete      { color: var(--muted); }
    .btn-row-delete:hover { background: #F9E8E4; border-color: var(--clay); color: var(--clay); }

    /* ── Mobile cards ────────────────────────────────────── */
    .m-card {
        background: var(--parchment);
        border: 1px solid var(--border);
        border-radius: var(--radius-default);
        padding: 16px;
        margin-bottom: 10px;
    }
    .m-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 8px;
    }
    .m-card-name  { font-weight: 600; font-size: 15px; color: var(--espresso); }
    .m-card-phone { font-size: 13px; color: var(--muted); margin-bottom: 10px; }
    .m-card-notes { font-size: 12.5px; color: var(--muted); margin-bottom: 12px; line-height: 1.4; }
    .m-card-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        padding-top: 12px;
        border-top: 1px solid var(--border);
    }

    /* ── Empty state ─────────────────────────────────────── */
    .empty-state { text-align: center; padding: 80px 24px; }
    .empty-icon  { width: 44px; height: 44px; margin: 0 auto 18px; color: var(--border); }
    .empty-title { font-family: "Cormorant Garamond", serif; font-size: 22px; font-weight: 600; color: var(--mid); margin-bottom: 8px; }
    .empty-text  { font-size: 13px; color: var(--muted); margin-bottom: 24px; }

    /* ── Flash ───────────────────────────────────────────── */
    .flash-success {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #DFF0DD;
        border: 1px solid #BFD9BC;
        border-radius: var(--radius-md);
        padding: 12px 16px;
        margin-bottom: 22px;
        font-size: 13.5px;
        color: var(--forest);
        font-weight: 500;
    }

    /* ── Drawer overlay ──────────────────────────────────── */
    #drawer-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(28, 24, 20, 0.42);
        z-index: 140;
        backdrop-filter: blur(1px);
    }
    #drawer-overlay.visible { display: block; }

    /* ── Drawer panel ────────────────────────────────────── */
    #drawer {
        position: fixed;
        top: 0; right: 0;
        height: 100vh;
        width: 380px;
        background: var(--parchment);
        border-left: 1px solid var(--border);
        z-index: 150;
        display: flex;
        flex-direction: column;
        transform: translateX(100%);
        transition: transform 0.22s ease;
    }
    #drawer.open { transform: translateX(0); }

    .drawer-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 22px 24px 18px;
        border-bottom: 1px solid var(--border);
        flex-shrink: 0;
    }
    .drawer-title { font-family: "Cormorant Garamond", serif; font-size: 22px; font-weight: 600; color: var(--espresso); }
    .drawer-close {
        background: none; border: none; cursor: pointer;
        padding: 4px 6px; color: var(--muted); font-size: 22px; line-height: 1;
        border-radius: 6px; transition: background 0.13s, color 0.13s;
    }
    .drawer-close:hover { background: var(--surface); color: var(--espresso); }
    .drawer-body   { flex: 1; overflow-y: auto; padding: 24px; }
    .drawer-footer { padding: 16px 24px; border-top: 1px solid var(--border); flex-shrink: 0; display: flex; gap: 10px; }

    /* ── Form fields ─────────────────────────────────────── */
    .form-group  { margin-bottom: 20px; }
    .form-label  {
        display: block; font-size: 11.5px; font-weight: 600; color: var(--muted);
        text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 7px;
    }
    .form-label .required { color: var(--clay); margin-left: 2px; }
    .form-input, .form-textarea {
        width: 100%; padding: 10px 13px;
        background: var(--surface); border: 1px solid var(--border); border-radius: 8px;
        font-family: "Plus Jakarta Sans", sans-serif; font-size: 13.5px; color: var(--espresso);
        outline: none; transition: border-color 0.13s, background 0.13s;
    }
    .form-input:focus, .form-textarea:focus { border-color: var(--terracotta); background: var(--parchment); }
    .form-input.is-error, .form-textarea.is-error { border-color: var(--clay); }
    .form-textarea { resize: vertical; min-height: 90px; }
    .field-error   { display: block; font-size: 12px; color: var(--clay); margin-top: 5px; }

    @media (max-width: 480px) {
        #drawer { width: 100vw; }
    }
</style>
@endsection

@section('content')

    {{-- Flash --}}
    @if(session('success'))
    <div class="flash-success">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
            <circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.4"/>
            <path d="M5 8l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if($suppliers->isEmpty())
        <div class="empty-state">
            <svg class="empty-icon" viewBox="0 0 44 44" fill="none">
                <rect x="2" y="2" width="40" height="40" rx="10" stroke="currentColor" stroke-width="2"/>
                <path d="M22 9v26M9 22h26" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <p class="empty-title">No suppliers added yet</p>
            <p class="empty-text">Add the people you restock from — Ali Traders, your tailor, your distributor. Keep track of what you owe them.</p>
            <button class="btn btn-primary" onclick="openAddDrawer()">Add your first supplier</button>
        </div>
    @else

        {{-- ── Desktop table (≥768px) ──────────────────────── --}}
        <div class="desktop-table card" style="padding:0; overflow:hidden;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Category</th>
                        <th>Notes</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $supplier)
                    <tr>
                        <td><span class="supplier-name">{{ $supplier->name }}</span></td>
                        <td>
                            @if($supplier->phone) {{ $supplier->phone }}
                            @else <span class="td-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($supplier->category)
                                <span class="badge badge-tan">{{ $supplier->category }}</span>
                            @else
                                <span class="td-muted">—</span>
                            @endif
                        </td>
                        <td class="td-muted">
                            {{ $supplier->notes ? \Illuminate\Support\Str::limit($supplier->notes, 50) : '—' }}
                        </td>
                        <td class="td-actions">
                            <button
                                class="btn-row btn-row-edit"
                                onclick="openEditDrawer(this)"
                                data-id="{{ $supplier->id }}"
                                data-name="{{ $supplier->name }}"
                                data-phone="{{ $supplier->phone }}"
                                data-category="{{ $supplier->category }}"
                                data-notes="{{ $supplier->notes }}"
                            >Edit</button>
                            <form method="POST"
                                  action="{{ route('suppliers.destroy', $supplier) }}"
                                  style="display:inline"
                                  onsubmit="return confirm('Delete {{ addslashes($supplier->name) }}? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-row btn-row-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ── Mobile cards (<768px) ───────────────────────── --}}
        <div class="mobile-cards">
            @foreach($suppliers as $supplier)
            <div class="m-card">
                {{-- Name + category badge --}}
                <div class="m-card-header">
                    <span class="m-card-name">{{ $supplier->name }}</span>
                    @if($supplier->category)
                        <span class="badge badge-tan" style="flex-shrink:0;">{{ $supplier->category }}</span>
                    @endif
                </div>

                {{-- Phone --}}
                @if($supplier->phone)
                    <div class="m-card-phone">{{ $supplier->phone }}</div>
                @endif

                {{-- Notes --}}
                @if($supplier->notes)
                    <div class="m-card-notes">{{ \Illuminate\Support\Str::limit($supplier->notes, 80) }}</div>
                @endif

                {{-- Actions --}}
                <div class="m-card-actions">
                    <button
                        class="btn-row btn-row-edit"
                        onclick="openEditDrawer(this)"
                        data-id="{{ $supplier->id }}"
                        data-name="{{ $supplier->name }}"
                        data-phone="{{ $supplier->phone }}"
                        data-category="{{ $supplier->category }}"
                        data-notes="{{ $supplier->notes }}"
                    >Edit</button>
                    <form method="POST"
                          action="{{ route('suppliers.destroy', $supplier) }}"
                          onsubmit="return confirm('Delete {{ addslashes($supplier->name) }}? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-row btn-row-delete">Delete</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

    @endif

    {{-- Drawer overlay --}}
    <div id="drawer-overlay" onclick="closeDrawer()"></div>

    {{-- Add / Edit Supplier drawer --}}
    <div id="drawer">
        <div class="drawer-header">
            <span class="drawer-title" id="drawer-title">Add Supplier</span>
            <button class="drawer-close" onclick="closeDrawer()" aria-label="Close">&times;</button>
        </div>

        <div class="drawer-body">

            @if($errors->any())
            <div style="background:#F9E8E4; border:1px solid #ECC9C2; border-radius:var(--radius-md); padding:12px 14px; margin-bottom:20px;">
                <p style="font-size:13px; font-weight:600; color:var(--clay); margin-bottom:6px;">Please fix the following:</p>
                <ul style="font-size:12.5px; color:var(--clay); padding-left:16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('suppliers.store') }}" id="supplier-form">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="">

                <div class="form-group">
                    <label class="form-label" for="f-name">Name <span class="required">*</span></label>
                    <input class="form-input {{ $errors->has('name') ? 'is-error' : '' }}"
                           type="text" id="f-name" name="name"
                           value="{{ old('name') }}" placeholder="e.g. Kariuki Fabrics" autocomplete="off">
                    @error('name') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="f-phone">Phone</label>
                    <input class="form-input {{ $errors->has('phone') ? 'is-error' : '' }}"
                           type="text" id="f-phone" name="phone"
                           value="{{ old('phone') }}" placeholder="e.g. 0712 345 678" autocomplete="off">
                    @error('phone') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="f-category">Category</label>
                    <input class="form-input {{ $errors->has('category') ? 'is-error' : '' }}"
                           type="text" id="f-category" name="category"
                           value="{{ old('category') }}" placeholder="e.g. Fabrics, Accessories, Shoes" autocomplete="off">
                    @error('category') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="f-notes">Notes</label>
                    <textarea class="form-textarea {{ $errors->has('notes') ? 'is-error' : '' }}"
                              id="f-notes" name="notes"
                              placeholder="Payment terms, delivery days, anything useful…">{{ old('notes') }}</textarea>
                    @error('notes') <span class="field-error">{{ $message }}</span> @enderror
                </div>
            </form>
        </div>

        <div class="drawer-footer">
            <button class="btn btn-primary" id="drawer-save-btn"
                    onclick="document.getElementById('supplier-form').submit()">
                Save Supplier
            </button>
            <button class="btn btn-secondary" onclick="closeDrawer()">Cancel</button>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    var STORE_URL = '{{ route("suppliers.store") }}';

    function openAddDrawer() {
        document.getElementById('drawer-title').textContent    = 'Add Supplier';
        document.getElementById('supplier-form').action        = STORE_URL;
        document.getElementById('form-method').value           = '';
        document.getElementById('drawer-save-btn').textContent = 'Save Supplier';
        document.getElementById('f-name').value     = '';
        document.getElementById('f-phone').value    = '';
        document.getElementById('f-category').value = '';
        document.getElementById('f-notes').value    = '';
        _openDrawer();
    }

    function openEditDrawer(btn) {
        document.getElementById('drawer-title').textContent    = 'Edit Supplier';
        document.getElementById('supplier-form').action        = '/suppliers/' + btn.dataset.id;
        document.getElementById('form-method').value           = 'PUT';
        document.getElementById('drawer-save-btn').textContent = 'Save Changes';
        document.getElementById('f-name').value     = btn.dataset.name     || '';
        document.getElementById('f-phone').value    = btn.dataset.phone    || '';
        document.getElementById('f-category').value = btn.dataset.category || '';
        document.getElementById('f-notes').value    = btn.dataset.notes    || '';
        _openDrawer();
    }

    function _openDrawer() {
        document.getElementById('drawer').classList.add('open');
        document.getElementById('drawer-overlay').classList.add('visible');
        document.getElementById('f-name').focus();
    }

    function closeDrawer() {
        document.getElementById('drawer').classList.remove('open');
        document.getElementById('drawer-overlay').classList.remove('visible');
    }

    @if($errors->isNotEmpty())
        _openDrawer();
    @endif
</script>
@endsection
