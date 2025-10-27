<?php

namespace Modules\User\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Services\Admin\HomeService;
use Modules\User\Services\Auth\LoginUserService;
use Modules\User\Http\Requests\Auth\LoginRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        protected LoginUserService $loginService,
        protected HomeService $homeService

    ) {}

    public function showImage(Media $media)
    {
        $path = $media->getPath();

        if (! file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    public function create()
    {
        return view('user::auth.login');
    }

    public function customerLogin(LoginRequest $request)
    {
        $result = $this->loginService->customerLogin($request->validated());

        if (! $result['success']) {
            return back()->withErrors([
                $result['field'] => $result['message'],
            ])->withInput();
        }

        return redirect()->intended(route('home'));
    }

    public function login(LoginRequest $request)
    {
        $result = $this->loginService->login($request->validated());

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

        return redirect()->route('auth.login');
    }
}
