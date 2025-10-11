@extends('core::store.layouts.app')
@section('title', __('Add Balance'))
@push('styles')
    <style>
        /* Payment Details Page Styles */
        .payment-details-container {
            max-width: 800px;
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

        .payment-info-card {
            background: var(--bg-primary);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid var(--border-color);
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .payment-method-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .payment-method-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: var(--bg-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--border-color);
        }

        .payment-method-icon img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .payment-method-info h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .payment-method-info p {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .amount-section {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .amount-label {
            font-size: 1rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .amount-input-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .amount-input {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
            background: none;
            border: none;
            text-align: center;
            width: 200px;
            padding: 0.5rem;
            border-radius: 8px;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .amount-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .currency-selector {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--bg-secondary);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .currency-selector:hover {
            border-color: var(--primary-color);
        }

        .currency-select {
            background: none;
            border: none;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-primary);
            cursor: pointer;
            outline: none;
        }

        .exchange-rate {
            background: var(--bg-accent);
            border: 2px solid var(--primary-light);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .exchange-rate::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-primary);
        }

        .exchange-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .exchange-amount {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .exchange-rate-text {
            font-size: 0.8rem;
            color: var(--text-light);
        }

        .exchange-rate.hidden {
            display: none;
        }

        .final-amount-display {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-radius: 16px;
            padding: 2rem;
            margin-top: 1.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.3);
        }

        .final-amount-display::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://www.transparenttextures.com/patterns/diagmonds.png') repeat;
            opacity: 0.1;
            z-index: 0;
        }

        .final-amount-display.hidden {
            display: none;
        }

        .final-amount-label {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 0.75rem;
            position: relative;
            z-index: 1;
        }

        .final-amount-value {
            font-size: 3rem;
            font-weight: 900;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .final-amount-currency {
            font-size: 1.8rem;
            font-weight: 700;
            opacity: 0.8;
        }

        .final-amount-subtitle {
            font-size: 0.9rem;
            opacity: 0.8;
            position: relative;
            z-index: 1;
        }


        .transfer-details {
            background: var(--bg-primary);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .transfer-details::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .section-title i {
            color: var(--primary-color);
            font-size: 1.3rem;
        }

        .transfer-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-item {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 1.5rem;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .info-item:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .info-item::before {
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

        .info-item:hover::before {
            transform: scaleX(1);
        }

        .info-label {
            font-size: 0.95rem;
            color: var(--text-secondary);
            margin-bottom: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-label i {
            color: var(--primary-color);
            font-size: 0.9rem;
        }

        .info-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
            word-break: break-all;
            margin-bottom: 1rem;
            padding: 0.75rem;
            background: var(--bg-primary);
            border-radius: 8px;
            border: 1px solid var(--border-light);
            font-family: 'Cairo', monospace;
            letter-spacing: 0.5px;
        }

        .info-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .copy-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
            justify-content: center;
        }

        .copy-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .copy-btn:active {
            transform: translateY(0);
        }

        .copy-btn.copied {
            background: var(--success-color);
        }

        .copy-btn.copied i::before {
            content: '\f00c';
        }

        .qr-code-btn {
            background: var(--secondary-color);
            color: white;
            border: none;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .qr-code-btn:hover {
            background: var(--secondary-dark);
            transform: translateY(-1px);
        }

        .important-note {
            background: var(--bg-accent);
            border: 2px solid var(--primary-light);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            position: relative;
        }

        .important-note::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-primary);
        }

        .note-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .note-title i {
            color: var(--warning-color);
        }

        .note-content {
            color: var(--text-primary);
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .note-list {
            margin: 0.75rem 0;
            padding-right: 1.5rem;
        }

        .note-list li {
            margin-bottom: 0.5rem;
            color: var(--text-secondary);
        }

        .note-list li::marker {
            color: var(--primary-color);
        }

        .upload-section {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 2px dashed var(--border-color);
            text-align: center;
            transition: all 0.3s ease;
        }

        .upload-section:hover {
            border-color: var(--primary-color);
            background: var(--bg-accent);
        }

        .upload-section.dragover {
            border-color: var(--primary-color);
            background: var(--bg-accent);
        }

        .upload-icon {
            font-size: 3rem;
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        .upload-text {
            font-size: 1.1rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .upload-subtext {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .file-input {
            display: none;
        }

        .upload-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .upload-btn:hover {
            background: var(--primary-dark);
        }

        .file-preview {
            margin-top: 1rem;
            padding: 1rem;
            background: var(--bg-primary);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            display: none;
        }

        .file-preview.show {
            display: block;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .file-icon {
            font-size: 2rem;
            color: var(--primary-color);
        }

        .file-details h4 {
            margin: 0 0 0.25rem 0;
            color: var(--text-primary);
        }

        .file-details p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .remove-file {
            background: var(--error-color);
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 6px;
            cursor: pointer;
            margin-right: auto;
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
            .payment-details-container {
                padding: 1rem;
            }

            .payment-method-header {
                flex-direction: column;
                text-align: center;
            }

            .amount-input-group {
                flex-direction: column;
                gap: 0.5rem;
            }

            .amount-input {
                width: 100%;
                font-size: 1.5rem;
            }

            .currency-selector {
                width: 100%;
                justify-content: center;
            }

            .currency-select {
                font-size: 1rem;
            }

            .exchange-rate {
                margin-top: 0.5rem;
            }

            .exchange-amount {
                font-size: 1.2rem;
            }

            .final-amount-display {
                margin-top: 1rem;
                padding: 1.5rem;
            }

            .final-amount-value {
                font-size: 2rem;
            }

            .final-amount-currency {
                font-size: 1.2rem;
            }

            .transfer-info {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
@endpush
@section('content')
    <div class="payment-details-container">
        <div class="page-header">
            <h1 class="page-title">{{ __('Payment Details') }}</h1>
            <p class="page-subtitle">{{ __('Enter the amount and upload the transfer receipt to complete the process') }}</p>
        </div>

        @php $media = $paymentMethod->getFirstMedia('payment_method_images'); @endphp

        <div class="payment-info-card">
            <div class="payment-method-header">
                <div class="payment-method-icon">
                    @if ($paymentMethod->getFirstMediaUrl('image'))
                        <img src="{{ route('payment.methode.image', $media->id) }}" alt="{{ $paymentMethod->name }}">
                    @else
                        <img src="{{ asset('assets/img/payment.png') }}" alt="{{ $paymentMethod->name }}">
                    @endif
                </div>
                <div class="payment-method-info">
                    <h3>{{ $paymentMethod->name }}</h3>
                    <p>{{ $paymentMethod->description }}</p>
                </div>
            </div>

            <form action="{{ route('payment-request.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="amount-section">
                    <div class="amount-label">{{ __('Amount to Add') }}</div>
                    <div class="amount-input-group">
                        <input class="amount-input" type="number" id="amountInput" name="amount" placeholder="0.00"
                            min="1" step="0.01">

                        <div class="currency-selector">
                            <select class="currency-select" id="currencySelect" name="currency">
                                @php
                                    $currencies = $paymentMethod->currencies; // JSON
                                    $currentLang = app()->getLocale(); // 'ar' أو 'en'
                                    $currencyEn = $currencies['en'];
                                    $currencyAr = $currencies['ar'];
                                @endphp

                                @foreach ($currencyEn as $index => $code)
                                    <option value="{{ $code }}">
                                        {{ $currencyAr[$index] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="exchange-rate hidden" id="exchangeRate">
                        <div class="exchange-label">{{ __('Amount in USD') }}</div>
                        <div class="exchange-amount" id="exchangeAmount">$0.00</div>
                        <div class="exchange-rate-text" id="exchangeRateText">{{ __('Exchange rate: 1.00') }}</div>
                    </div>

                    <div class="final-amount-display hidden" id="convertedDiv">
                        <div class="final-amount-label">{{ __('Final Amount to Transfer') }}</div>
                        <div class="final-amount-value">
                            <span id="convertedAmount">0.00</span> <span class="final-amount-currency">$</span>
                        </div>
                        <div class="final-amount-subtitle">{{ __('This amount will be added to your wallet') }}</div>
                    </div>
                </div>

                <!-- Upload Section -->
                <div class="upload-section" id="uploadSection">
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="upload-text">{{ __('Upload Transfer Receipt') }}</div>
                    <div class="upload-subtext">{{ __('Drag the file here or click to select') }}</div>
                    <input type="file" name="receipt_image" class="file-input" id="fileInput" accept="image/*,.pdf"
                        multiple style="display:none;">

                    <button type="button" class="upload-btn" onclick="document.getElementById('fileInput').click()">
                        <i class="fas fa-plus"></i>
                        {{ __('Choose File') }}
                    </button>

                    <!-- Preview -->
                    <div id="previewContainer" class="preview-container"
                        style="margin-top:10px; display:flex; gap:10px; flex-wrap:wrap;"></div>
                </div>

                <!-- Hidden Wallet ID -->
                <input type="hidden" name="wallet_id" value="{{ Auth::user()->wallet->id }}">

                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">{{ __('Confirm Payment') }}</button>
                </div>
            </form>
        </div>

        <div class="transfer-details">
            <h3 class="section-title"><i class="fas fa-university"></i> {{ __('Transfer Details') }}</h3>
            <div class="transfer-info">
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-user"></i> {{ __('Recipient Name') }}</div>
                    <div class="info-value">{{ $paymentMethod->recipient_name }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label"><i class="fas fa-credit-card"></i> {{ __('Account Number') }}</div>
                    <div class="info-value">{{ $paymentMethod->account_number }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label"><i class="fas fa-building"></i> {{ __('Bank Name') }}</div>
                    <div class="info-value">{{ $paymentMethod->bank_name }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label"><i class="fas fa-hashtag"></i> {{ __('Transfer Number') }}</div>
                    <div class="info-value">{{ $paymentMethod->transfer_number }}</div>
                </div>
            </div>

            <div class="important-note">
                <div class="note-title"><i class="fas fa-exclamation-triangle"></i> {{ __('Important Instructions') }}</div>
                <div class="note-content">
                    <ul class="note-list">
                        @foreach ($paymentMethod->instructions as $instruction)
                            <li>{{ $instruction }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountInput = document.getElementById('amountInput');
            const currencySelect = document.getElementById('currencySelect');
            const convertedDiv = document.getElementById('convertedDiv');
            const convertedAmount = document.getElementById('convertedAmount');

            async function convertAmount() {
                const amount = parseFloat(amountInput.value);
                const from = currencySelect.value;

                if (!amount || amount <= 0 || !from) {
                    convertedDiv.classList.add('hidden');
                    return;
                }

                try {
                    const res = await fetch('{{ route('convert.currency') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            amount,
                            from
                        })
                    });

                    const data = await res.json();
                    const convertedNumber = parseFloat(data.converted);

                    convertedAmount.textContent = isNaN(convertedNumber) ? '0.00' : convertedNumber.toFixed(2);
                    convertedDiv.classList.remove('hidden');
                } catch (err) {
                    console.error('Error converting currency:', err);
                    convertedAmount.textContent = '0.00';
                    convertedDiv.classList.remove('hidden');
                }
            }

            amountInput.addEventListener('input', convertAmount);
            currencySelect.addEventListener('change', convertAmount);
        });
    </script>
    <script>
        document.getElementById('fileInput').addEventListener('change', function(event) {
            const previewContainer = document.getElementById('previewContainer');
            previewContainer.innerHTML = ''; // امسح أي معاينات قديمة

            const files = event.target.files;
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.width = '100px';
                        img.style.height = '100px';
                        img.style.objectFit = 'cover';
                        img.style.border = '1px solid #ccc';
                        img.style.borderRadius = '5px';
                        previewContainer.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                } else {
                    const div = document.createElement('div');
                    div.textContent = file.name;
                    div.style.padding = '10px';
                    div.style.border = '1px solid #ccc';
                    div.style.borderRadius = '5px';
                    div.style.background = '#f9f9f9';
                    previewContainer.appendChild(div);
                }
            });
        });
    </script>
@endpush
