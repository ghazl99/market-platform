@if ($products->count() > 0)
    @foreach ($products as $product)
        <a href="{{ route('product.show', $product->id) }}" class="product-card-v2" style="text-decoration: none">
            @php $media = $product->getFirstMedia('product_images'); @endphp
            @if ($media)
                <div class="product-image">

                    <img src="{{ route('product.image', $media->id) }}" alt="{{ $product->name }}">

                </div>
            @endif
            <div class="product-info">
                <h3 class="product-name">{{ $product->name }}</h3>
            </div>
        </a>
    @endforeach
@else
    <div class="alert alert-info text-center w-100">
        {{ __('No products found for this category.') }}
    </div>
@endif
