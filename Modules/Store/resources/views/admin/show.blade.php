@extends('core::layouts.app')

@section('title', __('View Store') . ': ' . $store->name . ' - ' . __('Dashboard'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-store me-2"></i>
            {{ __('View Store') }}: {{ $store->name }}
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.stores.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right me-2"></i>
                {{ __('Back to Stores List') }}
            </a>
        </div>
    </div>

    <!-- Store Status Alert -->
    @if($store->status == 'pending')
        <div class="alert alert-warning" role="alert">
            <i class="fas fa-clock me-2"></i>
            <strong>{{ __('This store is under review') }}:</strong> {{ __('You can activate or reject it after reviewing the information.') }}
        </div>
    @elseif($store->status == 'inactive')
        <div class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>{{ __('This store is inactive') }}:</strong> {{ __('You can reactivate it or delete it.') }}
        </div>
    @endif

    <div class="row">
        <!-- Store Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ __('Store Info') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('Store Name') }}</label>
                                <p class="mb-0">{{ $store->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('Domain') }}</label>
                                <p class="mb-0"><code>{{ $store->store_url }}</code></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('Status') }}</label>
                                <p class="mb-0">
                                    <span class="badge bg-{{ $store->status == 'active' ? 'success' : ($store->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ $store->status == 'active' ? __('Active') : ($store->status == 'pending' ? __('Pending') : __('Inactive')) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('Theme') }}</label>
                                <p class="mb-0">{{ ucfirst($store->theme) }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('Description') }}</label>
                                <p class="mb-0">{{ $store->description ?: __('No description') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Store Banner -->
            @if($store->banner)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-image me-2"></i>
                        {{ __('Store Banner') }}
                    </h6>
                </div>
                <div class="card-body p-0">
                    <img src="{{ $store->banner }}" class="img-fluid w-100" alt="{{ $store->name }} Banner">
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Owner Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user me-2"></i>
                        {{ __('Owner Info') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="bg-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-user fa-2x text-white"></i>
                        </div>
                        <h5 class="mb-1">{{ $store->owners->first()->name }}</h5>
                        <p class="text-muted mb-2">{{ $store->owners->first()->email }}</p>
                        <span class="badge bg-info">{{ __('Regular User') }}</span>
                    </div>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="mb-2">
                                <h6 class="text-primary mb-1">{{ $store->owners->first()->stores()->count() }}</h6>
                                <small class="text-muted">{{ __('Stores') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-2">
                                <h6 class="text-success mb-1">{{ auth()->user()->created_at->diffForHumans() }}</h6>
                                <small class="text-muted">{{ __('Registered At') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs me-2"></i>
                        {{ __('Quick Actions') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($store->status == 'pending')
                            <button class="btn btn-success btn-lg" onclick="updateStoreStatus('active')">
                                <i class="fas fa-check me-2"></i>
                                {{ __('Activate Store') }}
                            </button>
                            <button class="btn btn-danger btn-lg" onclick="updateStoreStatus('inactive')">
                                <i class="fas fa-times me-2"></i>
                                {{ __('Reject Store') }}
                            </button>
                        @elseif($store->status == 'active')
                            <button class="btn btn-warning btn-lg" onclick="updateStoreStatus('inactive')">
                                <i class="fas fa-pause me-2"></i>
                                {{ __('Deactivate Store') }}
                            </button>
                        @else
                            <button class="btn btn-success btn-lg" onclick="updateStoreStatus('active')">
                                <i class="fas fa-play me-2"></i>
                                {{ __('Reactivate Store') }}
                            </button>
                        @endif

                        <a href="{{ $store->store_url }}" class="btn btn-outline-primary btn-lg" target="_blank">
                            <i class="fas fa-external-link-alt me-2"></i>
                            {{ __('Visit Store') }}
                        </a>

                        <!-- Alternative Links -->
                        <div class="mt-3">
                            <h6 class="text-muted mb-2">{{ __('Alternative Links') }}:</h6>
                            <div class="d-flex flex-column gap-2">
                                @if(app()->environment('production'))
                                    <a href="{{ $store->store_url }}" class="btn btn-outline-success btn-sm" target="_blank">
                                        <i class="fas fa-globe me-2"></i>
                                        {{ __('Main Domain') }}: {{ $store->domain }}
                                    </a>
                                    <a href="{{ $store->dashboard_url }}" class="btn btn-outline-info btn-sm" target="_blank">
                                        <i class="fas fa-tachometer-alt me-2"></i>
                                        {{ __('Dashboard') }}: {{ $store->domain }}/dashboard
                                    </a>
                                @else
                                    <a href="{{ $store->alternative_store_url }}" class="btn btn-outline-success btn-sm" target="_blank">
                                        <i class="fas fa-link me-2"></i>
                                        {{ __('Main Link') }}: /store/{{ $store->domain }}
                                    </a>
                                    <a href="{{ $store->alternative_dashboard_url }}" class="btn btn-outline-info btn-sm" target="_blank">
                                        <i class="fas fa-tachometer-alt me-2"></i>
                                        {{ __('Dashboard') }}: /dashboard/{{ $store->domain }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Store Statistics -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>
                        {{ __('Store Statistics') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-primary mb-1">0</h4>
                                <small class="text-muted">{{ __('Products') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-success mb-1">0</h4>
                                <small class="text-muted">{{ __('Orders') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-info mb-1">0</h4>
                                <small class="text-muted">{{ __('Customers') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-warning mb-1">0</h4>
                                <small class="text-muted">{{ __('Sales') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Form -->
<form id="statusForm" method="POST" action="{{ route('admin.stores.status', $store->id) }}" style="display: none;">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" id="statusInput">
</form>

<script>
function updateStoreStatus(status) {
    let message = '';
    if (status === 'active') {
        message = "{{ __('Are you sure you want to activate this store?') }}";
    } else if (status === 'inactive') {
        message = "{{ __('Are you sure you want to deactivate this store?') }}";
    }

    if (confirm(message)) {
        document.getElementById('statusInput').value = status;
        document.getElementById('statusForm').submit();
    }
}
</script>
@endsection
