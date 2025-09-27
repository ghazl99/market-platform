@extends('core::store.layouts.app')

@section('title', __('Order Details'))

@section('content')
    <div class="container my-5">
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
        <h2 class="fw-bold mb-4">
            {{ __('The Order') }}: <span class="fw-bold">#{{ $order->id }}</span>

            @if ($order->status === 'pending')
                <button id="cancelOrderBtn" class="btn btn-danger btn-sm float-end ms-3">
                    {{ __('Cancel Order') }}
                </button>
            @endif
        </h2>

        <p><strong>{{ __('Status') }}</strong> {{ $statusLabels[$order->status] ?? $order->status }}</p>
        <p><strong>{{ __('Payment Status') }}</strong>
            {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
        </p>


        <h4 class="mt-4">{{ __('Items') }}</h4>
        <table class="table">

            <thead>
                <tr>
                    <th>{{ __('Product') }}</th>
                    <th>{{ __('Quantity') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->product->price }}</td>
                        <td>{{ $item->quantity * $item->product->price }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end"><strong>{{ __('Total') }}</strong></td>
                    <td><strong>{{ $order->items->sum(fn($i) => $i->quantity * $i->product->price) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cancelBtn = document.getElementById('cancelOrderBtn');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    let swalTitle, swalText;

                    @if ($order->payment_status === 'unpaid')
                        swalTitle = '{{ __('Are you sure?') }}';
                        swalText =
                            '{{ __('This order is unpaid. Cancelling will not charge your wallet.') }}';
                    @else
                        swalTitle = '{{ __('Payment alert') }}';
                        swalText =
                            '{{ __('This order has been paid or partially paid. Cancelling will refund according to the payment status.') }}';
                    @endif

                    Swal.fire({
                        icon: 'warning',
                        title: swalTitle,
                        text: swalText,
                        showCancelButton: true,
                        confirmButtonText: '{{ __('Yes, cancel it!') }}',
                        cancelButtonText: '{{ __('No, keep it') }}'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('{{ route('order.update', $order->id) }}', {
                                    method: 'PATCH',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        action: 'canceled'
                                    })
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire('{{ __('Success') }}', data.message,
                                                'success')
                                            .then(() => location.reload());
                                    }
                                });
                        }
                    });
                });
            }
        });
    </script>
@endpush
