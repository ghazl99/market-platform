@extends('core::layouts.app')

@section('title', __('User Management - Dashboard'))
@push('styles')
    <style>
        /* Users Page Specific Styles */
        .users-container {
            padding: 2rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary, #1f2937);
            margin: 0;
        }

        .page-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .add-user-btn {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .add-user-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(5, 150, 105, 0.3);
            color: white;
        }

        .users-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--bg-primary, #ffffff);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color, #e5e7eb);
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin: 0 auto 1rem;
        }

        .stat-icon.total {
            background: linear-gradient(135deg, #059669, #10b981);
        }

        .stat-icon.active {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .stat-icon.new {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .stat-icon.premium {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary, #1f2937);
            margin: 0 0 0.5rem 0;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-secondary, #6b7280);
            margin: 0;
        }

        .search-filter-bar {
            background: var(--bg-primary, #ffffff);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color, #e5e7eb);
        }

        .search-filter-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 1rem;
            align-items: end;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem;
            padding-right: 3rem;
            border: 1px solid var(--border-color, #e5e7eb);
            border-radius: 8px;
            background: var(--bg-secondary, #f9fafb);
            color: var(--text-primary, #1f2937);
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .search-box i {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light, #9ca3af);
        }

        .filter-select {
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color, #e5e7eb);
            border-radius: 8px;
            background: var(--bg-secondary, #f9fafb);
            color: var(--text-primary, #1f2937);
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-select:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .filter-btn {
            background: #059669;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-btn:hover {
            background: #047857;
            transform: translateY(-1px);
        }

        .users-table-container {
            background: var(--bg-primary, #ffffff);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color, #e5e7eb);
            overflow-x: auto;
        }

        .users-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .users-table th {
            background: var(--bg-secondary, #f9fafb);
            padding: 1rem;
            font-weight: 600;
            text-align: right;
            border-bottom: 1px solid var(--border-color, #e5e7eb);
            color: var(--text-primary, #1f2937);
            font-size: 0.9rem;
        }

        .users-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-light, #f3f4f6);
            color: var(--text-secondary, #6b7280);
            font-size: 0.9rem;
        }

        .users-table tr:hover {
            background: var(--bg-secondary, #f9fafb);
        }

        .users-table tr:nth-child(even) {
            background: rgba(0, 0, 0, 0.02);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #059669, #10b981);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-details h6 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary, #1f2937);
        }

        .user-details p {
            margin: 0;
            font-size: 0.8rem;
            color: var(--text-secondary, #6b7280);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-badge.active {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .status-badge.inactive {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .status-badge.pending {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .role-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .action-btn.edit {
            background: var(--bg-secondary, #f9fafb);
            color: var(--text-secondary, #6b7280);
            border: 1px solid var(--border-color, #e5e7eb);
        }

        .action-btn.edit:hover {
            background: #059669;
            color: white;
            border-color: #059669;
        }

        .action-btn.delete {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .action-btn.delete:hover {
            background: #ef4444;
            color: white;
        }

        .action-btn.view {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .action-btn.view:hover {
            background: #3b82f6;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .search-filter-content {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .users-stats {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .users-container {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                align-items: stretch;
            }

            .page-actions {
                justify-content: space-between;
            }

            .users-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .users-table-container {
                padding: 1rem;
            }

            .users-table th,
            .users-table td {
                padding: 0.75rem 0.5rem;
                font-size: 0.8rem;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
@endpush
@section('content')
    <div class="users-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">{{ __('User Management') }}</h1>
            <div class="page-actions">
                <a href="{{ route('admin.dashboard') }}" class="add-user-btn">
                    <i class="fas fa-arrow-right"></i>
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
        </div>

        <!-- Users Statistics -->
        <div class="users-stats">
    <div class="stat-card">
        <div class="stat-icon total">
            <i class="fas fa-users"></i>
        </div>
        <div >{{ $users->count() }}</div>
        <div class="stat-label">{{ __('Total Users') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon active">
            <i class="fas fa-user-check"></i>
        </div>
        <div >
            {{ $users->whereNotNull('email_verified_at')->count() }}
        </div>
        <div class="stat-label">{{ __('Active') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon new">
            <i class="fas fa-user-plus"></i>
        </div>
        <div >
            {{ $users->filter(fn($u) => $u->created_at >= now()->subMonth())->count() }}
        </div>
        <div class="stat-label">{{ __('New This Month') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon premium">
            <i class="fas fa-crown"></i>
        </div>
        <div >
            {{ $users->filter(fn($u) => optional($u->roles)->contains('name', 'owner'))->count() }}
        </div>
        <div class="stat-label">{{ __('Store Owners') }}</div>
    </div>
</div>


        <!-- Search and Filter Bar -->
        <div class="search-filter-bar">
            <div class="search-filter-content">
                <div class="search-box">
                    <input type="text" placeholder="{{ __('Search users...') }}" id="searchInput">
                    <i class="fas fa-search"></i>
                </div>
                <select class="filter-select" id="statusFilter">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="active">{{ __('Active') }}</option>
                    <option value="inactive">{{ __('Inactive') }}</option>
                    <option value="pending">{{ __('Pending') }}</option>
                </select>
                <select class="filter-select" id="roleFilter">
                    <option value="">{{ __('All Roles') }}</option>
                    <option value="owner">{{ __('Store Owner') }}</option>
                    <option value="staff">{{ __('Staff') }}</option>
                    <option value="customer">{{ __('Customer') }}</option>
                </select>
                <button class="filter-btn" onclick="applyFilters()">
                    <i class="fas fa-filter"></i>
                    {{ __('Apply') }}
                </button>
            </div>
        </div>

        <!-- Users Table -->
        <div class="users-table-container">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Registration Date') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Role') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">{{ substr($user->name, 0, 1) }}</div>
                                    <div class="user-details">
                                        <h6>{{ $user->name }}</h6>
                                        <p>{{ __('ID') }}: #{{ $user->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? __('No phone') }}</td>
                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                            <td>
                                @if ($user->email_verified_at)
                                    <span class="status-badge active">{{ __('Active') }}</span>
                                @else
                                    <span class="status-badge pending">{{ __('Pending') }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="role-badge">{{ $user->getRoleNames()->first() ?? __('Customer') }}</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn view" onclick="viewUser({{ $user->id }})">
                                        <i class="fas fa-eye"></i>
                                        {{ __('View') }}
                                    </button>
                                    <button class="action-btn edit" onclick="editUser({{ $user->id }})">
                                        <i class="fas fa-edit"></i>
                                        {{ __('Edit') }}
                                    </button>
                                    <!-- Form للحذف بدون JS -->
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete">
                                            <i class="fas fa-trash"></i> {{ __('Delete') }}
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('No users yet') }}</h5>
                                <p class="text-muted">{{ __('No users registered yet') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
