@extends('digital.themes.app')

@section('title', __('Order Details'))
@push('styles')
    <style>
        /* Order Details Page Specific Styles */
        .order-details-section {
            padding: 2rem 0;
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
            min-height: calc(100vh - 80px);
            position: relative;
        }

        .order-details-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(5, 150, 105, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(59, 130, 246, 0.03) 0%, transparent 50%);
            pointer-events: none;
        }

        .order-details-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
        }

        /* Order Details Content */
        .order-details-content {
            max-width: 800px;
            margin: 0 auto;
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
        }

        .order-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 2rem 0;
            text-align: center;
        }

        .order-status-section {
            margin-bottom: 2rem;
        }

        .order-status-section h5 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 1rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .status-chip.success {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .response-time {
            color: #f59e0b;
            font-size: 1rem;
            font-weight: 600;
            margin: 0.5rem 0 0 0;
        }

        .order-info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .info-card {
            border-radius: 12px;
            padding: 1.5rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: rgba(255, 255, 255, 0.3);
        }

        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .info-card.primary {
            background: var(--primary-color);
        }

        .info-card.secondary {
            background: var(--secondary-color);
        }

        .info-card.success {
            background: var(--success-color);
        }

        .info-card.warning {
            background: var(--warning-color);
        }

        .info-card.info {
            background: var(--info-color);
        }

        .card-content {
            flex: 1;
        }

        .card-label {
            font-size: 0.875rem;
            font-weight: 500;
            opacity: 0.9;
        }

        .card-value {
            font-size: 1rem;
            font-weight: 700;
            text-align: center;
        }

        .divider {
            border: none;
            height: 1px;
            background: var(--border-color);
            margin: 2rem 0;
        }

        .player-info {
            margin-bottom: 2rem;
        }

        .player-card {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .player-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: var(--primary-color);
        }

        .response-section {
            margin-bottom: 2rem;
        }

        .response-card {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .response-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: var(--primary-color);
        }

        .response-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 1rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .response-title i {
            color: var(--primary-color);
        }

        .response-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .response-text {
            flex: 1;
        }

        .response-text pre {
            background: var(--bg-primary);
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            margin: 0;
            font-family: 'Cairo', monospace;
            font-size: 0.9rem;
            color: var(--text-primary);
        }

        .copy-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .copy-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .print-btn {
            background: var(--warning-color);
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .print-btn:hover {
            background: var(--accent-dark);
            transform: translateY(-2px);
        }

        .cancel-btn {
            background: var(--text-light);
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: not-allowed;
            opacity: 0.5;
            transition: all 0.3s ease;
        }


        /* Responsive Design */
        @media (max-width: 768px) {
            .order-details-container {
                padding: 0 1rem;
            }

            .order-details-content {
                padding: 1.5rem;
            }

            .order-info-cards {
                grid-template-columns: 1fr;
            }

            .response-content {
                flex-direction: column;
                align-items: stretch;
            }

            .action-buttons {
                flex-direction: column;
            }
        }

        /* Loading Animation */
        .loading-shimmer {
            background: linear-gradient(90deg,
                    var(--bg-secondary) 25%,
                    rgba(255, 255, 255, 0.1) 50%,
                    var(--bg-secondary) 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }
    </style>
@endpush
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
        <section class="order-details-section">
            <div class="order-details-container">
                <!-- Order Details -->
                <div class="order-details-content">
                    <div class="order-header">
                        <h2>{{ __('Order Details') }}</h2>
                    </div>

                    <div class="order-status-section">
                        <h5>{{ __('Status') }}
                            <span class="status-chip success">
                                <i class="fas fa-check"></i>
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                        </h5>
                        <h5>{{ __('Payment Status') }}
                            <span class="status-chip success">
                                <i class="fas fa-check"></i>
                                {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                            </span>
                        </h5>
                        <h4 class="response-time">
                            مدة الاستجابة للطلب: 0 دقيقة و 1 ثانية
                        </h4>
                    </div>

                    <div class="order-info-cards">
                        <div class="info-card primary">
                            <div class="card-content">
                                <div class="card-label">{{ __('The Order') }}</div>
                            </div>
                            <div class="card-value">#{{ $order->id }}</div>
                        </div>

                        <div class="info-card secondary">
                            <div class="card-content">
                                <div class="card-label">{{ __('Product') }}</div>
                            </div>
                            <div class="card-value">{{ $order->items->first()->product->name ?? '-' }}</div>
                        </div>

                        <div class="info-card success">
                            <div class="card-content">
                                <div class="card-label">{{ __('Quantity') }}</div>
                            </div>
                            <div class="card-value">{{ $order->items->first()->quantity ?? 0 }}</div>
                        </div>

                        <div class="info-card warning">
                            <div class="card-content">
                                <div class="card-label">{{ __('Total') }}</div>
                            </div>
                            <div class="card-value">
                                {{ ($order->items->first()?->quantity ?? 0) * ($order->items->first()?->product?->price ?? 0) }}$
                            </div>
                        </div>


                        <div class="info-card info">
                            <div class="card-content">
                                <div class="card-label">التاريخ</div>
                            </div>
                            <div class="card-value">{{ $order->created_at_in_store_timezone->format('Y-m-d H:i:s') }}</div>
                        </div>
                    </div>

                    <hr class="divider">
                    @if ($order->store->type === 'digital')
                        <div class="player-info">
                            <div class="player-card">
                                <div class="card-content">
                                    <div class="card-label">{{ __('Player ID') }}</div>
                                </div>
                                <div class="card-value">{{ $order->items->first()?->player_id ?? '' }}</div>
                            </div>
                        </div>
                    @endif
                    <div class="response-section">
                        <div class="response-card">
                            <h3 class="response-title">
                                <i class="fas fa-reply"></i>
                                الرد
                            </h3>
                            <div class="response-content">
                                <div class="response-text">
                                    <pre>تمت بنجاح</pre>
                                </div>
                                <button class="copy-btn" onclick="copyResponse()">
                                    <i class="fas fa-copy"></i>
                                    نسخ
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <a href="#" class="print-btn">
                            <i class="fas fa-print"></i>
                            طباعة
                        </a>
                        <button class="cancel-btn" disabled>
                            <i class="fas fa-times"></i>
                            إلغاء الطلب
                        </button>
                    </div>
                </div>

            </div>
        </section>

    </div>
@endsection

