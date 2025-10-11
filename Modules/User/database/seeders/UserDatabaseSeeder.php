<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Models\User;
use Modules\Store\Models\Store;
use Modules\Wallet\Models\PaymentMethod;
use Modules\Category\Models\Category;
use Modules\Product\Models\Product;

class UserDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::withoutEvents(function () {

            User::factory()->count(1)->create()->each(function ($user) {
                // إنشاء متجر
                $store = Store::factory()->create();

                // ربط المستخدم بالمتجر
                $store->users()->attach($user->id, ['is_active' => true]);
                $user->assignRole('owner');

                // إنشاء 2 طرق دفع
                PaymentMethod::factory()->count(2)->create([
                    'store_id' => $store->id
                ]);

                // إنشاء 3 أصناف رئيسية
                $mainCategories = Category::factory()->count(3)->create([
                    'store_id' => $store->id,
                ]);

                $mainCategories->each(function ($mainCat) use ($store) {
                    // إنشاء 2 أصناف فرعية تحت كل صنف رئيسي
                    $subCategories = Category::factory()->count(2)->child($mainCat->id)->create([
                        'store_id' => $store->id,
                    ]);

                    $subCategories->each(function ($subCat) use ($store) {
                        // إنشاء 5 منتجات مرتبطة بالصنف الفرعي
                        $products = Product::factory()->count(5)->create([
                            'store_id' => $store->id,
                        ]);

                        // ربط المنتجات بالصنف الفرعي
                        $subCat->products()->attach($products->pluck('id')->toArray());
                    });
                });
            });
        });
    }
}
