<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Membuat 10 produk dengan data palsu
        \App\Models\Product::factory(20)->create()->each(function ($product) {
            // Setiap produk akan memiliki 3 gambar terkait
            \App\Models\ProductImage::factory(3)->create([
                'product_id' => $product->id,
            ]);
        });
    }
}
