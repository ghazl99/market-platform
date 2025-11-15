 @forelse($products as $product)
     <tr>
         <td>{{ $product->id }}</td>
         <td>{{ $product->name }}</td>
         <td>{{ $product->description }}</td>
         <td>
             @php
                 $media = $product->getFirstMedia('product_images');
             @endphp
             @if ($media)
                 <img src="{{ route('product.image', $media->id) }}" alt="{{ $product->name }}" width="50">
             @else
                 -
             @endif
         </td>
         <td>{{ $product->price }}</td>
         <td>
             @foreach ($product->categories as $category)
                 <span class="badge bg-info text-dark">{{ $category->name }}</span>
             @endforeach
         </td>
         <td>
             @foreach ($product->attributes as $attribute)
                 @php
                     $value = json_decode($attribute->pivot->value, true);
                     $currentValue = $value[app()->getLocale()] ?? (reset($value) ?? '');
                 @endphp
                 <span class="badge bg-secondary">
                     {{ $attribute->name }}:
                     {{ $currentValue }}
                     @if ($attribute->unit)
                         ({{ $attribute->unit }})
                     @endif
                 </span>
             @endforeach
         </td>
         <td>
             <a href="{{ route('dashboard.product.edit', $product->id) }}" class="btn btn-sm btn-primary me-1">
                 <i class="fas fa-edit"></i>
             </a>
             <form action="{{ route('dashboard.product.destroy', $product->id) }}" method="POST"
                 class="d-inline delete-product-form">
                 @csrf
                 @method('DELETE')
                 <button type="button" class="btn btn-sm btn-danger delete-product-btn"
                     data-product-name="{{ $product->name }}">
                     <i class="fas fa-trash"></i>
                 </button>
             </form>

         </td>
     </tr>
 @empty
     <tr>
         <h6 class="text-center">{{ __('No products found') }}</h6>
     </tr>
 @endforelse
