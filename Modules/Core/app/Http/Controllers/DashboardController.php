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
            new Middleware('role:owner', only: ['index']),

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
        // Use Store model directly instead of helper function
        $store = Store::currentFromUrl()->first();

        if (!$store) {
            abort(404, 'Store not found');
        }

        return view('core::store.dashboard', compact('store'));
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
        $store = Store::currentFromUrl()->first();

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

        // إحصائيات المستخدمين
        $totalUsersQuery = User::whereHas('stores', function($query) use ($store) {
            $query->where('stores.id', $store->id);
        });
        if ($fromDate || $toDate) {
            $totalUsersQuery->where(function($q) use ($fromDate, $toDate) {
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
        $totalUsers = $totalUsersQuery->count();

        $activeUsers = User::whereHas('stores', function($query) use ($store) {
            $query->where('stores.id', $store->id);
        })
            ->whereNotNull('last_login_at')
            ->where('last_login_at', '>=', Carbon::now()->subDays(30))
            ->count();

        $newUsersToday = User::whereHas('stores', function($query) use ($store) {
            $query->where('stores.id', $store->id);
        })
            ->whereDate('created_at', Carbon::today())
            ->count();

        $newUsersThisMonth = User::whereHas('stores', function($query) use ($store) {
            $query->where('stores.id', $store->id);
        })
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

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
                    'users' => User::whereHas('stores', function($query) use ($store) {
                        $query->where('stores.id', $store->id);
                    })
                        ->whereDate('created_at', $date)
                        ->count(),
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
                    'users' => User::whereHas('stores', function($query) use ($store) {
                        $query->where('stores.id', $store->id);
                    })
                        ->whereDate('created_at', $date)
                        ->count(),
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
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        // أحدث المستخدمين
        $recentUsersQuery = User::whereHas('stores', function($query) use ($store) {
            $query->where('stores.id', $store->id);
        });
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
            ->latest()
            ->take(10)
            ->get();

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

        return view('core::dashboard.statistics', compact(
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
