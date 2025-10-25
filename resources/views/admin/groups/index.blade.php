@extends('core::dashboard.layouts.app')

@section('title', __('Groups Management'))

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/notifications.css') }}">

    <style>
        /* Dark Theme Groups Page */
        body {
            background: #1a1a1a;
            color: #ffffff;
        }

        .groups-container {
            background: #1a1a1a;
            min-height: 100vh;
            padding: 2rem;
        }

        .groups-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .groups-title {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
        }

        .add-group-btn {
            background: #f59e0b;
            color: #000000;
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .add-group-btn:hover {
            background: #d97706;
            transform: translateY(-2px);
        }

        .groups-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .group-card {
            background: #2d2d2d;
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid #404040;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .group-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            border-color: #f59e0b;
        }

        .group-card.default {
            border-color: #10b981;
        }

        .group-card.default::before {
            content: 'افتراضي';
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #10b981;
            color: #ffffff;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .group-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 1rem;
        }

        .group-stats {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .profit-percentage {
            background: #1e40af;
            color: #ffffff;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .users-count {
            color: #a0a0a0;
            font-size: 0.9rem;
        }

        .group-actions {
            display: flex;
            gap: 0.5rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .group-card:hover .group-actions {
            opacity: 1;
        }

        .action-btn {
            background: #374151;
            border: 1px solid #4b5563;
            border-radius: 8px;
            padding: 0.5rem;
            color: #ffffff;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }

        .action-btn:hover {
            background: #4b5563;
        }

        .action-btn.edit:hover {
            background: #3b82f6;
            border-color: #3b82f6;
        }

        .action-btn.delete:hover {
            background: #ef4444;
            border-color: #ef4444;
        }

        .add-group-card {
            background: #2d2d2d;
            border: 2px dashed #555;
            border-radius: 16px;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: #ffffff;
        }

        .add-group-card:hover {
            border-color: #f59e0b;
            background: #374151;
        }

        .add-group-icon {
            font-size: 3rem;
            color: #f59e0b;
            margin-bottom: 1rem;
        }

        .add-group-text {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .add-group-subtitle {
            color: #a0a0a0;
            font-size: 0.9rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .groups-grid {
                grid-template-columns: 1fr;
            }

            .groups-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .add-group-btn {
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="groups-container">
        <!-- Groups Header -->
        <div class="groups-header">
            <h1 class="groups-title">{{ __('Groups Management') }}</h1>
            <a href="{{ LaravelLocalization::localizeURL(route('admin.groups.create')) }}" class="add-group-btn">
                <i class="fas fa-plus"></i>
                {{ __('Add New Group') }}
            </a>
        </div>

        <!-- Groups Grid -->
        <div class="groups-grid">
            <!-- Add New Group Card -->
            <a href="{{ LaravelLocalization::localizeURL(route('admin.groups.create')) }}" class="add-group-card">
                <div class="add-group-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="add-group-text">{{ __('New Group') }}</div>
                <div class="add-group-subtitle">{{ __('Add') }}</div>
            </a>

            <!-- Existing Groups -->
            @forelse($groups as $group)
                <div class="group-card {{ $group->is_default ? 'default' : '' }}"
                    onclick="window.location.href='{{ LaravelLocalization::localizeURL(route('admin.groups.show', $group->id)) }}'">
                    <div class="group-name">{{ $group->name }}</div>
                    <div class="group-stats">
                        <div class="profit-percentage">{{ $group->profit_percentage }}%</div>
                        <div class="users-count">{{ $group->users_count }} {{ __('users') }}</div>
                    </div>
                    <div class="group-actions">
                        <a href="{{ LaravelLocalization::localizeURL(route('admin.groups.edit', $group->id)) }}"
                            class="action-btn edit" title="{{ __('Edit') }}">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if (!$group->is_default)
                            <form action="{{ route('admin.groups.destroy', $group->id) }}" method="POST"
                                style="display: inline;"
                                onsubmit="return confirm('{{ __('Are you sure you want to delete this group?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="{{ __('Delete') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="no-groups" style="grid-column: 1 / -1; text-align: center; padding: 4rem; color: #a0a0a0;">
                    <i class="fas fa-users" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                    <h3>{{ __('No Groups Found') }}</h3>
                    <p>{{ __('Start by creating your first group.') }}</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/notifications.js') }}"></script>
@endpush
