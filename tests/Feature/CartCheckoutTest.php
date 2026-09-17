<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CartCheckoutTest extends TestCase
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
            'name_customers' => 'QA Cart '.uniqid(),
            'email' => 'qa-cart-'.uniqid().'@example.test',
            'phone_number' => '081200000101',
            'dob' => '1995-01-01',
            'gender' => 'female',
            'address' => 'Jl. Cart 1',
            'province' => 'DIY',
            'city' => 'Yogyakarta',
            'password' => Hash::make('password123'),
        ]);
    }

    private function seller(): Seller
    {
        return Seller::create([
            'name_sellers' => 'QA Cart Seller '.uniqid(),
            'email' => 'qa-cart-seller-'.uniqid().'@example.test',
            'phone_number' => '081200000102',
            'password' => Hash::make('password123'),
            'address' => 'Jl. Seller Cart',
            'gender' => 'female',
            'province' => 'DIY',
            'city' => 'Yogyakarta',
            'store_name' => 'QA Cart Store '.uniqid(),
            'ktp_image' => 'ktp/test.jpg',
            'status' => 'approved',
        ]);
    }

    private function product(int $price = 50000): Product
    {
        return Product::create([
            'seller_id' => $this->seller()->id_sellers,
            'name' => 'QA Produk Cart '.uniqid(),
            'slug' => 'qa-produk-cart-'.uniqid(),
            'description' => 'Produk uji keranjang.',
            'price' => $price,
            'category' => 'Home Decor',
            'material_type' => 'Anyaman',
            'in_stock' => true,
            'is_active' => true,
            'status' => 'approved',
            'quantity' => 10,
        ]);
    }

    private function address(Customer $customer): CustomerAddress
    {
        return CustomerAddress::create([
            'customer_id' => $customer->id_customers,
            'label' => 'Rumah',
            'recipient_name' => 'QA Penerima',
            'phone' => '081200000103',
            'address' => 'Jl. Alamat Cart 1',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'postal_code' => '40111',
            'is_default' => true,
        ]);
    }

    public function test_cart_shows_total_of_all_items_when_nothing_selected_yet(): void
    {
        $customer = $this->customer();
        $first = $this->product(50000);
        $second = $this->product(20000);

        $this->actingAs($customer, 'customer')
            ->withSession(['cart' => [$first->id_products => 2, $second->id_products => 1]])
            ->get(route('cart.show'))
            ->assertOk()
            // Semua item dianggap terpilih, jadi totalnya konsisten dengan tampilan.
            ->assertSee('120.000')
            ->assertSee('2 dari 2 produk dipilih.');
    }

    public function test_cart_total_follows_saved_selection(): void
    {
        $customer = $this->customer();
        $first = $this->product(50000);
        $second = $this->product(20000);

        $this->actingAs($customer, 'customer')
            ->withSession([
                'cart' => [$first->id_products => 2, $second->id_products => 1],
                'cart_selection' => [$first->id_products],
            ])
            ->get(route('cart.show'))
            ->assertOk()
            ->assertSee('100.000')
            ->assertDontSee('120.000')
            ->assertSee('1 dari 2 produk dipilih.');
    }

    public function test_checkout_selection_is_required(): void
    {
        $customer = $this->customer();
        $product = $this->product();

        $this->actingAs($customer, 'customer')
            ->withSession(['cart' => [$product->id_products => 1]])
            ->post(route('cart.checkout'), ['product_ids' => [999999]])
            ->assertRedirect(route('cart.show'))
            ->assertSessionHas('error');
    }

    public function test_checkout_selection_redirects_to_checkout_page(): void
    {
        $customer = $this->customer();
        $product = $this->product();
        $this->address($customer);

        $this->actingAs($customer, 'customer')
            ->withSession(['cart' => [$product->id_products => 1]])
            ->post(route('cart.checkout'), ['product_ids' => [$product->id_products]])
            ->assertRedirect(route('checkout.index'))
            ->assertSessionHas('cart_selection', [$product->id_products]);
    }

    public function test_checkout_page_only_lists_selected_items(): void
    {
        $customer = $this->customer();
        $chosen = $this->product(50000);
        $leftOut = $this->product(20000);
        $this->address($customer);

        $this->actingAs($customer, 'customer')
            ->withSession([
                'cart' => [$chosen->id_products => 1, $leftOut->id_products => 1],
                'cart_selection' => [$chosen->id_products],
            ])
            ->get(route('checkout.index'))
            ->assertOk()
            ->assertSee($chosen->name)
            ->assertDontSee($leftOut->name);
    }

    public function test_only_selected_items_become_order_items(): void
    {
        $customer = $this->customer();
        $chosen = $this->product(50000);
        $leftOut = $this->product(20000);
        $address = $this->address($customer);

        $this->actingAs($customer, 'customer')
            ->withSession([
                'cart' => [$chosen->id_products => 2, $leftOut->id_products => 3],
                'cart_selection' => [$chosen->id_products],
            ])
            ->post(route('checkout.store'), [
                'customer_phone' => '081200000103',
                'address_choice' => (string) $address->id_addresses,
                'shipping_method' => 'Reguler',
                'payment_method' => 'COD',
            ])
            ->assertRedirect(route('track.track'));

        $this->assertDatabaseHas('order_items', [
            'product_id' => $chosen->id_products,
            'quantity' => 2,
        ]);

        $this->assertDatabaseMissing('order_items', [
            'product_id' => $leftOut->id_products,
        ]);
    }

    public function test_unselected_items_stay_in_the_cart_after_checkout(): void
    {
        $customer = $this->customer();
        $chosen = $this->product(50000);
        $leftOut = $this->product(20000);
        $address = $this->address($customer);

        $response = $this->actingAs($customer, 'customer')
            ->withSession([
                'cart' => [$chosen->id_products => 1, $leftOut->id_products => 3],
                'cart_selection' => [$chosen->id_products],
            ])
            ->post(route('checkout.store'), [
                'customer_phone' => '081200000103',
                'address_choice' => (string) $address->id_addresses,
                'shipping_method' => 'Reguler',
                'payment_method' => 'COD',
            ])
            ->assertRedirect(route('track.track'));

        $response->assertSessionHas('cart', [$leftOut->id_products => 3]);
        $response->assertSessionMissing('cart_selection');
    }

    public function test_buy_now_selects_only_that_product(): void
    {
        $customer = $this->customer();
        $other = $this->product(20000);
        $buyNow = $this->product(75000);

        $this->actingAs($customer, 'customer')
            ->withSession(['cart' => [$other->id_products => 1]])
            ->post(route('cart.items.store'), [
                'product_id' => $buyNow->id_products,
                'quantity' => 1,
                'buy_now' => 1,
            ])
            ->assertRedirect(route('checkout.index'))
            ->assertSessionHas('cart_selection', [$buyNow->id_products]);
    }
}
