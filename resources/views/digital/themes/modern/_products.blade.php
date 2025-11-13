@if ($products->count() > 0)
    @foreach ($products as $product)
        <a href="{{ route('product.show', $product->id) }}" class="product-card" style="text-decoration: none">
            @php $media = $product->getFirstMedia('product_images'); @endphp
            @if ($media)
                <img src="{{ route('product.image', $media->id) }}" alt="{{ $product->name }}" class="product-image">
            @endif
            <h3 class="product-title">{{ $product->name }}</h3>
        </a>
    @endforeach
@else
    <div class="alert alert-info text-center w-100">
        {{ __('No products found for this category.') }}
    </div>
@endif

