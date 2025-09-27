<?php

namespace Modules\Product\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Modules\Product\Models\Product;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('product::index');
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

        return view('product::app.show', compact('product'));
    }
}
