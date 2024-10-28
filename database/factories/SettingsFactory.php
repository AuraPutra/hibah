<?php

namespace Database\Factories;

use App\Models\Settings;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingsFactory extends Factory
{
    protected $model = Settings::class;

    public function definition()
    {
        return [
            'key' => $this->faker->unique()->randomElement([
                'whatsapp_number', 'email', 'tiktok', 'instagram', 'shopee', 'tokopedia', 'facebook'
            ]),
            'value' => $this->faker->randomElement([
                '6285159320043',
                'contact@example.com',
                'https://www.tiktok.com/@your_account',
                'https://www.instagram.com/your_account',
                'https://shopee.co.id/your_store',
                'https://www.tokopedia.com/your_store',
                'https://www.facebook.com/your_store',
            ]),
        ];
    }
}
