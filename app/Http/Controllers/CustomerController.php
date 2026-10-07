<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Order;
use App\Models\CommunityStory;
use App\Models\CommunityStoryComment;
use App\Services\ImpactService;

class CustomerController extends Controller
{
    protected ImpactService $impact;

    public function __construct(ImpactService $impact)
    {
        $this->impact = $impact;
    }

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
        $globalWaste = $this->impact->platformWaste();

        $stats = [
            'products' => Product::where('status', 'approved')->where('is_active', 1)->count(),
            'sellers' => Seller::where('status', 'approved')->count(),
            // 1 pohon per ~10 kg limbah dialihkan (estimasi), dihitung dari data nyata.
            'trees' => (int) floor($globalWaste / ImpactService::KG_PER_TREE),
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
            $customer = Auth::guard('customer')->user();
            $orders = Order::with(['items.product', 'items.seller'])
                ->where('customer_id', $customer->getKey())
                ->latest()
                ->get();
            $activeOrders = $orders->whereNotIn('status', ['Delivered', 'Cancelled'])->take(2);

            // Dampak dihitung dari faktor per produk (fallback default bila kosong).
            $impact = $this->impact->forCustomer($customer, $orders);
            $impactTrend = $impact['trend'];
        }

        $greetings = config('greetings.list');

        return view('customer.dashboard', compact('products', 'stories', 'stats', 'orders', 'activeOrders', 'impact', 'impactTrend', 'greetings'));
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
     * Menampilkan halaman Tentang Kami
     */
    public function about()
    {
        $stats = [
            'products' => Product::where('status', 'approved')->where('is_active', 1)->count(),
            'sellers' => Seller::where('status', 'approved')->count(),
            'trees' => (int) floor($this->impact->platformWaste() / ImpactService::KG_PER_TREE),
        ];

        return view('customer.about', compact('stats'));
    }

    /**
     * Menampilkan halaman Kebijakan Privasi
     */
    public function privacyPolicy()
    {
        return $this->legalPage('privacy');
    }

    /**
     * Menampilkan halaman Ketentuan Layanan
     */
    public function termsOfService()
    {
        return $this->legalPage('terms');
    }

    private function legalPage(string $key)
    {
        $doc = config("policies.{$key}");

        abort_if(empty($doc), 404);

        return view('customer.legal', compact('doc'));
    }

    /**
     * Menampilkan form edit profile
     */
    public function showProfileForm()
    {
        $user = Auth::guard('customer')->user();
        $regions = config('indonesia_regions');
        $postalCodes = config('indonesia_postal_codes');
        return view('customer.profile', compact('user', 'regions', 'postalCodes'));
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

        $regions = config('indonesia_regions');

        $request->validate([
            'name_customers' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:customers,email,' . $user->id_customers . ',id_customers',
            'phone_number' => ['required', 'regex:/^(\+62|62|0)8[1-9][0-9]{6,11}$/'],
            'address' => 'required|string|max:255',
            'province' => ['required', 'string', Rule::in(array_keys($regions))],
            'city' => 'required|string|max:100',
            'postal_code' => ['required', 'regex:/^[0-9]{5}$/'],
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'phone_number.regex' => 'Nomor WhatsApp harus nomor Indonesia yang valid (hanya angka, contoh: 081234567890).',
            'postal_code.regex' => 'Kode pos harus 5 digit angka.',
        ]);

        // Pastikan kota benar-benar milik provinsi yang dipilih.
        $validCities = $regions[$request->input('province')] ?? [];
        if (! in_array($request->input('city'), $validCities, true)) {
            return back()
                ->withErrors(['city' => 'Kota / kabupaten tidak sesuai dengan provinsi yang dipilih.'])
                ->withInput();
        }

        $data = $request->only([
            'name_customers', 'email', 'phone_number',
            'address', 'province', 'city', 'postal_code',
        ]);

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
