<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Store\Models\Store;
use Modules\User\Models\User;

class CoreController extends Controller
{
    /**
     * عرض الصفحة الرئيسية
     */
    public function index()
    {
        // في البيئة المحلية، استخدم بيانات وهمية
        if (app()->environment('local')) {
            $featuredStores = collect([
                (object) [
                    'id' => 1,
                    'name' => 'متجر كايمن',
                    'description' => 'متجر إلكتروني متخصص في بيع المنتجات التقنية والإلكترونية',
                    'domain' => 'kaymn',
                    'status' => 'active',
                    'banner' => null,
                    'store_url' => '#',
                ],
                (object) [
                    'id' => 2,
                    'name' => 'متجر الألوان',
                    'description' => 'متجر متخصص في بيع الألوان والمواد الفنية',
                    'domain' => 'colors',
                    'status' => 'active',
                    'banner' => null,
                    'store_url' => '#',
                ],
                (object) [
                    'id' => 3,
                    'name' => 'متجر الكتب',
                    'description' => 'متجر متخصص في بيع الكتب والمراجع العلمية',
                    'domain' => 'books',
                    'status' => 'active',
                    'banner' => null,
                    'store_url' => '#',
                ],
            ]);
        } else {
            // في البيئة الإنتاجية، استخدم قاعدة البيانات
            try {
                $featuredStores = Store::where('status', 'active')
                    ->latest()
                    ->take(6)
                    ->get();
            } catch (\Exception $e) {
                // في حالة حدوث خطأ، استخدم بيانات وهمية
                $featuredStores = collect([
                    (object) [
                        'id' => 1,
                        'name' => 'متجر نموذجي',
                        'description' => 'متجر إلكتروني متخصص في بيع المنتجات',
                        'domain' => 'demo',
                        'status' => 'active',
                        'banner' => null,
                        'store_url' => '#',
                    ],
                ]);
            }
        }

        return view('core::app.home', compact('featuredStores'));
    }

    public function dashboard()
    {
        $totalStores = Store::count();
        $activeStores = Store::where('status', 'active')->count();
        $pendingStores = Store::where('status', 'pending')->count();
        $totalUsers = User::count();
        $recentStores = Store::with('owners')->latest()->take(5)->get();

        return view('core::dashboard.dashboard', compact('totalStores', 'activeStores', 'pendingStores', 'totalUsers', 'recentStores'));
    }

    /**
     * عرض صفحة الخدمات
     */
    public function services()
    {
        return view('core::app.services');
    }

    /**
     * عرض صفحة الأسعار
     */
    public function pricing()
    {
        return view('core::app.pricing');
    }

    /**
     * عرض صفحة التواصل
     */
    public function contact()
    {
        return view('core::app.contact');
    }

    /**
     * عرض صفحة الديمو
     */
    public function demo()
    {
        return view('core::app.demo');
    }
}
