<?php

namespace Modules\Category\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Category\Http\Requests\CategoryRequest;
use Modules\Category\Http\Requests\UpdateCategoryRequest;
use Modules\Category\Models\Category;
use Modules\Category\Repositories\CategoryRepository;
use Modules\Category\Services\CategoryService;

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
    public function index()
    {
        $categories = $this->categoryService->getAllcategories();

        return view('category::dashboard.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category::dashboard.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $validatedData = $request->validated();

        $this->categoryService->store($validatedData + ['image' => $request->file('image')]);

        return redirect()->route('dashboard.category.index')->with('success', __('Created successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('category::dashboard.edit', compact('category'));
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
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
