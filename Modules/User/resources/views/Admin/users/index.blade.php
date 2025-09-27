@extends('core::layouts.app')

@section('title', __('User Management - Dashboard'))

@section('content')
    <div class="container-fluid mt-4">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-users me-2"></i>
                {{ __('Users list') }}
            </h1>
            <div class="d-flex gap-2">
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
                                    {{ __('Total users') }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                    {{ __('Regular users') }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $users->where('is_admin', false)->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-gray-300"></i>
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
                                    {{ __('Admins') }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $users->where('is_admin', true)->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-shield fa-2x text-gray-300"></i>
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
                                    {{ __('Active users') }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $users->where('email_verified_at', '!=', null)->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list me-2"></i>
                    {{ __('Users list') }}
                </h6>
            </div>
            <div class="card-body">
                @if ($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Role') }}</th>
                                    <th>{{ __('Verification') }}</th>
                                    <th>{{ __('Stores') }}</th>
                                    <th>{{ __('Registered at') }}</th>
                                    <th>{{ __('Last login') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold">{{ $user->name }}</div>
                                                    <small class="text-muted">{{ __('ID') }}:
                                                        {{ $user->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>{{ $user->email }}</div>
                                            @if ($user->email_verified_at)
                                                <small class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    {{ __('Verified') }}
                                                </small>
                                            @else
                                                <small class="text-warning">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ __('Not verified') }}
                                                </small>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="badge bg-info">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $user->getRoleNames()->first() }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($user->email_verified_at)
                                                <span class="badge bg-success">{{ __('Active') }}</span>
                                            @else
                                                <span class="badge bg-warning">{{ __('Pending verification') }}</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($user->stores->count() > 0)
                                                <button class="btn  p-0" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#stores-{{ $user->id }}" aria-expanded="false"
                                                    aria-controls="stores-{{ $user->id }}">
                                                    <div class="text-center">
                                                        <b class=" mb-0">{{ $user->stores->count() }}</b>
                                                        <small class="text-muted">{{ __('Stores') }}</small>
                                                    </div>
                                                </button>

                                                <div class="collapse mt-2" id="stores-{{ $user->id }}">
                                                    <ul class="list-unstyled ps-3 mb-0">
                                                        @foreach ($user->stores as $store)
                                                            <li>
                                                                <a href="{{ $store->store_url  }}" target="_blank"
                                                                    class="text-decoration-none">
                                                                    <i class="fas fa-store me-1"></i> {{ $store->name }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @else
                                                <div class="text-center">
                                                    <h6 class="text-muted mb-0">0</h6>
                                                    <small class="text-muted">{{ __('No stores') }}</small>
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            <div>{{ $user->created_at->format('Y-m-d') }}</div>
                                            <small class="text-muted">{{ $user->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            @if ($user->last_login_at)
                                                <div>{{ $user->last_login_at->format('Y-m-d') }}</div>
                                                <small class="text-muted">{{ $user->last_login_at->format('H:i') }}</small>
                                            @else
                                                <span class="text-muted">{{ __('Not verified') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('No users yet') }}</h5>
                        <p class="text-muted">{{ __('No users registered yet') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Admin Toggle Form -->
    <form id="adminForm" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
        <input type="hidden" name="is_admin" id="adminInput">
    </form>

    <script>
        function viewUser(userId) {
            alert('{{ __('View') }}: ' + userId);
        }

        function makeAdmin(userId) {
            if (confirm('{{ __('Promote to admin') }}؟')) {
                document.getElementById('adminInput').value = '1';
                document.getElementById('adminForm').action = `/admin/users/${userId}/admin`;
                document.getElementById('adminForm').submit();
            }
        }

        function removeAdmin(userId) {
            if (confirm('{{ __('Remove admin rights') }}؟')) {
                document.getElementById('adminInput').value = '0';
                document.getElementById('adminForm').action = `/admin/users/${userId}/admin`;
                document.getElementById('adminForm').submit();
            }
        }
    </script>
@endsection
