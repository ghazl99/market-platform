<?php

namespace Modules\Category\Database\Factories;

use Modules\Store\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Category\Models\Category::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
       

        $digitalCategories = [
            ['ar' => 'إلكترونيات', 'en' => 'Electronics'],
            ['ar' => 'أجهزة كمبيوتر', 'en' => 'Computers'],
            ['ar' => 'هواتف ذكية', 'en' => 'Smartphones'],
            ['ar' => 'ألعاب فيديو', 'en' => 'Video Games'],
            ['ar' => 'برامج وتطبيقات', 'en' => 'Software & Apps'],
            ['ar' => 'اشتراكات رقمية', 'en' => 'Digital Subscriptions'],
            ['ar' => 'أجهزة لوحية', 'en' => 'Tablets'],
            ['ar' => 'إكسسوارات', 'en' => 'Accessories'],
            ['ar' => 'سماعات وصوتيات', 'en' => 'Audio & Headphones'],
            ['ar' => 'أجهزة ذكية منزلية', 'en' => 'Smart Home Devices'],
            ['ar' => 'شاشات وعرض', 'en' => 'Monitors & Displays'],
            ['ar' => 'خدمات سحابية', 'en' => 'Cloud Services'],
            ['ar' => 'أمن وحماية', 'en' => 'Security & Protection'],
            ['ar' => 'برامج تصميم', 'en' => 'Design Software'],
            ['ar' => 'كاميرات رقمية', 'en' => 'Digital Cameras'],
        ];

        $randomCategory = $this->faker->randomElement($digitalCategories);

        return [
            'store_id'  => Store::factory(),
            'name'      => $randomCategory,
            'parent_id' => null,
        ];
    }


    /**
     * حالة لإنشاء صنف فرعي
     */
    public function child($parentId)
    {
        return $this->state(fn() => [
            'parent_id' => $parentId,
        ]);
    }
}
