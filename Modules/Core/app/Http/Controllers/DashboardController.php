<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Services\Admin\HomeService;
use Modules\Store\Models\Store;
use Modules\User\Models\User;

class DashboardController extends Controller
{
    public function __construct(
        protected HomeService $homeService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $host = $request->getHost();

        $store = $this->homeService->getStoreByHost($host);

        if (! $store) {
            abort(404, 'Store not found');
        }

        return view('core::store.dashboard', compact('store'));
    }

    public function dashboadAdmin()
    {
        $totalStores = Store::count();
        $activeStores = Store::where('status', 'active')->count();
        $pendingStores = Store::where('status', 'pending')->count();
        $totalUsers = User::count();
        $recentStores = Store::with('owners')->latest()->take(5)->get();

        return view('core::dashboard.index', compact('totalStores', 'activeStores', 'pendingStores', 'totalUsers', 'recentStores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('core::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('core::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('core::edit');
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
