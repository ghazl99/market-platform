@extends('core::store.main-dashboard.layouts.app')

@section('title', __('View Store') . ': ' . $store->name . ' - ' . __('Dashboard'))

@section('content')

<style>
    /* Layout */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .page-header h1 {
        font-size: 1.5rem;
        color: #333;
        margin: 0;
    }

    /* Buttons */
    .button {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        justify-content: center;
    }
    .button-secondary { background: #f5f5f5; color: #333; }
    .button-secondary:hover { background: #e0e0e0; }
    .button-success { background: #28a745; color: #fff; }
    .button-danger { background: #dc3545; color: #fff; }
    .button-warning { background: #ffc107; color: #000; }
    .button-outline { border: 1px solid #007bff; color: #007bff; background: #fff; }
    .button-outline:hover { background: #007bff; color: #fff; }

    /* Cards */
    .custom-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        overflow: hidden;
    }
    .custom-card-header {
        padding: 12px 16px;
        background: #f8f9fa;
        font-weight: bold;
        font-size: 15px;
        color: #007bff;
        border-bottom: 1px solid #eaeaea;
    }
    .custom-card-body {
        padding: 16px;
    }

    /* Alerts */
    .custom-alert {
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
    }
    .alert-warning { background: #fff3cd; color: #856404; }
    .alert-danger { background: #f8d7da; color: #721c24; }

    /* Grid */
    .grid {
        display: grid;
        gap: 20px;
    }
    .grid-2 {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }

    /* Labels */
    .label {
        font-weight: bold;
        color: #444;
        font-size: 14px;
    }
    .value {
        margin: 0;
        color: #333;
        font-size: 14px;
    }

    /* Badges */
    .badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
    }
    .badge-success { background: #28a745; color: #fff; }
    .badge-warning { background: #ffc107; color: #000; }
    .badge-danger { background: #dc3545; color: #fff; }
    .badge-info { background: #17a2b8; color: #fff; }

    /* Text Center */
    .text-center { text-align: center; }
    .text-muted { color: #6c757d; font-size: 13px; }

    /* Statistics */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        text-align: center;
    }
    .stats-item h4 {
        margin: 0;
        font-size: 20px;
    }
    .stats-item small {
        color: #6c757d;
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-store"></i> {{ __('View Store') }}: {{ $store->name }}</h1>
        <a href="{{ route('admin.stores.index') }}" class="button button-secondary">
            <i class="fas fa-arrow-right"></i> {{ __('Back to Stores List') }}
        </a>
    </div>

```
<!-- Store Status Alert -->
@if($store->status == 'pending')
    <div class="custom-alert alert-warning">
        <i class="fas fa-clock"></i> <strong>{{ __('This store is under review') }}</strong>
    </div>
@elseif($store->status == 'inactive')
    <div class="custom-alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i> <strong>{{ __('This store is inactive') }}</strong>
    </div>
@endif

<div class="grid grid-2">
    <!-- Store Information -->
    <div>
        <div class="custom-card">
            <div class="custom-card-header">
                <i class="fas fa-info-circle"></i> {{ __('Store Info') }}
            </div>
            <div class="custom-card-body grid grid-2">
                <div>
                    <label class="label">{{ __('Store Name') }}</label>
                    <p class="value">{{ $store->name }}</p>
                </div>
                <div>
                    <label class="label">{{ __('Domain') }}</label>
                    <p class="value"><code>{{ $store->store_url }}</code></p>
                </div>
                <div>
                    <label class="label">{{ __('Status') }}</label>
                    <p class="value">
                        <span class="badge {{ $store->status == 'active' ? 'badge-success' : ($store->status == 'pending' ? 'badge-warning' : 'badge-danger') }}">
                            {{ ucfirst($store->status) }}
                        </span>
                    </p>
                </div>
                <div>
                    <label class="label">{{ __('Theme') }}</label>
                    <p class="value">{{ ucfirst($store->theme) }}</p>
                </div>
                <div style="grid-column: 1 / -1;">
                    <label class="label">{{ __('Description') }}</label>
                    <p class="value">{{ $store->description ?: __('No description') }}</p>
                </div>
            </div>
        </div>

        <!-- Store Banner -->
        @if($store->banner)
        <div class="custom-card">
            <div class="custom-card-header">
                <i class="fas fa-image"></i> {{ __('Store Banner') }}
            </div>
            <div class="custom-card-body">
                <img src="{{ $store->banner }}" style="width: 100%; border-radius: 6px;" alt="{{ $store->name }} Banner">
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Owner Info -->
        <div class="custom-card">
            <div class="custom-card-header">
                <i class="fas fa-user"></i> {{ __('Owner Info') }}
            </div>
            <div class="custom-card-body text-center">
                <div style="width: 80px; height: 80px; border-radius: 50%; background: #007bff; display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                    <i class="fas fa-user fa-2x text-white"></i>
                </div>
                <h5>{{ $store->owners->first()->name }}</h5>
                <p class="text-muted">{{ $store->owners->first()->email }}</p>
                <span class="badge badge-info">{{ __('Regular User') }}</span>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="custom-card">
            <div class="custom-card-header">
                <i class="fas fa-cogs"></i> {{ __('Quick Actions') }}
            </div>
            <div class="custom-card-body">
                <div class="grid" style="gap:10px;">
                    @if($store->status == 'pending')
                        <button class="button button-success" onclick="updateStoreStatus('active')">
                            <i class="fas fa-check"></i> {{ __('Activate Store') }}
                        </button>
                        <button class="button button-danger" onclick="updateStoreStatus('inactive')">
                            <i class="fas fa-times"></i> {{ __('Reject Store') }}
                        </button>
                    @elseif($store->status == 'active')
                        <button class="button button-warning" onclick="updateStoreStatus('inactive')">
                            <i class="fas fa-pause"></i> {{ __('Deactivate Store') }}
                        </button>
                    @else
                        <button class="button button-success" onclick="updateStoreStatus('active')">
                            <i class="fas fa-play"></i> {{ __('Reactivate Store') }}
                        </button>
                    @endif

                    <a href="{{ $store->store_url }}" class="button button-outline" target="_blank">
                        <i class="fas fa-external-link-alt"></i> {{ __('Visit Store') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Store Stats -->
        <div class="custom-card">
            <div class="custom-card-header">
                <i class="fas fa-chart-bar"></i> {{ __('Store Statistics') }}
            </div>
            <div class="custom-card-body stats-grid">
                <div class="stats-item">
                    <h4 class="text-primary">0</h4>
                    <small>{{ __('Products') }}</small>
                </div>
                <div class="stats-item">
                    <h4 class="text-success">0</h4>
                    <small>{{ __('Orders') }}</small>
                </div>
                <div class="stats-item">
                    <h4 class="text-info">0</h4>
                    <small>{{ __('Customers') }}</small>
                </div>
                <div class="stats-item">
                    <h4 class="text-warning">0</h4>
                    <small>{{ __('Sales') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
```

</div>

<!-- Status Update Form -->

<form id="statusForm" method="POST" action="{{ route('admin.stores.status', $store->id) }}" style="display:none;">
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
