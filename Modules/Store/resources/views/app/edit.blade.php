@extends('core::layouts.app')

@section('title', 'تعديل ' . $store->name . ' - منصة المتاجر')

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
            max-width: 800px;
            margin-top: 120px;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        h4 {
            margin: 0;
            text-align: center;
            color: #fff;
        }

        .card-header {
            background-color: #10b981;
            padding: 15px;
            border-radius: 12px 12px 0 0;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            flex: 1 1 48%;
            min-width: 200px;
        }

        .form-group.full-width {
            flex: 1 1 100%;
        }

        label {
            font-weight: 600;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        select,
        textarea {
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 5px rgba(16, 185, 129, 0.3);
        }

        textarea {
            resize: vertical;
        }

        .form-text {
            font-size: 0.8rem;
            color: #777;
            margin-top: 3px;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: #d1ecf1;
            color: #0c5460;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: #10b981;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #0f766e;
        }

        .btn-outline-secondary {
            background: #fff;
            border: 2px solid #ccc;
            color: #555;
        }

        .btn-outline-secondary:hover {
            border-color: #10b981;
            color: #10b981;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #fff;
        }

        .badge-success {
            background-color: #10b981;
        }

        .badge-warning {
            background-color: #f59e0b;
        }

        .badge-danger {
            background-color: #ef4444;
        }

        .row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-edit me-2"></i> {{ __('Edit Store') }}: {{ $store->name }}</h4>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('stores.update', $store) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="form-group">
                            <label for="name"> {{ __('Store Name') }}</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $store->name) }}"
                                required>
                            <small class="form-text">{{ __('Store name as shown to customers') }}</small>
                        </div>

                        <div class="form-group">
                            <label for="domain">{{ __('Domain') }}</label>
                            <div style="display:flex;gap:5px;">
                                <input type="text" id="domain" value="{{ $store->domain }}" disabled style="flex:1;">
                                <span style="align-self:center">.{{ config('app.domain', 'localhost') }}</span>
                            </div>
                            <small class="form-text">{{ __('Cannot change domain after creation') }}</small>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="description">{{ __('Store Description') }}</label>
                        <textarea id="description" name="description" rows="3">{{ old('description', $store->description) }}</textarea>
                        <small class="form-text">{{ __('Short description about your store and products') }}</small>
                    </div>

                    <div class="form-group full-width">
                        <label for="theme">{{ __('Theme') }}</label>
                        <select id="theme" name="theme" required>
                            <option value="">{{ __('Select Theme') }}</option>
                            <option value="default" {{ old('theme', $store->theme) == 'default' ? 'selected' : '' }}>
                                {{ __('Default Theme') }}</option>
                            <option value="modern" {{ old('theme', $store->theme) == 'modern' ? 'selected' : '' }}>
                                {{ __('Modern Theme') }}</option>
                            <option value="classic" {{ old('theme', $store->theme) == 'classic' ? 'selected' : '' }}>
                                {{ __('Classic Theme') }}</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <div class="row">
                            <div class="form-group">
                                <strong>{{ __('Status') }}:</strong>
                                <span
                                    class="badge {{ $store->status == 'active' ? 'badge-success' : ($store->status == 'pending' ? 'badge-warning' : 'badge-danger') }}">
                                    {{ $store->status == 'active' ? __('Active') : ($store->status == 'pending' ? __('Pending') : __('Inactive')) }}
                                </span>
                            </div>
                            <div class="form-group">
                                @php
                                    $created = $store->created_at_in_store_timezone;
                                    $formatted = $created->format('d/m/Y h:i A');
                                    if (app()->getLocale() == 'ar') {
                                        $formatted = str_replace(['AM', 'PM'], ['صباحًا', 'مساءً'], $formatted);
                                    }
                                @endphp
                                <strong>{{ __('Created At') }}</strong>
                                <span>{{ $formatted }}</span>
                            </div>
                        </div>
                    </div>


                    <div style="display:flex; gap:10px; margin-top:20px;">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i> {{ __('Save') }}</button>
                        <a href="{{ route('stores.show', $store) }}" class="btn btn-outline-secondary"><i
                                class="fas fa-arrow-right me-2"></i> {{ __('Back') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
