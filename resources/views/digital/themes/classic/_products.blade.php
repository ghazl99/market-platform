@if ($products->count() > 0)
    @foreach ($products as $product)
     @php
        $media = $product->getFirstMedia('product_images');
        $route = $product->isParentProduct()
            ? route('product.subProducts', $product->id)  // راوت للمنتجات الفرعية
            : route('product.show', $product->id);       // راوت للمنتج نفسه
    @endphp
        <a href="{{ $route }}" class="product-card-v2" style="text-decoration: none">
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
