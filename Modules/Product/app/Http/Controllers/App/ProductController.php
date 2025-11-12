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
        protected \Modules\Product\Services\ProductService $productService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function subProducts($id, Request $request)
    {
        $storeId = current_store()->id;
        $query = $request->input('query'); // نص البحث
        $parentProductId = $id; // معرف المنتج الرئيسي

        $products = $this->productService->getSubProducts($id, $storeId, $query);

        if ($request->ajax()) {
            $html = view('themes.' . current_theme_name_en() . '._subProducts', compact('products'))->render();

            return response()->json([
                'html' => $html,
                'hasPages' => false, // لا pagination
            ]);
        }

        return view('themes.' . current_theme_name_en() . '.subProducts', compact('products', 'parentProductId'));
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
        $product->increment('views_count');
        $product->load(['categories', 'attributes', 'store']);

        return view('themes.' . current_theme_name_en() . '.product-purchase', compact('product'));
    }
}
