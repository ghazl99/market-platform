<?php

namespace Modules\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Modules\User\Http\Requests\Auth\RegisterOwnerRequest;
use Modules\User\Services\Auth\RegisterUserService;

class RegisteredUserController extends Controller
{
    // Inject the user registration service
    public function __construct(
        protected RegisterUserService $userService
    ) {}

    // Show the registration form view
    public function create()
    {
        return view('user::auth.register');
    }

    // Handle user registration request
    public function store(RegisterOwnerRequest $request)
    {
        $host = $request->getHost();
        // Call the service to register the user
        $this->userService->register($request->validated(), $host);

        // Redirect to home after successful registration
        return redirect()->route('home');
    }
}
