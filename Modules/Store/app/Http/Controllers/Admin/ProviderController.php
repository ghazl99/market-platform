<?php

namespace Modules\Store\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Modules\Store\Models\Provider;
use Modules\Store\Models\Store;
use Modules\Store\Services\ProviderService;

class ProviderController extends Controller implements HasMiddleware
{
    public function __construct(
        protected ProviderService $providerService
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('role:owner', only: ['index', 'store', 'destroy']),
        ];
    }

    /**
     * Display a listing of providers linked to the current store.
     */
    public function index()
    {
        $store = current_store();

        // Providers are only available for digital stores
        if ($store->type !== 'digital') {
            abort(404, 'Providers are only available for digital stores.');
        }

        // Get all providers linked to this store
        $linkedProviders = $store->providers()->get();

        // Get all available providers (for adding new ones)
        $allProviders = Provider::where('is_active', true)->get();

        return view('store::admin.providers.index', compact('store', 'linkedProviders', 'allProviders'));
    }

    /**
     * Store a newly linked provider to the store.
     */
    public function store(Request $request)
    {
        $store = current_store();

        // Providers are only available for digital stores
        if ($store->type !== 'digital') {
            abort(404, 'Providers are only available for digital stores.');
        }

        $request->validate([
            'provider_id' => 'required|exists:providers,id',
        ]);
        $provider = Provider::findOrFail($request->provider_id);

        // Check if provider is already linked to this store
        if ($store->providers()->where('providers.id', $provider->id)->exists()) {
            return redirect()->route('dashboard.providers.index')
                ->with('error', __('This provider is already linked to your store.'));
        }

        // Link provider to store
        $store->providers()->attach($provider->id, [
            'is_active' => true,
        ]);

        return redirect()->route('dashboard.providers.index')
            ->with('success', __('Provider linked successfully.'));
    }

    /**
     * Remove the provider link from the store.
     */
    public function destroy($providerId)
    {
        $store = current_store();

        // Providers are only available for digital stores
        if ($store->type !== 'digital') {
            abort(404, 'Providers are only available for digital stores.');
        }

        $provider = Provider::findOrFail($providerId);

        // Check if provider is linked to this store
        if (!$store->providers()->where('providers.id', $provider->id)->exists()) {
            return redirect()->route('dashboard.providers.index')
                ->with('error', __('Provider is not linked to your store.'));
        }

        // Remove provider link
        $store->providers()->detach($provider->id);

        return redirect()->route('dashboard.providers.index')
            ->with('success', __('Provider unlinked successfully.'));
    }

    /**
     * Toggle provider active status for the store.
     */
    public function toggleStatus(Request $request, $providerId)
    {
        $store = current_store();

        // Providers are only available for digital stores
        if ($store->type !== 'digital') {
            abort(404, 'Providers are only available for digital stores.');
        }

        $request->validate([
            'is_active' => 'required|boolean',
        ]);
        $provider = Provider::findOrFail($providerId);

        // Check if provider is linked to this store
        if (!$store->providers()->where('providers.id', $provider->id)->exists()) {
            return redirect()->route('dashboard.providers.index')
                ->with('error', __('Provider is not linked to your store.'));
        }

        // Update pivot table
        $store->providers()->updateExistingPivot($provider->id, [
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('dashboard.providers.index')
            ->with('success', __('Provider status updated successfully.'));
    }

    /**
     * Get products from provider API
     */
    public function getProducts(Provider $provider)
    {
        $store = current_store();

        // Providers are only available for digital stores
        if ($store->type !== 'digital') {
            abort(404, 'Providers are only available for digital stores.');
        }

        // Check if provider is linked to this store
        if (!$store->providers()->where('providers.id', $provider->id)->exists()) {
            return response()->json(['error' => __('Provider is not linked to your store.')], 403);
        }

        $products = $this->providerService->getProductsFromProvider($provider);

        // Log for debugging
        Log::info('Provider products fetched in controller', [
            'provider_id' => $provider->id,
            'provider_name' => $provider->name,
            'products_count' => count($products),
            'first_product' => $products[0] ?? null,
            'products_structure' => !empty($products) ? array_keys($products[0] ?? []) : []
        ]);

        // Format products for select dropdown
        // Format: name - id (value will be just the id)
        $formattedProducts = [];
        foreach ($products as $product) {
            // Handle both array and object formats
            if (is_array($product)) {
                $name = $product['name'] ?? $product['product_name'] ?? 'Unknown';
                $id = $product['id'] ?? '';
            } else {
                $name = $product->name ?? $product->product_name ?? 'Unknown';
                $id = $product->id ?? '';
            }

            if ($id && $name) {
                $formattedProducts[] = [
                    'value' => (string)$id, // رقم المنتج (id) - convert to string
                    'text' => $name . ' - ' . $id // الاسم والرقم للعرض
                ];
            }
        }

        Log::info('Formatted products for response', [
            'provider_id' => $provider->id,
            'formatted_count' => count($formattedProducts),
            'sample' => $formattedProducts[0] ?? null
        ]);

        return response()->json([
            'success' => true,
            'products' => $formattedProducts,
            'raw_count' => count($products) // For debugging
        ]);
    }
}

