@if ($products->count() > 0)
    @foreach ($products as $product)
        <a href="{{ route('product.show', $product->id) }}">
            <div class="product-card {{ !$product->status ? 'inactive-product' : '' }}">
                @php $media = $product->getFirstMedia('product_images'); @endphp
                <div class="product-image">
                    @if ($media)
                        <img src="{{ route('product.image', $media->id) }}" alt="{{ $product->name }}">
                    @else
                        <div class="category-img default-store-svg"
                            style="display:flex; align-items:center; justify-content:center; background:#e9ecef;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="100%" height="100%">
                                <!-- SVG للمنتج الافتراضي -->
                                <rect x="8" y="12" width="48" height="6" fill="#6c757d" rx="1" />
                                <text x="32" y="16" font-size="4" text-anchor="middle" fill="#fff"
                                    font-family="Arial, sans-serif">STORE</text>
                                <rect x="6" y="20" width="52" height="36" fill="#e9ecef" rx="2"
                                    stroke="#adb5bd" stroke-width="1" />
                                <rect x="28" y="40" width="8" height="16" fill="#343a40" rx="1" />
                                <rect x="12" y="26" width="8" height="12" fill="#ced4da" stroke="#adb5bd"
                                    stroke-width="0.5" />
                                <rect x="44" y="26" width="8" height="12" fill="#ced4da" stroke="#adb5bd"
                                    stroke-width="0.5" />
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="product-content">
                    <h3 class="product-title">{{ $product->name }}</h3>

                </div>
            </div>
        </a>
    @endforeach
@else
    <div class="alert alert-info text-center w-100">
        {{ __('No products found for this category.') }}
    </div>
@endif
