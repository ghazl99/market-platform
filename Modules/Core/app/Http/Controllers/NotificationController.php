<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\User\Models\User;

class NotificationController extends Controller
{
    /**
     * عرض صفحة الإشعارات في لوحة التحكم
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->paginate(20);

        return view('core::dashboard.notifications.index', compact('notifications'));
    }

    /**
     * تمييز إشعار كمقروء
     */
    public function markAsRead($id)
    {
        /** @var User $user */
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        // إعادة التوجيه إلى الصفحة الأصلية المحفوظة في البيانات
        $url = $notification->data['url'] ?? route('dashboard');

        return redirect($url);
    }

    /**
     * حذف إشعار
     */
    public function destroy($id)
    {
        /** @var User $user */
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->delete();

        return redirect()->back()->with('success', 'تم حذف الإشعار بنجاح');
    }

    /**
     * الحصول على عدد الإشعارات غير المقروءة (AJAX)
     */
    public function getUnreadCount()
    {
        /** @var User $user */
        $user = Auth::user();
        $count = $user->unreadNotifications()->count();

        return response()->json(['count' => $count]);
    }

}
