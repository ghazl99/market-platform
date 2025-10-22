<?php

namespace Modules\Category\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Modules\Category\Http\Requests\CategoryRequest;
use Modules\Category\Http\Requests\UpdateCategoryRequest;
use Modules\Category\Models\Category;
use Modules\Category\Repositories\CategoryRepository;
use Modules\Category\Services\CategoryService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:owner', only: ['create', 'store', 'edit', 'update', 'destroy']),

        ];
    }

    public function __construct(
        protected CategoryService $categoryService,
        protected CategoryRepository $categoryRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Category $parent = null)
    {
        if ($parent) {
            // عرض الأقسام الفرعية
            $categories = $parent->children()->with('children')->get();
            $parentCategory = $parent;
        } else {
            // عرض الأقسام الرئيسية مع العلاقات
            $categories = $this->categoryService->getAllcategories();
            $parentCategory = null;
        }

        return view('category::dashboard.index', compact('categories', 'parentCategory'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // إذا كان هناك parent_id لا داعي لجلب كل الأقسام لتسريع الصفحة
        $parentId = request('parent_id');
        if ($parentId) {
            $parent = $this->categoryService->getCategoryById((int) $parentId);
            $categories = $parent ? collect([$parent]) : collect();
        } else {
            $categories = $this->categoryService->getAllcategories();
        }
        return view('category::dashboard.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $this->categoryService->store($validatedData + ['image' => $request->file('image')]);

            return redirect()->route('dashboard.category.index')->with('success', __('تم إضافة القسم بنجاح'));
        } catch (\Exception $e) {
            Log::error('Category creation failed: ' . $e->getMessage(), [
                'data' => $request->all(),
                'exception' => $e
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة القسم: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $categories = $this->categoryService->getAllcategories();
        return view('category::dashboard.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validatedData = $request->validated();

        $success = $this->categoryService->updateCategory($category, $validatedData);

        if ($success) {
            return redirect()->route('dashboard.category.index')->with('success', __('Updated successfully'));
        }

        return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء التحديث.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        // توجيه إلى index مع عرض الأقسام الفرعية
        return redirect()->route('dashboard.category.index.parent', ['parent' => $category->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $this->categoryService->deleteCategory($category);

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Category deleted successfully'),
                ]);
            }

            return redirect()
                ->route('dashboard.category.index')
                ->with('success', __('Category deleted successfully'));
        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error deleting category: ') . $e->getMessage(),
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', __('Error deleting category: ') . $e->getMessage());
        }
    }

    /**
     * Show category image
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
