<?php

namespace Tests\Feature;

use App\Models\Courier;
use App\Models\CourierUser;
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

class CourierTaskTest extends TestCase
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
            'name_customers' => 'QA Kurir '.uniqid(),
            'email' => 'qa-courier-'.uniqid().'@example.test',
            'phone_number' => '081200000091',
            'dob' => '1995-01-01',
            'gender' => 'female',
            'address' => 'Jl. Penerima 1',
            'province' => 'DIY',
            'city' => 'Yogyakarta',
            'password' => Hash::make('password123'),
        ]);
    }

    private function seller(): Seller
    {
        return Seller::create([
            'name_sellers' => 'QA Kurir Seller '.uniqid(),
            'email' => 'qa-courier-seller-'.uniqid().'@example.test',
            'phone_number' => '081200000092',
            'password' => Hash::make('password123'),
            'address' => 'Jl. Pengrajin 1',
            'gender' => 'female',
            'province' => 'DIY',
            'city' => 'Yogyakarta',
            'store_name' => 'QA Kurir Store '.uniqid(),
            'ktp_image' => 'ktp/test.jpg',
            'status' => 'approved',
        ]);
    }

    private function product(Seller $seller): Product
    {
        return Product::create([
            'seller_id' => $seller->id_sellers,
            'name' => 'QA Produk Kurir '.uniqid(),
            'slug' => 'qa-produk-kurir-'.uniqid(),
            'description' => 'Produk uji kurir.',
            'price' => 90000,
            'category' => 'Home Decor',
            'material_type' => 'Anyaman',
            'in_stock' => true,
            'is_active' => true,
            'status' => 'approved',
            'quantity' => 5,
        ]);
    }

    private function localCourier(): Courier
    {
        return Courier::firstOrCreate(
            ['code' => 'qa_local_courier'],
            ['name' => 'QA Kurir Lokal', 'tracking_url' => null, 'is_local_delivery' => true, 'is_active' => true]
        );
    }

    private function courierUser(bool $active = true): CourierUser
    {
        return CourierUser::create([
            'courier_id' => $this->localCourier()->id_couriers,
            'name' => 'QA Petugas '.uniqid(),
            'email' => 'qa-petugas-'.uniqid().'@example.test',
            'phone_number' => '081200000093',
            'password' => Hash::make('password123'),
            'is_active' => $active,
        ]);
    }

    /**
     * Pesanan berisi satu paket yang sudah disiapkan pengrajin memakai kurir lokal.
     */
    private function shipmentReadyForPickup(): Shipment
    {
        $seller = $this->seller();
        $customer = $this->customer();
        $product = $this->product($seller);

        $order = Order::create([
            'customer_id' => $customer->id_customers,
            'order_number' => 'ORD-KURIR-'.uniqid(),
            'customer_name' => $customer->name_customers,
            'customer_email' => $customer->email,
            'customer_phone' => '081200000094',
            'shipping_address' => 'Jl. Tujuan 11',
            'shipping_city' => 'Bandung',
            'shipping_province' => 'Jawa Barat',
            'shipping_postal_code' => '40111',
            'shipping_method' => 'Sameday',
            'payment_method' => 'Transfer Bank',
            'payment_status' => Order::PAYMENT_PAID,
            'subtotal' => $product->price,
            'total' => $product->price,
            'status' => 'Processing',
        ]);

        $order->items()->create([
            'product_id' => $product->id_products,
            'seller_id' => $seller->id_sellers,
            'product_name' => $product->name,
            'quantity' => 1,
            'unit_price' => $product->price,
            'subtotal' => $product->price,
        ]);

        $this->actingAs($seller, 'seller')->get(route('seller.shipments.index'))->assertOk();

        $shipment = Shipment::where('order_id', $order->id_orders)->firstOrFail();

        $this->actingAs($seller, 'seller')
            ->put(route('seller.shipments.update', $shipment), [
                'courier_id' => $this->localCourier()->id_couriers,
                'status' => Shipment::STATUS_PACKED,
            ])
            ->assertRedirect();

        // Segarkan supaya pemanggil melihat status terbaru, bukan salinan lama.
        return $shipment->refresh();
    }

    public function test_guest_is_redirected_from_courier_area(): void
    {
        $this->get(route('courier.tasks.index'))->assertRedirect(route('courier.login'));
    }

    public function test_courier_can_login_and_see_tasks_page(): void
    {
        $courier = $this->courierUser();

        $this->post(route('courier.login.submit'), [
            'email' => $courier->email,
            'password' => 'password123',
        ])->assertRedirect(route('courier.tasks.index'));

        $this->assertAuthenticatedAs($courier, 'courier');
    }

    public function test_inactive_courier_cannot_login(): void
    {
        $courier = $this->courierUser(active: false);

        $this->post(route('courier.login.submit'), [
            'email' => $courier->email,
            'password' => 'password123',
        ])->assertSessionHasErrors('email');

        $this->assertGuest('courier');
    }

    public function test_inactive_courier_is_logged_out_on_access(): void
    {
        $courier = $this->courierUser(active: false);

        $this->actingAs($courier, 'courier')
            ->get(route('courier.tasks.index'))
            ->assertRedirect(route('courier.login'));

        $this->assertGuest('courier');
    }

    public function test_available_package_appears_in_pickup_list(): void
    {
        $shipment = $this->shipmentReadyForPickup();
        $courier = $this->courierUser();

        $this->actingAs($courier, 'courier')
            ->get(route('courier.tasks.index'))
            ->assertOk()
            ->assertSee($shipment->order->order_number);
    }

    public function test_courier_can_claim_a_ready_package(): void
    {
        $shipment = $this->shipmentReadyForPickup();
        $courier = $this->courierUser();

        $this->actingAs($courier, 'courier')
            ->post(route('courier.tasks.claim', $shipment))
            ->assertRedirect(route('courier.tasks.show', $shipment));

        $this->assertSame($courier->getKey(), $shipment->fresh()->courier_user_id);
    }

    public function test_package_cannot_be_claimed_twice(): void
    {
        $shipment = $this->shipmentReadyForPickup();
        $first = $this->courierUser();
        $second = $this->courierUser();

        $this->actingAs($first, 'courier')
            ->post(route('courier.tasks.claim', $shipment))
            ->assertRedirect();

        $this->actingAs($second, 'courier')
            ->post(route('courier.tasks.claim', $shipment))
            ->assertRedirect(route('courier.tasks.index'))
            ->assertSessionHas('error');

        $this->assertSame($first->getKey(), $shipment->fresh()->courier_user_id);
    }

    public function test_courier_cannot_open_another_couriers_task(): void
    {
        $shipment = $this->shipmentReadyForPickup();
        $owner = $this->courierUser();
        $other = $this->courierUser();

        $this->actingAs($owner, 'courier')->post(route('courier.tasks.claim', $shipment))->assertRedirect();

        $this->actingAs($other, 'courier')
            ->get(route('courier.tasks.show', $shipment))
            ->assertNotFound();

        $this->actingAs($other, 'courier')
            ->put(route('courier.tasks.update', $shipment), [])
            ->assertNotFound();
    }

    public function test_courier_delivery_requires_photo_and_receiver_name(): void
    {
        Storage::fake('public');

        $shipment = $this->shipmentReadyForPickup();
        $courier = $this->courierUser();

        $this->actingAs($courier, 'courier')->post(route('courier.tasks.claim', $shipment))->assertRedirect();

        $this->actingAs($courier, 'courier')
            ->put(route('courier.tasks.update', $shipment), [])
            ->assertSessionHasErrors(['receiver_name', 'proof_photo']);

        $this->assertNotSame(Shipment::STATUS_DELIVERED, $shipment->fresh()->status);
    }

    public function test_unpaid_order_does_not_appear_in_pickup_list(): void
    {
        $shipment = $this->shipmentReadyForPickup();
        $shipment->order->update(['payment_status' => Order::PAYMENT_UNPAID]);
        $courier = $this->courierUser();

        $this->actingAs($courier, 'courier')
            ->get(route('courier.tasks.index'))
            ->assertOk()
            ->assertDontSee($shipment->order->order_number);
    }

    public function test_courier_cannot_deliver_an_unpaid_order(): void
    {
        Storage::fake('public');

        $shipment = $this->shipmentReadyForPickup();
        $courier = $this->courierUser();

        $this->actingAs($courier, 'courier')->post(route('courier.tasks.claim', $shipment))->assertRedirect();

        // Pembayaran bisa dibatalkan setelah paket diambil, jadi guard tetap berlaku.
        $shipment->order->update(['payment_status' => Order::PAYMENT_UNPAID]);

        $this->actingAs($courier, 'courier')
            ->put(route('courier.tasks.update', $shipment), [
                'receiver_name' => 'Bapak Andi',
                'proof_photo' => UploadedFile::fake()->image('serah-terima.jpg'),
            ])
            ->assertSessionHasErrors('receiver_name');

        $this->assertSame(Shipment::STATUS_SHIPPED, $shipment->fresh()->status);
    }

    public function test_courier_can_open_the_task_detail(): void
    {
        $shipment = $this->shipmentReadyForPickup();
        $courier = $this->courierUser();

        $this->actingAs($courier, 'courier')->post(route('courier.tasks.claim', $shipment))->assertRedirect();

        $this->actingAs($courier, 'courier')
            ->get(route('courier.tasks.show', $shipment))
            ->assertOk()
            ->assertSee('Tambah titik perjalanan')
            ->assertSee($shipment->order->order_number);
    }

    public function test_courier_can_append_a_tracking_checkpoint(): void
    {
        $shipment = $this->shipmentReadyForPickup();
        $courier = $this->courierUser();

        $this->actingAs($courier, 'courier')->post(route('courier.tasks.claim', $shipment))->assertRedirect();

        $this->actingAs($courier, 'courier')
            ->post(route('courier.tasks.events.store', $shipment), [
                'description' => 'Paket sedang diantar',
                'location' => 'Jl. Malioboro',
            ])
            ->assertRedirect(route('courier.tasks.show', $shipment));

        // Titik perjalanan memakai status paket saat itu sebagai penanda waktu.
        $this->assertDatabaseHas('order_tracking_events', [
            'shipment_id' => $shipment->id_shipments,
            'status' => Shipment::STATUS_SHIPPED,
            'location' => 'Jl. Malioboro',
            'source' => Shipment::SOURCE_COURIER,
        ]);
    }

    public function test_courier_cannot_append_a_checkpoint_to_another_couriers_task(): void
    {
        $shipment = $this->shipmentReadyForPickup();
        $owner = $this->courierUser();
        $other = $this->courierUser();

        $this->actingAs($owner, 'courier')->post(route('courier.tasks.claim', $shipment))->assertRedirect();

        $this->actingAs($other, 'courier')
            ->post(route('courier.tasks.events.store', $shipment), [
                'description' => 'Coba catat',
            ])
            ->assertNotFound();
    }

    public function test_courier_marks_delivered_with_proof_photo(): void
    {
        Storage::fake('public');

        $shipment = $this->shipmentReadyForPickup();
        $courier = $this->courierUser();

        $this->actingAs($courier, 'courier')->post(route('courier.tasks.claim', $shipment))->assertRedirect();

        $this->actingAs($courier, 'courier')
            ->put(route('courier.tasks.update', $shipment), [
                'receiver_name' => 'Bapak Andi',
                'proof_photo' => UploadedFile::fake()->image('serah-terima.jpg'),
            ])
            ->assertRedirect(route('courier.tasks.show', $shipment));

        $shipment->refresh();

        $this->assertSame(Shipment::STATUS_DELIVERED, $shipment->status);
        $this->assertSame('Bapak Andi', $shipment->receiver_name);
        $this->assertNotNull($shipment->proof_photo);
        Storage::disk('public')->assertExists($shipment->proof_photo);
        $this->assertSame('Delivered', $shipment->order->fresh()->status);

        $this->assertDatabaseHas('order_tracking_events', [
            'shipment_id' => $shipment->id_shipments,
            'status' => Shipment::STATUS_DELIVERED,
            'source' => Shipment::SOURCE_COURIER,
        ]);
    }

    public function test_adding_a_checkpoint_keeps_the_assigned_courier(): void
    {
        $shipment = $this->shipmentReadyForPickup();
        $courier = $this->courierUser();

        $this->actingAs($courier, 'courier')->post(route('courier.tasks.claim', $shipment))->assertRedirect();

        $this->actingAs($courier, 'courier')
            ->post(route('courier.tasks.events.store', $shipment), [
                'description' => 'Paket sedang diantar',
                'location' => 'Jl. Malioboro',
            ])
            ->assertRedirect();

        $shipment->refresh();

        // Jalur ini tidak mengirim courier_id, jadi nilainya tidak boleh terhapus.
        $this->assertSame($this->localCourier()->id_couriers, $shipment->courier_id);
        $this->assertSame($courier->getKey(), $shipment->courier_user_id);
        $this->assertSame(Shipment::STATUS_SHIPPED, $shipment->status);
    }

    public function test_claiming_a_task_marks_the_package_shipped(): void
    {
        $shipment = $this->shipmentReadyForPickup();
        $courier = $this->courierUser();

        $this->assertSame(Shipment::STATUS_PACKED, $shipment->status);

        $this->actingAs($courier, 'courier')
            ->post(route('courier.tasks.claim', $shipment))
            ->assertRedirect(route('courier.tasks.show', $shipment));

        $shipment->refresh();

        // Mengambil tugas berarti paket berpindah ke tangan kurir.
        $this->assertSame(Shipment::STATUS_SHIPPED, $shipment->status);
        $this->assertNotNull($shipment->shipped_at);
        $this->assertSame($courier->getKey(), $shipment->courier_user_id);
        $this->assertSame('Shipped', $shipment->order->fresh()->status);
    }
}
