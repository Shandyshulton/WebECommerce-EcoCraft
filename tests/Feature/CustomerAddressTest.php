<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerAddressTest extends TestCase
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
            'name_customers' => 'QA Addr '.uniqid(),
            'email' => 'qa-addr-'.uniqid().'@example.test',
            'phone_number' => '081200000051',
            'dob' => '1995-01-01',
            'gender' => 'female',
            'address' => 'Jl. Registrasi 1',
            'province' => 'DIY',
            'city' => 'Yogyakarta',
            'password' => Hash::make('password123'),
        ]);
    }

    private function seller(): Seller
    {
        return Seller::create([
            'name_sellers' => 'QA Seller '.uniqid(),
            'email' => 'qa-addr-seller-'.uniqid().'@example.test',
            'phone_number' => '081200000052',
            'password' => Hash::make('password123'),
            'address' => 'Jl. Seller 1',
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
        return Product::create([
            'seller_id' => $this->seller()->id_sellers,
            'name' => 'QA Produk Alamat '.uniqid(),
            'slug' => 'qa-produk-alamat-'.uniqid(),
            'description' => 'Produk uji alamat.',
            'price' => 50000,
            'category' => 'Home Decor',
            'material_type' => 'Anyaman',
            'in_stock' => true,
            'is_active' => true,
            'status' => 'approved',
            'quantity' => 5,
        ]);
    }

    private function address(Customer $customer, bool $default = false, array $overrides = []): CustomerAddress
    {
        return CustomerAddress::create(array_merge([
            'customer_id' => $customer->id_customers,
            'label' => 'Rumah',
            'recipient_name' => 'QA Penerima',
            'phone' => '081200000053',
            'address' => 'Jl. Alamat 1',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'postal_code' => '40111',
            'is_default' => $default,
        ], $overrides));
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'label' => 'Kantor',
            'recipient_name' => 'QA Penerima',
            'phone' => '081200000054',
            'address' => 'Jl. Alamat Baru 2',
            'city' => 'Semarang',
            'province' => 'Jawa Tengah',
            'postal_code' => '50111',
        ], $overrides);
    }

    public function test_first_address_becomes_default(): void
    {
        $customer = $this->customer();

        $this->actingAs($customer, 'customer')
            ->post(route('customer.addresses.store'), $this->payload())
            ->assertRedirect(route('customer.addresses.index'));

        $this->assertDatabaseHas('customer_addresses', [
            'customer_id' => $customer->id_customers,
            'address' => 'Jl. Alamat Baru 2',
            'is_default' => 1,
        ]);
    }

    public function test_marking_default_unsets_others(): void
    {
        $customer = $this->customer();
        $first = $this->address($customer, true);
        $second = $this->address($customer, false, ['address' => 'Jl. Alamat 2']);

        $this->actingAs($customer, 'customer')
            ->post(route('customer.addresses.default', $second->id_addresses))
            ->assertRedirect(route('customer.addresses.index'));

        $this->assertFalse($first->fresh()->is_default);
        $this->assertTrue($second->fresh()->is_default);
    }

    public function test_deleting_default_promotes_another_address(): void
    {
        $customer = $this->customer();
        $first = $this->address($customer, true);
        $second = $this->address($customer, false, ['address' => 'Jl. Alamat 2']);

        $this->actingAs($customer, 'customer')
            ->delete(route('customer.addresses.destroy', $first->id_addresses))
            ->assertRedirect(route('customer.addresses.index'));

        $this->assertDatabaseMissing('customer_addresses', ['id_addresses' => $first->id_addresses]);
        $this->assertTrue($second->fresh()->is_default);
    }

    public function test_cannot_manage_another_customers_address(): void
    {
        $owner = $this->customer();
        $other = $this->customer();
        $address = $this->address($owner, true);

        $this->actingAs($other, 'customer')
            ->get(route('customer.addresses.edit', $address->id_addresses))
            ->assertNotFound();

        $this->actingAs($other, 'customer')
            ->put(route('customer.addresses.update', $address->id_addresses), $this->payload())
            ->assertNotFound();

        $this->actingAs($other, 'customer')
            ->delete(route('customer.addresses.destroy', $address->id_addresses))
            ->assertNotFound();

        $this->actingAs($other, 'customer')
            ->post(route('customer.addresses.default', $address->id_addresses))
            ->assertNotFound();
    }

    public function test_checkout_redirects_to_address_form_when_customer_has_no_address(): void
    {
        $customer = $this->customer();
        $product = $this->product();

        $this->actingAs($customer, 'customer')
            ->withSession(['cart' => [$product->id_products => 1]])
            ->get(route('checkout.index'))
            ->assertRedirect(route('customer.addresses.create', ['redirect' => 'checkout']))
            ->assertSessionHas('info');
    }

    public function test_adding_address_from_checkout_returns_to_checkout(): void
    {
        $customer = $this->customer();

        $this->actingAs($customer, 'customer')
            ->post(route('customer.addresses.store'), $this->payload(['redirect' => 'checkout']))
            ->assertRedirect(route('checkout.index'));

        $this->assertDatabaseHas('customer_addresses', [
            'customer_id' => $customer->id_customers,
            'address' => 'Jl. Alamat Baru 2',
        ]);
    }

    public function test_checkout_uses_selected_saved_address(): void
    {
        $customer = $this->customer();
        $product = $this->product();
        $address = $this->address($customer, true, [
            'address' => 'Jl. Alamat Tersimpan 1',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'postal_code' => '40111',
        ]);

        $this->actingAs($customer, 'customer')
            ->withSession(['cart' => [$product->id_products => 1]])
            ->post(route('checkout.store'), [
                'customer_phone' => '081200000053',
                'address_choice' => (string) $address->id_addresses,
                'shipping_method' => 'Reguler',
                'payment_method' => 'Transfer Bank',
            ])
            ->assertRedirect(route('track.track'));

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id_customers,
            'shipping_address' => 'Jl. Alamat Tersimpan 1',
            'shipping_city' => 'Bandung',
            'shipping_province' => 'Jawa Barat',
            'shipping_postal_code' => '40111',
        ]);
    }

    public function test_checkout_rejects_another_customers_address(): void
    {
        $customer = $this->customer();
        $other = $this->customer();
        $product = $this->product();
        $address = $this->address($other, true);

        $this->actingAs($customer, 'customer')
            ->withSession(['cart' => [$product->id_products => 1]])
            ->post(route('checkout.store'), [
                'customer_phone' => '081200000053',
                'address_choice' => (string) $address->id_addresses,
                'shipping_method' => 'Reguler',
                'payment_method' => 'Transfer Bank',
            ])
            ->assertSessionHasErrors('address_choice');

        $this->assertDatabaseMissing('orders', ['customer_id' => $customer->id_customers]);
    }

    public function test_checkout_can_save_new_address_to_book(): void
    {
        $customer = $this->customer();
        $product = $this->product();

        $this->actingAs($customer, 'customer')
            ->withSession(['cart' => [$product->id_products => 1]])
            ->post(route('checkout.store'), [
                'customer_phone' => '081200000053',
                'address_choice' => 'new',
                'address_label' => 'Kos',
                'save_address' => '1',
                'shipping_address' => 'Jl. Alamat Checkout 9',
                'shipping_city' => 'Malang',
                'shipping_province' => 'Jawa Timur',
                'shipping_postal_code' => '65111',
                'shipping_method' => 'Reguler',
                'payment_method' => 'COD',
            ])
            ->assertRedirect(route('track.track'));

        $this->assertDatabaseHas('customer_addresses', [
            'customer_id' => $customer->id_customers,
            'label' => 'Kos',
            'address' => 'Jl. Alamat Checkout 9',
            'is_default' => 1,
        ]);
    }
}
