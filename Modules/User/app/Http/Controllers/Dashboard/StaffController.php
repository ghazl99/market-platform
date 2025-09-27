<?php

namespace Modules\User\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Permission\Services\Dashboard\StaffPermissionService;
use Modules\Store\Models\Store;
use Modules\User\Http\Requests\Auth\RegisterUserRequest;
use Modules\User\Models\User;
use Modules\User\Services\Dashboard\StaffService;

class StaffController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:owner', only: ['index', 'create', 'store', 'toggleActivation', 'edit', 'update']),

        ];
    }

    public function __construct(
        protected StaffService $staffService,
        protected StaffPermissionService $staffPermissionService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $users = $this->staffService->getStaffs($search);
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();

        if (! $store) {
            abort(404, 'Store not found');
        }
        if ($request->ajax()) {
            $html = view('user::dashboard.staff.dataTables', compact('users', 'store'))->render();
            $pagination = $users->hasPages() ? $users->links()->toHtml() : '';

            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'hasPages' => $users->hasPages(),
            ]);
        }

        return view('user::dashboard.staff.index', compact('users', 'store'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user::dashboard.staff.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterUserRequest $request)
    {
        // Call the service to register the user
        $this->staffService->register($request->validated());

        return redirect()->route('dashboard.staff.index')->with('success', __('Created successfully'));
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('user::show');
    }

    public function toggleActivation(\Modules\User\Models\User $user)
    {
        $store = Store::currentFromUrl()->firstOrFail();

        $this->staffService->toggleActive($user, $store->id);

        return response()->json([
            'success' => true,
            'message' => __('Updated successfully'),
            'is_active' => $user->stores()->where('store_id', $store->id)->first()->pivot->is_active,
        ]);
    }

    public function edit(User $user)
    {
        $permissions = $this->staffPermissionService->getAllPermissions();

        return view('user::dashboard.staff.edit-permissions', compact('user', 'permissions'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'permissions' => 'array',
        ]);

        $permissions = $request->permissions ?? [];

        // foreach ($permissions as $perm) {
        //     if (!\Spatie\Permission\Models\Permission::where('name', $perm)->exists()) {
        //         // إذا الصلاحية غير موجودة أنشئها
        //         $this->staffPermissionService->addNewPermission($perm);
        //     }
        // }

        // تحديث صلاحيات المستخدم
        $this->staffPermissionService->assignPermissionsToUser($user, $permissions);

        return redirect()->route('dashboard.staff.index')
            ->with('success', __('Updated successfully.'));
    }
}
