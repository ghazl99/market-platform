@extends('core::dashboard.layouts.app')

@section('title', __('Create New Group'))

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/notifications.css') }}">

    <style>
        /* Dark Theme Create Group Page */
        body {
            background: #1a1a1a;
            color: #ffffff;
        }

        .create-group-container {
            background: #1a1a1a;
            min-height: 100vh;
            padding: 2rem;
        }

        .create-group-header {
            margin-bottom: 2rem;
        }

        .create-group-title {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0 0 0.5rem 0;
        }

        .create-group-subtitle {
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
    </style>
@endpush

@section('content')
    <div class="create-group-container">
        <!-- Header -->
        <div class="create-group-header">
            <h1 class="create-group-title">{{ __('Create New Group') }}</h1>
            <p class="create-group-subtitle">{{ __('Add a new group with profit percentage settings') }}</p>
        </div>

        <!-- Form -->
        <form class="form-container" method="POST"
            action="{{ LaravelLocalization::localizeURL(route('admin.groups.store')) }}">
            @csrf

            <!-- Group Name -->
            <div class="form-group">
                <label class="form-label">{{ __('Group Name') }}</label>
                <input type="text" class="form-input @error('name') error @enderror" name="name"
                    value="{{ old('name') }}" placeholder="{{ __('Enter group name') }}" required>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                <div class="form-help">{{ __('Choose a descriptive name for the group') }}</div>
            </div>

            <!-- Profit Percentage -->
            <div class="form-group">
                <label class="form-label">{{ __('Profit Percentage') }}</label>
                <input type="number" class="form-input @error('profit_percentage') error @enderror"
                    name="profit_percentage" value="{{ old('profit_percentage') }}" placeholder="0.00" step="0.01"
                    min="0" max="100" required>
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
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i>
                    {{ __('Create Group') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/notifications.js') }}"></script>
@endpush
