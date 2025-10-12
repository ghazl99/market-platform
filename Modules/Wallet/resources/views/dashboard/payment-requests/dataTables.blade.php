@foreach ($paymentRequests as $paymentRequest)
    <tr id="payment-row-{{ $paymentRequest->id }}">
        <td>
            <div class="user-info">
                <div>
                    <div class="user-name">{{ $paymentRequest->wallet->user->name ?? 'غير محدد' }}</div>
                    <div class="user-email">{{ $paymentRequest->wallet->user->email ?? 'غير محدد' }}</div>
                </div>
            </div>
        </td>
        <td>
            <div class="amount-text">
                <div class="original-amount">{{ number_format($paymentRequest->original_amount, 2) }}
                    {{ $paymentRequest->original_currency }}</div>
                <div class="usd-amount">= {{ number_format($paymentRequest->amount_usd, 2) }} USD</div>
                <div class="exchange-rate">سعر الصرف: {{ number_format($paymentRequest->exchange_rate, 4) }}</div>
            </div>
        </td>
        <td>
            <div class="payment-method">{{ $paymentRequest->wallet->store->name ?? 'غير محدد' }}</div>
        </td>
        <td>
            @php
                $statusClass = match ($paymentRequest->status) {
                    'pending' => 'pending',
                    'approved' => 'approved',
                    'rejected' => 'rejected',
                    default => 'pending',
                };
                $statusText = match ($paymentRequest->status) {
                    'pending' => __('في الانتظار'),
                    'approved' => __('مقبول'),
                    'rejected' => __('مرفوض'),
                    default => __('في الانتظار'),
                };
            @endphp
            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
        </td>
        <td>
            <div class="date-text">{{ $paymentRequest->created_at->format('Y-m-d H:i') }}</div>
        </td>
        <td>
            <div class="action-buttons">
                @if ($paymentRequest->status === 'pending')
                    <button class="action-btn approve"
                        onclick="openApprovalModal({{ $paymentRequest->id }}, 'approve')" title="{{ __('موافقة') }}">
                        <i class="fas fa-check"></i>
                        <span class="btn-text">{{ __('موافقة') }}</span>
                    </button>
                    <button class="action-btn reject" onclick="openApprovalModal({{ $paymentRequest->id }}, 'reject')"
                        title="{{ __('رفض') }}">
                        <i class="fas fa-times"></i>
                        <span class="btn-text">{{ __('رفض') }}</span>
                    </button>
                @else
                    <span class="action-btn view" style="background: #6b7280; cursor: default;"
                        title="{{ __('تم المعالجة') }}">
                        <i class="fas fa-check-circle"></i>
                        <span class="btn-text">{{ __('تم المعالجة') }}</span>
                    </span>
                @endif
            </div>
        </td>
    </tr>
@endforeach
