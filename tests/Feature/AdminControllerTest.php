<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Sales;
use App\Models\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_login_redirects_to_google()
    {
        $response = $this->get('/auth/google/redirect');
        $response->assertStatus(302);
    }

    public function test_google_login_success_with_registered_email()
    {
        $user = User::factory()->create(['email' => 'registereduser@example.com']);

        $socialiteUserMock = Mockery::mock(SocialiteUser::class);
        $socialiteUserMock->shouldReceive('getEmail')->andReturn('registereduser@example.com');

        Socialite::shouldReceive('driver->user')->andReturn($socialiteUserMock);

        $this->get('/auth/google/callback');

        $this->assertTrue(Auth::check());
        $this->assertEquals(Auth::user()->email, 'registereduser@example.com');
    }

    public function test_google_login_fails_with_unregistered_email()
    {
        $socialiteUserMock = Mockery::mock(SocialiteUser::class);
        $socialiteUserMock->shouldReceive('getEmail')->andReturn('unregistereduser@example.com');

        Socialite::shouldReceive('driver->user')->andReturn($socialiteUserMock);

        $response = $this->get('/auth/google/callback');

        $this->assertFalse(Auth::check());
        $response->assertRedirect('/admin-login');
        $response->assertSessionHas('error', 'Email Tidak Terdaftar.');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_admin_can_logout()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin/logout')->assertRedirect('/');
    }

    public function test_admin_can_access_dashboard()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin')
            ->assertStatus(200);
    }

    public function test_admin_can_access_products()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin/products')
            ->assertStatus(200);
    }

    public function test_admin_can_access_product_creation_form()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.products.add'))
            ->assertStatus(200);
    }

    public function test_admin_can_store_product()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'Product 1',
            'price' => 1000,
            'discount' => 10,
            'category' => 'Electronics',
            'stock' => 10,
            'minOrder' => 1,
            'description' => 'A sample product',
        ];

        $this->actingAs($user)
            ->post(route('admin.products.store'), $data)
            ->assertRedirect('/admin/products');
    }

    public function test_admin_can_access_sales_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin/sales')
            ->assertStatus(200);
    }

    public function test_admin_can_store_sales()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10, 'price' => 1000, 'minOrder' => 1]);

        $data = [
            'product_id' => $product->id,
            'amount' => 1,
            'sale_time' => Carbon::now()->format('Y-m-d'),
        ];

        $this->actingAs($user)
            ->post('/admin/sales/store', $data)
            ->assertJson(['success' => 'Penjualan berhasil disimpan.']);
    }

    public function test_admin_can_access_accounts_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin/accounts')
            ->assertStatus(200);
    }

    public function test_admin_can_access_settings_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin/settings')
            ->assertStatus(200);
    }

    public function test_admin_can_update_settings()
    {
        $user = User::factory()->create();
        $setting = Settings::factory()->create(['value' => 'Old Value']);

        $data = ['value' => 'New Value'];

        $this->actingAs($user)
            ->put(route('settings.update', $setting->id), $data)
            ->assertRedirect()
            ->assertSessionHas('success', 'Setting berhasil diperbarui');
    }

    public function test_admin_can_delete_item()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)
            ->delete(route('item.delete', ['table' => 'products', 'id' => $product->id]))
            ->assertRedirect()
            ->assertSessionHas('success', 'Produk berhasil dihapus.');
    }
}
