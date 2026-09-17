<?php

namespace Tests\Feature;

use App\Models\Courier;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Shipment;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ShipmentTest extends TestCase
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
            'name_customers' => 'QA Ship '.uniqid(),
            'email' => 'qa-ship-'.uniqid().'@example.test',
            'phone_number' => '081200000061',
            'dob' => '1995-01-01',
            'gender' => 'female',
            'address' => 'Jl. Kirim 1',
            'province' => 'DIY',
            'city' => 'Yogyakarta',
            'password' => Hash::make('password123'),
        ]);
    }

    private function seller(): Seller
    {
        return Seller::create([
            'name_sellers' => 'QA Ship Seller '.uniqid(),
            'email' => 'qa-ship-seller-'.uniqid().'@example.test',
            'phone_number' => '081200000062',
            'password' => Hash::make('password123'),
            'address' => 'Jl. Seller 2',
            'gender' => 'female',
            'province' => 'DIY',
            'city' => 'Yogyakarta',
            'store_name' => 'QA Ship Store '.uniqid(),
            'ktp_image' => 'ktp/test.jpg',
            'status' => 'approved',
        ]);
    }

    private function product(Seller $seller): Product
    {
        return Product::create([
            'seller_id' => $seller->id_sellers,
            'name' => 'QA Produk Kirim '.uniqid(),
            'slug' => 'qa-produk-kirim-'.uniqid(),
            'description' => 'Produk uji pengiriman.',
            'price' => 75000,
            'category' => 'Home Decor',
            'material_type' => 'Anyaman',
            'in_stock' => true,
            'is_active' => true,
            'status' => 'approved',
            'quantity' => 5,
        ]);
    }

    private function thirdPartyCourier(): Courier
    {
        return Courier::firstOrCreate(
            ['code' => 'qa_courier'],
            ['name' => 'QA Ekspedisi', 'tracking_url' => 'https://example.test/track/{resi}', 'is_active' => true]
        );
    }

    private function localCourier(): Courier
    {
        return Courier::firstOrCreate(
            ['code' => 'qa_local_courier'],
            ['name' => 'QA Kurir Lokal', 'tracking_url' => null, 'is_local_delivery' => true, 'is_active' => true]
        );
    }

    /**
     * Pesanan berisi barang dari beberapa pengrajin.
     */
    private function orderWithItems(array $products, ?Customer $customer = null): Order
    {
        $customer = $customer ?? $this->customer();
        $subtotal = collect($products)->sum(fn ($product) => $product->price);

        $order = Order::create([
            'customer_id' => $customer->id_customers,
            'order_number' => 'ORD-QA-'.uniqid(),
            'customer_name' => $customer->name_customers,
            'customer_email' => $customer->email,
            'customer_phone' => '081200000063',
            'shipping_address' => 'Jl. Tujuan 5',
            'shipping_city' => 'Bandung',
            'shipping_province' => 'Jawa Barat',
            'shipping_postal_code' => '40111',
            'shipping_method' => 'Reguler',
            'payment_method' => 'Transfer Bank',
            'payment_status' => Order::PAYMENT_PAID,
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'status' => 'Processing',
        ]);

        foreach ($products as $product) {
            $order->items()->create([
                'product_id' => $product->id_products,
                'seller_id' => $product->seller_id,
                'product_name' => $product->name,
                'quantity' => 1,
                'unit_price' => $product->price,
                'subtotal' => $product->price,
            ]);
        }

        return $order;
    }

    public function test_shipment_created_per_seller_when_order_has_multiple_sellers(): void
    {
        $first = $this->seller();
        $second = $this->seller();
        $order = $this->orderWithItems([
            $this->product($first),
            $this->product($second),
        ]);

        $this->actingAs($first, 'seller')
            ->get(route('seller.shipments.index'))
            ->assertOk();

        $this->assertSame(2, Shipment::where('order_id', $order->id_orders)->count());
        $this->assertDatabaseHas('shipments', [
            'order_id' => $order->id_orders,
            'seller_id' => $first->id_sellers,
            'status' => Shipment::STATUS_PENDING,
        ]);
        $this->assertDatabaseHas('shipments', [
            'order_id' => $order->id_orders,
            'seller_id' => $second->id_sellers,
            'status' => Shipment::STATUS_PENDING,
        ]);
    }

    public function test_syncing_shipments_twice_does_not_duplicate(): void
    {
        $seller = $this->seller();
        $order = $this->orderWithItems([$this->product($seller)]);

        $this->actingAs($seller, 'seller')->get(route('seller.shipments.index'))->assertOk();
        $this->actingAs($seller, 'seller')->get(route('seller.shipments.index'))->assertOk();

        $shipment = Shipment::where('order_id', $order->id_orders)->firstOrFail();

        $this->assertSame(1, Shipment::where('order_id', $order->id_orders)->count());
        $this->assertSame(1, $shipment->events()->count());
        $this->assertSame(Shipment::STATUS_PENDING, $shipment->status);
    }

    public function test_seller_can_mark_shipment_shipped_with_tracking_number(): void
    {
        $seller = $this->seller();
        $order = $this->orderWithItems([$this->product($seller)]);
        $this->actingAs($seller, 'seller')->get(route('seller.shipments.index'))->assertOk();

        $shipment = Shipment::where('order_id', $order->id_orders)->firstOrFail();

        $this->actingAs($seller, 'seller')
            ->put(route('seller.shipments.update', $shipment), [
                'courier_id' => $this->thirdPartyCourier()->id_couriers,
                'tracking_number' => 'QA123456789',
                'status' => Shipment::STATUS_SHIPPED,
            ])
            ->assertRedirect(route('seller.shipments.show', $shipment));

        $shipment->refresh();

        $this->assertSame(Shipment::STATUS_SHIPPED, $shipment->status);
        $this->assertSame('QA123456789', $shipment->tracking_number);
        $this->assertNotNull($shipment->shipped_at);
        $this->assertSame('Shipped', $order->fresh()->status);
        $this->assertSame(
            [Shipment::STATUS_PENDING, Shipment::STATUS_SHIPPED],
            $shipment->events()->pluck('status')->all()
        );
    }

    public function test_tracking_number_is_required_before_marking_shipped(): void
    {
        $seller = $this->seller();
        $order = $this->orderWithItems([$this->product($seller)]);
        $this->actingAs($seller, 'seller')->get(route('seller.shipments.index'))->assertOk();

        $shipment = Shipment::where('order_id', $order->id_orders)->firstOrFail();

        $this->actingAs($seller, 'seller')
            ->put(route('seller.shipments.update', $shipment), [
                'courier_id' => $this->thirdPartyCourier()->id_couriers,
                'status' => Shipment::STATUS_SHIPPED,
            ])
            ->assertSessionHasErrors('tracking_number');

        $this->assertSame(Shipment::STATUS_PENDING, $shipment->fresh()->status);
    }

    public function test_order_is_not_marked_delivered_until_every_seller_shipment_is_confirmed(): void
    {
        $customer = $this->customer();
        $first = $this->seller();
        $second = $this->seller();
        $order = $this->orderWithItems([
            $this->product($first),
            $this->product($second),
        ], $customer);

        $this->actingAs($first, 'seller')->get(route('seller.shipments.index'))->assertOk();

        $firstShipment = Shipment::where('order_id', $order->id_orders)->where('seller_id', $first->id_sellers)->firstOrFail();
        $secondShipment = Shipment::where('order_id', $order->id_orders)->where('seller_id', $second->id_sellers)->firstOrFail();

        foreach ([[$first, $firstShipment, 'QA-SELLER-1'], [$second, $secondShipment, 'QA-SELLER-2']] as [$seller, $shipment, $resi]) {
            $this->actingAs($seller, 'seller')
                ->put(route('seller.shipments.update', $shipment), [
                    'courier_id' => $this->thirdPartyCourier()->id_couriers,
                    'tracking_number' => $resi,
                    'status' => Shipment::STATUS_SHIPPED,
                ])
                ->assertRedirect();
        }

        $this->assertSame('Shipped', $order->fresh()->status);

        // Baru satu paket yang dikonfirmasi tiba, jadi pesanan belum selesai.
        $this->actingAs($customer, 'customer')
            ->post(route('customer.shipments.confirm', $firstShipment), ['receiver_name' => 'QA Penerima'])
            ->assertRedirect();

        $this->assertSame('Shipped', $order->fresh()->status);

        // Paket kedua tiba: pesanan baru menjadi Delivered.
        $this->actingAs($customer, 'customer')
            ->post(route('customer.shipments.confirm', $secondShipment), ['receiver_name' => 'QA Penerima'])
            ->assertRedirect();

        $this->assertSame('Delivered', $order->fresh()->status);
    }

    public function test_seller_marks_local_package_ready_for_pickup(): void
    {
        $seller = $this->seller();
        $order = $this->orderWithItems([$this->product($seller)]);
        $this->actingAs($seller, 'seller')->get(route('seller.shipments.index'))->assertOk();

        $shipment = Shipment::where('order_id', $order->id_orders)->firstOrFail();

        // Pengrajin hanya menyatakan paket siap; status perjalanan dikelola kurir.
        $this->actingAs($seller, 'seller')
            ->put(route('seller.shipments.update', $shipment), [
                'courier_id' => $this->localCourier()->id_couriers,
            ])
            ->assertRedirect();

        $this->assertSame(Shipment::STATUS_PACKED, $shipment->fresh()->status);
        $this->assertNull($shipment->fresh()->courier_user_id);
    }

    public function test_seller_cannot_change_a_shipment_after_handover(): void
    {
        $seller = $this->seller();
        $order = $this->orderWithItems([$this->product($seller)]);
        $this->actingAs($seller, 'seller')->get(route('seller.shipments.index'))->assertOk();

        $shipment = Shipment::where('order_id', $order->id_orders)->firstOrFail();

        $this->actingAs($seller, 'seller')
            ->put(route('seller.shipments.update', $shipment), [
                'courier_id' => $this->thirdPartyCourier()->id_couriers,
                'tracking_number' => 'QA-HANDOVER-1',
            ])
            ->assertRedirect();

        $this->assertSame(Shipment::STATUS_SHIPPED, $shipment->fresh()->status);

        // Setelah diserahkan, pengelolaannya ada di kurir atau ekspedisi.
        $this->actingAs($seller, 'seller')
            ->put(route('seller.shipments.update', $shipment), [
                'courier_id' => $this->thirdPartyCourier()->id_couriers,
                'tracking_number' => 'QA-DIUBAH-LAGI',
            ])
            ->assertSessionHas('error');

        $this->assertSame('QA-HANDOVER-1', $shipment->fresh()->tracking_number);

        // Halaman berubah jadi mode baca saja, tanpa form pengelolaan.
        $this->actingAs($seller, 'seller')
            ->get(route('seller.shipments.show', $shipment))
            ->assertOk()
            ->assertSee('Paket sudah diserahkan')
            ->assertDontSee('Serahkan paket');
    }

    public function test_seller_cannot_ship_an_unpaid_order(): void
    {
        $seller = $this->seller();
        $order = $this->orderWithItems([$this->product($seller)]);
        $order->update(['payment_status' => Order::PAYMENT_UNPAID]);

        $this->actingAs($seller, 'seller')->get(route('seller.shipments.index'))->assertOk();
        $shipment = Shipment::where('order_id', $order->id_orders)->firstOrFail();

        $this->actingAs($seller, 'seller')
            ->put(route('seller.shipments.update', $shipment), [
                'courier_id' => $this->thirdPartyCourier()->id_couriers,
                'tracking_number' => 'QA-UNPAID-1',
            ])
            ->assertSessionHasErrors('courier_id');

        $this->assertSame(Shipment::STATUS_PENDING, $shipment->fresh()->status);
    }

    public function test_customer_confirms_receipt_with_proof_photo(): void
    {
        Storage::fake('public');

        $customer = $this->customer();
        $seller = $this->seller();
        $order = $this->orderWithItems([$this->product($seller)], $customer);

        $this->actingAs($seller, 'seller')->get(route('seller.shipments.index'))->assertOk();
        $shipment = Shipment::where('order_id', $order->id_orders)->firstOrFail();

        $this->actingAs($seller, 'seller')
            ->put(route('seller.shipments.update', $shipment), [
                'courier_id' => $this->thirdPartyCourier()->id_couriers,
                'tracking_number' => 'QA-CONFIRM-1',
                'status' => Shipment::STATUS_SHIPPED,
            ])
            ->assertRedirect();

        $this->actingAs($customer, 'customer')
            ->post(route('customer.shipments.confirm', $shipment), [
                'receiver_name' => 'Ibu Sari',
                'proof_photo' => UploadedFile::fake()->image('bukti.jpg'),
            ])
            ->assertRedirect();

        $shipment->refresh();

        $this->assertSame(Shipment::STATUS_DELIVERED, $shipment->status);
        $this->assertSame('Ibu Sari', $shipment->receiver_name);
        $this->assertNotNull($shipment->delivered_at);
        $this->assertNotNull($shipment->proof_photo);
        Storage::disk('public')->assertExists($shipment->proof_photo);
        $this->assertSame('Delivered', $order->fresh()->status);

        $this->assertDatabaseHas('order_tracking_events', [
            'shipment_id' => $shipment->id_shipments,
            'status' => Shipment::STATUS_DELIVERED,
            'source' => Shipment::SOURCE_CUSTOMER,
        ]);
    }

    public function test_customer_cannot_confirm_another_customers_shipment(): void
    {
        $owner = $this->customer();
        $other = $this->customer();
        $seller = $this->seller();
        $order = $this->orderWithItems([$this->product($seller)], $owner);

        $this->actingAs($seller, 'seller')->get(route('seller.shipments.index'))->assertOk();
        $shipment = Shipment::where('order_id', $order->id_orders)->firstOrFail();

        $this->actingAs($seller, 'seller')
            ->put(route('seller.shipments.update', $shipment), [
                'courier_id' => $this->thirdPartyCourier()->id_couriers,
                'tracking_number' => 'QA-OTHER-1',
                'status' => Shipment::STATUS_SHIPPED,
            ])
            ->assertRedirect();

        $this->actingAs($other, 'customer')
            ->post(route('customer.shipments.confirm', $shipment), ['receiver_name' => 'Bukan Pemilik'])
            ->assertNotFound();

        $this->assertSame(Shipment::STATUS_SHIPPED, $shipment->fresh()->status);
    }

    public function test_customer_cannot_confirm_shipment_that_has_not_been_sent(): void
    {
        $customer = $this->customer();
        $seller = $this->seller();
        $order = $this->orderWithItems([$this->product($seller)], $customer);

        $this->actingAs($seller, 'seller')->get(route('seller.shipments.index'))->assertOk();
        $shipment = Shipment::where('order_id', $order->id_orders)->firstOrFail();

        $this->actingAs($customer, 'customer')
            ->post(route('customer.shipments.confirm', $shipment), ['receiver_name' => 'QA Penerima'])
            ->assertSessionHas('error');

        $this->assertSame(Shipment::STATUS_PENDING, $shipment->fresh()->status);
    }

    public function test_seller_cannot_manage_another_sellers_shipment(): void
    {
        $owner = $this->seller();
        $other = $this->seller();
        $order = $this->orderWithItems([$this->product($owner)]);
        $this->actingAs($owner, 'seller')->get(route('seller.shipments.index'))->assertOk();

        $shipment = Shipment::where('order_id', $order->id_orders)->firstOrFail();

        $this->actingAs($other, 'seller')
            ->get(route('seller.shipments.show', $shipment))
            ->assertNotFound();

        $this->actingAs($other, 'seller')
            ->put(route('seller.shipments.update', $shipment), [
                'status' => Shipment::STATUS_DELIVERED,
            ])
            ->assertNotFound();
    }

    public function test_customer_sees_shipment_timeline_and_tracking_link(): void
    {
        $customer = $this->customer();
        $seller = $this->seller();
        $order = $this->orderWithItems([$this->product($seller)], $customer);

        $this->actingAs($seller, 'seller')->get(route('seller.shipments.index'))->assertOk();
        $shipment = Shipment::where('order_id', $order->id_orders)->firstOrFail();

        $this->actingAs($seller, 'seller')
            ->put(route('seller.shipments.update', $shipment), [
                'courier_id' => $this->thirdPartyCourier()->id_couriers,
                'tracking_number' => 'QA-TRACK-99',
                'status' => Shipment::STATUS_SHIPPED,
            ])
            ->assertRedirect();

        $this->actingAs($customer, 'customer')
            ->get(route('track.track'))
            ->assertOk()
            ->assertSee('QA-TRACK-99')
            ->assertSee('QA Ekspedisi')
            ->assertSee('https://example.test/track/QA-TRACK-99', false);
    }

    public function test_tracking_timeline_shows_the_newest_status_first(): void
    {
        $customer = $this->customer();
        $seller = $this->seller();
        $order = $this->orderWithItems([$this->product($seller)], $customer);

        $this->actingAs($seller, 'seller')->get(route('seller.shipments.index'))->assertOk();
        $shipment = Shipment::where('order_id', $order->id_orders)->firstOrFail();

        $this->actingAs($seller, 'seller')
            ->put(route('seller.shipments.update', $shipment), [
                'courier_id' => $this->thirdPartyCourier()->id_couriers,
                'tracking_number' => 'QA-URUT-1',
            ])
            ->assertRedirect();

        $html = $this->actingAs($customer, 'customer')
            ->get(route('track.track'))
            ->assertOk()
            ->getContent();

        $terbaru = strpos($html, 'diserahkan ke');
        $terlama = strpos($html, 'disiapkan oleh pengrajin');

        $this->assertNotFalse($terbaru, 'Peristiwa terbaru tidak ada di timeline.');
        $this->assertNotFalse($terlama, 'Peristiwa terlama tidak ada di timeline.');
        $this->assertLessThan($terlama, $terbaru, 'Status terbaru harus tampil di atas status sebelumnya.');
    }

    public function test_seller_items_only_include_their_own_products(): void
    {
        $first = $this->seller();
        $second = $this->seller();
        $order = $this->orderWithItems([
            $this->product($first),
            $this->product($second),
        ]);

        $this->actingAs($first, 'seller')->get(route('seller.shipments.index'))->assertOk();

        $shipment = Shipment::where('order_id', $order->id_orders)->where('seller_id', $first->id_sellers)->firstOrFail();

        $this->actingAs($first, 'seller')
            ->get(route('seller.shipments.show', $shipment))
            ->assertOk();

        $this->assertCount(1, $shipment->sellerItems());
        $this->assertSame($first->id_sellers, $shipment->sellerItems()->first()->seller_id);
    }
}
