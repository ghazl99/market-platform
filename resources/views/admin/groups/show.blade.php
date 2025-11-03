@extends('core::dashboard.layouts.app')

@section('title', __('Group Users') . ' - ' . $group->name)

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/notifications.css') }}">

    <style>
        /* Dark Theme Group Users Page */
        body {
            background: #1a1a1a;
            color: #ffffff;
        }

        .group-users-container {
            background: #1a1a1a;
            min-height: 100vh;
            padding: 2rem;
        }

        .group-header {
            background: #2d2d2d;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #404040;
        }

        .group-title-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .group-name {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
        }

        .group-stats {
            display: flex;
            gap: 2rem;
            margin-top: 1rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #f59e0b;
        }

        .stat-label {
            color: #a0a0a0;
            font-size: 0.9rem;
        }

        .back-btn {
            background: #6b7280;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-btn:hover {
            background: #4b5563;
        }

        .users-table-container {
            background: #1f2937;
            border-radius: 12px;
            border: 1px solid #374151;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            color: #ffffff;
        }

        .table th {
            background: #111827;
            color: #ffffff;
            padding: 1.25rem 1rem;
            text-align: right;
            font-weight: 600;
            border-bottom: 2px solid #374151;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table td {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid #374151;
            color: #ffffff;
            font-size: 0.9rem;
            background: #1f2937;
        }

        .table tr:nth-child(even) td {
            background: #111827;
        }

        .table tr:nth-child(even):hover td {
            background: #374151;
        }

        .table tr:hover {
            background: #374151;
            transition: background-color 0.2s ease;
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .user-cell {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #374151;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #ffffff;
        }

        .user-name {
            font-weight: 500;
            color: #ffffff;
        }

        .user-email {
            color: #9ca3af;
            font-size: 0.8rem;
        }

        .status-badge {
            background: #10b981;
            color: #ffffff;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-badge.offline {
            background: #6b7280;
        }

        .balance-cell {
            font-weight: 600;
            color: #10b981;
        }

        .debt-cell {
            font-weight: 600;
            color: #ef4444;
        }

        .net-cell {
            font-weight: 600;
            color: #3b82f6;
        }

        .date-cell {
            color: #9ca3af;
            font-size: 0.85rem;
            font-family: 'Courier New', monospace;
        }

        .no-users {
            text-align: center;
            padding: 4rem;
            color: #a0a0a0;
        }

        .no-users i {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .group-title-section {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .group-stats {
                justify-content: space-around;
            }

            .table {
                font-size: 0.8rem;
            }

            .table th,
            .table td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="group-users-container">
        <!-- Group Header -->
        <div class="group-header">
            <div class="group-title-section">
                <h1 class="group-name">{{ $group->name }}</h1>
                <a href="{{ LaravelLocalization::localizeURL(route('admin.groups.index')) }}" class="back-btn">
                    <i class="fas fa-arrow-right"></i>
                    {{ __('Back to Groups') }}
                </a>
            </div>
            @if($group->store)
                <div style="margin-bottom: 1rem; padding: 0.75rem; background: #374151; border-radius: 8px; color: #ffffff;">
                    <i class="fas fa-store"></i> 
                    <strong>{{ __('Store') }}:</strong> {{ $group->store->name }}
                </div>
            @endif
            <div class="group-stats">
                <div class="stat-item">
                    <div class="stat-value">{{ $group->profit_percentage }}%</div>
                    <div class="stat-label">{{ __('Profit Percentage') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $users->total() }}</div>
                    <div class="stat-label">{{ __('Total Users') }}</div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="users-table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Balance') }}</th>
                        <th>{{ __('Debts') }}</th>
                        <th>{{ __('Net') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Last Activity') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar">
                                        {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="user-name">{{ $user->name }}</div>
                                        <div class="user-email">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td class="balance-cell">{{ number_format($user->walletForStore()?->balance ?? 0, 4) }}</td>
                            <td class="debt-cell">{{ number_format($user->walletForStore()?->debt ?? 0, 4) }}</td>
                            <td class="net-cell">
                                {{ number_format(($user->walletForStore()?->balance ?? 0) - ($user->walletForStore()?->debt ?? 0), 4) }}
                            </td>
                            <td>
                                <span
                                    class="status-badge {{ $user->last_login_at && $user->last_login_at->diffInMinutes() < 5 ? '' : 'offline' }}">
                                    {{ $user->last_login_at && $user->last_login_at->diffInMinutes() < 5 ? __('Online') : __('Offline') }}
                                </span>
                            </td>
                            <td class="date-cell">
                                {{ $user->last_login_at ? $user->last_login_at->format('H:i:s Y-m-d') : __('Never') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="no-users">
                                <i class="fas fa-users"></i>
                                <h3>{{ __('No Users Found') }}</h3>
                                <p>{{ __('This group has no users yet.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($users->hasPages())
            <div class="pagination-container" style="margin-top: 2rem; display: flex; justify-content: center;">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/notifications.js') }}"></script>
@endpush
