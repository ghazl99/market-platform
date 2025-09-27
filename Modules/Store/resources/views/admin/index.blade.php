@extends('core::layouts.app')

@section('title', __('Stores Management - Dashboard'))

@section('content')
    <div class="container-fluid " style="margin-top: 50px;">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-store me-2"></i>
                {{ __('Stores Management') }}
            </h1>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.stores.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    {{ __('Create New Store') }}
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-right me-2"></i>
                    {{ __('Back to dashboard') }}
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stores->count() }}</div>
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $stores->where('status', 'active')->count() }}
                                </div>
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
                                    {{ __('Pending Review') }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $stores->where('status', 'pending')->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    {{ __('Inactive Stores') }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $stores->where('status', 'inactive')->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stores Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list me-2"></i>
                    {{ __('Stores List') }}
                </h6>
                <a href="{{ route('admin.stores.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    {{ __('Add New Store') }}
                </a>
            </div>
            <div class="card-body">
                @if ($stores->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" id="storesTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>{{ __('Store') }}</th>
                                    <th>{{ __('Owner') }}</th>
                                    <th>{{ __('Domain') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Theme') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stores as $store)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($store->logo)
                                                    <img src="{{ $store->logo }}" class="rounded-circle me-2"
                                                        width="40" height="40" alt="{{ $store->name }}">
                                                @else
                                                    <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="fas fa-store text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-weight-bold">{{ $store->name }}</div>
                                                    @if ($store->description)
                                                        <small
                                                            class="text-muted">{{ Str::limit($store->description, 50) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold">{{ $store->owners->first()->name }}</div>
                                                    <small class="text-muted">{{ $store->owners->first()->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if (app()->environment('production'))
                                                <code>{{ $store->store_url }}</code>
                                            @else
                                                <code>{{ $store->store_url }}.localhost</code>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $store->type ? ($store->type == 'digital' ? __('Digital') : __('Traditional')) : __('Unknown') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $store->status == 'active' ? 'success' : ($store->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ $store->status == 'active' ? __('Active') : ($store->status == 'pending' ? __('Pending Review') : __('Inactive')) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ ucfirst($store->theme) }}</span>
                                        </td>
                                        <td>
                                            <div>{{ $store->created_at_in_store_timezone->format('Y-m-d') }}</div>
                                            <small
                                                class="text-muted">{{ $store->created_at_in_store_timezone->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.stores.show', $store->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="{{ __('View') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if ($store->status == 'pending')
                                                    <button class="btn btn-sm btn-success"
                                                        onclick="activateStore({{ $store->id }})"
                                                        title="{{ __('Activate') }}">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @elseif($store->status == 'active')
                                                    <button class="btn btn-sm btn-warning"
                                                        onclick="deactivateStore({{ $store->id }})"
                                                        title="{{ __('Deactivate') }}">
                                                        <i class="fas fa-pause"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-success"
                                                        onclick="activateStore({{ $store->id }})"
                                                        title="{{ __('Reactivate') }}">
                                                        <i class="fas fa-play"></i>
                                                    </button>
                                                @endif

                                                <!-- Store Visit -->
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-info dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown"
                                                        title="{{ __('Visit Store') }}">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if (app()->environment('production'))
                                                            <li>
                                                                <a class="dropdown-item" href="{{ $store->store_url }}"
                                                                    target="_blank">
                                                                    <i class="fas fa-globe me-2"></i>
                                                                    {{ __('Main Domain') }}
                                                                </a>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <a class="dropdown-item" href="{{ $store->store_url }}"
                                                                    target="_blank">
                                                                    <i class="fas fa-link me-2"></i>
                                                                    {{ __('Main Link') }}
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-store fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('No stores yet') }}</h5>
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

        function deactivateStore(storeId) {
            if (confirm('{{ __('Are you sure you want to deactivate this store?') }}')) {
                document.getElementById('statusInput').value = 'inactive';
                document.getElementById('statusForm').action = `/admin/stores/${storeId}/status`;
                document.getElementById('statusForm').submit();
            }
        }
    </script>
@endsection
