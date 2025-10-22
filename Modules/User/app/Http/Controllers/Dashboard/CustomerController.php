<?php

namespace Modules\User\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\User\Models\User;
use Modules\User\Services\Dashboard\CustomerService;

class CustomerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:owner', only: ['index']),

        ];
    }

    public function __construct(
        protected CustomerService $customerService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $users = $this->customerService->getCustomers($search);
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();

        if (! $store) {
            abort(404, 'Store not found');
        }
        if ($request->ajax()) {
            $html = view('user::dashboard.customer.dataTables', compact('users', 'store'))->render();
            $pagination = $users->hasPages() ? $users->links()->toHtml() : '';

            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'hasPages' => $users->hasPages(),
            ]);
        }

        return view('user::dashboard.customer.index', compact('users', 'store'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user::dashboard.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('=== CUSTOMER CREATION REQUEST ===');
        Log::info('Controller method reached!');
        Log::info('Request method:', ['method' => $request->method()]);
        Log::info('Request URL:', ['url' => $request->url()]);
        Log::info('Customer creation request received', [
            'data' => $request->all()
        ]);

        try {
            Log::info('Starting validation...');
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'nullable|string|max:20',
                'birth_date' => 'nullable|date',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|string|in:customer,staff,owner',
                'status' => 'nullable|string|in:active,inactive,pending',
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'country' => 'nullable|string|max:2',
                'language' => 'nullable|string|in:ar,en',
                'timezone' => 'nullable|string',
                'email_notifications' => 'nullable|string|in:on,off',
                'sms_notifications' => 'nullable|string|in:on,off',
                'debt_limit' => 'nullable|numeric|min:0',
            ]);

            Log::info('Validation passed successfully!');

            // الحصول على المتجر الحالي أو استخدام المتجر الأول كبديل
            $store = \Modules\Store\Models\Store::currentFromUrl()->first();

            if (!$store) {
                // إذا لم يتم العثور على متجر من الدومين، استخدم المتجر الأول كبديل
                Log::info('No store found from URL, searching for active store...');
                $store = \Modules\Store\Models\Store::where('status', 'active')->first();
                Log::info('Active store found:', ['store' => $store ? $store->toArray() : null]);

                if (!$store) {
                    Log::error('No active store found');
                    return redirect()->back()
                        ->with('error', __('Store not found'))
                        ->withInput();
                }
            }

            // إنشاء المستخدم
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone' => $request->phone,
                'email_notifications' => $request->email_notifications === 'on',
                'sms_notifications' => $request->sms_notifications === 'on',
                'email_verified_at' => $request->status === 'active' ? now() : null,
                'debt_limit' => $request->debt_limit !== null && $request->debt_limit !== '' ? (float) $request->debt_limit : 0,
            ]);

            Log::info('User created successfully:', ['user_id' => $user->id]);

            // ربط المستخدم بالمتجر
            $user->stores()->attach($store->id, [
                'is_active' => $request->status === 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // إعطاء المستخدم الدور المطلوب
            $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $request->role]);
            $user->assignRole($role);

            Log::info('Customer creation completed successfully');
            return redirect('/dashboard/customer')
                ->with('success', __('Customer created successfully'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', [
                'errors' => $e->validator->errors()->toArray(),
                'input' => $request->all()
            ]);
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating customer: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            return redirect()->back()
                ->with('error', __('An error occurred while creating the customer. Please try again.'))
                ->withInput();
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            Log::info('CUSTOMER SHOW REQUEST', [
                'customer_id' => $id,
                'user_id' => Auth::user()?->id
            ]);

            $customer = User::findOrFail($id);
            $store = $this->getCurrentStore();

            if (!$store || !$customer->stores->contains($store->id)) {
                return redirect()->back()->with('error', __('Customer not found or access denied'));
            }

            // Load relationships and ensure data is fresh
            $customer->load('roles');
            $customer = $customer->fresh();

            Log::info('Customer show page loaded successfully', [
                'customer_id' => $id,
                'customer_name' => $customer->name,
                'customer_data' => $customer->toArray()
            ]);

            return view('user::dashboard.customer.show', compact('customer'));

        } catch (\Exception $e) {
            Log::error('Error loading customer show page', [
                'customer_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', __('Error loading customer data'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            Log::info('CUSTOMER EDIT REQUEST', [
                'customer_id' => $id,
                'user_id' => Auth::user()?->id
            ]);

            // Find the customer
            $customer = User::findOrFail($id);

            // Check if customer belongs to current store
            $store = $this->getCurrentStore();
            if (!$store || !$customer->stores->contains($store->id)) {
                return redirect()->back()->with('error', __('Customer not found or access denied'));
            }

            // Load relationships and ensure data is fresh
            $customer->load('roles');
            $customer = $customer->fresh();

            // Log customer data for debugging
            Log::info('Customer edit form loaded successfully', [
                'customer_id' => $id,
                'customer_name' => $customer->name,
                'birth_date_raw' => $customer->birth_date,
                'birth_date_formatted' => $customer->birth_date ? $customer->birth_date->format('Y-m-d') : null,
                'customer_data' => $customer->toArray()
            ]);

            return view('user::dashboard.customer.edit', compact('customer'));

        } catch (\Exception $e) {
            Log::error('Error loading customer edit form', [
                'customer_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', __('Error loading customer data'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        Log::info('=== CUSTOMER UPDATE REQUEST ===');
        Log::info('Controller method reached!');
        Log::info('Request method:', ['method' => $request->method()]);
        Log::info('Request URL:', ['url' => $request->url()]);
        Log::info('Customer update request received', [
            'customer_id' => $id,
            'data' => $request->all()
        ]);

        try {
            Log::info('Starting validation...');

            // Find the customer
            $customer = User::findOrFail($id);

            // Check if customer belongs to current store
            $store = $this->getCurrentStore();
            if (!$store || !$customer->stores->contains($store->id)) {
                return redirect()->back()->with('error', __('Customer not found or access denied'));
            }

            // Validation rules
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'birth_date' => 'nullable|date',
                'role' => 'required|string|in:customer,staff,owner',
                'status' => 'nullable|string|in:active,inactive,pending',
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'country' => 'nullable|string|max:2',
                'language' => 'nullable|string|in:ar,en',
                'timezone' => 'nullable|string',
                'email_notifications' => 'nullable|string|in:on,off',
                'sms_notifications' => 'nullable|string|in:on,off',
                'debt_limit' => 'nullable|numeric|min:0',
            ];

            // Add password validation only if password is provided
            if ($request->filled('password')) {
                $rules['password'] = 'required|string|min:8|confirmed';
            }

            $request->validate($rules);

            Log::info('Validation passed successfully!');

            // Prepare update data
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'birth_date' => $request->birth_date,
                'address' => $request->address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'country' => $request->country,
                'language' => $request->language,
                'timezone' => $request->timezone,
                'email_notifications' => $request->email_notifications === 'on',
                'sms_notifications' => $request->sms_notifications === 'on',
                'email_verified_at' => $request->status === 'active' ? now() : null,
                'debt_limit' => $request->debt_limit !== null && $request->debt_limit !== '' ? (float) $request->debt_limit : 0,
            ];

            // Add password only if provided
            if ($request->filled('password')) {
                $updateData['password'] = bcrypt($request->password);
            }

            // Update the customer
            $customer->update($updateData);

            Log::info('Customer updated successfully:', ['customer_id' => $customer->id]);

            // Update role
            $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $request->role]);
            $customer->syncRoles([$role]);

            Log::info('Customer update completed successfully');

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Customer updated successfully')
                ], 200);
            }

            return redirect('/dashboard/customer')
                ->with('success', __('Customer updated successfully'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', [
                'errors' => $e->validator->errors()->toArray(),
                'input' => $request->all()
            ]);
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating customer: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            return redirect()->back()
                ->with('error', __('An error occurred while updating the customer. Please try again.'))
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            Log::info('CUSTOMER DELETION REQUEST', [
                'customer_id' => $id,
                'user_id' => Auth::user()?->id
            ]);

            // Find the customer
            $customer = User::findOrFail($id);

            // Check if customer belongs to current store
            $store = $this->getCurrentStore();
            if (!$store || !$customer->stores->contains($store->id)) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Customer not found or access denied')
                ], 404);
            }
                return redirect()->back()->with('error', __('Customer not found or access denied'));
            }

            // Delete the customer
            $customerName = $customer->name;
            $customer->delete();

            Log::info('Customer deleted successfully', [
                'customer_id' => $id,
                'customer_name' => $customerName
            ]);

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Customer deleted successfully')
                ], 200);
            }

            return redirect('/dashboard/customer')->with('success', __('Customer deleted successfully'));

        } catch (\Exception $e) {
            Log::error('Error deleting customer', [
                'customer_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error deleting customer')
                ], 500);
            }

            return redirect()->back()->with('error', __('Error deleting customer'));
        }
    }

    /**
     * Get the current store from the authenticated user
     */
    private function getCurrentStore()
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        // Get the first active store for the user
        return $user->stores()->wherePivot('is_active', true)->first();
    }
}
