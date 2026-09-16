<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Seller;
use App\Models\WarrantyClaim;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WarrantyClaimTest extends TestCase
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
            'name_customers' => 'QA Customer '.uniqid(),
            'email' => 'qa-cust-'.uniqid().'@example.test',
            'phone_number' => '081200000011',
            'dob' => '1995-01-01',
            'gender' => 'female',
            'address' => 'Jl. Test 11',
            'province' => 'DIY',
            'city' => 'Yogyakarta',
            'password' => Hash::make('password123'),
        ]);
    }

    private function seller(): Seller
    {
        return Seller::create([
            'name_sellers' => 'QA Seller '.uniqid(),
            'email' => 'qa-seller-'.uniqid().'@example.test',
            'phone_number' => '081200000012',
            'password' => Hash::make('password123'),
            'address' => 'Jl. Test 12',
            'gender' => 'female',
            'province' => 'DIY',
            'city' => 'Yogyakarta',
            'store_name' => 'QA Store '.uniqid(),
            'ktp_image' => 'ktp/test.jpg',
            'status' => 'approved',
        ]);
    }

    private function product(): Product
    {
        return Product::where('status', 'approved')->whereNotNull('seller_id')->firstOrFail();
    }

    private function order(?int $customerId, string $status = 'Delivered'): Order
    {
        return Order::create([
            'customer_id' => $customerId,
            'order_number' => 'QA-'.uniqid(),
            'customer_name' => 'QA',
            'customer_email' => 'qa@example.test',
            'customer_phone' => '081200000012',
            'shipping_address' => 'Jl. Test 12',
            'shipping_city' => 'Yogyakarta',
            'shipping_province' => 'DIY',
            'shipping_postal_code' => '55111',
            'shipping_method' => 'Reguler',
            'payment_method' => 'Transfer Bank',
            'subtotal' => 10000,
            'total' => 10000,
            'status' => $status,
        ]);
    }

    private function item(Order $order, Product $product): OrderItem
    {
        return OrderItem::create([
            'order_id' => $order->id_orders,
            'product_id' => $product->id_products,
            'seller_id' => $product->seller_id,
            'product_name' => $product->name,
            'quantity' => 1,
            'unit_price' => 10000,
            'subtotal' => 10000,
        ]);
    }

    private function claim(Customer $customer, Product $product, Seller $seller, string $status = 'Submitted'): WarrantyClaim
    {
        $order = $this->order($customer->id_customers);
        $item = $this->item($order, $product);

        return WarrantyClaim::create([
            'order_id' => $order->id_orders,
            'order_item_id' => $item->id,
            'customer_id' => $customer->id_customers,
            'seller_id' => $seller->id_sellers,
            'product_id' => $product->id_products,
            'category' => 'jahitan',
            'description' => 'Jahitan tas lepas setelah dipakai dua kali.',
            'status' => $status,
        ]);
    }

    public function test_guest_cannot_open_claims_page(): void
    {
        $this->get(route('customer.claims.index'))->assertRedirect(route('login'));
    }

    public function test_customer_can_claim_delivered_item(): void
    {
        $customer = $this->customer();
        $product = $this->product();
        $order = $this->order($customer->id_customers);
        $item = $this->item($order, $product);

        $this->actingAs($customer, 'customer')
            ->post(route('customer.claims.store'), [
                'order_item_id' => $item->id,
                'category' => 'jahitan',
                'description' => 'Jahitan tas lepas setelah dipakai dua kali.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('warranty_claims', [
            'order_item_id' => $item->id,
            'customer_id' => $customer->id_customers,
            'seller_id' => $product->seller_id,
            'status' => 'Submitted',
        ]);
    }

    public function test_customer_can_claim_shipped_item(): void
    {
        $customer = $this->customer();
        $product = $this->product();
        $order = $this->order($customer->id_customers, 'Shipped');
        $item = $this->item($order, $product);

        $this->actingAs($customer, 'customer')
            ->post(route('customer.claims.store'), [
                'order_item_id' => $item->id,
                'category' => 'kerusakan',
                'description' => 'Paket sampai dalam kondisi penyok.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('warranty_claims', [
            'order_item_id' => $item->id,
            'status' => 'Submitted',
        ]);
    }

    public function test_shipped_item_appears_on_claim_form(): void
    {
        $customer = $this->customer();
        $product = $this->product();
        $order = $this->order($customer->id_customers, 'Shipped');
        $this->item($order, $product);

        $this->actingAs($customer, 'customer')
            ->get(route('customer.claims.create'))
            ->assertOk()
            ->assertSee($product->name)
            ->assertDontSee('Tidak ada produk yang bisa diklaim');
    }

    public function test_customer_cannot_claim_undelivered_order(): void
    {
        $customer = $this->customer();
        $product = $this->product();
        $order = $this->order($customer->id_customers, 'Processing');
        $item = $this->item($order, $product);

        $this->actingAs($customer, 'customer')
            ->post(route('customer.claims.store'), [
                'order_item_id' => $item->id,
                'category' => 'kerusakan',
                'description' => 'Produk rusak saat diterima.',
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('warranty_claims', 0);
    }

    public function test_customer_cannot_claim_someone_elses_order(): void
    {
        $product = $this->product();
        $order = $this->order(null);
        $item = $this->item($order, $product);

        $this->actingAs($this->customer(), 'customer')
            ->post(route('customer.claims.store'), [
                'order_item_id' => $item->id,
                'category' => 'kerusakan',
                'description' => 'Produk rusak saat diterima.',
            ])
            ->assertForbidden();
    }

    public function test_seller_cannot_view_other_sellers_claim(): void
    {
        $claimSeller = $this->seller();
        $otherSeller = $this->seller();

        $claim = $this->claim($this->customer(), $this->product(), $claimSeller);

        $this->actingAs($otherSeller, 'seller')
            ->get(route('seller.claims.show', $claim->id_claims))
            ->assertNotFound();
    }

    public function test_seller_can_respond_to_claim(): void
    {
        $seller = $this->seller();
        $claim = $this->claim($this->customer(), $this->product(), $seller);

        $this->actingAs($seller, 'seller')
            ->post(route('seller.claims.respond', $claim->id_claims), [
                'status' => 'Approved',
                'resolution' => 'Kami perbaiki dan kirim ulang.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('warranty_claims', [
            'id_claims' => $claim->id_claims,
            'status' => 'Approved',
            'resolution' => 'Kami perbaiki dan kirim ulang.',
        ]);

        $this->assertNotNull($claim->fresh()->responded_at);
    }
}
