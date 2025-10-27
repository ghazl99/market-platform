<?php

namespace Modules\Store\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Store\Services\Admin\ThemeService;

class ThemeController extends Controller
{

    public function __construct(
        protected ThemeService $themeService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $themes = $this->themeService->getAllThemes();

        return view('store::admin.themes.index', compact('themes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('store::admin.themes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $this->themeService->createTheme($data);

        return redirect()->route('admin.themes.index')->with('success', 'تمت إضافة الثيم بنجاح');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('store::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('store::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
