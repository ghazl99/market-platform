@extends('core::store.layouts.app')

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

        /* Delete Button Style */
        .btn-delete {
            border: 1px solid #dc3545;
            color: #dc3545;
            background-color: transparent;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-delete:hover {
            background-color: #dc3545;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 2px 6px rgba(220, 53, 69, 0.3);
        }

        .btn-delete i {
            margin-right: 5px;
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
                                <li><i class="fas fa-check"></i> {{ __('Theme') }}: {{ $store->theme?->name }}</li>
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

                                {{-- <a href="{{ route('store.settings.edit', $store->id) }}"
                                    class="btn btn-outline-warning btn-sm flex-fill">
                                    <i class="fas fa-cog me-1"></i>
                                    {{ __('Settings') }}
                                </a> --}}

                                <form action="{{ route('stores.destroy', $store) }}" method="POST" class="flex-fill"
                                    onsubmit="return confirm('{{ __('Are you sure you want to delete this store?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete btn btn-sm w-100">
                                        <i class="fas fa-trash"></i>{{ __('Delete') }}
                                    </button>
                                </form>

                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-store fa-5x text-muted mb-4"></i>
                    <h4 class="text-muted">{{ __('No Stores Yet') }}</h4>
                    <p class="text-muted">{{ __('Start by creating your first store') }}</p>

                </div>
            @endif
        </div>
    </section>

@endsection
