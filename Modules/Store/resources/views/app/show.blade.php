@extends('core::layouts.app')

@section('title', $store->name . ' - ' . __('Store Info'))

@push('styles')
<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: 'Cairo', sans-serif;
}

body {
    background-color: #f5f5f5;
    color: #333;
}

.container {
    max-width: 1200px;
    margin: 120px auto 50px;
    display: flex;
    gap: 20px;
}

/* Main Content */
.main-content {
    flex: 2;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Cards */
.card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.card-header {
    background: #f8f9fa;
    padding: 15px 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-body {
    padding: 20px;
}

/* Sidebar */
.sidebar {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.sidebar-card {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.quick-stats .stat h4 {
    margin-bottom: 5px;
}

.quick-stats .stat small {
    color: #666;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 10px 15px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.95rem;
    transition: all 0.2s;
    text-decoration: none;
    text-align: center;
}

.btn-sm { font-size: 0.85rem; padding: 8px 12px; }

.btn-outline-primary { border: 1px solid #0d6efd; color: #0d6efd; background: #fff; }
.btn-outline-primary:hover { background: #0d6efd; color: #fff; }

.btn-outline-success { border: 1px solid #198754; color: #198754; background: #fff; }
.btn-outline-success:hover { background: #198754; color: #fff; }

.btn-outline-info { border: 1px solid #0dcaf0; color: #0dcaf0; background: #fff; }
.btn-outline-info:hover { background: #0dcaf0; color: #fff; }

.btn-outline-secondary { border: 1px solid #6c757d; color: #6c757d; background: #fff; }
.btn-outline-secondary:hover { background: #6c757d; color: #fff; }

.btn-outline-danger { border: 1px solid #dc3545; color: #dc3545; background: #fff; }
.btn-outline-danger:hover { background: #dc3545; color: #fff; }

/* Status badges */
.badge {
    padding: 5px 10px;
    border-radius: 8px;
    font-weight: 600;
    color: #fff;
}

.badge-success { background-color: #10b981; }
.badge-warning { background-color: #f59e0b; }
.badge-danger { background-color: #ef4444; }

/* Store header */
.store-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.store-header img, .store-header .logo-placeholder {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #6c757d;
    color: #fff;
    font-size: 28px;
}
</style>
@endpush

@section('content')
<div class="container">

    <!-- Main Content -->
    <div class="main-content">

        <!-- Store Header -->
        <div class="store-header">
            @if($store->logo)
                <img src="{{ $store->logo }}" alt="{{ $store->name }}">
            @else
                <div class="logo-placeholder"><i class="fas fa-store"></i></div>
            @endif
            <div>
                <h2>{{ $store->name }}</h2>
                <p style="color:#666">{{ $store->store_url }}</p>
            </div>
        </div>

        <!-- Store Status -->
        @if($store->status == 'pending')
            <div style="background:#fff3cd; padding:15px; border-radius:8px; color:#856404;">
                <i class="fas fa-clock me-2"></i> <strong>{{ __('Pending Review') }}</strong>
            </div>
        @elseif($store->status == 'inactive')
            <div style="background:#f8d7da; padding:15px; border-radius:8px; color:#842029;">
                <i class="fas fa-exclamation-triangle me-2"></i> <strong>{{ __('Inactive Store') }}</strong>
            </div>
        @endif

        <!-- Store Info Card -->
        <div class="card">
            <div class="card-header"><i class="fas fa-info-circle"></i> {{ __('Store Info') }}</div>
            <div class="card-body">
                <div style="display:flex; flex-wrap:wrap; gap:20px;">
                    <div style="flex:1; min-width:200px;">
                        <strong>{{ __('Store Name') }}</strong>
                        <p>{{ $store->name }}</p>
                    </div>
                    <div style="flex:1; min-width:200px;">
                        <strong>{{ __('Domain') }}</strong>
                        <p><code>{{ $store->domain }}.{{ config('app.domain', 'localhost') }}</code></p>
                    </div>
                    <div style="flex:1; min-width:200px;">
                        <strong>{{ __('Status') }}</strong>
                        <span class="badge {{ $store->status=='active'?'badge-success':($store->status=='pending'?'badge-warning':'badge-danger') }}">
                            {{ $store->status=='active'?__('Active'):($store->status=='pending'?__('Pending'):__('Inactive')) }}
                        </span>
                    </div>
                    <div style="flex:1; min-width:200px;">
                        <strong>{{ __('Theme') }}</strong>
                        <p>{{ ucfirst($store->theme) }}</p>
                    </div>
                    <div style="flex:1 1 100%;">
                        <strong>{{ __('Description') }}</strong>
                        <p>{{ $store->description ?: __('No description') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Store Banner -->
        @if($store->banner)
        <div class="card">
            <div class="card-header"><i class="fas fa-image"></i> {{ __('Store Banner') }}</div>
            <div class="card-body" style="padding:0">
                <img src="{{ $store->banner }}" alt="{{ $store->name }} Banner" style="width:100%; display:block;">
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Quick Stats -->
        <div class="card sidebar-card">
            <div class="card-header"><i class="fas fa-chart-bar"></i> {{ __('Quick Stats') }}</div>
            <div class="card-body quick-stats">
                <div style="display:flex; flex-wrap:wrap; gap:10px;">
                    <div style="flex:1; text-align:center;">
                        <h4 style="color:#0d6efd">0</h4>
                        <small>{{ __('Products') }}</small>
                    </div>
                    <div style="flex:1; text-align:center;">
                        <h4 style="color:#198754">0</h4>
                        <small>{{ __('Orders') }}</small>
                    </div>
                    <div style="flex:1; text-align:center;">
                        <h4 style="color:#0dcaf0">0</h4>
                        <small>{{ __('Customers') }}</small>
                    </div>
                    <div style="flex:1; text-align:center;">
                        <h4 style="color:#ffc107">0</h4>
                        <small>{{ __('Sales') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Store Actions -->
        <div class="card sidebar-card">
            <div class="card-header"><i class="fas fa-cogs"></i> {{ __('Store Actions') }}</div>
            <div class="card-body" style="display:flex; flex-direction:column; gap:10px;">
                @if($store->status=='active')
                    <a href="#" class="btn btn-outline-primary btn-sm"><i class="fas fa-plus"></i> {{ __('Add Product') }}</a>
                    <a href="#" class="btn btn-outline-success btn-sm"><i class="fas fa-shopping-cart"></i> {{ __('Manage Orders') }}</a>
                    <a href="#" class="btn btn-outline-info btn-sm"><i class="fas fa-chart-line"></i> {{ __('Reports') }}</a>
                @else
                    <button class="btn btn-outline-secondary btn-sm" disabled><i class="fas fa-lock"></i> {{ __('Not available until activation') }}</button>
                @endif
            </div>
        </div>

        <!-- Store Settings -->
        <div class="card sidebar-card">
            <div class="card-header"><i class="fas fa-cog"></i> {{ __('Store Settings') }}</div>
            <div class="card-body" style="display:flex; flex-direction:column; gap:10px;">
                <a href="{{ route('stores.edit', $store) }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-edit"></i> {{ __('Edit Store') }}</a>
                <button class="btn btn-outline-danger btn-sm" onclick="deleteStore()"><i class="fas fa-trash"></i> {{ __('Delete Store') }}</button>
            </div>
        </div>

    </div>
</div>

<script>
function deleteStore() {
    alert('{{ __("Confirm Delete") }}');
}
</script>
@endsection
