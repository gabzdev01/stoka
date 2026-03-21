<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    private function tenant(): \App\Models\Tenant
    {
        return \App\Models\Tenant::find(tenant('id'));
    }

    public function index()
    {
        $t     = $this->tenant();
        $owner = User::find(session('auth_user'));
        return view('settings.index', compact('t', 'owner'));
    }

    public function staffPage()
    {
        $staff = User::where('role', 'staff')->orderBy('name')->get();
        return view('settings.staff', compact('staff'));
    }

    // ── Section 1: Account ────────────────────────────────────────────────────

    public function saveAccount(Request $request)
    {
        $data = $request->validate([
            'owner_name'     => 'required|string|max:100',
            'owner_phone'    => 'required|string|max:20',
            'owner_whatsapp' => 'nullable|string|max:20',
        ]);

        $this->tenant()->update($data);
        User::find(session('auth_user'))->update(['name' => $data['owner_name'], 'phone' => $data['owner_phone']]);
        session(['auth_name' => $data['owner_name']]);

        return redirect(route('settings.index') . '#account')
            ->with('ok_account', 'Account details saved.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed',
        ]);

        $owner = User::find(session('auth_user'));
        if (!Hash::check($request->current_password, $owner->password)) {
            return redirect(route('settings.index') . '#account')
                ->with('err_password', 'Current password is incorrect.');
        }

        $owner->update(['password' => Hash::make($request->new_password)]);

        return redirect(route('settings.index') . '#account')
            ->with('ok_password', 'Password changed successfully.');
    }

    // ── Section 2: Shop ───────────────────────────────────────────────────────

    public function saveShop(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:100',
            'shop_location'         => 'nullable|string|max:150',
            'shop_description'      => 'nullable|string|max:200',
            'operating_hours_open'  => 'nullable|string|max:10',
            'operating_hours_close' => 'nullable|string|max:10',
            'currency'              => 'required|in:KES,UGX,TZS,RWF,ETB',
        ]);

        $this->tenant()->update($data);

        return redirect(route('settings.index') . '#shop')
            ->with('ok_shop', 'Shop details saved.');
    }

    public function toggleShop(Request $request)
    {
        $tenant = $this->tenant();
        $tenant->update(['shop_enabled' => !$tenant->shop_enabled]);
        return back()->with('ok_shop', $tenant->shop_enabled ? 'Your shop is now live.' : 'Your shop is now paused.');
    }

    // ── Section 3: Staff ──────────────────────────────────────────────────────

    public function addStaff(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'required|string|max:20|unique:users,phone',
            'pin'   => 'nullable|string|digits_between:4,6',
        ]);

        $pin = $data['pin'] ?: str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        $user = User::create([
            'name'   => $data['name'],
            'phone'  => $data['phone'],
            'role'   => 'staff',
            'pin'    => $pin,
            'active' => true,
        ]);

        return redirect(route('staff.index'))
            ->with('ok_add_staff', [
                'name' => $user->name,
                'pin'  => $pin,
            ]);
    }

    public function toggleStaffStatus(User $user)
    {
        $user->update(['active' => !$user->active]);
        return redirect(route('staff.index'));
    }

    public function resetStaffPin(User $user)
    {
        $pin = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $user->update(['pin' => $pin]);

        return redirect(route('staff.index'))
            ->with('ok_reset_pin', [
                'user_id' => $user->id,
                'name'    => $user->name,
                'pin'     => $pin,
            ]);
    }

    public function removeStaff(User $user)
    {
        $user->update(['active' => false]);
        return redirect(route('staff.index'))
            ->with('ok_remove', $user->name . ' has been removed.');
    }

    // ── Section 4: Receipts ───────────────────────────────────────────────────

    public function saveReceipts(Request $request)
    {
        $this->tenant()->update([
            'receipt_digital' => (bool) $request->input('receipt_digital', false),
            'receipt_print'   => (bool) $request->input('receipt_print', false),
            'receipt_footer'  => $request->input('receipt_footer'),
        ]);

        return redirect(route('settings.index') . '#receipts')
            ->with('ok_receipts', 'Receipt settings saved.');
    }

    // ── Section 5: Alerts ─────────────────────────────────────────────────────

    public function saveAlerts(Request $request)
    {
        $request->validate([
            'default_low_stock_threshold' => 'required|integer|min:1|max:100',
        ]);

        $this->tenant()->update([
            'notify_shift_close'          => (bool) $request->input('notify_shift_close', false),
            'notify_low_stock'            => (bool) $request->input('notify_low_stock', false),
            'notify_credit_overdue'       => (bool) $request->input('notify_credit_overdue', false),
            'default_low_stock_threshold' => (int) $request->input('default_low_stock_threshold', 3),
        ]);

        return redirect(route('settings.index') . '#alerts')
            ->with('ok_alerts', 'Alert settings saved.');
    }

    // ── Section 6: Export ─────────────────────────────────────────────────────

    public function export()
    {
        $shopName  = tenant('name');
        $monthStart = now()->startOfMonth();

        // Sales this month
        $sales = \App\Models\Sale::with(['product', 'variant', 'staff'])
            ->where('created_at', '>=', $monthStart)
            ->whereNull('voided_at')
            ->orderBy('created_at')
            ->get();

        // Credit balances
        $credits = \App\Models\CreditLedger::with('customer')
            ->where('status', 'open')
            ->orderBy('created_at')
            ->get();

        // Stock levels
        $products = \App\Models\Product::with(['variants', 'bottles' => fn($q) => $q->where('active', true)])
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $rows   = [];
        $rows[] = ['=== SALES THIS MONTH (' . now()->format('F Y') . ') ==='];
        $rows[] = ['Date', 'Product', 'Variant', 'Qty', 'Price', 'Total', 'Payment', 'Staff'];
        foreach ($sales as $s) {
            $rows[] = [
                $s->created_at->format('d M Y H:i'),
                $s->product->name ?? '',
                $s->variant->size ?? '',
                $s->quantity_or_ml,
                $s->actual_price,
                $s->total,
                $s->payment_type,
                $s->staff->name ?? '',
            ];
        }

        $rows[] = [];
        $rows[] = ['=== OPEN CREDIT BALANCES ==='];
        $rows[] = ['Customer', 'Phone', 'Amount', 'Paid', 'Balance', 'Age (days)', 'Status'];
        foreach ($credits as $c) {
            $rows[] = [
                $c->customer->name ?? '',
                $c->customer->phone ?? '',
                $c->amount,
                $c->paid,
                $c->balance,
                $c->created_at->diffInDays(now()),
                $c->status,
            ];
        }

        $rows[] = [];
        $rows[] = ['=== CURRENT STOCK LEVELS ==='];
        $rows[] = ['Product', 'Type', 'Variant/Size', 'Stock'];
        foreach ($products as $p) {
            if ($p->type === 'variant') {
                foreach ($p->variants as $v) {
                    $rows[] = [$p->name, 'variant', $v->size, $v->stock];
                }
            } elseif ($p->type === 'measured') {
                $bottle = $p->bottles->first();
                $rows[] = [$p->name, 'measured', 'ml remaining', $bottle ? $bottle->remaining_ml : 0];
            } else {
                $rows[] = [$p->name, 'unit', '', $p->stock];
            }
        }

        $filename = 'stoka-export-' . now()->format('Y-m') . '.csv';
        $handle   = fopen('php://temp', 'r+');
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
