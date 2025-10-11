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


        $categories = [
            [
                'ar' => 'الإلكترونيات',
                'en' => 'Electronics',
                'desc_ar' => 'أحدث الأجهزة الإلكترونية والإكسسوارات.',
                'desc_en' => 'The latest electronic devices and accessories.',
            ],
            [
                'ar' => 'الألعاب',
                'en' => 'Games',
                'desc_ar' => 'مجموعة متنوعة من الألعاب الرقمية والتفاعلية.',
                'desc_en' => 'A wide selection of digital and interactive games.',
            ],
            [
                'ar' => 'البرامج',
                'en' => 'Software',
                'desc_ar' => 'برامج أصلية وأدوات للمطورين والمستخدمين.',
                'desc_en' => 'Original software and tools for developers and users.',
            ],
            [
                'ar' => 'الكتب الإلكترونية',
                'en' => 'E-books',
                'desc_ar' => 'كتب رقمية في مختلف المجالات.',
                'desc_en' => 'Digital books covering various topics.',
            ],
            [
                'ar' => 'الاشتراكات',
                'en' => 'Subscriptions',
                'desc_ar' => 'خطط اشتراك شهرية وسنوية للخدمات الرقمية.',
                'desc_en' => 'Monthly and yearly plans for digital services.',
            ],
            [
                'ar' => 'الموسيقى',
                'en' => 'Music',
                'desc_ar' => 'مكتبة موسيقية ضخمة لجميع الأذواق.',
                'desc_en' => 'A large music library for every taste.',
            ],
            [
                'ar' => 'التطبيقات',
                'en' => 'Apps',
                'desc_ar' => 'تطبيقات مفيدة ومبتكرة للهاتف والكمبيوتر.',
                'desc_en' => 'Useful and innovative apps for phone and computer.',
            ],
            [
                'ar' => 'الدورات التعليمية',
                'en' => 'Online Courses',
                'desc_ar' => 'دورات رقمية لتعلم المهارات الحديثة.',
                'desc_en' => 'Digital courses to learn modern skills.',
            ],
        ];

        $category = $this->faker->randomElement($categories);
        $randomSuffix = $this->faker->unique()->numberBetween(1, 9999);

        return [
            'store_id'     => Store::factory(),
            'name'         => [
                'ar' => $category['ar'] . '-' . $randomSuffix,
                'en' => $category['en'] . '-' . $randomSuffix,
            ],
            'description'  => [
                'ar' => $category['desc_ar'],
                'en' => $category['desc_en'],
            ],
            'original_price' => $this->faker->randomFloat(2, 10, 200),
            'price'          => $this->faker->randomFloat(2, 5, 150),
            'status'         => true,
            'views_count'    => $this->faker->numberBetween(0, 500),
            'orders_count'   => $this->faker->numberBetween(0, 200),
            'min_quantity'   => 1,
            'max_quantity'   => 10,
        ];
    }
}
