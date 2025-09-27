@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Manage Orders'))

@section('content')
    <div class="container my-5">
        <h2 class="fw-bold mb-4">{{ __('My Orders') }}</h2>

        @forelse($orders as $order)
            @php
                $statusLabels = [
                    'pending' => __('Pending'),
                    'confirmed' => __('Confirmed'),
                    'completed' => __('Completed'),
                    'canceled' => __('Canceled'),
                ];
                $paymentLabels = [
                    'paid' => __('Paid'),
                    'unpaid' => __('Unpaid'),
                    'partially_paid' => __('Partially Paid'),
                ];
            @endphp
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1">{{ __('Order ID') }}:
                            <span class="fw-bold">{{ ucfirst($order->id) }}</span>
                        </p>

                        <p class="mb-1">{{ __('Status') }}:
                            <span class="fw-bold">{{ $statusLabels[$order->status] ?? $order->status }}</span>
                        </p>
                        <p class="mb-0">
                            {{ __('Total') }}:
                            <span class="fw-bold">{{ $order->items->sum(fn($i) => $i->quantity * $i->product->price) }}
                                $</span>
                        </p>
                        <p class="mb-0">
                            {{ __('Payment Status') }}:
                            <span
                                class="fw-bold">{{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}</span>

                        </p>
                    </div>
                    <a href="{{ route('dashboard.order.show', $order->id) }}" class="btn btn-primary">
                        {{ __('View Details') }}
                    </a>
                </div>
            </div>
        @empty
            <p>{{ __('No orders') }}</p>
        @endforelse
        <div class="d-flex justify-content-center mt-4">
            @if ($orders->hasPages())
                {{ $orders->links() }}
            @endif
        </div>
    </div>
@endsection
