<?php

namespace Modules\User\Http\Controllers\Auth\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\User\Services\Auth\ProfileService;
use Modules\User\Http\Requests\Auth\ProfileUpdateRequest;

class ProfileController extends Controller
{

    public function __construct(
        protected ProfileService $profileService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user::Auth.customer.security');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user::create');
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
        return view('user::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('user::Auth.customer.profile');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileUpdateRequest $request, $id)
    {
        $user = Auth::user();

        $this->profileService->updateProfile($user, $request->validated());

        return  redirect()->route('auth.profile.edit', $user->id)
            ->with('success', __('Updated successfully'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
