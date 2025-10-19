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
        $notification = Auth::user()->notifications()->findOrFail($id);

        // علّم الإشعار كمقروء إذا لم يكن كذلك
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        // إعادة التوجيه إلى الصفحة الأصلية المحفوظة في البيانات
        $url = $notification->data['url'] ?? route('home');

        return redirect($url);
    }
}
