<?php

namespace Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\User\Services\Admin\UserService;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin', only: ['index']),

        ];
    }

    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userService->getAllUsers();

        return view('user::Admin.users.index', compact('users'));
    }

    // public function toggleAdminStatus(Request $request, $id)
    // {
    //     $user = User::findOrFail($id);

    //     $validator = Validator::make($request->all(), [
    //         'is_admin' => 'required|boolean',
    //     ]);

    //     if ($validator->fails()) {
    //         return back()->withErrors($validator);
    //     }

    //     $user->update(['is_admin' => $request->is_admin]);

    //     $statusText = $request->is_admin ? 'مدير' : 'مستخدم عادي';

    //     return back()->with('success', "تم تحديث صلاحيات المستخدم إلى: {$statusText}");
    // }
}
