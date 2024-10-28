<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Settings;
use App\Models\Sales;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_displays_products()
    {
        Product::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('products');
    }

    public function test_about_page_displays_correctly()
    {
        $response = $this->get('/about');

        $response->assertStatus(200)
            ->assertSee('About');
    }

    public function test_catalog_page_shows_products()
    {
        Product::factory()->count(3)->create();

        $response = $this->get('/catalog');

        $response->assertStatus(200)
            ->assertSee('products');
    }

    public function test_contact_page_shows_settings()
    {
        Settings::factory()->create(['key' => 'whatsapp_number', 'value' => '6285159320043']);
        Settings::factory()->create(['key' => 'email', 'value' => 'contact@example.com']);

        $response = $this->get('/contact');

        $response->assertStatus(200)
            ->assertSee('6285159320043')
            ->assertSee('contact@example.com');
    }

    public function test_product_detail_page_displays_product()
    {
        $product = Product::factory()->create(['name' => 'Test Product']);

        $response = $this->get("/product/{$product->id}");

        $response->assertStatus(200)
            ->assertSee('Test Product');
    }

    public function test_product_detail_page_returns_404_for_invalid_product()
    {
        $response = $this->get('/product/9999');

        $response->assertStatus(404);
    }

    public function test_product_buy_redirects_to_whatsapp_with_order_details()
    {

        Settings::factory()->create(['key' => 'whatsapp_number', 'value' => '6285159320043']);
        $product = Product::factory()->create(['name' => 'Test Product', 'price' => 10000, 'stock' => 10, 'discount' => 25]);

        $quantity = 2;
        $priceAfterDiscount = $product->price - ($product->price * ($product->discount / 100));
        $totalPrice = $priceAfterDiscount * $quantity;

        $response = $this->get("/product/{$product->id}/buy/{$quantity}");

        $sale = Sales::latest()->first();
        $encodedMessage = urlencode
        ("*ORDER RH LAKSAMANA*\n*ID Pesanan*: {$sale->id}\n*Nama Produk*: {$product->name}\n*Kuantitas*: {$quantity} Kg\n*Total Harga*: Rp. " .
        number_format($totalPrice, 0, ',', '.'));

        $response->assertStatus(302)
            ->assertRedirect("https://wa.me/6285159320043/?text={$encodedMessage}");
    }


    public function test_product_buy_reduces_stock_and_creates_sale()
    {
        Settings::factory()->create(['key' => 'whatsapp_number', 'value' => '6285159320043']);


        $product = Product::factory()->create(['price' => 10000, 'stock' => 10, 'discount' => 5]);

        $quantity = 2;
        $priceAfterDiscount = $product->price - ($product->price * ($product->discount / 100));
        $totalPrice = $priceAfterDiscount * $quantity;


        $this->get("/product/{$product->id}/buy/{$quantity}");


        $this->assertDatabaseHas('sales', [
            'product_id' => $product->id,
            'amount' => $quantity,
            'total_price' => $totalPrice,
        ]);

        // Verify product stock is reduced
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => $product->stock - $quantity,
        ]);
    }
}
