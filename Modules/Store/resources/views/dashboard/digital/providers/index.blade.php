@extends('core::dashboard.layouts.app')

@section('title', __('Providers') . ' - ' . __('Dashboard'))

@push('styles')
    <style>
        .providers-container {
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1e40af;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .add-provider-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .add-provider-section h2 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2d3748;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #d1d5e1;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .providers-list {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .providers-list h2 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2d3748;
        }

        .providers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .provider-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
            transition: all 0.2s;
        }

        .provider-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .provider-card-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }

        .provider-card-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge.active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .provider-info {
            margin-bottom: 15px;
        }

        .provider-info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            font-size: 14px;
            color: #6b7280;
        }

        .provider-info-item i {
            color: #9ca3af;
            width: 16px;
        }

        .provider-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #d1d5e1;
        }

        .empty-state h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #374151;
        }

        .empty-state p {
            font-size: 14px;
            color: #6b7280;
        }

        /* Dark mode support */
        [data-theme="dark"] .add-provider-section,
        [data-theme="dark"] .providers-list,
        [data-theme="dark"] .provider-card {
            background: #1f2937;
            border-color: #374151;
        }

        [data-theme="dark"] .page-header h1,
        [data-theme="dark"] .add-provider-section h2,
        [data-theme="dark"] .providers-list h2,
        [data-theme="dark"] .provider-card-header h3 {
            color: #f3f4f6;
        }

        [data-theme="dark"] .form-control {
            background: #111827;
            border-color: #374151;
            color: #f3f4f6;
        }

        [data-theme="dark"] .provider-info-item {
            color: #9ca3af;
        }
    </style>
@endpush

@section('content')
    <div class="providers-container">
        <div class="page-header">
            <h1>
                <i class="fas fa-network-wired"></i>
                {{ __('Providers') }}
            </h1>
        </div>

        <!-- Add Provider Section -->
        <div class="add-provider-section">
            <h2>
                <i class="fas fa-plus-circle"></i>
                {{ __('Add Provider') }}
            </h2>
            <form action="{{ route('dashboard.providers.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="provider_id">{{ __('Select Provider') }}</label>
                    <select name="provider_id" id="provider_id" class="form-control" required>
                        <option value="">{{ __('Choose a provider...') }}</option>
                        @foreach ($allProviders as $provider)
                            @if (!$linkedProviders->contains('id', $provider->id))
                                <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    @if ($allProviders->whereNotIn('id', $linkedProviders->pluck('id'))->isEmpty())
                        <small style="color: #6b7280; margin-top: 5px; display: block;">
                            {{ __('All available providers are already linked to your store.') }}
                        </small>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary"
                    {{ $allProviders->whereNotIn('id', $linkedProviders->pluck('id'))->isEmpty() ? 'disabled' : '' }}>
                    <i class="fas fa-link"></i>
                    {{ __('Link Provider') }}
                </button>
            </form>
        </div>

        <!-- Providers List -->
        <div class="providers-list">
            <h2>
                <i class="fas fa-list"></i>
                {{ __('Linked Providers') }}
            </h2>

            @if ($linkedProviders->count() > 0)
                <div class="providers-grid">
                    @foreach ($linkedProviders as $provider)
                        @php
                            $pivot = $provider->pivot;
                            $isActive = $pivot->is_active ?? true;
                        @endphp
                        <div class="provider-card">
                            <div class="provider-card-header">
                                <h3>{{ $provider->name }}</h3>
                                <span class="status-badge {{ $isActive ? 'active' : 'inactive' }}">
                                    {{ $isActive ? __('Active') : __('Inactive') }}
                                </span>
                            </div>
                            <div class="provider-info">
                                <div class="provider-info-item">
                                    <i class="fas fa-link"></i>
                                    <span><strong>{{ __('API URL') }}:</strong> {{ $provider->api_url }}</span>
                                </div>
                                @if ($provider->description)
                                    <div class="provider-info-item">
                                        <i class="fas fa-info-circle"></i>
                                        <span>{{ $provider->description }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="provider-actions">
                                <form action="{{ route('dashboard.providers.toggle-status', $provider->id) }}"
                                    method="POST" style="display: inline;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_active" value="{{ $isActive ? 0 : 1 }}">
                                    <button type="submit" class="btn {{ $isActive ? 'btn-secondary' : 'btn-success' }}"
                                        style="font-size: 12px; padding: 6px 12px;">
                                        <i class="fas fa-{{ $isActive ? 'pause' : 'play' }}"></i>
                                        {{ $isActive ? __('Deactivate') : __('Activate') }}
                                    </button>
                                </form>
                                <form action="{{ route('dashboard.providers.destroy', $provider->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        style="font-size: 12px; padding: 6px 12px;">
                                        <i class="fas fa-unlink"></i>
                                        {{ __('Unlink') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-network-wired"></i>
                    <h3>{{ __('No Providers Linked') }}</h3>
                    <p>{{ __('You haven\'t linked any providers to your store yet. Use the form above to add a provider.') }}
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection
