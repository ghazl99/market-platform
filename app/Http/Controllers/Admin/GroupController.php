<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Group;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = Group::withCount('users')->get();

        return view('admin.groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profit_percentage' => 'required|numeric|min:0|max:100',
        ]);

        Group::create([
            'name' => $request->name,
            'profit_percentage' => $request->profit_percentage,
            'is_default' => false,
        ]);

        return redirect()->to(LaravelLocalization::localizeURL(route('admin.groups.index')))
            ->with('success', __('Group created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        $users = $group->users()->paginate(20);

        return view('admin.groups.show', compact('group', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
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

        $group->update([
            'name' => $request->name,
            'profit_percentage' => $request->profit_percentage,
        ]);

        return redirect()->to(LaravelLocalization::localizeURL(route('admin.groups.index')))
            ->with('success', __('Group updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        // Check if this is the default group
        if ($group->is_default) {
            return redirect()->back()
                ->with('error', __('Cannot delete the default group'));
        }

        // Move users to default group before deleting
        $defaultGroup = Group::getDefaultGroup();
        if ($defaultGroup) {
            $group->users()->update(['group_id' => $defaultGroup->id]);
        }

        $group->delete();

        return redirect()->to(LaravelLocalization::localizeURL(route('admin.groups.index')))
            ->with('success', __('Group deleted successfully'));
    }
}
