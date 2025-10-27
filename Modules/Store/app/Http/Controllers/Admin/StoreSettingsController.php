<?php

namespace Modules\Store\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Store\Models\Store;
use App\Http\Controllers\Controller;
use Modules\Store\Services\Admin\StoreService;

class StoreSettingsController extends Controller
{
    public function __construct(
        protected StoreService $storeService
    ) {}
    /**
     * Show the form for editing the specified resource.
     */
    public function editSettings(Store $store)
    {
        $settings = $this->storeService->getSettings($store);

        return view('store::admin.settings', compact('store', 'settings'));
    }

    public function updateSettings(Request $request, Store $store)
    {
        $data = $request->all();

        $this->storeService->updateSettings($store, $data);

        return redirect()->route('store.settings.edit', $store->id)
            ->with('success', __('Settings updated successfully.'));
    }
}
