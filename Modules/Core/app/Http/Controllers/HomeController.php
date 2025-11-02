<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Category\Services\CategoryService;
use Modules\Core\Services\Admin\HomeService;
use Modules\Product\Services\ProductService;

class HomeController extends Controller
{
    public function __construct(
        protected HomeService $homeService,
        protected CategoryService $categoryService,
        protected ProductService $productService

    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $host = $request->getHost();

        $mainDomain = app()->environment('production')
            ? config('app.main_domain', 'soqsyria.com')
            : 'market-platform.localhost';

        if (auth()->check() && auth()->user()->hasAnyRole(['owner', 'admin'])) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        // ✅ التحقق الصحيح
        if ($host === $mainDomain) {
            return view('core::app.home');
        }

        // في حالة المتجر الفرعي
        $store = current_store();

        if (! $store) {
            abort(404, 'Store not found');
        }

        $categories = $this->categoryService->getAllcategories();
        $topOrdered = $this->productService->getTopOrderedProducts($store->id, 10);
        $topViewed = $this->productService->getTopViewedProducts($store->id, 10);

        return view('themes.' . current_theme_name_en() . '.home', compact('store', 'categories', 'topOrdered', 'topViewed'));
    }
}
