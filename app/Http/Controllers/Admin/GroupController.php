<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Group;
use App\Services\GroupService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Store\Models\Store;

class GroupController extends Controller
{
    public function __construct(
        protected GroupService $groupService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = Group::with(['store', 'users'])->withCount('users')->get();

        return view('admin.groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Store will be obtained automatically using helper in store() method
        return view('admin.groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Get current store using helper
        $currentStore = current_store();

        if (!$currentStore) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('Store not found. Please access from a valid store subdomain.'));
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'profit_percentage' => 'required|numeric|min:0|max:100',
        ]);

        try {
            \Log::info('GroupController::store called', [
                'request_data' => $request->all(),
                'store_id' => $currentStore->id,
                'store_name' => $currentStore->name,
            ]);

            $groupData = [
                'name' => trim($request->name),
                'profit_percentage' => (float) $request->profit_percentage,
                'is_default' => false,
                'store_id' => (int) $currentStore->id, // Ensure it's an integer
            ];

            \Log::info('Group data prepared for service:', $groupData);

            $group = $this->groupService->create($groupData);

            \Log::info('Group created successfully in controller', [
                'id' => $group->id,
                'store_id' => $group->store_id,
                'name' => $group->name,
            ]);

            return redirect()->to(LaravelLocalization::localizeURL(route('admin.groups.index')))
                ->with('success', __('Group created successfully'));
        } catch (\Exception $e) {
            \Log::error('Group creation failed in controller', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'store_id' => $currentStore->id ?? null,
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', __('Failed to create group: ') . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        $group->load('store');
        $users = $group->users()->paginate(20);

        return view('admin.groups.show', compact('group', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        // Store association cannot be changed, so no need to pass currentStore
        return view('admin.groups.edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profit_percentage' => 'required|numeric|min:0|max:100',
        ]);

        try {
            // Keep the original store_id, don't allow changing it
            $this->groupService->update($group, [
                'name' => $request->name,
                'profit_percentage' => $request->profit_percentage,
                // store_id is not updated - keep the original store association
            ]);

            return redirect()->to(LaravelLocalization::localizeURL(route('admin.groups.index')))
                ->with('success', __('Group updated successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('Failed to update group. Please try again.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        try {
            \Log::info('Attempting to delete group', [
                'group_id' => $group->id,
                'group_name' => $group->name,
                'is_default' => $group->is_default,
            ]);

            // Check if this is the default group before calling service
            if ($group->is_default) {
                \Log::warning('Attempted to delete default group', ['group_id' => $group->id]);
                return redirect()->route('admin.groups.index')
                    ->with('error', __('Cannot delete the default group'));
            }

            $this->groupService->delete($group);

            \Log::info('Group deleted successfully', ['group_id' => $group->id]);

            return redirect()->to(LaravelLocalization::localizeURL(route('admin.groups.index')))
                ->with('success', __('Group deleted successfully'));
        } catch (\Exception $e) {
            \Log::error('Group deletion failed', [
                'group_id' => $group->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $message = $e->getMessage();
            
            if (str_contains($message, 'Cannot delete the default group')) {
                return redirect()->route('admin.groups.index')
                    ->with('error', __('Cannot delete the default group'));
            }

            return redirect()->route('admin.groups.index')
                ->with('error', __('Failed to delete group: ') . $e->getMessage());
        }
    }
}
