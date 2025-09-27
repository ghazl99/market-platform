@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard') . ' - ' . $store->name)

@section('content')
    <div class="row">
        <!-- Page Header -->
        <div class="col-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    {{ __('Dashboard') }} {{ $store->name }}
                </h1>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-outline-primary" target="_blank">
                        <i class="fas fa-external-link-alt me-2"></i>
                        عرض المتجر
                    </a>
                    <a href="#" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>
                        إضافة منتج
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-right-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                {{ __('Products') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-right-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                {{ __('Orders') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-right-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                {{ __('Customers') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-right-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                الإيرادات
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">ريال</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>
                        إجراءات سريعة
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('dashboard.product.index') }}"
                                class="btn btn-outline-primary btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="fas fa-box fa-3x mb-3"></i>
                                <span>{{ __('Manage Products') }}</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ Route('dashboard.order.index') }}"
                                class="btn btn-outline-success btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                                <span>{{ __('Manage Orders') }}</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#"
                                class="btn btn-outline-info btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="fas fa-users fa-3x mb-3"></i>
                                <span>{{ __('Manage Customers') }}</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('dashboard.category.index') }}"
                                class="btn btn-outline-warning btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="fas fa-th-large fa-3x mb-3"></i>
                                <span>{{ __('Mangae Categories') }}</span>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Store Information -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات المتجر
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>اسم المتجر:</strong> {{ $store->name }}</p>
                            <p><strong>الدومين:</strong> {{ $store->domain }}</p>
                            <p><strong>الحالة:</strong>
                                @if ($store->status == 'active')
                                    <span class="badge bg-success">نشط</span>
                                @elseif($store->status == 'pending')
                                    <span class="badge bg-warning">قيد المراجعة</span>
                                @else
                                    <span class="badge bg-danger">غير نشط</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>الثيم:</strong> {{ $store->theme }}</p>
                            <p><strong>تاريخ الإنشاء:</strong> {{ $store->created_at->format('Y-m-d') }}</p>
                            <p><strong>آخر تحديث:</strong> {{ $store->updated_at->format('Y-m-d') }}</p>
                        </div>
                    </div>
                    @if ($store->description)
                        <hr>
                        <p><strong>الوصف:</strong></p>
                        <p class="text-muted">{{ $store->description }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>
                        نشاط المتجر
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <p class="text-muted">لا توجد بيانات نشاط بعد</p>
                        <p class="small text-muted">ستظهر هنا إحصائيات المتجر بعد إضافة المنتجات والطلبات</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
