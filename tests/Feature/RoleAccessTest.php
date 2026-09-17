<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Seller;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Menjaga registrasi guard dan middleware tetap benar.
 * Area ini tidak tercakup test lain, padahal paling rawan rusak saat upgrade framework.
 */
class RoleAccessTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Sama seperti CourierTaskTest: test POST butuh CSRF dimatikan.
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    private function admin(string $role = 'admin'): Admin
    {
        return Admin::create([
            'name' => 'QA Admin '.uniqid(),
            'email' => 'qa-role-admin-'.uniqid().'@example.test',
            'phone_number' => '081200000081',
            'password' => Hash::make('password123'),
            'address' => 'Kantor QA',
            'gender' => 'other',
            'role' => $role,
        ]);
    }

    private function seller(string $status = 'approved'): Seller
    {
        return Seller::create([
            'name_sellers' => 'QA Role Seller '.uniqid(),
            'email' => 'qa-role-seller-'.uniqid().'@example.test',
            'phone_number' => '081200000082',
            'password' => Hash::make('password123'),
            'address' => 'Jl. Seller QA',
            'gender' => 'female',
            'province' => 'DIY',
            'city' => 'Yogyakarta',
            'store_name' => 'QA Role Store '.uniqid(),
            'ktp_image' => 'ktp/test.jpg',
            'status' => $status,
        ]);
    }

    private function customer(): Customer
    {
        return Customer::create([
            'name_customers' => 'QA Role Customer '.uniqid(),
            'email' => 'qa-role-customer-'.uniqid().'@example.test',
            'phone_number' => '081200000083',
            'dob' => '1995-01-01',
            'gender' => 'female',
            'address' => 'Jl. Customer QA',
            'province' => 'DIY',
            'city' => 'Yogyakarta',
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_guest_is_redirected_to_login_from_protected_areas(): void
    {
        $this->get(route('customer.profile'))->assertRedirect(route('login'));
        $this->get(route('cart.show'))->assertRedirect(route('login'));
        $this->get(route('track.track'))->assertRedirect(route('login'));
        $this->get(route('seller.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_customer_can_open_their_area(): void
    {
        $customer = $this->customer();

        $this->actingAs($customer, 'customer')->get(route('customer.profile'))->assertOk();
        $this->actingAs($customer, 'customer')->get(route('cart.show'))->assertOk();
        $this->actingAs($customer, 'customer')->get(route('track.track'))->assertOk();
        $this->actingAs($customer, 'customer')->get(route('customer.wallet'))->assertOk();
        $this->actingAs($customer, 'customer')->get(route('customer.addresses.index'))->assertOk();
    }

    public function test_customer_cannot_open_seller_or_admin_area(): void
    {
        $customer = $this->customer();

        $this->actingAs($customer, 'customer')->get(route('seller.dashboard'))->assertRedirect(route('login'));
        $this->actingAs($customer, 'customer')->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_approved_seller_can_open_seller_area(): void
    {
        $seller = $this->seller('approved');

        $this->actingAs($seller, 'seller')->get(route('seller.dashboard'))->assertOk();
        $this->actingAs($seller, 'seller')->get(route('products.index'))->assertOk();
        $this->actingAs($seller, 'seller')->get(route('order.index'))->assertOk();
        $this->actingAs($seller, 'seller')->get(route('seller.shipments.index'))->assertOk();
        $this->actingAs($seller, 'seller')->get(route('seller.claims.index'))->assertOk();
    }

    public function test_pending_seller_is_logged_out_and_kept_out(): void
    {
        $seller = $this->seller('pending');

        $this->actingAs($seller, 'seller')
            ->get(route('seller.dashboard'))
            ->assertRedirect(route('login'));

        $this->assertGuest('seller');
    }

    public function test_admin_can_open_admin_area(): void
    {
        $admin = $this->admin('admin');

        $this->actingAs($admin, 'admin')->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.customers'))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.sellers.verify'))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.products.verify'))->assertOk();
    }

    public function test_super_admin_can_open_super_admin_only_pages(): void
    {
        $admin = $this->admin('super_admin');

        $this->actingAs($admin, 'admin')->get(route('admin.staff'))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.vouchers.index'))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.impact.index'))->assertOk();
    }

    public function test_plain_admin_is_kept_out_of_super_admin_pages(): void
    {
        $admin = $this->admin('admin');

        $this->actingAs($admin, 'admin')
            ->get(route('admin.staff'))
            ->assertRedirect(route('admin.dashboard'));

        $this->actingAs($admin, 'admin')
            ->get(route('admin.vouchers.index'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_cannot_open_customer_area(): void
    {
        $admin = $this->admin('super_admin');

        $this->actingAs($admin, 'admin')->get(route('customer.profile'))->assertRedirect(route('login'));
    }

    /*
     * Setiap halaman login hanya boleh memeriksa guard-nya sendiri.
     * Sebelumnya /login mencoba guard admin lalu seller, sehingga akun admin
     * dan seller bisa masuk lewat pintu customer.
     */

    public function test_customer_login_accepts_customer_credentials(): void
    {
        $customer = $this->customer();

        $this->post(route('login.submit'), [
            'email' => $customer->email,
            'password' => 'password123',
        ])->assertRedirect(route('customer.dashboard'));

        $this->assertAuthenticatedAs($customer, 'customer');
    }

    public function test_customer_login_rejects_admin_credentials(): void
    {
        $admin = $this->admin('super_admin');

        $this->from(route('login'))
            ->post(route('login.submit'), [
                'email' => $admin->email,
                'password' => 'password123',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest('admin');
    }

    public function test_customer_login_rejects_seller_credentials(): void
    {
        $seller = $this->seller('approved');

        $this->from(route('login'))
            ->post(route('login.submit'), [
                'email' => $seller->email,
                'password' => 'password123',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest('seller');
    }

    public function test_seller_login_accepts_approved_seller(): void
    {
        $seller = $this->seller('approved');

        $this->post(route('seller.login.submit'), [
            'email' => $seller->email,
            'password' => 'password123',
        ])->assertRedirect(route('seller.dashboard'));

        $this->assertAuthenticatedAs($seller, 'seller');
    }

    public function test_seller_login_rejects_admin_credentials(): void
    {
        $admin = $this->admin('super_admin');

        $this->from(route('seller.login'))
            ->post(route('seller.login.submit'), [
                'email' => $admin->email,
                'password' => 'password123',
            ])
            ->assertRedirect(route('seller.login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest('admin');
        $this->assertGuest('seller');
    }
}
