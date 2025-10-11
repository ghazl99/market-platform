<?php

namespace Modules\Wallet\Repositories\App;

use Carbon\Carbon;
use Modules\Wallet\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Modules\Wallet\Models\WalletTransaction;

class WalletModelRepository implements WalletRepository
{
    public function create(array $data): Wallet
    {
        return Wallet::create($data);
    }

    public function index(array $filters = [])
    {
        $user = Auth::user();

        $query = $user->walletForStore
            ->transactions()
            ->with(['wallet.user', 'order']);

        // تحديد المنطقة الزمنية من إعدادات المستخدم أو الإعداد الافتراضي
        $userTimezone = $user->settings['timezone'] ?? config('app.timezone');
        $now = Carbon::now($userTimezone);

        // فلترة سريعة (اليوم، الأسبوع، الشهر، العام)
        if (!empty($filters['quick'])) {
            switch ($filters['quick']) {
                case 'today':
                    $start = $now->copy()->startOfDay()->setTimezone('UTC');
                    $end   = $now->copy()->endOfDay()->setTimezone('UTC');
                    $query->whereBetween('created_at', [$start, $end]);
                    break;

                case 'week':
                    $start = $now->copy()->startOfWeek()->clone()->setTimezone('UTC');
                    $end   = $now->copy()->endOfWeek()->clone()->setTimezone('UTC');
                    $query->whereBetween('created_at', [$start, $end]);
                    break;

                case 'month':
                    $start = $now->copy()->startOfMonth()->clone()->setTimezone('UTC');
                    $end   = $now->copy()->endOfMonth()->clone()->setTimezone('UTC');
                    $query->whereBetween('created_at', [$start, $end]);
                    break;

                case 'year':
                    $start = $now->copy()->startOfYear()->clone()->setTimezone('UTC');
                    $end   = $now->copy()->endOfYear()->clone()->setTimezone('UTC');
                    $query->whereBetween('created_at', [$start, $end]);
                    break;
            }
        }


        // فلترة مخصصة بالتاريخ (من - إلى)
        if (!empty($filters['date_from'])) {
            $from = Carbon::parse($filters['date_from'], $userTimezone)
                ->startOfDay()
                ->timezone('UTC');
            $query->where('created_at', '>=', $from);
        }

        if (!empty($filters['date_to'])) {
            $to = Carbon::parse($filters['date_to'], $userTimezone)
                ->endOfDay()
                ->timezone('UTC');
            $query->where('created_at', '<=', $to);
        }

        // فلترة حسب نوع المعاملة (اختياري)
        if (!empty($filters['type']) && $filters['type'] !== 'all') {
            $query->where('type', $filters['type']);
        }

        return $query->latest()->paginate(10);
    }
}
