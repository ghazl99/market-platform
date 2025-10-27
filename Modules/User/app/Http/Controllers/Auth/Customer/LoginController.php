<?php

namespace Modules\User\Http\Controllers\Auth\Customer;

use App\Http\Controllers\Controller;
use Modules\User\Http\Requests\Auth\LoginRequest;
use Modules\User\Services\Auth\LoginUserService;

class LoginController extends Controller
{
    public function __construct(
        protected LoginUserService $loginService
    ) {}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $theme = current_store()->theme;
        return view("themes.$theme.login");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LoginRequest $request)
    {
        $result = $this->loginService->customerLogin($request->validated());

        if (! $result['success']) {
            return back()->withErrors([
                $result['field'] => $result['message'],
            ])->withInput();
        }

        return redirect()->intended(route('home'));
    }
    public function destroy()
    {
        $this->loginService->logout();


        return redirect()->route('auth.customer.login');
    }
}
