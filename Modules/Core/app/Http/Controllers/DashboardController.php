<?php

namespace Modules\Core\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\User\Models\User;
use Modules\Order\Models\Order;
use Modules\Store\Models\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Order\Models\OrderItem;
use Modules\Product\Models\Product;
use App\Http\Controllers\Controller;
use Modules\Category\Models\Category;
use Modules\Wallet\Models\PaymentRequest;
use Modules\Core\Services\Admin\HomeService;
use Modules\Wallet\Models\WalletTransaction;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin', only: ['dashboadAdmin']),
            new Middleware('role:owner', only: ['index', 'statistics']),

        ];
    }
    public function __construct(
        protected HomeService $homeService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $store = current_store(); // Using current_store() helper

        if (!$store) {
            abort(404, 'Store not found');
        }

        // إحصائيات حقيقية من المتجر
        // إجمالي المبيعات (جميع الطلبات المكتملة)
        $totalSales = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->sum('total_amount');

        // مبيعات الشهر الماضي
        $totalSalesLastMonth = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('total_amount');

        // مبيعات الشهر الحالي
        $totalSalesThisMonth = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

        // حساب نسبة النمو (مقارنة الشهر الحالي بالشهر الماضي)
        $salesGrowth = $totalSalesLastMonth > 0
            ? (($totalSalesThisMonth - $totalSalesLastMonth) / $totalSalesLastMonth) * 100
            : ($totalSalesThisMonth > 0 ? 100 : 0);

        // الطلبات الجديدة اليوم
        $newOrders = Order::where('store_id', $store->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        // الطلبات في نفس اليوم من الأسبوع الماضي (للمقارنة الصحيحة)
        $sameDayLastWeek = Carbon::now()->subWeek();
        $newOrdersLastWeek = Order::where('store_id', $store->id)
            ->whereDate('created_at', $sameDayLastWeek)
            ->count();

        // حساب نسبة النمو (مقارنة اليوم بنفس اليوم من الأسبوع الماضي)
        $ordersGrowth = $newOrdersLastWeek > 0
            ? (($newOrders - $newOrdersLastWeek) / $newOrdersLastWeek) * 100
            : ($newOrders > 0 ? 100 : 0);

        // العملاء الجدد اليوم (العملاء الحقيقيون = الذين لديهم طلبات في المتجر)
        // جلب العملاء الذين لديهم أول طلب لهم اليوم (ليس لديهم طلبات سابقة)
        $customersWithOrdersToday = Order::where('store_id', $store->id)
            ->whereDate('created_at', Carbon::today())
            ->select('user_id')
            ->distinct()
            ->pluck('user_id')
            ->toArray();

        // جلب العملاء الذين لديهم طلبات قبل اليوم
        $customersWithPreviousOrders = Order::where('store_id', $store->id)
            ->whereDate('created_at', '<', Carbon::today())
            ->select('user_id')
            ->distinct()
            ->pluck('user_id')
            ->toArray();

        // العملاء الجدد = الذين لديهم طلبات اليوم وليس لديهم طلبات سابقة
        $newCustomers = count(array_diff($customersWithOrdersToday, $customersWithPreviousOrders));

        // العملاء الجدد في نفس اليوم من الأسبوع الماضي (للمقارنة الصحيحة)
        // استخدام نفس المتغير $sameDayLastWeek المحدد أعلاه
        $customersWithOrdersSameDayLastWeek = Order::where('store_id', $store->id)
            ->whereDate('created_at', $sameDayLastWeek)
            ->select('user_id')
            ->distinct()
            ->pluck('user_id')
            ->toArray();

        $customersWithPreviousOrdersBeforeLastWeek = Order::where('store_id', $store->id)
            ->whereDate('created_at', '<', $sameDayLastWeek)
            ->select('user_id')
            ->distinct()
            ->pluck('user_id')
            ->toArray();

        $newCustomersLastWeek = count(array_diff($customersWithOrdersSameDayLastWeek, $customersWithPreviousOrdersBeforeLastWeek));

        // حساب نسبة النمو (مقارنة اليوم بنفس اليوم من الأسبوع الماضي)
        $customersGrowth = $newCustomersLastWeek > 0
            ? (($newCustomers - $newCustomersLastWeek) / $newCustomersLastWeek) * 100
            : ($newCustomers > 0 ? 100 : 0);

        // حساب معدل التحويل (عدد العملاء الذين لديهم طلبات / إجمالي العملاء الفريدين)
        $totalOrders = Order::where('store_id', $store->id)->count();

        // إجمالي العملاء الفريدين (الذين لديهم طلبات في المتجر)
        $totalCustomers = Order::where('store_id', $store->id)
            ->select('user_id')
            ->distinct()
            ->count('user_id');

        // معدل التحويل الحالي = (عدد الطلبات / عدد العملاء الفريدين)
        // هذا يعطي متوسط عدد الطلبات لكل عميل
        $conversionRate = $totalCustomers > 0 ? ($totalOrders / $totalCustomers) : 0;

        // الطلبات في الشهر الماضي
        $ordersLastMonth = Order::where('store_id', $store->id)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        // العملاء الفريدين حتى نهاية الشهر الماضي
        $customersUntilLastMonth = Order::where('store_id', $store->id)
            ->where('created_at', '<=', Carbon::now()->subMonth()->endOfMonth())
            ->select('user_id')
            ->distinct()
            ->count('user_id');

        // معدل التحويل الشهر الماضي
        $conversionRateLastMonth = $customersUntilLastMonth > 0
            ? ($ordersLastMonth / $customersUntilLastMonth)
            : 0;

        // حساب نسبة النمو في معدل التحويل
        $conversionGrowth = $conversionRateLastMonth > 0
            ? (($conversionRate - $conversionRateLastMonth) / $conversionRateLastMonth) * 100
            : ($conversionRate > 0 ? 100 : 0);

        // الأنشطة الأخيرة الحقيقية
        $activities = collect();

        // أحدث الطلبات
        $recentOrders = Order::where('store_id', $store->id)
            ->with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($order) {
                return [
                    'type' => 'order',
                    'icon' => 'shopping-cart',
                    'title' => __('New Order') . ' #' . $order->id,
                    'description' => __('Order received with amount') . ' $' . number_format($order->total_amount, 2),
                    'time' => $order->created_at,
                    'url' => route('dashboard.order.show', $order->id)
                ];
            });

        // أحدث المدفوعات
        $recentPayments = WalletTransaction::whereHas('wallet', function($query) use ($store) {
            $query->where('store_id', $store->id);
        })
        ->where('type', 'deposit')
        ->latest()
        ->take(5)
        ->get()
        ->map(function($transaction) {
            return [
                'type' => 'payment',
                'icon' => 'credit-card',
                'title' => __('Payment Received'),
                'description' => __('Payment with amount') . ' $' . number_format($transaction->amount, 2),
                'time' => $transaction->created_at,
                'url' => null
            ];
        });

        // أحدث العملاء (الذين لديهم طلبات في المتجر) - حسب تاريخ أول طلب
        $recentUsers = Order::where('store_id', $store->id)
            ->select('user_id', DB::raw('MIN(created_at) as first_order_date'))
            ->groupBy('user_id')
            ->orderBy('first_order_date', 'desc')
            ->take(5)
            ->get()
            ->map(function($order) use ($store) {
                $user = User::find($order->user_id);
                if (!$user) {
                    return null;
                }

                return [
                    'type' => 'user',
                    'icon' => 'user-plus',
                    'title' => __('New Customer'),
                    'description' => $user->name . ' ' . __('placed first order'),
                    'time' => Carbon::parse($order->first_order_date),
                    'url' => route('dashboard.customer.show', $user->id)
                ];
            })
            ->filter(); // إزالة القيم null

        // أحدث المنتجات
        $recentProducts = Product::where('store_id', $store->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function($product) {
                return [
                    'type' => 'product',
                    'icon' => 'box',
                    'title' => __('Stock Update'),
                    'description' => __('New product added') . ': ' . $product->name,
                    'time' => $product->created_at,
                    'url' => route('dashboard.product.show', $product->id)
                ];
            });

        // دمج جميع الأنشطة وترتيبها حسب التاريخ
        $activities = $recentOrders
            ->concat($recentPayments)
            ->concat($recentUsers)
            ->concat($recentProducts)
            ->sortByDesc('time')
            ->take(10)
            ->values();

        // بيانات المبيعات للرسم البياني (آخر 7 أيام)
        $salesData7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $daySales = Order::where('store_id', $store->id)
                ->where('status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('total_amount');

            $salesData7Days[] = [
                'date' => $date->format('Y-m-d'),
                'label' => $date->locale(app()->getLocale())->dayName,
                'sales' => (float) $daySales // تأكد من أن القيمة عدد عشري
            ];
        }

        // بيانات المبيعات للرسم البياني (آخر 30 يوم)
        $salesData30Days = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $daySales = Order::where('store_id', $store->id)
                ->where('status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('total_amount');

            $salesData30Days[] = [
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('d/m'),
                'sales' => (float) $daySales // تأكد من أن القيمة عدد عشري
            ];
        }

        // بيانات المبيعات للرسم البياني (آخر 90 يوم - تجميع أسبوعي)
        $salesData90Days = [];
        for ($i = 12; $i >= 0; $i--) {
            $weekStart = Carbon::now()->subWeeks($i)->startOfWeek();
            $weekEnd = Carbon::now()->subWeeks($i)->endOfWeek();
            $weekSales = Order::where('store_id', $store->id)
                ->where('status', 'completed')
                ->whereBetween('created_at', [$weekStart, $weekEnd])
                ->sum('total_amount');

            $salesData90Days[] = [
                'date' => $weekStart->format('Y-m-d'),
                'label' => $weekStart->format('d/m') . ' - ' . $weekEnd->format('d/m'),
                'sales' => (float) $weekSales // تأكد من أن القيمة عدد عشري
            ];
        }

        // معلومات إضافية مفيدة
        // إجمالي العملاء (الذين لديهم طلبات)
        $totalCustomersCount = $totalCustomers;

        // متوسط قيمة الطلب
        $averageOrderValue = $totalOrders > 0 ? ($totalSales / $totalOrders) : 0;

        // المنتجات النشطة
        $activeProducts = Product::where('store_id', $store->id)
            ->where(function($query) {
                $query->where('status', 'active')
                      ->orWhere('is_active', true);
            })
            ->count();

        // الطلبات قيد الانتظار
        $pendingOrders = Order::where('store_id', $store->id)
            ->where('status', 'pending')
            ->count();

        // مبيعات هذا الأسبوع
        $thisWeekSales = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->sum('total_amount');

        // مبيعات الأسبوع الماضي
        $lastWeekSales = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [
                Carbon::now()->subWeek()->startOfWeek(),
                Carbon::now()->subWeek()->endOfWeek()
            ])
            ->sum('total_amount');

        return view('core::dashboard.'. $store->type .'.dashboard', compact(
            'store',
            'totalSales',
            'salesGrowth',
            'newOrders',
            'ordersGrowth',
            'newCustomers',
            'customersGrowth',
            'conversionRate',
            'conversionGrowth',
            'activities',
            'salesData7Days',
            'salesData30Days',
            'salesData90Days',
            'totalCustomersCount',
            'averageOrderValue',
            'activeProducts',
            'pendingOrders',
            'thisWeekSales',
            'lastWeekSales'
        ));
    }


    public function dashboadAdmin()
    {
        $totalStores = Store::count();
        $activeStores = Store::where('status', 'active')->count();
        $pendingStores = Store::where('status', 'pending')->count();
        $totalUsers = User::count();
        $recentStores = Store::with('owners')->latest()->take(5)->get();

        return view('core::dashboard.index', compact('totalStores', 'activeStores', 'pendingStores', 'totalUsers', 'recentStores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('core::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('core::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('core::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}

    /**
     * Display statistics page
     */
    public function statistics(Request $request)
    {
        $store = current_store(); // Using current_store() helper

        if (!$store) {
            abort(404, 'Store not found');
        }

        // الحصول على نطاق التاريخ من الطلب
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // التحقق من صحة التواريخ
        if ($fromDate && $toDate && strtotime($fromDate) > strtotime($toDate)) {
            return redirect()->route('dashboard.statistics')
                ->with('error', __('From date must be before to date'));
        }

        // وظيفة للفلترة حسب التاريخ
        $applyDateFilter = function($query) use ($fromDate, $toDate) {
            if ($fromDate && $toDate) {
                $query->whereBetween('created_at', [
                    Carbon::parse($fromDate)->startOfDay(),
                    Carbon::parse($toDate)->endOfDay()
                ]);
            } elseif ($fromDate) {
                $query->whereDate('created_at', '>=', $fromDate);
            } elseif ($toDate) {
                $query->whereDate('created_at', '<=', $toDate);
            }
        };

        // إحصائيات العملاء (الذين لديهم طلبات في المتجر)
        // إجمالي العملاء الفريدين
        $totalUsersQuery = Order::where('store_id', $store->id)
            ->select('user_id')
            ->distinct();
        if ($fromDate || $toDate) {
            $applyDateFilter($totalUsersQuery);
        }
        $totalUsers = $totalUsersQuery->count('user_id');

        // العملاء النشطين (الذين لديهم طلبات في آخر 30 يوم)
        $activeUsers = Order::where('store_id', $store->id)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->select('user_id')
            ->distinct()
            ->count('user_id');

        // العملاء الجدد اليوم (أول طلب لهم اليوم)
        $customersWithOrdersToday = Order::where('store_id', $store->id)
            ->whereDate('created_at', Carbon::today())
            ->select('user_id')
            ->distinct()
            ->pluck('user_id')
            ->toArray();

        $customersWithPreviousOrders = Order::where('store_id', $store->id)
            ->whereDate('created_at', '<', Carbon::today())
            ->select('user_id')
            ->distinct()
            ->pluck('user_id')
            ->toArray();

        $newUsersToday = count(array_diff($customersWithOrdersToday, $customersWithPreviousOrders));

        // العملاء الجدد هذا الشهر (أول طلب لهم هذا الشهر)
        $customersWithOrdersThisMonth = Order::where('store_id', $store->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->select('user_id')
            ->distinct()
            ->pluck('user_id')
            ->toArray();

        $customersWithPreviousOrdersBeforeMonth = Order::where('store_id', $store->id)
            ->where('created_at', '<', Carbon::now()->startOfMonth())
            ->select('user_id')
            ->distinct()
            ->pluck('user_id')
            ->toArray();

        $newUsersThisMonth = count(array_diff($customersWithOrdersThisMonth, $customersWithPreviousOrdersBeforeMonth));

        // إحصائيات الطلبات
        $totalOrdersQuery = Order::where('store_id', $store->id);
        if ($fromDate || $toDate) {
            $applyDateFilter($totalOrdersQuery);
        }
        $totalOrders = $totalOrdersQuery->count();

        $completedOrdersQuery = Order::where('store_id', $store->id)->where('status', 'completed');
        if ($fromDate || $toDate) {
            $applyDateFilter($completedOrdersQuery);
        }
        $completedOrders = $completedOrdersQuery->count();

        $pendingOrdersQuery = Order::where('store_id', $store->id)->where('status', 'pending');
        if ($fromDate || $toDate) {
            $applyDateFilter($pendingOrdersQuery);
        }
        $pendingOrders = $pendingOrdersQuery->count();

        $confirmedOrdersQuery = Order::where('store_id', $store->id)->where('status', 'confirmed');
        if ($fromDate || $toDate) {
            $applyDateFilter($confirmedOrdersQuery);
        }
        $confirmedOrders = $confirmedOrdersQuery->count();

        $cancelledOrdersQuery = Order::where('store_id', $store->id)->whereIn('status', ['cancelled', 'canceled']);
        if ($fromDate || $toDate) {
            $applyDateFilter($cancelledOrdersQuery);
        }
        $cancelledOrders = $cancelledOrdersQuery->count();

        // التحقق من تطابق مجموع الطلبات
        $ordersSum = $completedOrders + $pendingOrders + $confirmedOrders + $cancelledOrders;
        $ordersDifference = $totalOrders - $ordersSum;

        // التحقق من تطابق مجموع الطلبات (قد يكون هناك حالات أخرى غير المعروضة)
        // هذا للتحقق فقط - لا يؤثر على البيانات المعروضة

        $totalSalesAmountQuery = Order::where('store_id', $store->id)->where('status', 'completed');
        if ($fromDate || $toDate) {
            $applyDateFilter($totalSalesAmountQuery);
        }
        $totalSalesAmount = $totalSalesAmountQuery->sum('total_amount');

        $todaySales = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->whereDate('created_at', Carbon::today())
            ->sum('total_amount');

        $thisMonthSales = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

        $todayOrders = Order::where('store_id', $store->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $thisMonthOrders = Order::where('store_id', $store->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // إحصائيات المنتجات
        $totalProducts = Product::where('store_id', $store->id)->count();
        $activeProducts = Product::where('store_id', $store->id)
            ->where(function($query) {
                $query->where('status', 'active')
                      ->orWhere('is_active', true);
            })
            ->count();
        $draftProducts = Product::where('store_id', $store->id)
            ->where('status', 'draft')
            ->count();
        $featuredProducts = Product::where('store_id', $store->id)
            ->where('is_featured', true)
            ->count();

        // إحصائيات الأقسام
        $totalCategories = Category::where('store_id', $store->id)->count();
        $activeCategories = Category::where('store_id', $store->id)
            ->where('is_active', true)
            ->count();

        // إحصائيات المحفظة والمعاملات
        $totalDepositsQuery = WalletTransaction::whereHas('wallet', function($query) use ($store) {
            $query->where('store_id', $store->id);
        })->where('type', 'deposit');
        if ($fromDate || $toDate) {
            $applyDateFilter($totalDepositsQuery);
        }
        $totalDeposits = $totalDepositsQuery->sum('amount');

        $totalWithdrawalsQuery = WalletTransaction::whereHas('wallet', function($query) use ($store) {
            $query->where('store_id', $store->id);
        })->where('type', 'withdraw');
        if ($fromDate || $toDate) {
            $applyDateFilter($totalWithdrawalsQuery);
        }
        $totalWithdrawals = $totalWithdrawalsQuery->sum('amount');

        $todayDeposits = WalletTransaction::whereHas('wallet', function($query) use ($store) {
            $query->where('store_id', $store->id);
        })
        ->where('type', 'deposit')
        ->whereDate('created_at', Carbon::today())
        ->sum('amount');

        $thisMonthDeposits = WalletTransaction::whereHas('wallet', function($query) use ($store) {
            $query->where('store_id', $store->id);
        })
        ->where('type', 'deposit')
        ->whereMonth('created_at', Carbon::now()->month)
        ->whereYear('created_at', Carbon::now()->year)
        ->sum('amount');

        // إحصائيات طلبات الدفع
        $totalPaymentRequestsQuery = PaymentRequest::whereHas('wallet', function($query) use ($store) {
            $query->where('store_id', $store->id);
        });
        if ($fromDate || $toDate) {
            $applyDateFilter($totalPaymentRequestsQuery);
        }
        $totalPaymentRequests = $totalPaymentRequestsQuery->count();

        $pendingPaymentRequestsQuery = PaymentRequest::whereHas('wallet', function($query) use ($store) {
            $query->where('store_id', $store->id);
        })->where('status', 'pending');
        if ($fromDate || $toDate) {
            $applyDateFilter($pendingPaymentRequestsQuery);
        }
        $pendingPaymentRequests = $pendingPaymentRequestsQuery->count();

        $approvedPaymentRequestsQuery = PaymentRequest::whereHas('wallet', function($query) use ($store) {
            $query->where('store_id', $store->id);
        })->where('status', 'approved');
        if ($fromDate || $toDate) {
            $applyDateFilter($approvedPaymentRequestsQuery);
        }
        $approvedPaymentRequests = $approvedPaymentRequestsQuery->count();

        $rejectedPaymentRequestsQuery = PaymentRequest::whereHas('wallet', function($query) use ($store) {
            $query->where('store_id', $store->id);
        })->where('status', 'rejected');
        if ($fromDate || $toDate) {
            $applyDateFilter($rejectedPaymentRequestsQuery);
        }
        $rejectedPaymentRequests = $rejectedPaymentRequestsQuery->count();

        // إحصائيات النشاط (آخر 7 أيام أو حسب النطاق المحدد)
        $last7Days = [];
        if ($fromDate && $toDate) {
            // إذا كان هناك نطاق محدد، استخدمه
            $startDate = Carbon::parse($fromDate);
            $endDate = Carbon::parse($toDate);
            $diffDays = $startDate->diffInDays($endDate);

            // تحديد عدد الأيام (بحد أقصى 30 يوم)
            $days = min($diffDays + 1, 30);

            for ($i = 0; $i < $days; $i++) {
                $date = $startDate->copy()->addDays($i);
                if ($date->greaterThan($endDate)) break;

                $last7Days[] = [
                    'date' => $date->format('Y-m-d'),
                    'date_label' => $date->format('d/m'),
                    'day_name' => $date->locale(app()->getLocale())->dayName,
                    'orders' => Order::where('store_id', $store->id)
                        ->whereDate('created_at', $date)
                        ->count(),
                    'sales' => Order::where('store_id', $store->id)
                        ->where('status', 'completed')
                        ->whereDate('created_at', $date)
                        ->sum('total_amount'),
                    'users' => Order::where('store_id', $store->id)
                        ->whereDate('created_at', $date)
                        ->select('user_id')
                        ->distinct()
                        ->count('user_id'),
                ];
            }
        } else {
            // إذا لم يكن هناك نطاق، استخدم آخر 7 أيام
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $last7Days[] = [
                    'date' => $date->format('Y-m-d'),
                    'date_label' => $date->format('d/m'),
                    'day_name' => $date->locale(app()->getLocale())->dayName,
                    'orders' => Order::where('store_id', $store->id)
                        ->whereDate('created_at', $date)
                        ->count(),
                    'sales' => Order::where('store_id', $store->id)
                        ->where('status', 'completed')
                        ->whereDate('created_at', $date)
                        ->sum('total_amount'),
                    'users' => Order::where('store_id', $store->id)
                        ->whereDate('created_at', $date)
                        ->select('user_id')
                        ->distinct()
                        ->count('user_id'),
                ];
            }
        }

        // إحصائيات الطلبات حسب الحالة (معالجة كلتا الحالتين: cancelled و canceled)
        $ordersByStatusQuery = Order::where('store_id', $store->id);
        if ($fromDate || $toDate) {
            $applyDateFilter($ordersByStatusQuery);
        }
        $ordersByStatusRaw = $ordersByStatusQuery
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->toArray();

        $ordersByStatus = [];
        foreach ($ordersByStatusRaw as $item) {
            $status = $item['status'];
            // دمج cancelled و canceled في حالة واحدة
            if ($status === 'canceled' || $status === 'cancelled') {
                $status = 'cancelled';
            }
            // دمج confirmed مع processing للرسم البياني فقط (لكن نعرض confirmed منفصلة في البطاقة)
            if ($status === 'confirmed') {
                $status = 'processing';
            }
            if (!isset($ordersByStatus[$status])) {
                $ordersByStatus[$status] = 0;
            }
            $ordersByStatus[$status] += $item['count'];
        }

        // ضمان وجود قيم افتراضية لجميع الحالات
        $defaultStatuses = ['pending' => 0, 'processing' => 0, 'completed' => 0, 'cancelled' => 0];
        $ordersByStatus = array_merge($defaultStatuses, $ordersByStatus);

        // إضافة confirmed للرسم البياني (من confirmedOrders)
        if ($confirmedOrders > 0 && isset($ordersByStatus['processing'])) {
            // processing يحتوي على confirmed بالفعل
        } elseif ($confirmedOrders > 0) {
            $ordersByStatus['processing'] = $confirmedOrders;
        }

        // أحدث الطلبات
        $recentOrdersQuery = Order::where('store_id', $store->id);

        if ($fromDate || $toDate) {
            $applyDateFilter($recentOrdersQuery);
        }

        $recentOrders = $recentOrdersQuery
            ->with(['user' => function($query) {
                // تحميل جميع الحقول المطلوبة
                $query->select('id', 'name', 'email');
            }])
            ->latest()
            ->take(10)
            ->get();

        // التأكد من تحميل العلاقة لكل طلب
        foreach ($recentOrders as $order) {
            if ($order->user_id && !$order->relationLoaded('user')) {
                $order->load('user');
            }
        }

        // أحدث العملاء (الذين لديهم طلبات في المتجر) - حسب تاريخ أول طلب
        $recentUsersQuery = Order::where('store_id', $store->id)
            ->select('user_id', DB::raw('MIN(created_at) as first_order_date'));

        if ($fromDate || $toDate) {
            $recentUsersQuery->where(function($q) use ($fromDate, $toDate) {
                if ($fromDate && $toDate) {
                    $q->whereBetween('created_at', [
                        Carbon::parse($fromDate)->startOfDay(),
                        Carbon::parse($toDate)->endOfDay()
                    ]);
                } elseif ($fromDate) {
                    $q->whereDate('created_at', '>=', $fromDate);
                } elseif ($toDate) {
                    $q->whereDate('created_at', '<=', $toDate);
                }
            });
        }

        $recentUsers = $recentUsersQuery
            ->groupBy('user_id')
            ->orderBy('first_order_date', 'desc')
            ->take(10)
            ->get()
            ->map(function($order) {
                $user = User::find($order->user_id);
                if (!$user) {
                    return null;
                }
                return $user;
            })
            ->filter();

        // أفضل المنتجات مبيعاً
        $topProductsQuery = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.store_id', $store->id)
            ->where('orders.status', 'completed');

        if ($fromDate && $toDate) {
            $topProductsQuery->whereBetween('orders.created_at', [
                Carbon::parse($fromDate)->startOfDay(),
                Carbon::parse($toDate)->endOfDay()
            ]);
        } elseif ($fromDate) {
            $topProductsQuery->whereDate('orders.created_at', '>=', $fromDate);
        } elseif ($toDate) {
            $topProductsQuery->whereDate('orders.created_at', '<=', $toDate);
        }

        $topProducts = $topProductsQuery
            ->select('products.id', 'products.name', DB::raw('sum(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->take(10)
            ->get();

        return view('core::dashboard.'. $store->type .'.statistics', compact(
            'store',
            'totalUsers',
            'activeUsers',
            'newUsersToday',
            'newUsersThisMonth',
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'confirmedOrders',
            'cancelledOrders',
            'totalSalesAmount',
            'todaySales',
            'thisMonthSales',
            'todayOrders',
            'thisMonthOrders',
            'totalProducts',
            'activeProducts',
            'draftProducts',
            'featuredProducts',
            'totalCategories',
            'activeCategories',
            'totalDeposits',
            'totalWithdrawals',
            'todayDeposits',
            'thisMonthDeposits',
            'totalPaymentRequests',
            'pendingPaymentRequests',
            'approvedPaymentRequests',
            'rejectedPaymentRequests',
            'last7Days',
            'ordersByStatus',
            'recentOrders',
            'recentUsers',
            'topProducts'
        ));
    }
}
