@extends('core::store.layouts.app')

@section('title', __('Cart'))

@section('content')
    <div class="container my-5">
        @php
            $totalPrice = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        @endphp

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>{{ __('Cart Items') }}</h2>
            <h4 class="fw-bold">{{ __('Total Price') }}: <b style="color: darkred">{{ number_format($totalPrice, 2) }} $</b>
            </h4>
        </div>

        @forelse($cartItems as $item)
            <div class="card mb-3 shadow-sm">
                <div class="card-body d-flex">
                    @php
                        $media = $item->product->getFirstMedia('product_images');
                    @endphp

                    <!-- Right: Product image -->
                    <div class="ms-3">
                        @if ($media)
                            <img src="{{ route('product.image', $media->id) }}" alt="{{ $item->product->name }}"
                                class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px; object-fit: cover;">
                                <i class="fas fa-store fa-3x"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Left: Product info -->
                    <div class="flex-grow-1 ms-3 me-3">
                        <!-- First line: Product name + delete button -->
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-1">{{ $item->product->name }}</h5>
                            <button class="btn btn-sm btn-danger delete-cart-item" data-id="{{ $item->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                        <!-- Second line: Player ID + Quantity + Price -->
                        <div class="d-flex align-items-center mt-2">
                            @if ($item->player_id)
                                <div class="me-3">
                                    <label class="form-label mb-0 fw-bold">{{ __('Player ID') }}:</label>
                                    <input type="text" class="form-control form-control-sm"
                                        value="{{ $item->player_id }}" readonly style="width: 120px;" readonly>
                                </div>
                            @endif

                            <div class="d-flex align-items-center me-3">
                                <label class="form-label mb-0 fw-bold me-2">{{ __('Quantity') }}:</label>
                                <button type="button" class="btn btn-sm btn-outline-secondary me-1 quantity-decrease"
                                    data-id="{{ $item->id }}">-</button>
                                <input type="number" min="1" value="{{ $item->quantity }}"
                                    class="form-control form-control-sm quantity-input" data-id="{{ $item->id }}"
                                    style="width: 60px;">
                                <button type="button" class="btn btn-sm btn-outline-secondary ms-1 quantity-increase"
                                    data-id="{{ $item->id }}">+</button>
                            </div>

                            <div>
                                <span class="fw-bold">{{ __('Price') }}:
                                    ${{ number_format($item->product->price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>{{ __('Your cart is empty.') }}</p>
        @endforelse
        @if ($cartItems->count())
            <form action="{{ route('order.store') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success mt-4">
                    {{ __('Confirm Order') }} <i class="fas fa-check"></i>
                </button>
            </form>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Delete cart item
                document.querySelectorAll('.delete-cart-item').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        fetch(`/carts/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => location.reload());
                    });
                });

                // Update quantity input
                function updateQuantity(id, quantity) {
                    fetch(`/carts/${id}`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                quantity
                            })
                        })
                        .then(res => res.json())
                        .then(data => location.reload());
                }

                document.querySelectorAll('.quantity-input').forEach(input => {
                    input.addEventListener('change', function() {
                        const id = this.dataset.id;
                        const quantity = parseInt(this.value);
                        if (quantity < 1) this.value = 1;
                        updateQuantity(id, quantity);
                    });
                });

                // Quantity increase / decrease buttons
                document.querySelectorAll('.quantity-increase').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const input = document.querySelector(`.quantity-input[data-id='${id}']`);
                        input.value = parseInt(input.value) + 1;
                        updateQuantity(id, parseInt(input.value));
                    });
                });

                document.querySelectorAll('.quantity-decrease').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const input = document.querySelector(`.quantity-input[data-id='${id}']`);
                        if (parseInt(input.value) > 1) {
                            input.value = parseInt(input.value) - 1;
                            updateQuantity(id, parseInt(input.value));
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
