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
        $stores = Store::where('status', 'active')->get();
        return view('admin.groups.create', compact('stores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profit_percentage' => 'required|numeric|min:0|max:100',
            'store_id' => 'nullable|exists:stores,id',
        ]);

        try {
            $this->groupService->create([
                'name' => $request->name,
                'profit_percentage' => $request->profit_percentage,
                'is_default' => false,
                'store_id' => $request->store_id,
            ]);

            return redirect()->to(LaravelLocalization::localizeURL(route('admin.groups.index')))
                ->with('success', __('Group created successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('Failed to create group. Please try again.'));
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
        $stores = Store::where('status', 'active')->get();
        return view('admin.groups.edit', compact('group', 'stores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profit_percentage' => 'required|numeric|min:0|max:100',
            'store_id' => 'nullable|exists:stores,id',
        ]);

        try {
            $this->groupService->update($group, [
                'name' => $request->name,
                'profit_percentage' => $request->profit_percentage,
                'store_id' => $request->store_id,
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
            $this->groupService->delete($group);

            return redirect()->to(LaravelLocalization::localizeURL(route('admin.groups.index')))
                ->with('success', __('Group deleted successfully'));
        } catch (\Exception $e) {
            $message = $e->getMessage();
            
            if (str_contains($message, 'Cannot delete the default group')) {
                return redirect()->back()
                    ->with('error', __('Cannot delete the default group'));
            }

            return redirect()->back()
                ->with('error', __('Failed to delete group. Please try again.'));
        }
    }
}
