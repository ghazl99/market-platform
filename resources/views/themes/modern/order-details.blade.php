@extends('themes.app')

@section('title', __('Order Details'))
@push('styles')
    <style>
        /* Order Details Page Specific Styles */
        .order-details-section {
            padding: 2rem 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
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
                radial-gradient(circle at 20% 80%, rgba(102, 126, 234, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(118, 75, 162, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        .order-details-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
        }

        /* Back Button */
        .back-button {
            background: #ffffff;
            border: 2px solid #e5e7eb;
            color: #667eea;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-button:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        /* Order Details Layout */
        .order-details-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
        }

        /* Main Order Info */
        .order-main {
            background: #ffffff;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .order-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .order-id {
            font-size: 1rem;
            color: #666;
        }

        .order-status {
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .order-status.pending {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .order-status.processing {
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }

        .order-status.completed {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }

        .order-status.cancelled {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        /* Order Product */
        .order-product {
            display: grid;
            grid-template-columns: 120px 1fr auto;
            gap: 1.5rem;
            align-items: center;
            padding: 1.5rem;
            background: rgba(102, 126, 234, 0.05);
            border-radius: 15px;
            margin-bottom: 2rem;
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .product-image {
            width: 120px;
            height: 120px;
            border-radius: 15px;
            object-fit: cover;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .product-details {
            flex: 1;
        }

        .product-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .product-description {
            font-size: 1rem;
            color: #666;
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        .product-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .meta-label {
            font-size: 0.8rem;
            color: #999;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .meta-value {
            font-size: 0.9rem;
            color: #333;
            font-weight: 600;
        }

        .product-price {
            text-align: left;
        }

        .price-amount {
            font-size: 2rem;
            font-weight: 800;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .price-label {
            font-size: 0.9rem;
            color: #666;
        }

        /* Order Timeline */
        .order-timeline {
            background: #ffffff;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(102, 126, 234, 0.1);
            margin-bottom: 2rem;
        }

        .timeline-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1.5rem;
        }

        .timeline-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f1f5f9;
            position: relative;
        }

        .timeline-item:last-child {
            border-bottom: none;
        }

        .timeline-item.completed {
            opacity: 1;
        }

        .timeline-item.current {
            opacity: 1;
        }

        .timeline-item.pending {
            opacity: 0.6;
        }

        .timeline-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 1rem;
            font-size: 1rem;
            color: white;
            position: relative;
            z-index: 2;
        }

        .timeline-icon.completed {
            background: linear-gradient(135deg, #4ade80, #22c55e);
        }

        .timeline-icon.current {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            animation: pulse 2s infinite;
        }

        .timeline-icon.pending {
            background: #e5e7eb;
            color: #999;
        }

        .timeline-content {
            flex: 1;
        }

        .timeline-title-text {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .timeline-description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.25rem;
        }

        .timeline-time {
            font-size: 0.8rem;
            color: #999;
        }

        /* Order Summary */
        .order-summary {
            background: #ffffff;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(102, 126, 234, 0.1);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .summary-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1.5rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .summary-item:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 1.1rem;
            color: #333;
        }

        .summary-label {
            color: #666;
        }

        .summary-value {
            color: #333;
            font-weight: 600;
        }

        .summary-total {
            background: rgba(102, 126, 234, 0.05);
            padding: 1rem;
            border-radius: 10px;
            margin-top: 1rem;
        }

        .summary-total .summary-label {
            color: #667eea;
            font-weight: 700;
        }

        .summary-total .summary-value {
            color: #667eea;
            font-weight: 800;
            font-size: 1.2rem;
        }

        /* Order Actions */
        .order-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-action {
            flex: 1;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }

        .btn-danger {
            background: transparent;
            color: #ef4444;
            border: 2px solid #ef4444;
        }

        .btn-danger:hover {
            background: #ef4444;
            color: white;
        }

        /* Customer Support */
        .support-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 20px;
            padding: 2rem;
            margin-top: 2rem;
            text-align: center;
        }

        .support-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .support-description {
            font-size: 1rem;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }

        .support-button {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 0.75rem 2rem;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .support-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .order-details-container {
                padding: 0 1rem;
            }

            .order-details-layout {
                grid-template-columns: 1fr;
            }

            .order-summary {
                position: static;
            }

            .order-product {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .product-image {
                margin: 0 auto;
            }

            .product-price {
                text-align: center;
            }

            .product-meta {
                grid-template-columns: 1fr;
            }

            .order-actions {
                flex-direction: column;
            }
        }
    </style>
@endpush
@section('content')
    <!-- Order Details Section -->
    <section class="order-details-section">
        <div class="order-details-container">
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
            <!-- Order Details Layout -->
            <div class="order-details-layout">
                <!-- Main Order Info -->
                <div class="order-main">
                    <!-- Order Header -->
                    <div class="order-header">
                        <div>
                            <h1 class="order-title">{{ $order->items->first()->product->name ?? '-' }}</h1>
                            <p class="order-id"> #{{ $order->id }}</p>
                        </div>
                        <div class="order-status processing"> {{ $statusLabels[$order->status] ?? $order->status }}</div>
                    </div>

                    <!-- Order Product -->
                    <div class="order-product">
                        <img src="https://1click-pay1.com/images/1744470524.png" alt="رصيد واتساب" class="product-image">
                        <div class="product-details">
                            <h2 class="product-name"> {{ $order->items->first()->product->name ?? '-' }}</h2>
                            <p class="product-description">{{ $order->items->first()->product->description ?? '-' }}</p>
                            <div class="product-meta">
                                <div class="meta-item">
                                    <span class="meta-label">{{ __('Quantity') }}</span>
                                    <span class="meta-value">{{ $order->items->first()->quantity ?? 0 }}</span>
                                </div>
                                @if ($order->store->type === 'digital')
                                    <div class="meta-item">
                                        <span class="meta-label">{{ __('Player ID') }} </span>
                                        <span class="meta-value">{{ $order->items->first()?->player_id ?? '' }}</span>
                                    </div>
                                @endif

                                <div class="meta-item">
                                    <span class="meta-label">تاريخ الطلب</span>
                                    <span
                                        class="meta-value">{{ $order->created_at_in_store_timezone->format('Y-m-d H:i:s') }}</span>
                                </div>
                                {{-- <div class="meta-item">
                                    <span class="meta-label">طريقة الدفع</span>
                                    <span class="meta-value">محفظة ون كليك</span>
                                </div> --}}
                            </div>
                        </div>
                        <div class="product-price">
                            <div class="price-amount">{{ ($order->items->first()?->quantity ?? 0) * ($order->items->first()?->product?->price ?? 0) }}$</div>
                            <div class="price-label">{{ __('Total') }} </div>
                        </div>
                    </div>

                    <!-- Order Timeline -->
                    {{-- <div class="order-timeline">
                        <h2 class="timeline-title">مسار الطلب</h2>

                        <div class="timeline-item completed">
                            <div class="timeline-icon completed">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h3 class="timeline-title-text">تم إنشاء الطلب</h3>
                                <p class="timeline-description">تم إنشاء الطلب بنجاح وانتظار الدفع</p>
                                <div class="timeline-time">اليوم، 2:30 م</div>
                            </div>
                        </div>

                        <div class="timeline-item completed">
                            <div class="timeline-icon completed">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="timeline-content">
                                <h3 class="timeline-title-text">تم الدفع</h3>
                                <p class="timeline-description">تم تأكيد الدفع من محفظة ون كليك</p>
                                <div class="timeline-time">اليوم، 2:32 م</div>
                            </div>
                        </div>

                        <div class="timeline-item current">
                            <div class="timeline-icon current">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="timeline-content">
                                <h3 class="timeline-title-text">قيد المعالجة</h3>
                                <p class="timeline-description">جاري معالجة الطلب وإرسال الرصيد</p>
                                <div class="timeline-time">اليوم، 2:35 م</div>
                            </div>
                        </div>

                        <div class="timeline-item pending">
                            <div class="timeline-icon pending">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <div class="timeline-content">
                                <h3 class="timeline-title-text">تم الإرسال</h3>
                                <p class="timeline-description">سيتم إرسال الرصيد إلى رقم الهاتف المحدد</p>
                                <div class="timeline-time">قريباً</div>
                            </div>
                        </div>

                        <div class="timeline-item pending">
                            <div class="timeline-icon pending">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="timeline-content">
                                <h3 class="timeline-title-text">مكتمل</h3>
                                <p class="timeline-description">تم تسليم الرصيد بنجاح</p>
                                <div class="timeline-time">قريباً</div>
                            </div>
                        </div>
                    </div> --}}

                    {{-- <!-- Order Actions -->
                    <div class="order-actions">
                        <button class="btn-action btn-primary" onclick="trackOrder()">تتبع الطلب</button>
                        <button class="btn-action btn-secondary" onclick="downloadInvoice()">تحميل الفاتورة</button>
                        <button class="btn-action btn-danger" onclick="cancelOrder()">إلغاء الطلب</button>
                    </div> --}}
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <h2 class="summary-title">ملخص الطلب</h2>

                    <div class="summary-item">
                        <span class="summary-label">سعر المنتج</span>
                        <span class="summary-value">{{ $order->items->first()->product->price ?? '-' }} $</span>
                    </div>

                    <div class="summary-item">
                        <span class="summary-label">رسوم الخدمة</span>
                        <span class="summary-value">$0.00</span>
                    </div>

                    <div class="summary-item">
                        <span class="summary-label">الضريبة</span>
                        <span class="summary-value">$0.00</span>
                    </div>

                    <div class="summary-item">
                        <span class="summary-label">رسوم المعالجة</span>
                        <span class="summary-value">$0.00</span>
                    </div>

                    <div class="summary-total">
                        <div class="summary-item">
                            <span class="summary-label">المجموع</span>
                            <span class="summary-value">{{ ($order->items->first()?->quantity ?? 0) * ($order->items->first()?->product?->price ?? 0) }} $</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
