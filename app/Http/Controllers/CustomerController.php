<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Order;
use App\Models\CommunityStory;
use App\Models\CommunityStoryComment;
use App\Models\CustomerVoucher;

class CustomerController extends Controller
{
    /**
     * Menampilkan dashboard customer
     */
    public function dashboard(Request $request)
    {
        $products = Product::where('status', 'approved')
            ->where('is_active', 1)
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->string('q')->trim()->toString();

                $query->where(function ($productQuery) use ($term) {
                    $productQuery->where('name', 'like', "%{$term}%")
                        ->orWhere('category', 'like', "%{$term}%")
                        ->orWhere('material_type', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // Total limbah dialihkan (global) dari seluruh item pesanan × faktor produk.
        $globalWaste = \App\Models\OrderItem::with('product')->get()->sum(function ($item) {
            return $item->quantity * (float) (optional($item->product)->waste_factor ?? 1.2);
        });

        $stats = [
            'products' => Product::where('status', 'approved')->where('is_active', 1)->count(),
            'sellers' => Seller::where('status', 'approved')->count(),
            // 1 pohon per ~10 kg limbah dialihkan (estimasi), dihitung dari data nyata.
            'trees' => (int) floor($globalWaste / 10),
            'orders' => 0,
            'spent' => 0,
        ];

        $stories = CommunityStory::where('is_active', 1)
            ->where('featured', 1)
            ->orderBy('sort_order')
            ->take(8)
            ->get();

        if (Auth::guard('customer')->check()) {
            $customerId = Auth::guard('customer')->id();
            $stats['orders'] = Order::where('customer_id', $customerId)->count();
            $stats['spent'] = Order::where('customer_id', $customerId)->sum('total');
        }

        $orders = collect();
        $activeOrders = collect();
        $impactTrend = [];
        $impact = ['waste' => 0, 'carbon' => 0, 'artisans' => 0, 'coins' => 0, 'vouchers' => 0];

        if (Auth::guard('customer')->check()) {
            $orders = Order::with(['items.product', 'items.seller'])
                ->where('customer_id', Auth::guard('customer')->id())
                ->latest()
                ->get();
            $activeOrders = $orders->whereNotIn('status', ['Delivered', 'Cancelled'])->take(2);
            $quantity = $orders->sum(fn ($order) => $order->items->sum('quantity'));

            // Dampak dihitung dari faktor per produk (fallback default bila kosong).
            $allItems = $orders->flatMap(fn ($order) => $order->items);
            $wasteTotal = $allItems->sum(function ($item) {
                $factor = optional($item->product)->waste_factor ?? 1.2;
                return $item->quantity * (float) $factor;
            });
            $carbonTotal = $allItems->sum(function ($item) {
                $factor = optional($item->product)->carbon_factor ?? 2.7;
                return $item->quantity * (float) $factor;
            });

            $impact = [
                'waste' => round($wasteTotal, 1),
                'carbon' => round($carbonTotal, 1),
                'artisans' => $allItems->pluck('seller_id')->unique()->count(),
                // Saldo koin sirkular nyata dari dompet customer.
                'coins' => (int) Auth::guard('customer')->user()->coin_balance,
                // 1 pohon per ~10 kg limbah dialihkan (estimasi), non-hardcode.
                'trees' => (int) floor($wasteTotal / 10),
                // Voucher yang siap dipakai dari dompet customer.
                'vouchers' => CustomerVoucher::where('customer_id', $customerId)
                    ->where('status', 'available')
                    ->count(),
            ];
            for ($month = 5; $month >= 0; $month--) {
                $date = now()->subMonths($month);
                $monthlyItems = $orders->filter(fn ($order) => $order->created_at->isSameMonth($date))
                    ->flatMap(fn ($order) => $order->items);
                $impactTrend[] = [
                    'label' => $date->format("M 'y"),
                    'waste' => round($monthlyItems->sum(fn ($i) => $i->quantity * (float) (optional($i->product)->waste_factor ?? 1.2)), 1),
                    'carbon' => round($monthlyItems->sum(fn ($i) => $i->quantity * (float) (optional($i->product)->carbon_factor ?? 2.7)), 1),
                ];
            }
        }

        return view('customer.dashboard', compact('products', 'stories', 'stats', 'orders', 'activeOrders', 'impact', 'impactTrend'));
    }

    /**
     * Halaman katalog: semua produk terverifikasi dengan pencarian
     */
    public function catalog(Request $request)
    {
        $products = Product::where('status', 'approved')
            ->where('is_active', 1)
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->string('q')->trim()->toString();

                $query->where(function ($productQuery) use ($term) {
                    $productQuery->where('name', 'like', "%{$term}%")
                        ->orWhere('category', 'like', "%{$term}%")
                        ->orWhere('material_type', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('customer.catalog', compact('products'));
    }

    /**
     * Menampilkan halaman komunitas berisi baris sorotan dan cerita per topic
     */
    public function community()
    {
        $featured = CommunityStory::where('is_active', 1)
            ->where('featured', 1)
            ->orderBy('sort_order')
            ->get();

        $topics = CommunityStory::where('is_active', 1)
            ->whereNotNull('topic')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('topic');

        return view('customer.community', compact('featured', 'topics'));
    }

    /**
     * Menampilkan detail sebuah cerita komunitas beserta produk terkait
     */
    public function communityShow(CommunityStory $story)
    {
        abort_unless($story->is_active, 404);

        $related = Product::where('status', 'approved')
            ->where('is_active', 1)
            ->when($story->material, function ($query) use ($story) {
                $material = $story->material;
                $query->where(function ($productQuery) use ($material) {
                    $productQuery->where('material_type', 'like', "%{$material}%")
                        ->orWhere('category', 'like', "%{$material}%")
                        ->orWhere('name', 'like', "%{$material}%");
                });
            })
            ->latest()
            ->take(6)
            ->get();

        $relatedLabel = 'Karya dari bahan senada';

        // Jika tidak ada produk senada, tampilkan karya pilihan terbaru
        if ($related->isEmpty()) {
            $related = Product::where('status', 'approved')
                ->where('is_active', 1)
                ->latest()
                ->take(6)
                ->get();
            $relatedLabel = 'Karya pilihan EcoCraft';
        }

        $comments = CommunityStoryComment::with('customer')
            ->where('story_id', $story->id_stories)
            ->orderBy('created_at')
            ->get();

        return view('customer.community-show', compact('story', 'related', 'relatedLabel', 'comments'));
    }

    /**
     * Menyimpan komentar/pertanyaan member pada cerita komunitas
     */
    public function storeComment(CommunityStory $story, Request $request)
    {
        abort_unless($story->is_active, 404);

        $validated = $request->validate([
            'comment' => 'required|string|min:3|max:1000',
        ]);

        CommunityStoryComment::create([
            'story_id' => $story->id_stories,
            'customer_id' => Auth::guard('customer')->id(),
            'comment' => $validated['comment'],
        ]);

        return redirect()->to(route('community.show', $story->slug) . '#diskusi')
            ->with('success', 'Komentar berhasil dikirim.');
    }

    /**
     * Menampilkan form edit profile
     */
    public function showProfileForm()
    {
        $user = Auth::guard('customer')->user();
        return view('customer.profile', compact('user'));
    }

    /**
     * Update data profil customer
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::guard('customer')->user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name_customers' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:customers,email,' . $user->id_customers . ',id_customers',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name_customers', 'email']);

        // Jika ada foto baru di-upload
        if ($request->hasFile('profile_image')) {
            // Jangan hapus foto lama (sesuai permintaan kamu sebelumnya)
            $path = $request->file('profile_image')->store('profile-photos', 'public');
            $data['profile_image'] = $path;
        }

        // Update data menggunakan query builder dengan primary key id_customers
        DB::table('customers')->where('id_customers', $user->id_customers)->update($data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
