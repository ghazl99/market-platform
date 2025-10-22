@extends('themes.app')
@section('title', __('Payment Requests'))
@push('styles')
    <style>
        :root {
            --primary-color: #7C3AED;
            /* Purple 600 */
            --primary-dark: #5B21B6;
            /* Purple 800 */
            --primary-light: #C4B5FD;
            /* Purple 300 */
            --secondary-color: #8B5CF6;
            --secondary-dark: #6D28D9;
            --success-color: #10B981;
            --error-color: #EF4444;
            --warning-color: #F59E0B;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
            --text-light: #9CA3AF;
            --bg-primary: #F9FAFB;
            --bg-secondary: #FFFFFF;
            --bg-accent: #F3E8FF;
            --border-color: #E5E7EB;
            --border-light: #EDE9FE;
            --gradient-primary: linear-gradient(135deg, #8B5CF6, #7C3AED);
        }

        .payment-requests-section {
            padding: 2rem 0;
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
            min-height: calc(100vh - 80px);
        }

        .requests-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .requests-header {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
        }

        .requests-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .requests-list {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
        }

        .request-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            margin-bottom: 1rem;
            background: var(--bg-secondary);
            border-radius: 16px;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .request-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-color);
        }

        .request-info {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .request-info div {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .request-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .request-status.accepted {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .request-status.pending {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .request-status.rejected {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .request-date {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .empty-state p {
            font-size: 1rem;
            margin: 0;
        }
    </style>
@endpush

@section('content')
    <div class="payment-requests-section">
        <div class="requests-container">

            <!-- Page Header -->
            <div class="requests-header">
                <h2 class="requests-title">{{ __('Payment Requests') }}</h2>
            </div>

            <!-- Requests List -->
            <div class="requests-list">
                @forelse($paymentRequests as $request)
                    <div class="request-item">
                        <div class="request-info">
                            <div>{{ __('Original:') }} {{ $request->original_amount }} {{ $request->original_currency }}
                            </div>
                            <div>{{ __('Exchange Rate:') }} {{ $request->exchange_rate }}</div>
                            <div>{{ __('USD:') }} {{ $request->amount_usd }} $</div>
                        </div>
                        @php
                            $statusClass = match ($request->status) {
                                'pending' => 'pending',
                                'approved' => 'accepted',
                                'rejected' => 'rejected',
                                default => 'pending',
                            };
                        @endphp
                        <div class="request-status {{ $statusClass }}">
                            @if ($statusClass == 'accepted')
                                <i class="fas fa-check-circle"></i>
                            @elseif($statusClass == 'rejected')
                                <i class="fas fa-times-circle"></i>
                            @elseif($statusClass == 'pending')
                                <i class="fas fa-clock"></i>
                            @endif
                            <span>{{ $statusLabels[$request->status] ?? $request->status }}</span>
                        </div>
                        <div class="request-date">{{ $request->created_at->format('Y-m-d H:i:s') }}</div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-wallet"></i>
                        <h3>{{ __('No payment requests') }}</h3>
                        <p>{{ __('There are no payment requests yet.') }}</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                @if ($paymentRequests->hasPages())
                    {{ $paymentRequests->links() }}
                @endif
            </div>

        </div>
    </div>
@endsection
