@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Store Platform'))

@section('content')
    <div class="container-fluid "style="margin-top: 120px; ">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tachometer-alt me-2"></i>
                {{ __('Dashboard') }}
            </h1>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.stores.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    {{ __('Create New Store') }}
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    {{ __('Total Stores') }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalStores }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-store fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    {{ __('Active Stores') }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeStores }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    {{ __('Pending Stores') }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingStores }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    {{ __('Total Users') }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
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
                            {{ __('Quick Actions') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <a href="{{ route('admin.stores.create') }}"
                                    class="btn btn-primary btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="fas fa-plus fa-2x mb-2"></i>
                                    <span>{{ __('Create New Store') }}</span>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.stores.index') }}"
                                    class="btn btn-info btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="fas fa-store fa-2x mb-2"></i>
                                    <span>{{ __('Manage Stores') }}</span>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.users.index') }}"
                                    class="btn btn-success btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="fas fa-users fa-2x mb-2"></i>
                                    <span>{{ __('Manage Users') }}</span>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.themes.index') }}"
                                    class="btn btn-secondary btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="fas fa-home fa-2x mb-2"></i>
                                    <span>{{ __('View Themes') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Stores -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-list me-2"></i>
                            {{ __('Recent Stores') }}
                        </h6>
                        <a href="{{ route('admin.stores.index') }}" class="btn btn-sm btn-outline-primary">
                            {{ __('View All') }}
                        </a>
                    </div>
                    <div class="card-body">
                        @if ($recentStores->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Store') }}</th>
                                            <th>{{ __('Owner') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Created At') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentStores as $store)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($store->logo)
                                                            <img src="{{ $store->logo }}" class="rounded-circle me-2"
                                                                width="32" height="32" alt="{{ $store->name }}">
                                                        @else
                                                            <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                                style="width: 32px; height: 32px;">
                                                                <i class="fas fa-store text-white"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="font-weight-bold">{{ $store->name }}</div>
                                                            <small
                                                                class="text-muted">{{ $store->domain }}.{{ config('app.domain', 'localhost') }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($store->owners->count() > 0)
                                                        <div>{{ $store->owners->first()->name }}</div>
                                                        <small
                                                            class="text-muted">{{ $store->owners->first()->email }}</small>
                                                    @else
                                                        <div>-</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $store->status == 'active' ? 'success' : ($store->status == 'pending' ? 'warning' : 'danger') }}">
                                                        {{ $store->status == 'active' ? __('Active') : ($store->status == 'pending' ? __('Pending') : __('Inactive')) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div>{{ $store->created_at->format('Y-m-d') }}</div>
                                                    <small
                                                        class="text-muted">{{ $store->created_at->format('H:i') }}</small>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('admin.stores.show', $store->id) }}"
                                                            class="btn btn-sm btn-outline-primary"
                                                            title="{{ __('View') }}">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @if ($store->status == 'pending')
                                                            <button class="btn btn-sm btn-success"
                                                                onclick="activateStore({{ $store->id }})"
                                                                title="{{ __('Activate') }}">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-store fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('No Stores Yet') }}</h5>
                                <p class="text-muted">{{ __('Start by creating your first store') }}</p>
                                <a href="{{ route('admin.stores.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>
                                    {{ __('Create New Store') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Stats and Info -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-pie me-2"></i>
                            {{ __('Quick Stats') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>{{ __('Active Stores') }}</span>
                                <span class="text-success">{{ $activeStores }}</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success"
                                    style="width: {{ $totalStores > 0 ? ($activeStores / $totalStores) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>{{ __('Pending Stores') }}</span>
                                <span class="text-warning">{{ $pendingStores }}</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-warning"
                                    style="width: {{ $totalStores > 0 ? ($pendingStores / $totalStores) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>{{ __('Inactive Stores') }}</span>
                                <span class="text-danger">{{ $totalStores - $activeStores - $pendingStores }}</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-danger"
                                    style="width: {{ $totalStores > 0 ? (($totalStores - $activeStores - $pendingStores) / $totalStores) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ __('Info') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-calendar text-primary me-2"></i>
                            <strong>{{ __('Date') }}:</strong> {{ now()->format('Y-m-d') }}
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-clock text-primary me-2"></i>
                            <strong>{{ __('Time') }}:</strong> {{ now()->format('H:i') }}
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-server text-primary me-2"></i>
                            <strong>{{ __('Version') }}:</strong> Laravel {{ app()->version() }}
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-database text-primary me-2"></i>
                            <strong>{{ __('Database') }}:</strong> {{ config('database.default') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Form -->
    <form id="statusForm" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" id="statusInput">
    </form>

    <script>
        function activateStore(storeId) {
            if (confirm('{{ __('Are you sure you want to activate this store?') }}')) {
                document.getElementById('statusInput').value = 'active';
                document.getElementById('statusForm').action = `/admin/stores/${storeId}/status`;
                document.getElementById('statusForm').submit();
            }
        }
    </script>
@endsection
