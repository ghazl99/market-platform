<?php

namespace Modules\Category\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Category\Models\Category;
use Modules\Category\Services\CategoryService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
    ) {}

    public function showImage(Media $media)
    {
        $path = $media->getPath();

        if (! file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $categories = $this->categoryService->getAllcategories();

    //     return view('category::app.index', compact('categories'));
    // }

    public function show(Category $category, Request $request)
    {
        $query = $request->input('query');
        $products = $this->categoryService->getProducts($category, $query);

        if ($request->ajax()) {
            $html = view("themes.' . current_theme_name_en() . '._products", compact('products'))->render();
            $pagination = $products->hasPages() ? $products->links()->toHtml() : '';

            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'hasPages' => $products->hasPages(),
            ]);
        }

        return view('themes.' . current_theme_name_en() . '.products', compact('category', 'products'));
    }

    public function getSubCategoryById($id)
    {
        $category = $this->categoryService->getAllSubcategoriesById($id);

        return view('themes.' . current_theme_name_en() . '.subCategoryById', compact('category'));
    }
}
