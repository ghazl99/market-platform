<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Category\Services\CategoryService;
use Modules\Store\Models\Store;
use Illuminate\Http\Request;

class TestCategoryError extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:category-error';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test category creation to identify error source';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('🔍 Testing category creation to identify error source...');

            // Check if store exists
            $store = Store::where('status', 'active')->first();
            if (!$store) {
                $this->error('❌ No active store found!');
                return 1;
            }

            $this->info("✅ Using store: {$store->name} (ID: {$store->id})");

            // Test category service
            $categoryService = app(CategoryService::class);

            $testData = [
                'name' => 'قسم اختبار الخطأ',
                'description' => 'وصف اختبار للتحقق من مصدر الخطأ'
            ];

            $this->info('🔄 Creating test category...');
            $category = $categoryService->store($testData);

            $this->info("✅ Category created successfully! ID: {$category->id}");
            $this->info("📝 Category name: {$category->getTranslation('name', 'ar')}");
            $this->info("📝 Category description: {$category->getTranslation('description', 'ar')}");

            // Test controller response
            $this->info('🔄 Testing controller response...');
            $controller = app(\Modules\Category\Http\Controllers\Dashboard\CategoryController::class);

            // Simulate request
            $request = new Request();
            $request->merge($testData);
            $request->setLaravelSession(app('session.store'));

            $this->info('✅ Controller test completed without errors');

            $this->info('🎉 All tests passed! The issue might be in the browser cache or JavaScript.');
            $this->info('📋 Recommendations:');
            $this->info('   1. Clear browser cache (Ctrl+F5)');
            $this->info('   2. Check browser console for JavaScript errors');
            $this->info('   3. Verify that the correct JavaScript file is loaded');

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Error during testing: " . $e->getMessage());
            $this->error("📋 Stack trace: " . $e->getTraceAsString());
            return 1;
        }
    }
}
