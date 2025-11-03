<?php

namespace Modules\Store\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Modules\Store\Http\Requests\Admin\StoreCreateRequest;
use Modules\Store\Models\Store;
use Modules\Store\Services\Admin\StoreService;
use Modules\User\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class StoreController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin', only: ['index', 'create', 'store', 'show', 'updateStoreStatus']),

        ];
    }

    public function __construct(
        protected StoreService $storeService
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
        return view('store::admin.index', compact('stores'));
    }

    public function updateStoreStatus(Request $request, $id)
    {
        try {
            $statusText = $this->storeService->updateStoreStatus($id, $request->status);

            return back()->with('success', __('Updated successfully'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    /**
     * Show the form for creating a new store.
     */
    public function create()
    {
        // Get all users who have "owner" role
        $owners = User::role('owner')->get();

        return view('store::admin.create', compact('owners'));
    }

    /**
     * Store a newly created store in the database.
     */
    public function store(StoreCreateRequest $request)
    {
        try {
            $data = $request->validated();

            // Delegate store creation to the service layer
            $store = $this->storeService->store($data);

            return redirect()->route('admin.stores.show', $store)
                ->with('success', __('Created successfully'));
        } catch (\Exception $e) {
            // Log the error or return JSON response for AJAX requests
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a specific store if the authenticated user is a member.
     */
    public function show(Store $store)
    {
        return view('store::admin.show', compact('store'));
    }
}
