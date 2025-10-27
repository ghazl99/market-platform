@extends('core::layouts.app')

@section('title', __('Create New Store') . ' - ' . __('Stores Platform'))

@push('styles')
    <style>
        /* Reset بعض الأشياء */
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
            max-width: 900px;
            margin: 100px auto 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #10b981;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
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
        input[type="email"],
        input[type="file"],
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

        .store-type-card {
            padding: 15px 10px;
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .store-type-card:hover {
            border-color: #10b981;
        }

        .store-type-input {
            display: none;
        }

        .store-type-input:checked+.store-type-card {
            border-color: #10b981;
            background: #d1fae5;
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.2);
        }

        .store-icon {
            width: 40px;
            height: 40px;
            margin: 0 auto 8px;
            background: #e0e0e0;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #10b981;
            font-size: 1.2rem;
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

        .row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <h2>{{ __('Create New Store') }}</h2>

        <form method="POST" action="{{ route('stores.store') }}" enctype="multipart/form-data">
            @csrf

            @hasanyrole('owner|staff')
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            @endhasanyrole

            {{-- Store Name --}}
            <div class="form-group">
                <label for="name">{{ __('Store Name') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Domain --}}
            @php
                $isProduction = app()->environment('production');
                $mainDomain = config('app.main_domain', 'localhost');
            @endphp

            <div class="form-group">
                <label for="domain">{{ __('Domain') }}</label>
                <div style="display:flex;gap:5px;">
                    <input type="text" id="domain" name="domain" value="{{ old('domain') }}" required
                        style="flex:1;">
                    @unless ($isProduction)
                        <span style="align-self:center">.localhost</span>
                    @endunless
                </div>
                @error('domain')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>


            {{-- Theme --}}
            <div class="form-group">
                <label for="theme_id">{{ __('Theme') }}</label>
                <select id="theme_id" name="theme_id" required>
                    <option value="">{{ __('Select Theme') }}</option>
                    @foreach ($themes as $theme)
                        <option value="{{ $theme->id }}"
                            {{ old('theme', $store->theme_id ?? '') == $theme->id ? 'selected' : '' }}>
                            {{ $theme->getTranslation('name', app()->getLocale()) }}
                        </option>
                    @endforeach
                </select>
                @error('theme')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>


            {{-- Timezone --}}
            <div class="form-group">
                <label for="timezone">{{ __('Timezone') }}</label>
                <select name="timezone" id="timezone" required>
                    @foreach (timezone_identifiers_list() as $tz)
                        <option value="{{ $tz }}" {{ old('timezone') == $tz ? 'selected' : '' }}>
                            {{ $tz }}
                        </option>
                    @endforeach
                </select>
                @error('timezone')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Logo --}}
            <div class="form-group">
                <label for="logo">{{ __('Store Logo') }}</label>
                <input type="file" id="logo" name="logo">
                @error('logo')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>


            {{-- Store Type --}}
            <div class="form-group full-width">
                <label>{{ __('Preferred Store Type') }}</label>
                <div class="row">
                    <label>
                        <input type="radio" name="type" value="traditional" class="store-type-input"
                            {{ old('type') == 'traditional' ? 'checked' : '' }}>
                        <div class="store-type-card">
                            <div class="store-icon"><i class="fas fa-shopping-bag"></i></div>
                            <span>{{ __('Traditional') }}</span>
                        </div>
                    </label>

                    <label>
                        <input type="radio" name="type" value="digital" class="store-type-input"
                            {{ old('type') == 'digital' ? 'checked' : '' }}>
                        <div class="store-type-card">
                            <div class="store-icon"><i class="fas fa-laptop"></i></div>
                            <span>{{ __('Digital') }}</span>
                        </div>
                    </label>

                    <label>
                        <input type="radio" name="type" value="educational" class="store-type-input"
                            {{ old('type') == 'educational' ? 'checked' : '' }}>
                        <div class="store-type-card">
                            <div class="store-icon"><i class="fas fa-graduation-cap"></i></div>
                            <span>{{ __('Educational') }}</span>
                        </div>
                    </label>
                </div>
                @error('type')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Info Alert --}}
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>{{ __('Note:') }}</strong>
                {{ __('After creating the store, it will be reviewed by the administration before activation.') }}
            </div>

            {{-- Submit Buttons --}}
            <div style="display:flex; gap:10px; margin-top:20px;">
                <button type="submit" class="btn btn-primary">{{ __('Create Store') }}</button>
                <a href="{{ route('stores.index') }}" class="btn btn-outline-secondary">{{ __('Back') }}</a>
            </div>
        </form>
    </div>
@endsection
