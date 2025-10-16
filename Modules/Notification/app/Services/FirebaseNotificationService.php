<?php

namespace Modules\Notification\Services;

use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Modules\User\Models\FcmToken;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class FirebaseNotificationService
{
    public function handle() {}
    protected static $messaging;

    /**
     * إعداد Firebase مرة واحدة فقط
     */
    protected static function init()
    {
        if (! self::$messaging) {
            $factory = (new Factory)
                ->withServiceAccount(base_path('firebase_credentials.json'));
            self::$messaging = $factory->createMessaging();
        }
    }

    /**
     * إرسال إشعار Firebase لمستخدم معين
     *
     * @param string $title     عنوان الإشعار
     * @param string $body      نص الإشعار
     * @param mixed  $user      كائن المستخدم المستهدف
     * @param array|null $data  بيانات إضافية (اختيارية)
     * @return bool
     */
    public static function send($user, ?array $data = null): bool
    {
        self::init();

        if (! $user) {
            Log::warning('Firebase Notification: user not provided.');
            return false;
        }
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();
        $fcmTokens = FcmToken::where('user_id', $user->id)->where('store_id', $store->id)->pluck('token');

        if ($fcmTokens->isEmpty()) {
            Log::warning("Firebase Notification: No FCM tokens found for user #{$user->id}");
            return false;
        }

        // $notification = FirebaseNotification::create($data['title'], $data['body'], $data['icon'] ?? null);

        foreach ($fcmTokens as $token) {
            try {
                $message = CloudMessage::new()
                    // ->withNotification($notification)
                    ->withData($data)
                    ->toToken($token);

                self::$messaging->send($message);
            } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
                // التوكن غير صالح — نحذفه
                FcmToken::where('token', $token)->delete();
                Log::warning("Invalid FCM token removed: {$token}");
            } catch (\Throwable $e) {
                Log::error("Firebase Notification Error: {$e->getMessage()}");
            }
        }

        return true;
    }
}
