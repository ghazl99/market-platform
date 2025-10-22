<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Category\Services\CategoryService;
use Modules\Core\Services\Admin\HomeService;

class HomeController extends Controller
{
    public function __construct(
        protected HomeService $homeService,
        protected CategoryService $categoryService,

    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $host = $request->getHost();

        $mainDomain = config('app.main_domain', 'market-platform.localhost');

        if ($this->homeService->isMainDomain($host, $mainDomain)) {
            return view('core::app.home');
        }

        $store = $this->homeService->getStoreByHost($host);

        if (! $store) {
            abort(404, 'Store not found');
        }
        $categories = $this->categoryService->getAllcategories();

        return view('themes.' . current_store()->theme . '.home', compact('store', 'categories'));
    }
}
