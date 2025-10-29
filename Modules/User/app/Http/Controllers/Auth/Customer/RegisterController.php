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
        return view('themes.' . current_theme_name_en() . '.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterUserRequest $request)
    {
        $host = $request->getHost();
        // Call the service to register the user
        $this->userService->registerCustomer($request->validated(), $host);

        // Redirect to home after successful registration
        return redirect()->route('home');
    }
}
