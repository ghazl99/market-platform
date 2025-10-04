<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Category\Services\CategoryService;
use Modules\Store\Models\Store;

class CreateSampleCategoriesData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:sample-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create sample categories to test the interface';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('🎨 Creating sample categories to test the interface...');

            // Check if store exists
            $store = Store::where('status', 'active')->first();
            if (!$store) {
                $this->error('❌ No active store found!');
                return 1;
            }

            $this->info("✅ Using store: {$store->name} (ID: {$store->id})");

            // Test category service
            $categoryService = app(CategoryService::class);

            // Sample categories data
            $categoriesData = [
                [
                    'name' => 'كتب',
                    'description' => 'مجموعة واسعة من الكتب في مختلف المجالات واللغات',
                    'subcategories' => ['تكنولوجيا', 'أدب', 'تاريخ', 'علوم']
                ],
                [
                    'name' => 'ملابس',
                    'description' => 'مجموعة متنوعة من الملابس للرجال والنساء والأطفال بأحدث الموضات',
                    'subcategories' => ['رجال', 'نساء', 'أطفال', 'أحذية']
                ],
                [
                    'name' => 'إلكترونيات',
                    'description' => 'جميع الأجهزة الإلكترونية والكهربائية من هواتف وحواسيب وأجهزة ذكية',
                    'subcategories' => ['هواتف', 'حواسيب', 'سماعات', 'أجهزة ذكية']
                ]
            ];

            foreach ($categoriesData as $index => $categoryData) {
                $this->info("🔄 Creating category: {$categoryData['name']}...");

                try {
                    $category = $categoryService->store($categoryData);
                    $this->info("✅ Category created successfully! ID: {$category->id}");

                    // Update products count for testing
                    $category->update(['products_count' => rand(8, 15)]);

                } catch (\Exception $e) {
                    $this->warn("⚠️ Failed to create category '{$categoryData['name']}': " . $e->getMessage());
                }
            }

            $this->info('🎉 Sample categories created successfully!');
            $this->info('📋 You can now view the categories in the dashboard to see the new design.');

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Error during creation: " . $e->getMessage());
            return 1;
        }
    }
}
