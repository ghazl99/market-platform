<?php

namespace Modules\Store\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Store\Models\Store::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('ar_SA'); // العربية
        $fakerEn = $this->faker; // الإنجليزية

        return [
            'name' => [
                'ar' => $faker->company(),
                'en' => $fakerEn->company(),
            ],
            'type' => $fakerEn->randomElement(['traditional', 'digital', 'educational']),
            'domain' => $fakerEn->unique()->slug(1), // دومين بالإنجليزي/slug
            'description' => [
                'ar' => $faker->realText(50),
                'en' => $fakerEn->sentence(),
            ],
            'status' => 'active',
            'theme' => 'default',
            'settings' => [
                'currency' => 'USD',
                'timezone' => 'Asia/Riyadh',
            ],
        ];
    }
}
