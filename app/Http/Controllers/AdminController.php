<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Customer;

class AdminController extends Controller
{
    // Daftar customer yang berhasil mendaftar
    public function customers(Request $request)
    {
        $query = Customer::query();

        if ($search = trim((string) $request->get('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name_customers', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }
        if ($city = $request->get('city')) {
            $query->where('city', $city);
        }

        $customers = $query->orderByDesc('id_customers')->paginate(12)->withQueryString();

        $cities = Customer::whereNotNull('city')->where('city', '!=', '')
            ->distinct()->orderBy('city')->pluck('city');

        $stats = [
            'total' => Customer::count(),
            'newMonth' => Customer::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count(),
            'withOrders' => Customer::whereHas('orders')->count(),
        ];

        return view('admin.customers', compact('customers', 'cities', 'stats'));
    }

    // Tampilkan dashboard admin
    public function dashboard()
    {
        return view('admin.dashboard', [
            'pendingSellers' => Seller::where('status', 'pending')->count(),
            'approvedSellers' => Seller::where('status', 'approved')->count(),
            'pendingProducts' => Product::where('status', 'pending')->count(),
            'approvedProducts' => Product::where('status', 'approved')->count(),
            'totalCustomers' => Customer::count(),
            'recentSellers' => Seller::latest('id_sellers')->take(5)->get(),
            'recentProducts' => Product::latest('id_products')->take(5)->get(),
        ]);
    }

    public function staff(Request $request)
    {
        $query = \App\Models\Admin::query();

        if ($search = trim((string) $request->get('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        return view('admin.staff', [
            'admin' => auth('admin')->user(),
            'admins' => $query->orderByDesc('id_admins')->get(),
            'stats' => [
                'total' => \App\Models\Admin::count(),
                'super' => \App\Models\Admin::where('role', 'super_admin')->count(),
                'admin' => \App\Models\Admin::where('role', 'admin')->count(),
            ],
        ]);
    }

    // Super admin: tambah akun admin baru
    public function storeAdmin(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email'],
            'phone_number' => ['required', 'string', 'max:20'],
            'role' => ['required', 'in:super_admin,admin'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        \App\Models\Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'role' => $data['role'],
            'password' => bcrypt($data['password']),
        ]);

        return back()->with('success', 'Admin baru berhasil ditambahkan.');
    }

    // Super admin: ubah peran admin lain
    public function updateAdminRole(Request $request, $id)
    {
        $data = $request->validate([
            'role' => ['required', 'in:super_admin,admin'],
        ]);

        $current = auth('admin')->user();
        if ((int) $current->id_admins === (int) $id) {
            return back()->with('error', 'Kamu tidak bisa mengubah peranmu sendiri.');
        }

        $target = \App\Models\Admin::findOrFail($id);
        $target->role = $data['role'];
        $target->save();

        return back()->with('success', 'Peran admin diperbarui.');
    }

    // Super admin: hapus akun admin (tidak bisa hapus diri sendiri)
    public function destroyAdmin($id)
    {
        $current = auth('admin')->user();
        if ((int) $current->id_admins === (int) $id) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        \App\Models\Admin::findOrFail($id)->delete();

        return back()->with('success', 'Akun admin dihapus.');
    }

    // Tampilkan daftar seller berdasarkan status (pending & sudah diproses)
    public function verifySellers(Request $request)
    {
        $search = trim((string) $request->get('q'));
        $filter = function ($query) use ($search) {
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name_sellers', 'like', "%{$search}%")
                      ->orWhere('store_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            return $query;
        };

        $pendingSellers = $filter(Seller::where('status', 'pending'))
            ->paginate(10, ['*'], 'pendingPage')->withQueryString();
        $processedSellers = $filter(Seller::whereIn('status', ['approved', 'rejected']))
            ->paginate(10, ['*'], 'processedPage')->withQueryString();

        $stats = [
            'total' => Seller::count(),
            'pending' => Seller::where('status', 'pending')->count(),
            'approved' => Seller::where('status', 'approved')->count(),
            'rejected' => Seller::where('status', 'rejected')->count(),
        ];

        return view('admin.verify_sellers', compact('pendingSellers', 'processedSellers', 'stats'));
    }


    // Approve seller berdasarkan id
    public function approveSeller($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->status = 'approved';
        $seller->save();

        return back()->with('success', 'Seller berhasil disetujui.');
    }

    // Reject seller berdasarkan id
    public function rejectSeller($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->status = 'rejected';
        $seller->save();

        return back()->with('error', 'Seller ditolak.');
    }

    public function show($id)
    {
        $seller = Seller::findOrFail($id);
        return view('admin.show', compact('seller'));
    }

    // Menampilkan halaman verifikasi produk
    public function verifyProducts(Request $request)
    {
        $applyFilter = function ($query) use ($request) {
            if ($search = trim((string) $request->get('q'))) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhereHas('seller', function ($s) use ($search) {
                          $s->where('name_sellers', 'like', "%{$search}%")
                            ->orWhere('store_name', 'like', "%{$search}%");
                      });
                });
            }
            return $query;
        };

        $pendingProducts = $applyFilter(Product::with('seller')->where('status', 'pending'))
            ->paginate(10)->withQueryString();
        $approvedProducts = $applyFilter(Product::with('seller')->whereIn('status', ['approved', 'rejected']))
            ->paginate(10)->withQueryString();

        $stats = [
            'total' => Product::count(),
            'pending' => Product::where('status', 'pending')->count(),
            'approved' => Product::where('status', 'approved')->count(),
            'rejected' => Product::where('status', 'rejected')->count(),
        ];

        return view('admin.verify_products', compact('pendingProducts', 'approvedProducts', 'stats'));
    }

    // Menyetujui produk
    public function approveProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->status = 'approved';
        $product->is_active = true;
        $product->save();

        return back()->with('success', 'Produk berhasil disetujui.');
    }

    // Menolak produk
    public function rejectProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->status = 'rejected';
        $product->save();

        return back()->with('error', 'Produk ditolak.');
    }
}
