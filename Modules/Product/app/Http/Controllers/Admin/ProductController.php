<?php

namespace Modules\Product\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Modules\Attribute\Services\AttributeService;
use Modules\Category\Services\CategoryService;
use Modules\Product\Http\Requests\StoreProductRequest;
use Modules\Product\Http\Requests\UpdateProductRequest;
use Modules\Product\Models\Product;
use Modules\Product\Services\ProductService;

class ProductController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:owner', only: ['index', 'create', 'store', 'edit', 'update', 'destroy']),

        ];
    }

    public function __construct(
        protected AttributeService $attributeService,
        protected CategoryService $categoryService,
        protected ProductService $productService,

    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('search');
        $products = $this->productService->getAllProducts($keyword);
        // return $products;
        if ($request->ajax()) {
            $html = view('product::dashboard.dataTables', compact('products'))->render();
            $pagination = $products->hasPages() ? $products->links()->toHtml() : '';

            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'hasPages' => $products->hasPages(),
            ]);
        }

        return view('product::dashboard.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $attributes = $this->attributeService->getAllAttributes();
        $categories = $this->categoryService->getAllSubcategories();

        return view('product::dashboard.create', compact('attributes', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        $this->productService->createProduct($data);

        return redirect()->route('dashboard.product.index')
            ->with('success', __('Created successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $user = Auth::user();
        $attributes = $this->attributeService->getAllAttributes();
        $categories = $this->categoryService->getAllSubcategories();

        return view('product::dashboard.edit', compact('product', 'attributes', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $data = $request->validated();

        try {
            $product = $this->productService->updateProduct($id, $data);

            if (! $product) {
                return redirect()->back()->with('error', __('Failed to update the product.'));
            }

            return redirect()->route('dashboard.product.index')->with('success', __('Updated successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product);

        return redirect()->route('dashboard.product.index')
            ->with('success', __('Deleted successfully'));
    }
}
