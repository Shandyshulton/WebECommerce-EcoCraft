<?php

namespace App\Http\Controllers;

use App\Models\WarrantyClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerWarrantyClaimController extends Controller
{
    /**
     * Inbox klaim garansi milik seller yang sedang login.
     */
    public function index(Request $request)
    {
        $sellerId = Auth::guard('seller')->id();

        $query = WarrantyClaim::with(['product', 'customer', 'order'])
            ->where('seller_id', $sellerId);

        $status = $request->get('status');
        if ($status && array_key_exists($status, WarrantyClaim::STATUSES)) {
            $query->where('status', $status);
        }

        if ($search = trim((string) $request->get('q'))) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', fn ($c) => $c->where('name_customers', 'like', "%{$search}%"))
                  ->orWhereHas('product', fn ($p) => $p->where('name', 'like', "%{$search}%"))
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $claims = $query->orderByDesc('id_claims')->paginate(15)->withQueryString();

        $stats = [
            'total' => WarrantyClaim::where('seller_id', $sellerId)->count(),
            'pending' => WarrantyClaim::where('seller_id', $sellerId)->where('status', 'Submitted')->count(),
        ];

        return view('seller.claims.index', compact('claims', 'stats'));
    }

    /**
     * Detail klaim milik seller.
     */
    public function show($id)
    {
        $sellerId = Auth::guard('seller')->id();

        $claim = WarrantyClaim::with(['product', 'customer', 'order', 'item'])
            ->where('seller_id', $sellerId)
            ->findOrFail($id);

        return view('seller.claims.show', compact('claim'));
    }

    /**
     * Seller menanggapi klaim: ubah status dan tulis resolusi.
     */
    public function respond(Request $request, $id)
    {
        $sellerId = Auth::guard('seller')->id();

        $claim = WarrantyClaim::where('seller_id', $sellerId)->findOrFail($id);

        $data = $request->validate([
            'status' => ['required', 'in:Reviewing,Approved,Rejected,Completed'],
            'resolution' => ['nullable', 'string', 'max:2000'],
        ], [
            'status.in' => 'Pilih status penanganan yang valid.',
        ]);

        $claim->update([
            'status' => $data['status'],
            'resolution' => $data['resolution'] ?? null,
            'responded_at' => now(),
        ]);

        return redirect()
            ->route('seller.claims.show', $claim->id_claims)
            ->with('success', 'Tanggapan klaim tersimpan.');
    }
}
