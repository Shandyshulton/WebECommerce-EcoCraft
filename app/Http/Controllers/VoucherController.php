<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::orderByDesc('is_active')->orderBy('title')->get();

        return view('admin.vouchers', compact('vouchers'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['code'] = strtoupper($data['code']);

        Voucher::create($data);

        return back()->with('success', 'Voucher ditambahkan.');
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);

        return view('admin.vouchers_edit', compact('voucher'));
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);
        $data = $this->validated($request, $voucher->id);
        $data['code'] = strtoupper($data['code']);

        $voucher->update($data);

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher diperbarui.');
    }

    public function toggle($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->update(['is_active' => ! $voucher->is_active]);

        return back()->with('success', $voucher->is_active ? 'Voucher diaktifkan.' : 'Voucher dinonaktifkan.');
    }

    public function destroy($id)
    {
        Voucher::findOrFail($id)->delete();

        return back()->with('success', 'Voucher dihapus.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:vouchers,code'.($ignoreId ? ','.$ignoreId : '')],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:fixed,percent'],
            'value' => ['required', 'numeric', 'min:0'],
            'min_spend' => ['nullable', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'per_customer_limit' => ['required', 'integer', 'min:1'],
        ]);

        $data['min_spend'] = $data['min_spend'] ?? 0;
        $data['max_discount'] = ($data['max_discount'] ?? null) === null ? null : $data['max_discount'];
        $data['is_claimable'] = $request->boolean('is_claimable');
        $data['is_reward'] = $request->boolean('is_reward');
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
