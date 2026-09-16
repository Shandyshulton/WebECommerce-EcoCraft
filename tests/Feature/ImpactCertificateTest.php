<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Services\ImpactService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ImpactCertificateTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    private function customer(): Customer
    {
        return Customer::create([
            'name_customers' => 'QA Dampak '.uniqid(),
            'email' => 'qa-impact-'.uniqid().'@example.test',
            'phone_number' => '081200000071',
            'dob' => '1995-01-01',
            'gender' => 'female',
            'address' => 'Jl. Dampak 1',
            'province' => 'DIY',
            'city' => 'Yogyakarta',
            'password' => Hash::make('password123'),
        ]);
    }

    private function seller(): Seller
    {
        return Seller::create([
            'name_sellers' => 'QA Dampak Seller '.uniqid(),
            'email' => 'qa-impact-seller-'.uniqid().'@example.test',
            'phone_number' => '081200000072',
            'password' => Hash::make('password123'),
            'address' => 'Jl. Seller 3',
            'gender' => 'female',
            'province' => 'DIY',
            'city' => 'Yogyakarta',
            'store_name' => 'QA Dampak Store '.uniqid(),
            'ktp_image' => 'ktp/test.jpg',
            'status' => 'approved',
        ]);
    }

    private function product(Seller $seller, float $waste = 4.0, float $carbon = 6.5): Product
    {
        return Product::create([
            'seller_id' => $seller->id_sellers,
            'name' => 'QA Produk Dampak '.uniqid(),
            'slug' => 'qa-produk-dampak-'.uniqid(),
            'description' => 'Produk uji dampak lingkungan.',
            'price' => 60000,
            'category' => 'Home Decor',
            'material_type' => 'Anyaman',
            'waste_factor' => $waste,
            'carbon_factor' => $carbon,
            'in_stock' => true,
            'is_active' => true,
            'status' => 'approved',
            'quantity' => 10,
        ]);
    }

    private function orderWith(Customer $customer, Product $product, int $quantity): Order
    {
        $order = Order::create([
            'customer_id' => $customer->id_customers,
            'order_number' => 'ORD-IMP-'.uniqid(),
            'customer_name' => $customer->name_customers,
            'customer_email' => $customer->email,
            'customer_phone' => '081200000073',
            'shipping_address' => 'Jl. Tujuan 7',
            'shipping_city' => 'Bandung',
            'shipping_province' => 'Jawa Barat',
            'shipping_postal_code' => '40111',
            'shipping_method' => 'Reguler',
            'payment_method' => 'Transfer Bank',
            'subtotal' => $product->price * $quantity,
            'total' => $product->price * $quantity,
            'status' => 'Delivered',
        ]);

        $order->items()->create([
            'product_id' => $product->id_products,
            'seller_id' => $product->seller_id,
            'product_name' => $product->name,
            'quantity' => $quantity,
            'unit_price' => $product->price,
            'subtotal' => $product->price * $quantity,
        ]);

        return $order;
    }

    public function test_guest_cannot_open_impact_certificate(): void
    {
        $this->get(route('customer.impact.certificate'))
            ->assertRedirect(route('login'));
    }

    public function test_customer_can_open_impact_certificate(): void
    {
        $customer = $this->customer();

        $this->actingAs($customer, 'customer')
            ->get(route('customer.impact.certificate'))
            ->assertOk()
            ->assertSee('Sertifikat Dampak Lingkungan')
            ->assertSee($customer->name_customers);
    }

    public function test_certificate_shows_impact_computed_from_orders(): void
    {
        $customer = $this->customer();
        $product = $this->product($this->seller(), waste: 4.0, carbon: 6.5);
        $this->orderWith($customer, $product, 3);

        // 3 × 4,0 kg = 12,0 kg limbah; 3 × 6,5 kg = 19,5 kg CO2e.
        $this->actingAs($customer, 'customer')
            ->get(route('customer.impact.certificate'))
            ->assertOk()
            ->assertSee('12,0')
            ->assertSee('19,5')
            ->assertSee('1 pesanan sirkular');
    }

    public function test_certificate_number_is_stable_across_requests(): void
    {
        $customer = $this->customer();

        $first = $this->actingAs($customer, 'customer')
            ->get(route('customer.impact.certificate'))
            ->assertOk();

        preg_match('/ECO-IMP-[A-F0-9]{10}/', $first->getContent(), $matches);
        $this->assertNotEmpty($matches, 'Nomor sertifikat tidak ditemukan pada halaman.');

        $this->actingAs($customer, 'customer')
            ->get(route('customer.impact.certificate'))
            ->assertOk()
            ->assertSee($matches[0]);
    }

    public function test_certificate_handles_customer_without_orders(): void
    {
        $customer = $this->customer();

        $this->actingAs($customer, 'customer')
            ->get(route('customer.impact.certificate'))
            ->assertOk()
            ->assertSee('Belum ada pesanan tercatat');
    }

    public function test_dashboard_still_renders_and_links_to_certificate(): void
    {
        $customer = $this->customer();
        $product = $this->product($this->seller(), waste: 4.0, carbon: 6.5);
        $this->orderWith($customer, $product, 3);

        $this->actingAs($customer, 'customer')
            ->get(route('customer.dashboard'))
            ->assertOk()
            ->assertSee('Dampak Kolektif Belanja Sirkularmu')
            ->assertSee('12.0')
            ->assertSee('19.5')
            ->assertSee(route('customer.impact.certificate'), false);
    }

    public function test_guest_dashboard_still_renders(): void
    {
        $this->get(route('customer.dashboard'))
            ->assertOk()
            ->assertSee('Selamat datang di EcoCraft');
    }

    public function test_product_page_shows_impact_factors(): void
    {
        $product = $this->product($this->seller(), waste: 4.0, carbon: 6.5);

        $this->get(route('products.show', $product->id_products))
            ->assertOk()
            ->assertSee('Limbah dialihkan')
            ->assertSee('Emisi dihindari')
            ->assertSee('4,00 kg / pcs')
            ->assertSee('6,50 kg CO');
    }

    public function test_impact_service_returns_six_month_trend(): void
    {
        $customer = $this->customer();
        $product = $this->product($this->seller());
        $this->orderWith($customer, $product, 2);

        $impact = app(ImpactService::class)->forCustomer($customer);

        $this->assertCount(6, $impact['trend']);
        $this->assertSame(8.0, $impact['waste']);
        $this->assertSame(13.0, $impact['carbon']);
        $this->assertSame(1, $impact['artisans']);
        $this->assertSame(1, $impact['orders']);
        $this->assertSame(2, $impact['items']);
    }
}
