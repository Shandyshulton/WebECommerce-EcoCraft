<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PaymentTest extends TestCase
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
            'name_customers' => 'QA Bayar '.uniqid(),
            'email' => 'qa-pay-'.uniqid().'@example.test',
            'phone_number' => '081200000301',
            'dob' => '1995-01-01',
            'gender' => 'female',
            'address' => 'Jl. Bayar 1',
            'province' => 'DIY',
            'city' => 'Yogyakarta',
            'password' => Hash::make('password123'),
        ]);
    }

    private function seller(): Seller
    {
        return Seller::create([
            'name_sellers' => 'QA Bayar Seller '.uniqid(),
            'email' => 'qa-pay-seller-'.uniqid().'@example.test',
            'phone_number' => '081200000302',
            'password' => Hash::make('password123'),
            'address' => 'Jl. Seller Bayar',
            'gender' => 'female',
            'province' => 'DIY',
            'city' => 'Yogyakarta',
            'store_name' => 'QA Bayar Store '.uniqid(),
            'ktp_image' => 'ktp/test.jpg',
            'status' => 'approved',
        ]);
    }

    private function product(int $price = 120000): Product
    {
        return Product::create([
            'seller_id' => $this->seller()->id_sellers,
            'name' => 'QA Produk Bayar '.uniqid(),
            'slug' => 'qa-produk-bayar-'.uniqid(),
            'description' => 'Produk uji pembayaran.',
            'price' => $price,
            'category' => 'Home Decor',
            'material_type' => 'Anyaman',
            'in_stock' => true,
            'is_active' => true,
            'status' => 'approved',
            'quantity' => 5,
        ]);
    }

    private function address(Customer $customer): CustomerAddress
    {
        return CustomerAddress::create([
            'customer_id' => $customer->id_customers,
            'label' => 'Rumah',
            'recipient_name' => 'QA Penerima',
            'phone' => '081200000303',
            'address' => 'Jl. Alamat Bayar 1',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'postal_code' => '40111',
            'is_default' => true,
        ]);
    }

    private function order(Customer $customer, string $method = Order::PAYMENT_TRANSFER, float $total = 120000): Order
    {
        return Order::create([
            'customer_id' => $customer->id_customers,
            'order_number' => 'ORD-PAY-'.uniqid(),
            'customer_name' => $customer->name_customers,
            'customer_email' => $customer->email,
            'customer_phone' => '081200000303',
            'shipping_address' => 'Jl. Alamat Bayar 1',
            'shipping_city' => 'Bandung',
            'shipping_province' => 'Jawa Barat',
            'shipping_postal_code' => '40111',
            'shipping_method' => 'Reguler',
            'payment_method' => $method,
            'subtotal' => $total,
            'total' => $total,
            'status' => 'Processing',
        ]);
    }

    public function test_checkout_with_transfer_goes_to_payment_page(): void
    {
        $customer = $this->customer();
        $product = $this->product();
        $address = $this->address($customer);

        $this->actingAs($customer, 'customer')
            ->withSession(['cart' => [$product->id_products => 1]])
            ->post(route('checkout.store'), [
                'customer_phone' => '081200000303',
                'address_choice' => (string) $address->id_addresses,
                'shipping_method' => 'Reguler',
                'payment_method' => Order::PAYMENT_TRANSFER,
            ])
            ->assertRedirect(route('payment.show', Order::where('customer_id', $customer->id_customers)->firstOrFail()));
    }

    public function test_checkout_with_qris_goes_to_payment_page(): void
    {
        $customer = $this->customer();
        $product = $this->product();
        $address = $this->address($customer);

        $this->actingAs($customer, 'customer')
            ->withSession(['cart' => [$product->id_products => 1]])
            ->post(route('checkout.store'), [
                'customer_phone' => '081200000303',
                'address_choice' => (string) $address->id_addresses,
                'shipping_method' => 'Reguler',
                'payment_method' => Order::PAYMENT_QRIS,
            ])
            ->assertRedirect(route('payment.show', Order::where('customer_id', $customer->id_customers)->firstOrFail()));
    }

    public function test_checkout_with_cod_goes_straight_to_tracking(): void
    {
        $customer = $this->customer();
        $product = $this->product();
        $address = $this->address($customer);

        $this->actingAs($customer, 'customer')
            ->withSession(['cart' => [$product->id_products => 1]])
            ->post(route('checkout.store'), [
                'customer_phone' => '081200000303',
                'address_choice' => (string) $address->id_addresses,
                'shipping_method' => 'Reguler',
                'payment_method' => Order::PAYMENT_COD,
            ])
            ->assertRedirect(route('track.track'));
    }

    public function test_transfer_page_shows_virtual_account(): void
    {
        $customer = $this->customer();
        $order = $this->order($customer, Order::PAYMENT_TRANSFER);

        $response = $this->actingAs($customer, 'customer')
            ->get(route('payment.show', $order))
            ->assertOk()
            ->assertSee('Transfer ke Virtual Account');

        $order->refresh();

        $this->assertNotNull($order->virtual_account);
        $response->assertSee($order->virtual_account);
    }

    public function test_virtual_account_stays_the_same_across_visits(): void
    {
        $customer = $this->customer();
        $order = $this->order($customer, Order::PAYMENT_TRANSFER);

        $this->actingAs($customer, 'customer')->get(route('payment.show', $order))->assertOk();
        $first = $order->fresh()->virtual_account;

        $this->actingAs($customer, 'customer')->get(route('payment.show', $order))->assertOk();

        $this->assertSame($first, $order->fresh()->virtual_account);
    }

    public function test_qris_page_shows_payload_with_order_reference(): void
    {
        $customer = $this->customer();
        $order = $this->order($customer, Order::PAYMENT_QRIS);

        $payload = app(PaymentService::class)->qrisPayload($order);

        $this->assertStringContainsString($order->order_number, $payload);
        $this->assertStringContainsString('120000.00', $payload);
        $this->assertStringStartsWith('000201', $payload);

        $this->actingAs($customer, 'customer')
            ->get(route('payment.show', $order))
            ->assertOk()
            ->assertSee('Pindai kode QRIS')
            ->assertSee($order->order_number);
    }

    public function test_two_orders_with_the_same_total_have_different_qr_payloads(): void
    {
        $customer = $this->customer();
        $first = $this->order($customer, Order::PAYMENT_QRIS, 120000);
        $second = $this->order($customer, Order::PAYMENT_QRIS, 120000);

        $payments = app(PaymentService::class);

        $this->assertNotSame($payments->qrisPayload($first), $payments->qrisPayload($second));
    }

    public function test_crc16_matches_the_published_check_value(): void
    {
        // Nilai acuan CRC-16/CCITT-FALSE untuk string "123456789".
        $this->assertSame('29B1', app(PaymentService::class)->crc16('123456789'));
    }

    public function test_payload_crc_matches_its_own_content(): void
    {
        $order = $this->order($this->customer(), Order::PAYMENT_QRIS);
        $payments = app(PaymentService::class);

        $payload = $payments->qrisPayload($order);
        $withoutCrc = substr($payload, 0, -4);

        $this->assertStringEndsWith($payments->crc16($withoutCrc), $payload);
    }

    public function test_customer_can_confirm_payment_and_sees_paid_state(): void
    {
        $customer = $this->customer();
        $order = $this->order($customer, Order::PAYMENT_TRANSFER);

        $this->actingAs($customer, 'customer')
            ->post(route('payment.confirm', $order))
            ->assertRedirect(route('payment.show', $order));

        $order->refresh();

        $this->assertTrue($order->isPaid());
        $this->assertNotNull($order->paid_at);

        $this->actingAs($customer, 'customer')
            ->get(route('payment.show', $order))
            ->assertOk()
            ->assertSee('Pembayaran berhasil');
    }

    public function test_customer_cannot_open_another_customers_payment_page(): void
    {
        $owner = $this->customer();
        $other = $this->customer();
        $order = $this->order($owner, Order::PAYMENT_TRANSFER);

        $this->actingAs($other, 'customer')
            ->get(route('payment.show', $order))
            ->assertNotFound();

        $this->actingAs($other, 'customer')
            ->post(route('payment.confirm', $order))
            ->assertNotFound();

        $this->assertFalse($order->fresh()->isPaid());
    }

    public function test_cod_order_is_redirected_away_from_payment_page(): void
    {
        $customer = $this->customer();
        $order = $this->order($customer, Order::PAYMENT_COD);

        $this->actingAs($customer, 'customer')
            ->get(route('payment.show', $order))
            ->assertRedirect(route('track.track'));
    }

    public function test_tracking_page_shows_payment_status_and_pay_link(): void
    {
        $customer = $this->customer();
        $order = $this->order($customer, Order::PAYMENT_TRANSFER);

        $this->actingAs($customer, 'customer')
            ->get(route('track.track'))
            ->assertOk()
            ->assertSee('Belum dibayar')
            ->assertSee(route('payment.show', $order), false);

        app(PaymentService::class)->markPaid($order);

        $this->actingAs($customer, 'customer')
            ->get(route('track.track'))
            ->assertOk()
            ->assertSee('Sudah dibayar')
            ->assertDontSee(route('payment.show', $order), false);
    }

    public function test_guest_cannot_reach_payment_page(): void
    {
        $order = $this->order($this->customer(), Order::PAYMENT_TRANSFER);

        $this->get(route('payment.show', $order))->assertRedirect(route('login'));
    }
}
