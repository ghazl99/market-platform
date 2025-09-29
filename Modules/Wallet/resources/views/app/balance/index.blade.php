@extends('core::store.layouts.app')
@section('title', __('Balance'))
@push('styles')
    <style>
        /* Add Balance Page Styles */
        .add-balance-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 1rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-subtitle {
            font-size: 1.1rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }

        .current-balance {
            background: var(--bg-secondary);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            border: 1px solid var(--border-color);
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .balance-label {
            font-size: 1rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .balance-amount {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .balance-currency {
            font-size: 1.2rem;
            color: var(--text-light);
        }

        /* Tabs System */
        .tabs-container {
            background: var(--bg-primary);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .tabs-header {
            display: flex;
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
        }

        .tab-button {
            flex: 1;
            padding: 1.5rem 2rem;
            background: none;
            border: none;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .tab-button:hover {
            background: var(--bg-tertiary);
            color: var(--text-primary);
        }

        .tab-button.active {
            background: var(--bg-primary);
            color: var(--primary-color);
        }

        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-primary);
        }

        .tab-content {
            display: none;
            padding: 2rem;
        }

        .tab-content.active {
            display: block;
        }

        .tab-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tab-title i {
            color: var(--primary-color);
        }

        .tab-description {
            font-size: 1rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .payment-methods-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .payment-method-card {
            background: var(--bg-secondary);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            text-decoration: none;
            color: var(--text-primary);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .payment-method-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .payment-method-card:hover::before {
            transform: scaleX(1);
        }

        .payment-method-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .payment-method-card.selected {
            border-color: var(--primary-color);
            background: var(--bg-accent);
        }

        .payment-method-card.selected::before {
            transform: scaleX(1);
        }

        .payment-method-image {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-bottom: 1rem;
            border-radius: 8px;
            background: var(--bg-primary);
            padding: 0.5rem;
            border: 1px solid var(--border-light);
        }

        .payment-method-name {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border: 2px solid var(--border-color);
        }

        .btn-secondary:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .add-balance-container {
                padding: 1rem;
            }

            .tabs-header {
                flex-direction: column;
            }

            .tab-button {
                padding: 1rem;
                font-size: 1rem;
            }

            .tab-content {
                padding: 1.5rem;
            }

            .payment-methods-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
@endpush
@section('content')
    <div class="add-balance-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">إضافة رصيد</h1>
            <p class="page-subtitle">اختر طريقة الدفع المناسبة لإضافة رصيد إلى محفظتك</p>
        </div>

        <!-- Current Balance -->
        <div class="current-balance">
            <div class="balance-label">الرصيد الحالي</div>
            <div class="balance-amount">1,250.50</div>
            <div class="balance-currency">دولار أمريكي</div>
        </div>

        <!-- Tabs System -->
        <div class="tabs-container">
            <div class="tabs-header">
                <button class="tab-button active" data-tab="manual">
                    <i class="fas fa-hand-holding-usd"></i>
                    دفع يدوي
                </button>
                <button class="tab-button" data-tab="electronic">
                    <i class="fas fa-credit-card"></i>
                    دفع إلكتروني
                </button>
            </div>

            <!-- Manual Payment Tab -->
            <div class="tab-content active" id="manualTab">
                <h3 class="tab-title">
                    <i class="fas fa-file-invoice"></i>
                    طرق الدفع اليدوية
                </h3>
                <p class="tab-description">
                    اختر طريقة الدفع اليدوية المناسبة لك. سيتم إرسال طلب إضافة الرصيد للمدير للمراجعة والموافقة.
                </p>
                <div class="payment-methods-grid">
                    <a href="payment-5" class="payment-method-card" data-gateway="binance">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736503888.png" alt="بينانس">
                        <div class="payment-method-name">بينانس</div>
                    </a>
                    <a href="payment-6" class="payment-method-card" data-gateway="usdt">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736506495.png" alt="USDT TRC20">
                        <div class="payment-method-name">USDT TRC20</div>
                    </a>
                    <a href="payment-7" class="payment-method-card" data-gateway="zain">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736507124.png"
                            alt="زين كاش العراق">
                        <div class="payment-method-name">زين كاش العراق</div>
                    </a>
                    <a href="payment-8" class="payment-method-card" data-gateway="turkey">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736509159.png" alt="بنوك تركيا">
                        <div class="payment-method-name">بنوك تركيا</div>
                    </a>
                    <a href="payment-9" class="payment-method-card" data-gateway="uae">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736509628.png"
                            alt="بنوك الامارت">
                        <div class="payment-method-name">بنوك الامارت</div>
                    </a>
                    <a href="payment-10" class="payment-method-card" data-gateway="wish">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736510139.png"
                            alt="ويش مني لبنان">
                        <div class="payment-method-name">ويش مني لبنان</div>
                    </a>
                    <a href="payment-11" class="payment-method-card" data-gateway="paypal">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736511909.png"
                            alt="باي بال يورو">
                        <div class="payment-method-name">باي بال يورو</div>
                    </a>
                    <a href="payment-12" class="payment-method-card" data-gateway="vodafone">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736512284.png" alt="فودافون مصر">
                        <div class="payment-method-name">فودافون مصر</div>
                    </a>
                    <a href="payment-13" class="payment-method-card" data-gateway="morocco">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736513721.png" alt="بنوك المغرب">
                        <div class="payment-method-name">بنوك المغرب</div>
                    </a>
                    <a href="payment-14" class="payment-method-card" data-gateway="brady">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736514212.png" alt="بريدي موب">
                        <div class="payment-method-name">بريدي موب</div>
                    </a>
                    <a href="payment-15" class="payment-method-card" data-gateway="syria">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736514947.png"
                            alt="سوريا حوالات">
                        <div class="payment-method-name">سوريا حوالات</div>
                    </a>
                    <a href="payment-16" class="payment-method-card" data-gateway="europe">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736516312.png"
                            alt="بنوك اوربا">
                        <div class="payment-method-name">بنوك اوربا</div>
                    </a>
                    <a href="payment-17" class="payment-method-card" data-gateway="jordan">
                        <img class="payment-method-image" src="https://qu-card.com/images/1737319096.png"
                            alt="حوالات الاردن">
                        <div class="payment-method-name">حوالات الاردن</div>
                    </a>
                </div>
            </div>

            <!-- Electronic Payment Tab -->
            <div class="tab-content" id="electronicTab">
                <h3 class="tab-title">
                    <i class="fas fa-shield-alt"></i>
                    بوابات الدفع الإلكترونية
                </h3>
                <p class="tab-description">
                    اختر بوابة الدفع الإلكترونية المناسبة لك. سيتم توجيهك للدفع الفوري عبر البوابة المختارة.
                </p>
                <div class="payment-methods-grid">
                    <a href="payment-5" class="payment-method-card" data-gateway="binance">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736503888.png" alt="بينانس">
                        <div class="payment-method-name">بينانس</div>
                    </a>
                    <a href="payment-6" class="payment-method-card" data-gateway="usdt">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736506495.png"
                            alt="USDT TRC20">
                        <div class="payment-method-name">USDT TRC20</div>
                    </a>
                    <a href="payment-7" class="payment-method-card" data-gateway="zain">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736507124.png"
                            alt="زين كاش العراق">
                        <div class="payment-method-name">زين كاش العراق</div>
                    </a>
                    <a href="payment-8" class="payment-method-card" data-gateway="turkey">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736509159.png"
                            alt="بنوك تركيا">
                        <div class="payment-method-name">بنوك تركيا</div>
                    </a>
                    <a href="payment-9" class="payment-method-card" data-gateway="uae">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736509628.png"
                            alt="بنوك الامارت">
                        <div class="payment-method-name">بنوك الامارت</div>
                    </a>
                    <a href="payment-10" class="payment-method-card" data-gateway="wish">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736510139.png"
                            alt="ويش مني لبنان">
                        <div class="payment-method-name">ويش مني لبنان</div>
                    </a>
                    <a href="payment-11" class="payment-method-card" data-gateway="paypal">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736511909.png"
                            alt="باي بال يورو">
                        <div class="payment-method-name">باي بال يورو</div>
                    </a>
                    <a href="payment-12" class="payment-method-card" data-gateway="vodafone">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736512284.png"
                            alt="فودافون مصر">
                        <div class="payment-method-name">فودافون مصر</div>
                    </a>
                    <a href="payment-13" class="payment-method-card" data-gateway="morocco">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736513721.png"
                            alt="بنوك المغرب">
                        <div class="payment-method-name">بنوك المغرب</div>
                    </a>
                    <a href="payment-14" class="payment-method-card" data-gateway="brady">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736514212.png"
                            alt="بريدي موب">
                        <div class="payment-method-name">بريدي موب</div>
                    </a>
                    <a href="payment-15" class="payment-method-card" data-gateway="syria">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736514947.png"
                            alt="سوريا حوالات">
                        <div class="payment-method-name">سوريا حوالات</div>
                    </a>
                    <a href="payment-16" class="payment-method-card" data-gateway="europe">
                        <img class="payment-method-image" src="https://qu-card.com/images/1736516312.png"
                            alt="بنوك اوربا">
                        <div class="payment-method-name">بنوك اوربا</div>
                    </a>
                    <a href="payment-17" class="payment-method-card" data-gateway="jordan">
                        <img class="payment-method-image" src="https://qu-card.com/images/1737319096.png"
                            alt="حوالات الاردن">
                        <div class="payment-method-name">حوالات الاردن</div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button class="btn btn-secondary" onclick="goBack()">
                <i class="fas fa-arrow-right"></i>
                العودة
            </button>
        </div>
    </div>
@endsection
