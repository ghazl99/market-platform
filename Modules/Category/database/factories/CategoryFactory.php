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
        $fakerAr = \Faker\Factory::create('ar_SA'); // العربية
        $fakerEn = $this->faker; // الإنجليزية

        return [
            'store_id'  => Store::factory(),
            'name'      => [
                'ar' => $fakerAr->realText(20),
                'en' => $fakerEn->word(),
            ],
            'parent_id' => null, // افتراضي صنف رئيسي
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
