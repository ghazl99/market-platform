@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Order Details'))

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

        <h2 class="fw-bold mb-4">{{ __('The Order') }}: <span class="fw-bold">#{{ $order->id }}</span></h2>

        <form id="order-update-form" action="{{ route('dashboard.order.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="status" class="form-label"><strong>{{ __('Status') }}</strong></label>
                <select name="status" id="status" class="form-select">
                    @php
                        $allowedStatuses = [];
                        if ($order->status === 'pending') {
                            $allowedStatuses = ['confirmed', 'completed'];
                            // إذا حالة الدفع unpaid نسمح بالإلغاء
                            if ($order->payment_status === 'unpaid') {
                                $allowedStatuses[] = 'canceled';
                            }
                        } elseif ($order->status === 'confirmed') {
                            $allowedStatuses = ['completed'];
                            if ($order->payment_status === 'unpaid') {
                                $allowedStatuses[] = 'canceled';
                            }
                        } else {
                            $allowedStatuses = [];
                        }
                    @endphp

                    <option value="{{ $order->status }}" selected disabled>{{ $statusLabels[$order->status] }}</option>
                    @foreach ($allowedStatuses as $s)
                        <option value="{{ $s }}">{{ $statusLabels[$s] }}</option>
                    @endforeach
                </select>
            </div>


            <button type="submit" class="btn btn-success">{{ __('Save') }}</button>
        </form>

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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('order-update-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: new FormData(form)
                })
                .then(res => res.json()) // تحويل الرد لـ JSON
                .then(res => {
                    if (res.success) {
                        Swal.fire('{{ __('Success') }}', res.message, 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                })
                .catch(() => Swal.fire('Error', 'Something went wrong', 'error'));
        });
    </script>
@endpush
