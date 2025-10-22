<?php

namespace Modules\Core\Database\Seeders;

use Modules\User\Models\User;
use Illuminate\Database\Seeder;
use Modules\Store\Models\Store;
use Modules\Wallet\Models\Wallet;
use Modules\Product\Models\Product;
use Illuminate\Support\Facades\Hash;
use Modules\Category\Models\Category;
use Modules\Wallet\Models\PaymentMethod;

class StoreOwnerSeeder extends Seeder
{
    public function run(): void
    {
        User::withoutEvents(function () {

            // ====== 1️⃣ إنشاء المالك ======
            $owner = User::create([
                'name' => 'Store Owner',
                'email' => 'owner@example.com',
                'password' => Hash::make('password'),
            ]);
            $owner->assignRole('owner');

            /*
    |--------------------------------------------------------------------------
    | 🏪 المتجر التقليدي (Golden Store)
    |--------------------------------------------------------------------------
    */
            $store1 = Store::create([
                'name' => [
                    'en' => 'Golden Store',
                    'ar' => 'المتجر الذهبي',
                ],
                'type' => 'traditional',
                'domain' => 'golden-store',
                'description' => [
                    'en' => 'Best store for quality products',
                    'ar' => 'أفضل متجر للمنتجات عالية الجودة',
                ],
                'status' => 'active',
                'theme' => 'default',
                'settings' => [
                    'currency' => 'USD',
                    'language' => 'en',
                ],
            ]);
            $store1->users()->attach($owner->id);

            // تصنيفات
            $electronics = Category::create([
                'name' => ['en' => 'Electronics', 'ar' => 'إلكترونيات'],
                'store_id' => $store1->id,
            ]);

            $mobiles = Category::create([
                'name' => ['en' => 'Mobiles', 'ar' => 'هواتف'],
                'parent_id' => $electronics->id,
                'store_id' => $store1->id,
            ]);

            $laptops = Category::create([
                'name' => ['en' => 'Laptops', 'ar' => 'حاسبات محمولة'],
                'parent_id' => $electronics->id,
                'store_id' => $store1->id,
            ]);

            // منتجات
            $products1 = [
                [
                    'name' => ['en' => 'iPhone 15', 'ar' => 'آيفون 15'],
                    'description' => ['en' => 'Latest iPhone model', 'ar' => 'أحدث إصدار من آيفون'],
                    'price' => 999.99,
                    'original_price' => 1099.99,
                    'sale_price' => 949.99,   // سعر بعد الخصم
                    'stock_quantity' => 20,
                    'weight' => 0.5,           // بالكيلوغرام
                    'store_id' => $store1->id,
                    'status' => 'active',
                    'is_active' => true,
                    'is_featured' => true,
                    'sku' => 'IP15-001',
                    'views_count' => 0,
                    'orders_count' => 0,
                    'min_quantity' => 1,
                    'max_quantity' => 5,
                    'dimensions' => '15x7x0.8 cm',
                    'seo_title' => 'iPhone 15 - Golden Store',
                    'seo_description' => 'Buy the latest iPhone 15 at Golden Store.',
                ],
                [
                    'name' => ['en' => 'Dell XPS 15', 'ar' => 'ديل إكس بي إس 15'],
                    'description' => ['en' => 'Powerful performance laptop', 'ar' => 'حاسوب محمول عالي الأداء'],
                    'price' => 1499.99,
                    'original_price' => 1599.99,
                    'sale_price' => 1399.99,
                    'stock_quantity' => 10,
                    'weight' => 2.0,
                    'store_id' => $store1->id,
                    'status' => 'active',
                    'is_active' => true,
                    'is_featured' => false,
                    'sku' => 'DX15-002',
                    'views_count' => 0,
                    'orders_count' => 0,
                    'min_quantity' => 1,
                    'max_quantity' => 3,
                    'dimensions' => '35x24x2 cm',
                    'seo_title' => 'Dell XPS 15 - Golden Store',
                    'seo_description' => 'Buy the powerful Dell XPS 15 laptop at Golden Store.',
                ],
            ];


            foreach ($products1 as $productData) {
                $product = Product::create($productData);
                $product->categories()->attach($productData['name']['en'] === 'iPhone 15' ? $mobiles->id : $laptops->id);
            }

            // طرق الدفع
            PaymentMethod::factory()->count(2)->create([
                'store_id' => $store1->id
            ]);

            // عميل للمتجر الأول
            $customer1 = User::create([
                'name' => 'Traditional Customer',
                'email' => 'customer1@example.com',
                'password' => Hash::make('password'),
            ]);
            $customer1->assignRole('customer');
            $store1->users()->attach($customer1->id);
            Wallet::create([
                'user_id' => $customer1->id,
                'store_id' => $store1->id,
                'balance' => 200,
            ]);

            /*
    |--------------------------------------------------------------------------
    | 💻 المتجر الرقمي (Digital Hub)
    |--------------------------------------------------------------------------
    */
            $store2 = Store::create([
                'name' => [
                    'en' => 'Digital Hub',
                    'ar' => 'المركز الرقمي',
                ],
                'type' => 'digital',
                'domain' => 'digital-hub',
                'description' => [
                    'en' => 'Your marketplace for digital products',
                    'ar' => 'سوقك للمنتجات الرقمية',
                ],
                'status' => 'active',
                'theme' => 'modern',
                'settings' => [
                    'currency' => 'USD',
                    'language' => 'en',
                ],
            ]);
            $store2->users()->attach($owner->id);

            // تصنيفات رقمية
            $software = Category::create([
                'name' => ['en' => 'Software', 'ar' => 'برمجيات'],
                'store_id' => $store2->id,
            ]);

            $ebooks = Category::create([
                'name' => ['en' => 'E-books', 'ar' => 'كتب إلكترونية'],
                'store_id' => $store2->id,
            ]);
            // ====== تصنيفات فرعية لـ Software ======
            $photoEditing = Category::create([
                'name' => ['en' => 'Photo Editing', 'ar' => 'تعديل الصور'],
                'parent_id' => $software->id,
                'store_id' => $store2->id,
            ]);

            $videoEditing = Category::create([
                'name' => ['en' => 'Video Editing', 'ar' => 'تعديل الفيديو'],
                'parent_id' => $software->id,
                'store_id' => $store2->id,
            ]);

            $developmentTools = Category::create([
                'name' => ['en' => 'Development Tools', 'ar' => 'أدوات التطوير'],
                'parent_id' => $software->id,
                'store_id' => $store2->id,
            ]);

            // ====== تصنيفات فرعية لـ E-books ======
            $laravelBooks = Category::create([
                'name' => ['en' => 'Laravel Books', 'ar' => 'كتب لارافيل'],
                'parent_id' => $ebooks->id,
                'store_id' => $store2->id,
            ]);

            $phpBooks = Category::create([
                'name' => ['en' => 'PHP Books', 'ar' => 'كتب بي اتش بي'],
                'parent_id' => $ebooks->id,
                'store_id' => $store2->id,
            ]);

            $javascriptBooks = Category::create([
                'name' => ['en' => 'JavaScript Books', 'ar' => 'كتب جافاسكربت'],
                'parent_id' => $ebooks->id,
                'store_id' => $store2->id,
            ]);

            // منتجات رقمية
            $products2 = [
                [
                    'name' => ['en' => 'Photo Editing Software', 'ar' => 'برنامج تعديل الصور'],
                    'description' => ['en' => 'Powerful editing tool for professionals', 'ar' => 'أداة قوية لتعديل الصور للمحترفين'],
                    'price' => 49.99,
                    'original_price' => 69.99,
                    'sale_price' => 39.99,
                    'stock_quantity' => 9999,
                    'weight' => 0.1, // افتراضي للمنتجات الرقمية
                    'store_id' => $store2->id,
                    'status' => 'active',
                    'is_active' => true,
                    'is_featured' => true,
                    'sku' => 'DIGI-SW-001',
                    'views_count' => 0,
                    'orders_count' => 0,
                    'min_quantity' => 1,
                    'max_quantity' => 10,
                    'dimensions' => null, // لا ينطبق على الرقمي
                    'seo_title' => 'Photo Editing Software - Digital Hub',
                    'seo_description' => 'Download powerful photo editing software from Digital Hub.',
                ],
                [
                    'name' => ['en' => 'Laravel Mastery E-book', 'ar' => 'كتاب إتقان لارافيل'],
                    'description' => ['en' => 'Comprehensive guide to Laravel framework', 'ar' => 'دليل شامل لإطار عمل لارافيل'],
                    'price' => 19.99,
                    'original_price' => 29.99,
                    'sale_price' => 14.99,
                    'stock_quantity' => 9999,
                    'weight' => 0.05,
                    'store_id' => $store2->id,
                    'status' => 'active',
                    'is_active' => true,
                    'is_featured' => false,
                    'sku' => 'DIGI-BOOK-002',
                    'views_count' => 0,
                    'orders_count' => 0,
                    'min_quantity' => 1,
                    'max_quantity' => 10,
                    'dimensions' => null,
                    'seo_title' => 'Laravel Mastery E-book - Digital Hub',
                    'seo_description' => 'Get the complete Laravel mastery e-book from Digital Hub.',
                ],
            ];


            foreach ($products2 as $productData) {
                $product = Product::create($productData);
                if (str_contains($productData['sku'], 'SW')) {
                    // مثال: جميع برامج تعديل الصور مرتبطة بـ Photo Editing
                    $product->categories()->attach($photoEditing->id);
                } else {
                    // مثال: جميع كتب لارافيل مرتبطة بـ Laravel Books
                    $product->categories()->attach($laravelBooks->id);
                }
            }

            // طرق دفع للمتجر الرقمي
            PaymentMethod::factory()->count(2)->create([
                'store_id' => $store2->id
            ]);

            // عميل للمتجر الرقمي
            $customer2 = User::create([
                'name' => 'Digital Customer',
                'email' => 'customer2@example.com',
                'password' => Hash::make('password'),
            ]);
            $customer2->assignRole('customer');
            $store2->users()->attach($customer2->id);
            Wallet::create([
                'user_id' => $customer2->id,
                'store_id' => $store2->id,
                'balance' => 150,
            ]);

            echo "✅ Seeding completed successfully.\n";
            echo "Owner login: owner@example.com / password\n";
            echo "Traditional Customer: customer1@example.com / password\n";
            echo "Digital Customer: customer2@example.com / password\n";
        });
    }
}
