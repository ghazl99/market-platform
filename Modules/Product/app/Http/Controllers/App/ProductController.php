<?php

namespace Modules\Product\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\Models\Product;
use Modules\Category\Services\CategoryService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('product::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->categoryService->getAllSubcategories();

        return view('product::dashboard.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Redirect to dashboard create page
        return redirect()->route('dashboard.product.create');
    }

    public function showImage(Media $media)
    {
        $path = $media->getPath();

        if (! file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    /**
     * Show the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['categories', 'attributes', 'store']);
        $theme = current_store()->theme;

        return view("themes.$theme.product-purchase", compact('product'));
    }
}
