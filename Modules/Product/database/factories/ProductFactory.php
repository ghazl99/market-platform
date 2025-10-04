<?php

namespace Modules\Product\Database\Factories;

use Modules\Store\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Product\Models\Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $fakerAr = \Faker\Factory::create('ar_SA'); // العربية
        $fakerEn = $this->faker; // الإنجليزية
        return [
            'store_id'        => Store::factory(),
            'name'            => [
                'ar' => $fakerAr->realText(20),
                'en' => $fakerEn->word(),
            ],
            'description'     => [
                'ar' => $fakerAr->realText(50),
                'en' => $fakerEn->sentence(),
            ],
            'original_price'  => $fakerEn->randomFloat(2, 10, 200),
            'price'           => $fakerEn->randomFloat(2, 5, 150),
            'status'          => true,
            'views_count'     => $fakerEn->numberBetween(0, 500),
            'orders_count'    => $fakerEn->numberBetween(0, 200),
            'min_quantity'    => 1,
            'max_quantity'    => 10,
        ];
    }
}
