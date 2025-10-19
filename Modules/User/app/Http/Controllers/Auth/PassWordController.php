<?php

namespace Modules\User\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\User\Services\Auth\ProfileService;
use Modules\User\Http\Requests\Auth\ChangePasswordRequest;

class PassWordController extends Controller
{
    public function __construct(
        protected ProfileService $profileService
    ) {}

    public function changePasswordForm()
    {
        $theme = current_store()->theme;

        return view("themes.$theme.reset-password");
    }
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();
        $this->profileService->changePassword($user, $request->validated());

        return  redirect()->route('auth.change-password')
            ->with('success', __('Updated successfully'));
    }
}
