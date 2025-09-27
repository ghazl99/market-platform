<?php

namespace Modules\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Services\Admin\HomeService;
use Modules\User\Http\Requests\Auth\LoginRequest;
use Modules\User\Services\Auth\LoginUserService;
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

    public function store(LoginRequest $request)
    {
        $result = $this->loginService->login($request->validated());

        if (! $result['success']) {
            return back()->withErrors([
                $result['field'] => $result['message'],
            ])->withInput();
        }

        return redirect()->intended(route('home'));
    }

    public function destroy(Request $request)
    {
        $this->loginService->logout();
        $host = $request->getHost();
        $mainDomain = config('app.main_domain', 'market-platform.localhost');

        if ($this->homeService->isMainDomain($host, $mainDomain)) {
            return redirect()->route('auth.login');
        }

        return redirect()->route('auth.customer.login');
    }
}
