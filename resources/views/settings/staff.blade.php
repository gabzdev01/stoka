@extends('layouts.app')

@section('title', 'Staff')

@section('header')
<p class="page-subtitle">Manage who has access to {{ tenant('name') }}</p>
@endsection

@section('styles')
<style>
.settings-wrap { max-width: 680px; display: flex; flex-direction: column; gap: 28px; }

.s-card {
    background: #fff;
    border: 1px solid #EDE8E0;
    border-radius: var(--radius-default);
    padding: 28px;
}
.s-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
}
.s-card-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--espresso);
}
.s-card-count {
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
    background: var(--parchment);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 2px 10px;
}
.s-field { margin-bottom: 18px; }
.s-field:last-child { margin-bottom: 0; }
.s-label { display: block; font-size: 13px; font-weight: 600; color: var(--espresso); margin-bottom: 6px; }
.s-helper { font-size: 12px; color: var(--muted); margin-top: 5px; line-height: 1.4; display: block; }
.s-input {
    width: 100%; height: 44px;
    border: 1.5px solid var(--border); border-radius: var(--radius-md);
    background: var(--parchment);
    font-family: "Plus Jakarta Sans", sans-serif; font-size: 14px; color: var(--espresso);
    padding: 0 14px; transition: border-color 0.15s; outline: none; box-sizing: border-box;
}
.s-input:focus { border-color: var(--espresso); }
.s-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.s-sub-title { font-size: 13px; font-weight: 700; color: var(--espresso); margin-bottom: 14px; }
.s-save {
    display: inline-flex; align-items: center; gap: 6px;
    height: 42px; padding: 0 22px;
    background: var(--espresso); color: #fff; border: none;
    border-radius: var(--radius-md);
    font-family: "Plus Jakarta Sans", sans-serif; font-size: 13.5px; font-weight: 600;
    cursor: pointer; transition: opacity 0.13s;
}
.s-save:hover { opacity: 0.85; }
.s-link {
    background: none; border: none; cursor: pointer;
    font-family: "Plus Jakarta Sans", sans-serif; font-size: 12px; font-weight: 500;
    color: var(--muted); padding: 0;
    text-decoration: underline; text-underline-offset: 2px; transition: color 0.13s;
}
.s-link:hover { color: var(--espresso); }
.s-link-danger { color: var(--clay); }
.s-link-danger:hover { color: var(--clay); opacity: 0.75; }

.flash-ok {
    display: flex; align-items: center; gap: 8px;
    background: #DFF0DD; color: var(--forest);
    border-radius: var(--radius-md); padding: 11px 15px;
    font-size: 13px; font-weight: 600; margin-bottom: 18px;
}
.flash-err {
    display: flex; align-items: center; gap: 8px;
    background: #F5DDD8; color: var(--clay);
    border-radius: var(--radius-md); padding: 11px 15px;
    font-size: 13px; font-weight: 600; margin-bottom: 18px;
}

/* Staff list */
.staff-list { display: flex; flex-direction: column; }
.staff-row {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 0; border-bottom: 1px solid #F5F0EB;
}
.staff-row:last-child { border-bottom: none; }
.staff-info { flex: 1; min-width: 0; }
.staff-name { font-size: 14.5px; font-weight: 600; color: var(--espresso); display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.staff-phone { font-family: "DM Mono", monospace; font-size: 12px; color: var(--muted); display: block; margin-top: 2px; }
.staff-pin-wrap { display: flex; align-items: center; gap: 6px; }
.staff-pin { font-family: "DM Mono", monospace; font-size: 13px; color: var(--espresso); letter-spacing: 0.12em; }
.pin-show-btn {
    background: none; border: none; cursor: pointer; color: var(--muted);
    font-size: 11px; font-weight: 600; font-family: "Plus Jakarta Sans", sans-serif;
    padding: 2px 4px; transition: color 0.13s;
}
.pin-show-btn:hover { color: var(--terracotta); }
.status-pill {
    display: inline-flex; align-items: center; padding: 3px 10px;
    border-radius: var(--radius-full); font-size: 11px; font-weight: 700;
    white-space: nowrap; cursor: pointer; border: none;
    font-family: "Plus Jakarta Sans", sans-serif;
}
.status-pill.active   { background: #DFF0DD; color: var(--forest); }
.status-pill.inactive { background: #F0EDE8; color: var(--muted); }
.staff-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

/* Mobile staff row — stack actions */
@media (max-width: 560px) {
    .staff-row { flex-wrap: wrap; gap: 10px; }
    .staff-pin-wrap { order: 3; }
    .staff-actions { order: 4; }
}

.new-pin-card { background: #DFF0DD; border-radius: var(--radius-md); padding: 14px 18px; margin-top: 10px; }
.new-pin-name { font-size: 13px; color: var(--forest); font-weight: 600; }
.new-pin-number { font-family: "DM Mono", monospace; font-size: 32px; font-weight: 700; color: var(--forest); letter-spacing: 0.15em; display: block; margin-top: 4px; }

.remove-confirm {
    display: none; align-items: center; gap: 10px;
    background: #FDF5EC; border: 1px solid #E8D8C0;
    border-radius: var(--radius-md); padding: 10px 14px;
    margin-top: 8px; font-size: 13px; color: var(--espresso);
    flex-wrap: wrap; gap: 8px;
}
.remove-confirm.visible { display: flex; }
.confirm-text { flex: 1; min-width: 160px; line-height: 1.4; }

.add-staff-toggle {
    background: none; border: 1.5px dashed var(--border);
    border-radius: var(--radius-md); width: 100%; padding: 12px;
    font-family: "Plus Jakarta Sans", sans-serif; font-size: 13.5px; font-weight: 600;
    color: var(--muted); cursor: pointer; margin-top: 16px;
    transition: border-color 0.15s, color 0.15s;
    display: flex; align-items: center; justify-content: center; gap: 7px;
}
.add-staff-toggle:hover { border-color: var(--terracotta); color: var(--terracotta); }
.add-staff-form {
    display: none; background: var(--parchment);
    border: 1px solid var(--border); border-radius: var(--radius-md);
    padding: 20px; margin-top: 12px;
}
.add-staff-form.visible { display: block; }

@media (max-width: 560px) {
    .s-card { padding: 20px 18px; }
    .s-grid-2 { grid-template-columns: 1fr; }
    .s-save { width: 100%; justify-content: center; }
}
</style>
@endsection

@section('content')
<div class="settings-wrap">

<div class="s-card" id="staff">
    <div class="s-card-head">
        <span class="s-card-title">My staff</span>
        <span class="s-card-count">{{ $staff->count() }} {{ $staff->count() === 1 ? 'member' : 'members' }}</span>
    </div>

    @if(session('ok_remove'))
        <div class="flash-ok">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2.5 7l3.5 3.5 5.5-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ session('ok_remove') }}
        </div>
    @endif
    @if(session('ok_add_staff'))
        @php $addedStaff = session('ok_add_staff'); @endphp
        <div class="new-pin-card" style="margin-bottom:18px;">
            <div class="new-pin-name">{{ $addedStaff['name'] }} has been added to {{ tenant('name') }}.</div>
            <div style="font-size:12px; color:var(--forest); margin-top:2px;">Their PIN is</div>
            <span class="new-pin-number">{{ $addedStaff['pin'] }}</span>
            <div style="font-size:11px; color:var(--forest); margin-top:6px; opacity:0.75;">Save this — you can always reset it from here.</div>
        </div>
    @endif

    @php $resetPin = session('ok_reset_pin'); @endphp

    <div class="staff-list">
        @forelse($staff as $member)
        <div>
            <div class="staff-row">
                <div class="staff-info">
                    <span class="staff-name">{{ $member->name }}</span>
                    <span class="staff-phone">{{ $member->phone }}</span>
                </div>
                <div class="staff-pin-wrap">
                    <span class="staff-pin" id="pin-{{ $member->id }}">&#9679;&#9679;&#9679;&#9679;</span>
                    <button type="button" class="pin-show-btn"
                            onclick="togglePin({{ $member->id }}, '{{ $member->pin }}')"
                            id="pin-btn-{{ $member->id }}">Show</button>
                </div>
                <form method="POST" action="{{ route('settings.staff.toggle', $member) }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="status-pill {{ $member->active ? 'active' : 'inactive' }}">
                        {{ $member->active ? 'Active' : 'Inactive' }}
                    </button>
                </form>
                <div class="staff-actions">
                    <form method="POST" action="{{ route('settings.staff.reset-pin', $member) }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="s-link">Reset PIN</button>
                    </form>
                    <button type="button" class="s-link s-link-danger"
                            onclick="showRemoveConfirm({{ $member->id }})">Remove</button>
                </div>
            </div>

            @if($resetPin && $resetPin['user_id'] == $member->id)
            <div class="new-pin-card" style="margin-bottom:12px;">
                <div class="new-pin-name">{{ $resetPin['name'] }}'s new PIN is</div>
                <span class="new-pin-number">{{ $resetPin['pin'] }}</span>
            </div>
            @endif

            <div class="remove-confirm" id="remove-{{ $member->id }}">
                <span class="confirm-text">Remove {{ $member->name }}? Their shift history will be kept.</span>
                <form method="POST" action="{{ route('settings.staff.remove', $member) }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="s-save" style="background:var(--clay); height:34px; padding:0 14px; font-size:12px;">Confirm</button>
                </form>
                <button type="button" class="s-link" onclick="hideRemoveConfirm({{ $member->id }})">Cancel</button>
            </div>
        </div>
        @empty
        <p style="color:var(--muted); font-size:14px; padding:16px 0;">No staff members yet. Add your first one below.</p>
        @endforelse
    </div>

    <button type="button" class="add-staff-toggle" onclick="toggleAddStaff()" id="add-staff-btn">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
            <path d="M7 1v12M1 7h12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
        Add staff member
    </button>

    <div class="add-staff-form" id="add-staff-form">
        <div class="s-sub-title" style="margin-bottom:16px;">New staff member</div>
        @if($errors->has('name') || $errors->has('phone') || $errors->has('pin'))
            <div class="flash-err" style="margin-bottom:14px;">
                {{ $errors->first('name') ?: ($errors->first('phone') ?: $errors->first('pin')) }}
            </div>
        @endif
        <form method="POST" action="{{ route('settings.staff.add') }}">
            @csrf
            <div class="s-grid-2">
                <div class="s-field">
                    <label class="s-label">Name</label>
                    <input type="text" name="name" class="s-input"
                           value="{{ old('name') }}" required placeholder="e.g. Grace Otieno">
                </div>
                <div class="s-field">
                    <label class="s-label">Phone</label>
                    <input type="tel" name="phone" class="s-input"
                           value="{{ old('phone') }}" required placeholder="0712 345 678">
                </div>
                <div class="s-field">
                    <label class="s-label">PIN <span style="font-weight:400; color:var(--muted);">(optional)</span></label>
                    <input type="text" name="pin" class="s-input" inputmode="numeric"
                           maxlength="6" placeholder="Leave blank to auto-generate">
                    <span class="s-helper">Leave blank to auto-generate a 4-digit PIN</span>
                </div>
            </div>
            <button type="submit" class="s-save">Add staff member</button>
        </form>
    </div>
</div>

</div>
@endsection

@section('scripts')
<script>
function togglePin(id, pin) {
    var el  = document.getElementById('pin-' + id);
    var btn = document.getElementById('pin-btn-' + id);
    if (btn.textContent === 'Show') {
        el.textContent  = pin;
        btn.textContent = 'Hide';
    } else {
        el.textContent  = '\u25CF\u25CF\u25CF\u25CF';
        btn.textContent = 'Show';
    }
}
function showRemoveConfirm(id) { document.getElementById('remove-' + id).classList.add('visible'); }
function hideRemoveConfirm(id) { document.getElementById('remove-' + id).classList.remove('visible'); }
function toggleAddStaff() {
    var form = document.getElementById('add-staff-form');
    var btn  = document.getElementById('add-staff-btn');
    var open = form.classList.toggle('visible');
    btn.style.display = open ? 'none' : '';
}
@if($errors->has('name') || $errors->has('phone') || $errors->has('pin'))
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('add-staff-form').classList.add('visible');
        document.getElementById('add-staff-btn').style.display = 'none';
    });
@endif
</script>
@endsection
