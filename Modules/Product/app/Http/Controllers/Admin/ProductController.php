<?php

namespace Modules\Product\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
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
        $categoryFilter = $request->input('category');
        $statusFilter = $request->input('status');

        // دعم فلترة المنتجات حسب القسم من URL parameter
        if ($request->route('category')) {
            $categoryFilter = $request->route('category');
        }

        $products = $this->productService->getAllProducts($keyword, $categoryFilter, $statusFilter);
        $categories = $this->categoryService->getAllSubcategories();

        // الحصول على معلومات القسم المحدد إذا كان موجوداً
        $selectedCategory = null;
        if ($categoryFilter) {
            $selectedCategory = $this->categoryService->getCategoryById($categoryFilter);
        }

        if ($request->ajax()) {
            $html = view('product::dashboard.dataTables', compact('products'))->render();
            $pagination = $products->hasPages() ? $products->links()->toHtml() : '';

            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'hasPages' => $products->hasPages(),
            ]);
        }

        return view('product::dashboard.index', compact('products', 'categories', 'selectedCategory'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $attributes = $this->attributeService->getAllAttributes();
        $categories = $this->categoryService->getAllCategoriesForProducts();

        return view('product::dashboard.create', compact('attributes', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            // Check if this is a duplicate request (same data within 5 seconds)
            $requestHash = md5(json_encode($request->except(['_token', 'image'])));
            $cacheKey = 'product_creation_' . $requestHash;

            if (Cache::has($cacheKey)) {
                Log::info('Duplicate product creation request detected, ignoring...');
                return response()->json([
                    'success' => false,
                    'message' => 'تم إرسال الطلب مسبقاً، يرجى الانتظار...'
                ]);
            }

            // Cache the request for 5 seconds
            Cache::put($cacheKey, true, 5);

            $data = $request->validated();

            // Skip duplicate check for now to test
            Log::info('Skipping duplicate check for testing');

            // Log request details
            Log::info('Product store request:', [
                'is_ajax' => $request->ajax(),
                'expects_json' => $request->expectsJson(),
                'content_type' => $request->header('Content-Type'),
                'x_requested_with' => $request->header('X-Requested-With'),
                'accept' => $request->header('Accept')
            ]);

            Log::info('Creating product with data:', $data);

            $product = $this->productService->createProduct($data);

            Log::info('Product created successfully with ID:', ['id' => $product->id]);

            // Always return redirect with success message like Category
            Log::info('Returning redirect response with success message');
            return redirect()->route('dashboard.product.index')
                ->with('success', __('تم إنشاء المنتج بنجاح'));
        } catch (\Exception $e) {
            Log::error('Product creation error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            if ($request->expectsJson() || $request->ajax()) {
                Log::info('Returning JSON error response');
                return response()->json([
                    'success' => false,
                    'message' => __('حدث خطأ أثناء إنشاء المنتج. يرجى المحاولة مرة أخرى.'),
                    'error' => $e->getMessage()
                ], 500);
            }

            Log::info('Returning redirect error response');
            return redirect()->back()
                ->withInput()
                ->with('error', __('حدث خطأ أثناء إنشاء المنتج. يرجى المحاولة مرة أخرى.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['categories', 'attributes']);

        // جلب البيانات الحقيقية من قاعدة البيانات
        $maxQuantity = $product->max_quantity ?? 0;
        $minQuantity = $product->min_quantity ?? 0;
        $capital = $product->cost ?? 0;

        // حساب كمية المبيعات من جدول الطلبات
        $salesQuantity = \Modules\Order\Models\OrderItem::where('product_id', $product->id)
            ->whereHas('order', function($query) {
                $query->where('status', 'completed');
            })
            ->sum('quantity');

        // حساب البيانات المالية
        $totalSales = \Modules\Order\Models\OrderItem::where('product_id', $product->id)
            ->whereHas('order', function($query) {
                $query->where('status', 'completed');
            })
            ->get()
            ->sum(function($item) {
                return $item->quantity * ($item->product->price ?? 0);
            });

        $netProfit = $totalSales - $capital;

        // جلب بيانات الجدول (آخر 10 طلبات)
        $recentOrders = \Modules\Order\Models\OrderItem::where('product_id', $product->id)
            ->with(['order', 'product'])
            ->whereHas('order', function($query) {
                $query->where('status', 'completed');
            })
            ->latest()
            ->limit(10)
            ->get();

        return view('product::dashboard.show', compact(
            'product',
            'maxQuantity',
            'minQuantity',
            'capital',
            'salesQuantity',
            'totalSales',
            'netProfit',
            'recentOrders'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $user = Auth::user();
        $attributes = $this->attributeService->getAllAttributes();
        $categories = $this->categoryService->getAllCategoriesForProducts();

        return view('product::dashboard.edit', compact('product', 'attributes', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        Log::info('Product update method called', [
            'id' => $id,
            'request_data' => $request->all(),
            'method' => $request->method()
        ]);

        $data = $request->validated();

        try {
            $product = $this->productService->updateProduct($id, $data);

            if (! $product) {
                Log::error('Product update failed - product not found', ['id' => $id]);
                return redirect()->back()->with('error', __('Failed to update the product.'));
            }

            Log::info('Product updated successfully', ['id' => $id, 'product' => $product->toArray()]);
            return redirect()->route('dashboard.product.index')->with('success', __('Product updated successfully'));
        } catch (\Exception $e) {
            Log::error('Product update exception', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $this->productService->deleteProduct($product);

            return response()->json([
                'success' => true,
                'message' => __('Product deleted successfully'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
