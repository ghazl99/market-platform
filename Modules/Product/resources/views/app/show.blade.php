@extends('core::store.layouts.app')

@section('title', __('Product') . ' - ' . $product->name)
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/product-purchase.css') }}?v={{ time() }}">
@endpush
@section('content')
    <div class="container my-5">
        <!-- Product Purchase Section -->
        <section class="product-purchase-section">
            <div class="product-purchase-container">
                <div class="product-card" id="product-requesr-main" product-request-id="7" product-request-apiid="7"
                    product-request-skipping="2" product-request-pricesource="Level">
                    <div class="card-content">
                        @php
                            $media = $product->getFirstMedia('product_images');
                        @endphp
                        <!-- Product Image Section -->
                        <div class="product-image-section">
                            <div class="product-image-wrapper">
                                @if ($media)
                                    <img id="product-request-image" src="{{ route('product.image', $media->id) }}"
                                        class="product-image" alt="{{ $product->name }}">
                                @else
                                    <div id="product-request-image" class="product-image"
                                        style="display:flex; align-items:center; justify-content:center; background:#e9ecef;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="100%"
                                            height="100%">
                                            <!-- SVG للمنتج الافتراضي -->
                                            <rect x="8" y="12" width="48" height="6" fill="#6c757d"
                                                rx="1" />
                                            <text x="32" y="16" font-size="4" text-anchor="middle" fill="#fff"
                                                font-family="Arial, sans-serif">STORE</text>
                                            <rect x="6" y="20" width="52" height="36" fill="#e9ecef" rx="2"
                                                stroke="#adb5bd" stroke-width="1" />
                                            <rect x="28" y="40" width="8" height="16" fill="#343a40"
                                                rx="1" />
                                            <rect x="12" y="26" width="8" height="12" fill="#ced4da"
                                                stroke="#adb5bd" stroke-width="0.5" />
                                            <rect x="44" y="26" width="8" height="12" fill="#ced4da"
                                                stroke="#adb5bd" stroke-width="0.5" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="api-badge" id="product-request-api">
                                    <i class="fas fa-code"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Product Info Section -->
                        <div class="product-info-section">
                            <h4 class="product-title" id="product-request-name"> {{ $product->name }}</h4>
                            <!-- الأقسام -->
                            {{-- <div class="mb-3 product-category-section">
                                <h5>{{ __('Categories') }}</h5>
                                <div class="product-categories">
                                    @foreach ($product->categories as $category)
                                        <span class="category-badge">{{ $category->name }}</span>
                                    @endforeach
                                </div>
                            </div> --}}

                            <!-- الخصائص -->
                            @if ($product->attributes->count() > 0)
                                <div class="mb-3 product-category-section">
                                    <h5>{{ __('Attributes') }}</h5>
                                    <div class="product-attributes">
                                        @foreach ($product->attributes as $attribute)
                                            @php
                                                $value = json_decode($attribute->pivot->value, true);
                                                $currentValue = $value[app()->getLocale()] ?? (reset($value) ?? '');
                                            @endphp
                                            <span class="product-attribute">
                                                {{ $attribute->name }}: {{ $currentValue }}
                                                @if ($attribute->unit)
                                                    ({{ $attribute->unit }})
                                                @endif
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Quantity Info Cards -->
                            <div class="quantity-cards">
                                <div class="quantity-card">
                                    <div class="quantity-label">{{ __('Minimum quantity') }}</div>
                                    <div class="quantity-value" id="Product-request-min">{{ $product->min_quantity }}</div>
                                </div>
                                <div class="quantity-card">
                                    <div class="quantity-label">{{ __('Maximum quantity') }}</div>
                                    <div class="quantity-value" id="Product-request-max">{{ $product->max_quantity }}</div>
                                </div>
                            </div>

                            <!-- Price Section -->
                            <div class="price-section">
                                <div class="price-label">السعر النهائي</div>
                                <div class="price-value">
                                    <span id="product-request-TotalPrice">{{ $product->price }}</span> $
                                </div>
                            </div>

                        </div>
                        @if ($product->status)
                            <!-- Form Section -->
                            <form action="{{ route('order.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div class="form-section">
                                    <!-- Quantity Section -->
                                    <div class="form-group">
                                        <label class="form-label">{{ __('Quantity') }}</label>
                                        <div class="input-group">
                                            <input name="quantity" id="quantity" placeholder="{{ __('Quantity') }}"
                                                class="form-control int-format-jaafar" value="1">
                                            <span class="input-group-text">{{ $product->price }} $</span>
                                        </div>
                                    </div>

                                    @if ($product->store->type === 'digital')
                                        <div class="form-group">
                                            <label class="form-label"> {{ __('Player ID') }}</label>
                                            <div class="input-group">
                                                <input name="player_id" id="player_id"
                                                    placeholder="{{ __('Enter Player ID') }}" required class="form-control">

                                            </div>
                                        </div>
                                    @endif
                                    <div class="form-group d-none" id="product-request-playername-container">
                                        <div class="input-group">
                                            <img src="/assets/images/media/ajax-loader.gif" id="loadingUserInfoImage"
                                                style="display: block;width:35px">
                                            <input name="playerId" id="product-request-playername" class="form-control">
                                        </div>
                                    </div>

                                </div>

                                <!-- Purchase Button Section -->
                                <div class="purchase-section">
                                    <button id="product-request-buyid" type="submit" class="purchase-btn">
                                        <i class="fas fa-credit-card"></i>
                                        {{ __('Complete the purchase') }}
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
        </section>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity');
            const totalPriceDisplay = document.getElementById('product-request-TotalPrice');
            const unitPrice = parseFloat("{{ $product->price }}");

            function updateTotalPrice() {
                let quantity = parseInt(quantityInput.value) || 1;

                const minQty = parseInt("{{ $product->min_quantity }}");
                const maxQty = parseInt("{{ $product->max_quantity }}") || Infinity;

                if (quantity < minQty) quantity = minQty;
                if (quantity > maxQty) quantity = maxQty;

                quantityInput.value = quantity;

                const total = (unitPrice * quantity).toFixed(2);
                totalPriceDisplay.textContent = total;
            }

            updateTotalPrice();
            quantityInput.addEventListener('input', updateTotalPrice);
        });
    </script>

@endpush
