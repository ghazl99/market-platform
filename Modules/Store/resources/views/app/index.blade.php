@extends('core::layouts.app')

@section('title', __('My Stores') . ' - ' . config('app.name'))
@push('styles')
    <style>
        .section-header {
            display: flex;
            justify-content: space-between;
            /* العنوان يمين - الزر يسار */
            align-items: center;
            margin-bottom: 20px;
        }

        .section-header h2 {
            margin: 0;
            /* منع نزول العنوان لسطر جديد */
        }
    </style>
@endpush
@section('content')

    <section class="store-types" id="services">
        <br>
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-store me-2"></i>
                    {{ __('My Stores') }}
                </h2>
                <a href="{{ route('stores.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    {{ __('Create New Store') }}
                </a>
            </div>

            @if ($stores->count() > 0)
                <div class="store-types-grid">
                    @foreach ($stores as $store)
                        <div class="store-type-card">
                            <div class="store-type-icon">
                                @php
                                    $media = $store->getFirstMedia('logo');
                                    $created = $store->created_at_in_store_timezone;
                                    $formatted = $created->format('d/m/Y h:i A');

                                    if (app()->getLocale() === 'ar') {
                                        // استبدال AM/PM بالعربية
                                        $formatted = str_replace(['AM', 'PM'], ['صباحًا', 'مساءً'], $formatted);
                                    }
                                @endphp

                                @if ($media)
                                    <img src="{{ route('store.image', $media->id) }}" alt="{{ $store->name }}"
                                        style="width: 100%; height: 100%; object-fit: cover; ">
                                @else
                                    <i class="fas fa-store fa-2x"></i>
                                @endif
                            </div>

                            <h3>{{ $store->name }}</h3>
                            <p class="card-text text-muted">{{ Str::limit($store->description, 80) }}</p>
                            <span
                                class="badge bg-{{ $store->status == 'active' ? 'success' : ($store->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ $store->status == 'active' ? __('Active') : ($store->status == 'pending' ? __('Pending') : __('Inactive')) }}
                            </span>
                            <ul class="store-features">
                                <li>
                                    <i class="fas fa-check"></i>
                                    <a href="{{ $store->store_url }}" target="_blank" class="text-decoration-none">
                                        <b>{{ __('Visit Store') }}</b>
                                    </a>
                                </li>

                                <li>
                                    <i class="fas fa-check"></i>
                                    <a href="{{ $store->dashboard_url }}" target="_blank" class="text-decoration-none">
                                        <b> {{ __('Visit Dashboard') }}</b>
                                    </a>
                                </li>
                                <li><i class="fas fa-check"></i> {{ __('Theme') }}: {{ $store->theme }}</li>
                                <li><i class="fas fa-check"></i> {{ __('Created At') }}: {{ $formatted }}</li>
                                <li><i class="fas fa-check"></i> {{ __('Sales Reports') }}</li>
                            </ul>
                            <div class="store-examples d-flex gap-2 flex-wrap">
                                <a href="{{ route('stores.show', $store) }}"
                                    class="btn btn-outline-primary btn-sm flex-fill">
                                    <i class="fas fa-eye me-1"></i>
                                    {{ __('View') }}
                                </a>

                                <a href="{{ route('stores.edit', $store) }}"
                                    class="btn btn-outline-success btn-sm flex-fill">
                                    <i class="fas fa-edit me-1"></i>
                                    {{ __('Edit') }}
                                </a>
                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-store fa-5x text-muted mb-4"></i>
                    <h4 class="text-muted">{{ __('No Stores Yet') }}</h4>
                    <p class="text-muted">{{ __('Start by creating your first store') }}</p>
                    <a href="{{ route('stores.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>
                        {{ __('Create New Store') }}
                    </a>
                </div>
            @endif
        </div>
    </section>
    {{-- <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-store me-2"></i>
                {{ __('My Stores') }}
            </h2>
            <a href="{{ route('stores.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                {{ __('Create New Store') }}
            </a>
        </div>

        @if ($stores->count() > 0)

            <div class="row g-4"> --}}
    @foreach ($stores as $store)
        {{-- <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        @php
                            $media = $store->getFirstMedia('banner');
                            $created = $store->created_at_in_store_timezone;
                            $formatted = $created->format('d/m/Y h:i A');

                            if (app()->getLocale() === 'ar') {
                                // استبدال AM/PM بالعربية
                                $formatted = str_replace(['AM', 'PM'], ['صباحًا', 'مساءً'], $formatted);
                            }
                        @endphp
                        @if ($media)
                            <img src="{{ route('store.image', $media->id) }}" class="card-img-top"
                                alt="{{ $store->name }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center"
                                style="height: 200px;">
                                <i class="fas fa-store fa-3x"></i>
                            </div>
                        @endif

                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $store->name }}</h5>
                                <span
                                    class="badge bg-{{ $store->status == 'active' ? 'success' : ($store->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ $store->status == 'active' ? __('Active') : ($store->status == 'pending' ? __('Pending') : __('Inactive')) }}
                                </span>
                            </div>

                            <p class="card-text text-muted">{{ Str::limit($store->description, 80) }}</p>

                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-globe me-1"></i>
                                    {{ $store->store_url }}
                                </small>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-palette me-1"></i>
                                    {{ __('Theme') }}: {{ $store->theme }}
                                </small>
                            </div>
                            @php
                                $created = $store->created_at_in_store_timezone;
                                $formatted = $created->format('d/m/Y h:i A');

                                if (app()->getLocale() === 'ar') {
                                    // استبدال AM/PM بالعربية
                                    $formatted = str_replace(['AM', 'PM'], ['صباحًا', 'مساءً'], $formatted);
                                }
                            @endphp
                            <div class="mb-2 text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ __('Created At') }}: {{ $formatted }}
                            </div>

                        </div>

                        <div class="card-footer bg-transparent border-0">
                            <div class="d-flex gap-2">
                                <a href="{{ route('stores.show', $store) }}"
                                    class="btn btn-outline-primary btn-sm flex-fill">
                                    <i class="fas fa-eye me-1"></i>
                                    {{ __('View') }}
                                </a>
                                <a href="{{ route('stores.edit', $store) }}"
                                    class="btn btn-outline-secondary btn-sm flex-fill">
                                    <i class="fas fa-edit me-1"></i>
                                    {{ __('Edit') }}
                                </a>
                            </div>

                            @if ($store->status == 'active')
                                <div class="mt-2">
                                    <a href="{{ $store->store_url }}" class="btn btn-success btn-sm w-100" target="_blank">
                                        <i class="fas fa-external-link-alt me-1"></i>
                                        {{ __('Visit Store') }}
                                    </a>
                                </div>

                                <div class="mt-2">
                                    <a href="{{ $store->dashboard_url }}" class="btn btn-success btn-sm w-100"
                                        target="_blank">
                                        <i class="fas fa-external-link-alt me-1"></i>
                                        {{ __('Visit Dashboard') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div> --}}
    @endforeach
    {{-- </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-store fa-5x text-muted mb-4"></i>
                <h4 class="text-muted">{{ __('No Stores Yet') }}</h4>
                <p class="text-muted">{{ __('Start by creating your first store') }}</p>
                <a href="{{ route('stores.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>
                    {{ __('Create New Store') }}
                </a>
            </div>
        @endif
    </div> --}}
@endsection
