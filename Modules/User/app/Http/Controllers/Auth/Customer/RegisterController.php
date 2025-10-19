<?php

namespace Modules\User\Http\Controllers\Auth\Customer;

use App\Http\Controllers\Controller;
use Modules\User\Http\Requests\Auth\RegisterUserRequest;
use Modules\User\Services\Auth\RegisterUserService;

class RegisterController extends Controller
{
    public function __construct(
        protected RegisterUserService $userService
    ) {}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $theme = current_store()->theme;
        return view("themes.$theme.register");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterUserRequest $request)
    {
        $host = $request->getHost();
        // Call the service to register the user
        $this->userService->register($request->validated(), $host);

        // Redirect to home after successful registration
        return redirect()->route('home');
    }
}
