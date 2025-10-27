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
        // Check authorization - only store owner or staff can access
        $this->authorizeStoreAccess($store);

        $settings = $this->storeService->getSettings($store);

        return view('core::store.settings', compact('store', 'settings'));
    }

    public function updateSettings(Request $request, Store $store)
    {
        // Check authorization - only store owner or staff can update
        $this->authorizeStoreAccess($store);

        $data = $request->all();

        $this->storeService->updateSettings($store, $data);

        return redirect()->route('store.settings.edit', $store->id)
            ->with('success', __('Settings updated successfully.'));
    }

    public function updateTheme(Request $request, Store $store)
    {
        // Check authorization - only store owner or staff can update
        $this->authorizeStoreAccess($store);

        $request->validate([
            'theme_id' => 'required|exists:themes,id',
        ]);

        $store->update([
            'theme_id' => $request->theme_id,
        ]);

        return redirect()->route('store.settings.edit', $store->id)
            ->with('success', __('Theme updated successfully.'));
    }

    /**
     * Check if current user has access to this store
     */
    protected function authorizeStoreAccess(Store $store): void
    {
        $user = request()->user();

        // Check if user is associated with the store (as owner or staff)
        if ($store->users()->where('users.id', $user->id)->exists()) {
            return;
        }

        // If not authorized, abort with 403
        abort(403, 'You do not have access to this store.');
    }
}
