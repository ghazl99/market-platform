@extends('digital.themes.app')

@section('title', __('Order Details'))
@push('styles')

    <style>
        /* Order Details Page Styles */
        .order-details-section {
            padding: 2rem 0;
            min-height: calc(100vh - 80px);
        }

        .order-details-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Order Details Layout */
        .order-details-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .order-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .back-btn {
            padding: 0.75rem 1.5rem;
            background: var(--card-bg);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .back-btn:hover {
            background: var(--gradient-primary);
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .details-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 5px 20px var(--shadow-color);
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .order-status-timeline {
            position: relative;
            padding: 1rem 0;
        }

        .timeline-item {
            display: flex;
            gap: 1rem;
            padding: 1rem 0;
            position: relative;
            padding-right: 2rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            right: 20px;
            top: 0;
            bottom: -1rem;
            width: 2px;
            background: var(--border-color);
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.1rem;
            border: 2px solid var(--border-color);
            background: var(--card-bg);
            color: var(--text-secondary);
        }

        .timeline-item.active .timeline-icon {
            background: var(--gradient-primary);
            color: white;
            border-color: var(--primary-color);
        }

        .timeline-item.completed .timeline-icon {
            background: var(--success-color);
            color: white;
            border-color: var(--success-color);
        }

        .timeline-content h4 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .timeline-content p {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .info-row {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: var(--text-primary);
        }

        .info-value {
            color: var(--text-secondary);
        }

        /* Order Product */
        .order-product {
            display: grid;
            grid-template-columns: 120px 1fr auto;
            gap: 1.5rem;
            align-items: center;
            padding: 1.5rem;
            background: rgba(255, 133, 51, 0.05);
            border-radius: 15px;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 133, 51, 0.1);
        }

        .product-image {
            width: 120px;
            height: 120px;
            border-radius: 15px;
            object-fit: cover;
            box-shadow: 0 4px 15px var(--shadow-color);
        }

        .product-details {
            flex: 1;
        }

        .product-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .product-description {
            font-size: 1rem;
            color: var(--text-secondary);
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
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .meta-value {
            font-size: 0.9rem;
            color: var(--text-primary);
            font-weight: 600;
        }

        .product-price {
            text-align: left;
        }

        .price-amount {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .price-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .order-summary-card {
            background: var(--gradient-primary);
            border-radius: 20px;
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
        }

        /* Order Summary Sidebar */
        .order-summary {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 5px 20px var(--shadow-color);
            border: 1px solid var(--border-color);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .summary-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .summary-row:last-child {
            border-bottom: none;
            font-size: 1.3rem;
            font-weight: 800;
            padding-top: 1rem;
        }

        .summary-label {
            opacity: 0.9;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }



        .action-btn.primary {
            background: var(--gradient-primary);
            color: white;
        }

        .action-btn.secondary {
            background: var(--card-bg);
            color: var(--text-primary);
            border: 2px solid var(--border-color);
        }

        .action-btn.danger {
            background: transparent;
            color: var(--danger-color);
            border: 2px solid var(--danger-color);
        }

        .action-btn.danger:hover {
            background: var(--danger-color);
            color: white;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px var(--shadow-color);
        }

        /* Customer Support */
        .support-card {
            background: var(--gradient-primary);
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

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: var(--text-secondary);
        }

        .summary-value {
            color: var(--text-primary);
            font-weight: 600;
        }

        .summary-total {
            background: rgba(255, 133, 51, 0.05);
            padding: 1rem;
            border-radius: 10px;
            margin-top: 1rem;
        }

        .summary-total .summary-label {
            color: var(--primary-color);
            font-weight: 700;
        }

        .summary-total .summary-value {
            color: var(--primary-color);
            font-weight: 800;
            font-size: 1.2rem;
        }

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

            .order-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .order-header h1 {
                font-size: 2rem;
            }

            .info-row {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

        }

        @media (max-width: 480px) {
            .order-details-container {
                padding: 0 1rem;
            }

            .details-card {
                padding: 1.5rem;
            }
        }
    </style>
@endpush
@section('content')
    <main class="main-content-adjust">
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
                            <span style="padding: 0.75rem 1.5rem; border-radius: 25px; font-size: 0.9rem; font-weight: 600; background: rgba(60, 229, 81, 0.1); color: var(--success-color);">{{ $statusLabels[$order->status] ?? $order->status }}</span>
                        </div>

                        <!-- Order Product -->
                        <div class="order-product">
                            @php $media =  $order->items->first()->product->getFirstMedia('product_images'); @endphp

                            @if ($media)
                                <img src="{{ route('product.image', $media->id) }}" alt="alt="{{  $order->items->first()->product->name ?? '' }}""
                                    class="product-image">
                            @endif
                            <div class="product-details">
                                <h2 class="product-name"> {{ $order->items->first()->product->name ?? '-' }}</h2>
                                <p class="product-description">
                                    {{ $order->items->first()->product->description ?? '-' }}
                                </p>
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
                                <div class="price-amount">
                                    {{ ($order->items->first()?->quantity ?? 0) * ($order->items->first()?->product?->price ?? 0) }}$
                                </div>
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
                                <span
                                    class="summary-value">{{ ($order->items->first()?->quantity ?? 0) * ($order->items->first()?->product?->price ?? 0) }}
                                    $</span>
                            </div>
                        </div>
                        <!-- Customer Support -->
                        <div class="support-card">
                            <h3 class="support-title">تحتاج مساعدة؟</h3>
                            <p class="support-description">فريق الدعم متاح على مدار الساعة لمساعدتك</p>
                            <button class="support-button" onclick="contactSupport()">
                                <i class="fas fa-headset"></i>
                                اتصل بالدعم
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
