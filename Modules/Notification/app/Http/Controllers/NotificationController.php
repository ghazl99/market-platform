<?php

namespace Modules\Notification\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $theme = current_store()->theme;

        return view("themes.$theme.notifications");
    }

    public function markAsRead($id)
    {
        $user = Auth::user(); // Get the authenticated user
        $userId = $user->id;
        $userType = get_class($user); // Get the class name of the user model

        // Find the notification that matches the given ID and belongs to the authenticated user.
        // This approach bypasses the 'notifications()' method on the User model,
        // which the linter indicated as undefined, by directly querying the DatabaseNotification model.
        $notification = \Illuminate\Notifications\DatabaseNotification::where('id', $id)
            ->where('notifiable_id', $userId)
            ->where('notifiable_type', $userType)
            ->firstOrFail();

        // علّم الإشعار كمقروء إذا لم يكن كذلك
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        // إعادة التوجيه إلى الصفحة الأصلية المحفوظة في البيانات
        $url = $notification->data['url'] ?? route('home');

        return redirect($url);
    }
}
