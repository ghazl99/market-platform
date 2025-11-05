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
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
    public function create(Request $request)
    {
        $user = Auth::user();
        $attributes = $this->attributeService->getAllAttributes();
        $categories = $this->categoryService->getAllCategoriesForProducts();

        // Get parent product if parent_id is provided
        $parentProduct = null;
        if ($request->has('parent_id')) {
            $parentProduct = Product::with('categories')->find($request->parent_id);
        }

        // Get selected category if category_id is provided
        $selectedCategory = null;
        if ($request->has('category_id')) {
            $selectedCategory = $this->categoryService->getCategoryById($request->category_id);
        }

        return view('product::dashboard.create', compact('attributes', 'categories', 'parentProduct', 'selectedCategory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            // Log all request data first
            Log::info('=== PRODUCT CREATION REQUEST START ===');
            Log::info('Request All Data:', $request->all());
            Log::info('Is AJAX: ' . ($request->ajax() ? 'Yes' : 'No'));
            Log::info('Is Sub-Product: ' . ($request->has('parent_id') ? 'Yes (Parent ID: ' . $request->parent_id . ')' : 'No'));

            // Skip duplicate check
            $data = $request->validated();

            Log::info('Validation Passed');
            Log::info('Validated Data:', $data);

            $product = $this->productService->createProduct($data);

            Log::info('Product created successfully with ID: ' . $product->id);

            // إذا كان منتج فرعي، إعادة التوجيه إلى صفحة المنتج الأب مع تاب المنتجات الفرعية
            if (isset($data['parent_id']) && $data['parent_id']) {
                Log::info('Redirecting to parent product page with sub-products tab: ' . $data['parent_id']);
                return redirect()
                    ->to(route('dashboard.product.show', $data['parent_id']) . '#subproducts-tab')
                    ->with('success', __('Sub-product created successfully'))
                    ->with('active_tab', 'subproducts');
            }

            // إذا تم إضافة المنتج من صفحة قسم معين، البقاء في نفس الصفحة
            if (isset($data['category']) && $data['category']) {
                $categoryId = $data['category'];
                Log::info('Redirecting to category products page: ' . $categoryId);
                return redirect()->route('dashboard.product.category', $categoryId)
                    ->with('success', __('تم إنشاء المنتج بنجاح'));
            }

            Log::info('Redirecting to products index');
            return redirect()->route('dashboard.product.index')
                ->with('success', __('تم إنشاء المنتج بنجاح'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('=== VALIDATION ERROR ===');
            Log::error('Validation errors: ' . json_encode($e->errors()));
            Log::error('Request data: ' . json_encode($request->all()));
            Log::error('==========');

            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Exception $e) {
            Log::error('=== PRODUCT CREATION ERROR ===');
            Log::error('Error: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            Log::error('Request data: ' . json_encode($request->all()));
            Log::error('==========');

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('حدث خطأ أثناء إنشاء المنتج. يرجى المحاولة مرة أخرى.'),
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', __('حدث خطأ أثناء إنشاء المنتج: ') . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['categories', 'attributes', 'children']);

        // جلب البيانات الحقيقية من قاعدة البيانات
        $maxQuantity = $product->max_quantity ?? 0;
        $minQuantity = $product->min_quantity ?? 0;

        // حساب كمية المبيعات من جدول الطلبات (الطلبات المكتملة فقط)
        $salesQuantity = \Modules\Order\Models\OrderItem::where('product_id', $product->id)
            ->whereHas('order', function($query) {
                $query->where('status', 'completed');
            })
            ->sum('quantity');

        // حساب إجمالي المبيعات (الكمية × سعر المنتج الحالي)
        $productPrice = $product->price ?? 0;
        $totalSales = $salesQuantity * $productPrice;

        // تحديد تكلفة الشراء: استخدام capital إذا كانت قيمة موجبة، وإلا استخدام original_price
        // إذا كانت capital غير موجودة أو صفر، نستخدم original_price
        $costPrice = null;

        if (isset($product->capital) && $product->capital > 0) {
            $costPrice = $product->capital;
        } elseif (isset($product->original_price) && $product->original_price > 0) {
            $costPrice = $product->original_price;
        } else {
            $costPrice = 0;
        }

        // رأس المال الإجمالي = تكلفة الشراء × الكمية المباعة
        $capital = $salesQuantity * $costPrice;

        // صافي الربح = إجمالي المبيعات - رأس المال
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

    /**
     * Show product image
     */
    public function showImage(Media $media)
    {
        $path = $media->getPath();

        if (! file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }
}
