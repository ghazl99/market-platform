@extends('core::dashboard.layouts.app')

@section('title', __('Edit Group') . ' - ' . $group->name)

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/notifications.css') }}">

    <style>
        /* Dark Theme Edit Group Page */
        body {
            background: #1a1a1a;
            color: #ffffff;
        }

        .edit-group-container {
            background: #1a1a1a;
            min-height: 100vh;
            padding: 2rem;
        }

        .edit-group-header {
            margin-bottom: 2rem;
        }

        .edit-group-title {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0 0 0.5rem 0;
        }

        .edit-group-subtitle {
            color: #a0a0a0;
            font-size: 1rem;
        }

        .form-container {
            background: #2d2d2d;
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid #404040;
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #374151;
            border: 1px solid #4b5563;
            border-radius: 8px;
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .form-input:disabled {
            background: #1f2937;
            color: #6b7280;
            cursor: not-allowed;
        }

        .form-help {
            color: #9ca3af;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        .btn-primary {
            background: #f59e0b;
            color: #000000;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            background: #d97706;
        }

        .btn-secondary {
            background: #6b7280;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .btn-danger {
            background: #ef4444;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        .success-message {
            color: #10b981;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        .default-notice {
            background: #1e40af;
            border: 1px solid #3b82f6;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #ffffff;
        }

        .default-notice i {
            color: #60a5fa;
            margin-left: 0.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="edit-group-container">
        <!-- Header -->
        <div class="edit-group-header">
            <h1 class="edit-group-title">{{ __('Edit Group') }}</h1>
            <p class="edit-group-subtitle">{{ __('Update group information and settings') }}</p>
        </div>

        <!-- Form -->
        <form class="form-container" method="POST"
            action="{{ LaravelLocalization::localizeURL(route('admin.groups.update', $group->id)) }}">
            @csrf
            @method('PUT')

            @if ($group->is_default)
                <div class="default-notice">
                    <i class="fas fa-info-circle"></i>
                    {{ __('This is the default group. You can modify its settings.') }}
                </div>
            @endif

            <!-- Group Name -->
            <div class="form-group">
                <label class="form-label">{{ __('Group Name') }}</label>
                <input type="text" class="form-input @error('name') error @enderror" name="name"
                    value="{{ old('name', $group->name) }}" placeholder="{{ __('Enter group name') }}" required>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                <div class="form-help">{{ __('Choose a descriptive name for the group') }}</div>
            </div>

            <!-- Profit Percentage -->
            <div class="form-group">
                <label class="form-label">{{ __('Profit Percentage') }}</label>
                <input type="number" class="form-input @error('profit_percentage') error @enderror"
                    name="profit_percentage" value="{{ old('profit_percentage', $group->profit_percentage) }}"
                    placeholder="0.00" step="0.01" min="0" max="100" required>
                @error('profit_percentage')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                <div class="form-help">{{ __('Enter profit percentage (0-100)') }}</div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ LaravelLocalization::localizeURL(route('admin.groups.index')) }}" class="btn-secondary">
                    <i class="fas fa-times"></i>
                    {{ __('Cancel') }}
                </a>
                @if (!$group->is_default)
                    <form action="{{ LaravelLocalization::localizeURL(route('admin.groups.destroy', $group->id)) }}"
                        method="POST" style="display: inline;"
                        onsubmit="return confirm('{{ __('Are you sure you want to delete this group?') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">
                            <i class="fas fa-trash"></i>
                            {{ __('Delete Group') }}
                        </button>
                    </form>
                @endif
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i>
                    {{ __('Update Group') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/notifications.js') }}"></script>
@endpush
