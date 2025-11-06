<?php

namespace Modules\Store\Http\Controllers\App;

use Modules\Store\Models\Store;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Store\Services\Admin\StoreService;
use Modules\Store\Services\Admin\ThemeService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Modules\Store\Http\Requests\App\StoreCreateRequest;
use Modules\Store\Http\Requests\App\StoreUpdateRequest;

class StoreController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:owner', only: ['index', 'create', 'store', 'edit', 'update', 'destroy']),
        ];
    }

    public function __construct(
        protected StoreService $storeService,
        protected ThemeService $themeService

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
     * Display a list of stores owned by the authenticated user.
     */
    public function index()
    {
        $stores = $this->storeService->index(Auth::user());

        return view('store::app.index', compact('stores'));
    }

    /**
     * Show the form for creating a new store.
     */
    public function create()
    {
        $themes = $this->themeService->getAllThemes();

        return view('store::app.create', compact('themes'));
    }

    /**
     * Store a newly created store in the database.
     */
    public function store(StoreCreateRequest $request)
    {
        try {
            $data = $request->validated();

            $data['logo'] = $request->file('logo');

            $store = $this->storeService->store($data);

            return redirect()
                ->route('stores.index')
                ->with('success', __('Created successfully'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }



    /**
     * Display a specific store if the authenticated user is a member.
     */
    public function show(Store $store)
    {
        return view('store::app.show', compact('store'));
    }

    /**
     * Show the form for editing a specific store.
     */
    public function edit(Store $store)
    {
        $this->authorizeStoreAccess($store);
        $themes = $this->themeService->getAllThemes();

        return view('store::app.edit', compact('store', 'themes'));
    }

    /**
     * Update a specific store in the database.
     */
    public function update(StoreUpdateRequest $request, Store $store)
    {
        try {
            $this->authorizeStoreAccess($store);

            $data = $request->validated();

            // Delegate update logic to the service layer
            $store = $this->storeService->update($data, $store);

            return redirect()->route('stores.show', $store)
                ->with('success', __('Updated successfully'));
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a specific store from the database.
     */
    public function destroy(Store $store)
    {
        try {
            $this->authorizeStoreAccess($store);

            $this->storeService->delete($store);

            return redirect()->route('stores.index')
                ->with('success', __('Deleted successfully'));
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check if the authenticated user has access to the given store.
     * Throws a 403 error if the user is not a member.
     */
    private function authorizeStoreAccess(Store $store): void
    {
        if (! $store->users->contains(Auth::id())) {
            abort(403, 'You are not authorized to access this store.');
        }
    }
}
