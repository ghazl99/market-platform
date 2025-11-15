@extends('core::dashboard.'. current_store()->type .'.layouts.app')

@section('title', __('Edit Payment Method'))

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('styles')
    <style>
        .payment-method-form-container {
            padding: 2rem;
            background: var(--bg-primary);
            min-height: 100vh;
        }

        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .form-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .close-button {
            background: #ef4444;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .close-button:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        .form-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--bg-secondary);
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--bg-secondary);
            color: var(--text-primary);
            font-size: 1rem;
            cursor: pointer;
        }

        /* Multi-Select Currency Styles */
        .multi-select-container {
            position: relative;
        }

        .selected-currencies {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            min-height: 40px;
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--bg-secondary);
        }

        .currency-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            background: var(--primary-color);
            color: white;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .remove-currency {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 1rem;
            padding: 0;
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.2s;
        }

        .remove-currency:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .currency-input-container {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .currency-input-container .form-input {
            flex: 1;
            margin-bottom: 0;
        }

        .add-currency-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 45px;
        }

        .add-currency-btn:hover {
            background: #047857;
            transform: translateY(-1px);
        }

        .add-currency-btn i {
            pointer-events: none;
        }

        .currency-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
            display: none;
        }

        .currency-suggestions.show {
            display: block;
        }

        .suggestion-group {
            padding: 0.75rem;
            border-bottom: 1px solid var(--border-color);
        }

        .suggestion-group:last-child {
            border-bottom: none;
        }

        .suggestion-group h6 {
            margin: 0 0 0.5rem 0;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .suggestion-items {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
        }

        .suggestion-item {
            padding: 0.25rem 0.5rem;
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.2s ease;
            color: var(--text-primary);
        }

        .suggestion-item:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .suggestion-item.selected {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .image-upload-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .image-preview {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid var(--border-color);
            background: var(--bg-secondary);
        }

        .upload-button {
            background: #f59e0b;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .upload-button:hover {
            background: #d97706;
            transform: translateY(-2px);
        }

        .file-input {
            display: none;
        }

        .rich-text-editor {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            min-height: 200px;
        }

        .editor-toolbar {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-primary);
            border-radius: 8px 8px 0 0;
        }

        .toolbar-button {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .toolbar-button:hover {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .editor-content {
            padding: 1rem;
            min-height: 150px;
            color: var(--text-primary);
        }

        .editor-content:focus {
            outline: none;
        }

        .form-actions {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }

        .save-button {
            background: #f59e0b;
            color: white;
            padding: 1rem 3rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .save-button:hover {
            background: #d97706;
            transform: translateY(-2px);
        }

        .save-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .payment-method-form-container {
                padding: 1rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .form-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .image-upload-section {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
@endpush

@section('content')
    <div class="payment-method-form-container">
        <!-- Form Header -->
        <div class="form-header">
            <h1 class="form-title">{{ __('Edit Payment Method') }}</h1>
            <a href="{{ route('dashboard.payment-methods.index') }}" class="close-button">
                <i class="fas fa-times"></i>
            </a>
        </div>

        <!-- Form Content -->
        <div class="form-content">
            <form action="{{ route('dashboard.payment-methods.update', $paymentMethod) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <!-- Name Field -->
                    <div class="form-group">
                        <label for="name" class="form-label">{{ __('Name') }}</label>
                        <input type="text" id="name" name="name" class="form-input"
                            value="{{ old('name', $paymentMethod->getTranslation('name', app()->getLocale())) }}" required
                            placeholder="{{ __('Enter payment method name') }}">
                        @error('name')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Currency Field -->
                    <div class="form-group">
                        <label for="currencies" class="form-label">{{ __('Currencies') }}</label>
                        <div class="multi-select-container">
                            <div class="selected-currencies" id="selected-currencies">
                                @php
                                    // جلب البيانات القديمة من النموذج (currencies[])
                                    $oldCurrencies = old('currencies', []);

                                    // إذا لم تكن هناك بيانات قديمة، استخدم البيانات المحفوظة
                                    if (empty($oldCurrencies)) {
                                        if (
                                            isset($paymentMethod->currencies['en']) &&
                                            is_array($paymentMethod->currencies['en'])
                                        ) {
                                            $currentCurrencies = $paymentMethod->currencies['en'];
                                        } elseif (
                                            isset($paymentMethod->currencies['en']) &&
                                            is_string($paymentMethod->currencies['en'])
                                        ) {
                                            // إذا كانت سلسلة نصية، حولها إلى مصفوفة
                                            $currentCurrencies = explode(',', $paymentMethod->currencies['en']);
                                            $currentCurrencies = array_map('trim', $currentCurrencies);
                                            $currentCurrencies = array_filter($currentCurrencies); // إزالة القيم الفارغة
                                        } else {
                                            // إذا لم تكن هناك عملات محفوظة، استخدم العملة الافتراضية
                                            // محاولة الحصول على أول عملة من currencies أو استخدام USD كـ default
                                            $defaultCurrency = 'USD';
                                            if (isset($paymentMethod->currencies) && is_array($paymentMethod->currencies)) {
                                                if (isset($paymentMethod->currencies['en']) && is_array($paymentMethod->currencies['en']) && !empty($paymentMethod->currencies['en'])) {
                                                    $defaultCurrency = $paymentMethod->currencies['en'][0];
                                                } elseif (isset($paymentMethod->currencies['ar']) && is_array($paymentMethod->currencies['ar']) && !empty($paymentMethod->currencies['ar'])) {
                                                    $defaultCurrency = $paymentMethod->currencies['ar'][0];
                                                }
                                            }
                                            $currentCurrencies = [$defaultCurrency];
                                        }
                                    } else {
                                        // استخدام البيانات القديمة
                                        $currentCurrencies = $oldCurrencies;
                                    }

                                    // تأكد من أن $currentCurrencies مصفوفة وليست فارغة
                                    if (!is_array($currentCurrencies)) {
                                        $currentCurrencies = [$currentCurrencies];
                                    }
                                    if (empty($currentCurrencies)) {
                                        $currentCurrencies = ['USD'];
                                    }

                                    // إزالة التكرار من المصفوفة
                                    $currentCurrencies = array_unique($currentCurrencies);
                                    $currentCurrencies = array_values($currentCurrencies); // إعادة ترقيم المفاتيح

                                    // تسجيل للتشخيص
                                    \Log::info('Edit page currencies:', [
                                        'old_currencies' => $oldCurrencies,
                                        'payment_method_currencies' => $paymentMethod->currencies ?? 'not_set',
                                        'final_currencies' => $currentCurrencies,
                                    ]);
                                @endphp
                                @foreach ($currentCurrencies as $currency)
                                    <span class="currency-tag" data-currency="{{ $currency }}">
                                        {{ $currency }}
                                        <button type="button" class="remove-currency"
                                            onclick="removeCurrency('{{ $currency }}')">&times;</button>
                                    </span>
                                @endforeach
                            </div>
                            <div class="currency-input-container">
                                <input type="text" id="currency-input" class="form-input"
                                    placeholder="{{ __('Add currency (e.g., USD, EUR, BTC)') }}" maxlength="10">
                                <button type="button" id="add-currency-btn" class="add-currency-btn"
                                    onclick="addCurrencyFromInput()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="currency-suggestions" id="currency-suggestions">
                                <!-- Popular currencies -->
                                <div class="suggestion-group">
                                    <h6>{{ __('Popular') }}</h6>
                                    <div class="suggestion-items">
                                        <span class="suggestion-item" data-currency="USD">USD -
                                            {{ __('US Dollar') }}</span>
                                        <span class="suggestion-item" data-currency="EUR">EUR - {{ __('Euro') }}</span>
                                        <span class="suggestion-item" data-currency="SAR">SAR -
                                            {{ __('Saudi Riyal') }}</span>
                                        <span class="suggestion-item" data-currency="AED">AED -
                                            {{ __('UAE Dirham') }}</span>
                                        <span class="suggestion-item" data-currency="KWD">KWD -
                                            {{ __('Kuwaiti Dinar') }}</span>
                                        <span class="suggestion-item" data-currency="QAR">QAR -
                                            {{ __('Qatari Riyal') }}</span>
                                        <span class="suggestion-item" data-currency="BHD">BHD -
                                            {{ __('Bahraini Dinar') }}</span>
                                        <span class="suggestion-item" data-currency="OMR">OMR -
                                            {{ __('Omani Riyal') }}</span>
                                        <span class="suggestion-item" data-currency="JOD">JOD -
                                            {{ __('Jordanian Dinar') }}</span>
                                        <span class="suggestion-item" data-currency="EGP">EGP -
                                            {{ __('Egyptian Pound') }}</span>
                                    </div>
                                </div>
                                <div class="suggestion-group">
                                    <h6>{{ __('Crypto') }}</h6>
                                    <div class="suggestion-items">
                                        <span class="suggestion-item" data-currency="BTC">BTC - {{ __('Bitcoin') }}</span>
                                        <span class="suggestion-item" data-currency="ETH">ETH - {{ __('Ethereum') }}</span>
                                        <span class="suggestion-item" data-currency="USDT">USDT -
                                            {{ __('Tether') }}</span>
                                        <span class="suggestion-item" data-currency="USDC">USDC -
                                            {{ __('USD Coin') }}</span>
                                        <span class="suggestion-item" data-currency="BNB">BNB -
                                            {{ __('Binance Coin') }}</span>
                                        <span class="suggestion-item" data-currency="ADA">ADA - {{ __('Cardano') }}</span>
                                        <span class="suggestion-item" data-currency="SOL">SOL - {{ __('Solana') }}</span>
                                        <span class="suggestion-item" data-currency="DOT">DOT - {{ __('Polkadot') }}</span>
                                        <span class="suggestion-item" data-currency="MATIC">MATIC -
                                            {{ __('Polygon') }}</span>
                                        <span class="suggestion-item" data-currency="AVAX">AVAX -
                                            {{ __('Avalanche') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="currencies" id="currencies-input"
                            value="{{ implode(',', $currentCurrencies) }}">
                        @error('currencies')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Recipient Name Field -->
                    <div class="form-group">
                        <label for="recipient_name" class="form-label">{{ __('Recipient Name') }}</label>
                        <input type="text" id="recipient_name" name="recipient_name" class="form-input"
                            value="{{ old('recipient_name', $paymentMethod->getTranslation('recipient_name', app()->getLocale()) ?? '') }}"
                            placeholder="{{ __('Enter recipient name') }}">
                        @error('recipient_name')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Account Number Field -->
                    <div class="form-group">
                        <label for="account_number" class="form-label">{{ __('Account Number') }}</label>
                        <input type="text" id="account_number" name="account_number" class="form-input"
                            value="{{ old('account_number', $paymentMethod->account_number ?? '') }}"
                            placeholder="{{ __('Enter account number') }}">
                        @error('account_number')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bank Name Field -->
                    <div class="form-group">
                        <label for="bank_name" class="form-label">{{ __('Bank Name') }}</label>
                        <input type="text" id="bank_name" name="bank_name" class="form-input"
                            value="{{ old('bank_name', $paymentMethod->getTranslation('bank_name', app()->getLocale()) ?? '') }}"
                            placeholder="{{ __('Enter bank name') }}">
                        @error('bank_name')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Transfer Number Field -->
                    <div class="form-group">
                        <label for="transfer_number" class="form-label">{{ __('Transfer Number') }}</label>
                        <input type="text" id="transfer_number" name="transfer_number" class="form-input"
                            value="{{ old('transfer_number', $paymentMethod->transfer_number ?? '') }}"
                            placeholder="{{ __('Enter transfer number') }}">
                        @error('transfer_number')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image Upload -->
                    <div class="form-group full-width">
                        <label class="form-label">{{ __('Image') }}</label>
                        <div class="image-upload-section">
                            @if ($paymentMethod->getFirstMediaUrl('payment_method_images'))
                                @php $media = $paymentMethod->getFirstMedia('payment_method_images'); @endphp
                                <img id="image-preview" class="image-preview"
                                    src="{{ route('payment.methode.image', $media->id) }}"
                                    alt="{{ $paymentMethod->getTranslation('name', app()->getLocale()) }}">
                            @else
                                <img id="image-preview" class="image-preview" src="{{ asset('images/no-image.png') }}"
                                    alt="{{ __('No image selected') }}">
                            @endif
                            <div>
                                <input type="file" id="image" name="image" class="file-input" accept="image/*"
                                    onchange="previewImage(this)">
                                <button type="button" class="upload-button"
                                    onclick="document.getElementById('image').click()">
                                    {{ __('Select Image') }}
                                </button>
                            </div>
                        </div>
                        @error('image')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Transfer Information -->
                <div class="form-group">
                    <label class="form-label">{{ __('Transfer Information') }}</label>
                    <div class="rich-text-editor">
                        <div class="editor-toolbar">
                            <button type="button" class="toolbar-button" onclick="formatText('bold')">
                                <i class="fas fa-bold"></i>
                            </button>
                            <button type="button" class="toolbar-button" onclick="formatText('italic')">
                                <i class="fas fa-italic"></i>
                            </button>
                            <button type="button" class="toolbar-button" onclick="formatText('underline')">
                                <i class="fas fa-underline"></i>
                            </button>
                            <div style="width: 1px; height: 20px; background: var(--border-color); margin: 0 0.5rem;">
                            </div>
                            <button type="button" class="toolbar-button" onclick="formatText('insertUnorderedList')">
                                <i class="fas fa-list-ul"></i>
                            </button>
                            <button type="button" class="toolbar-button" onclick="formatText('insertOrderedList')">
                                <i class="fas fa-list-ol"></i>
                            </button>
                            <div style="width: 1px; height: 20px; background: var(--border-color); margin: 0 0.5rem;">
                            </div>
                            <button type="button" class="toolbar-button" onclick="formatText('undo')">
                                <i class="fas fa-undo"></i>
                            </button>
                            <button type="button" class="toolbar-button" onclick="formatText('redo')">
                                <i class="fas fa-redo"></i>
                            </button>
                        </div>
                        <div class="editor-content" contenteditable="true" id="instructions-editor">
                            @php
                                $instructions = old('instructions');
                                if (!$instructions) {
                                    $instructions = $paymentMethod->getTranslation('instructions', app()->getLocale());
                                    if (is_array($instructions)) {
                                        $instructions = implode('<br>', $instructions);
                                    }
                                }
                            @endphp
                            {!! $instructions !!}
                        </div>
                    </div>
                    <textarea name="instructions" id="instructions" style="display: none;"></textarea>
                    @error('instructions')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="save-button">
                        {{ __('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-preview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function formatText(command) {
            document.execCommand(command, false, null);
            updateInstructions();
        }

        function updateInstructions() {
            document.getElementById('instructions').value = document.getElementById('instructions-editor').innerHTML;
        }

        // Update instructions on input
        document.getElementById('instructions-editor').addEventListener('input', updateInstructions);

        // Update instructions on form submit
        document.querySelector('form').addEventListener('submit', function(e) {
            console.log('Form submit event triggered');

            updateInstructions();

            // تنظيف الحقول المخفية قبل الإرسال
            const existingInputs = document.querySelectorAll('input[name="currencies[]"]');
            console.log('Removing existing currency inputs:', existingInputs.length);
            existingInputs.forEach(input => input.remove());

            // تحديث الحقول المخفية
            updateCurrenciesInput();

            // التحقق من وجود الحقول المخفية بعد التحديث
            const finalInputs = document.querySelectorAll('input[name="currencies[]"]');
            console.log('Final currency inputs count:', finalInputs.length);

            if (finalInputs.length === 0) {
                console.error('No currency inputs found! Adding default USD');
                const container = document.querySelector('.multi-select-container');
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'currencies[]';
                hiddenInput.value = 'USD';
                container.appendChild(hiddenInput);
            }

            // تسجيل البيانات المرسلة للتشخيص
            const formData = new FormData(this);
            const currencies = [];
            const currencyInputs = document.querySelectorAll('input[name="currencies[]"]');
            currencyInputs.forEach(input => currencies.push(input.value));

            console.log('Form submission - currencies:', currencies);
            console.log('Form submission - currencies count:', currencies.length);
            console.log('Form submission - all form data:', Array.from(formData.entries()));

            // التحقق من أن العملات موجودة في FormData
            const formCurrencies = [];
            for (let [key, value] of formData.entries()) {
                if (key === 'currencies[]') {
                    formCurrencies.push(value);
                }
            }
            console.log('FormData currencies:', formCurrencies);

            // التأكد من وجود العملات قبل الإرسال
            if (currencies.length === 0) {
                console.error('No currencies found! Preventing form submission');
                e.preventDefault();
                alert('يجب اختيار عملة واحدة على الأقل');
                return false;
            }

            // إرسال البيانات إلى الخادم للتشخيص (اختياري)
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                fetch('/api/debug-form-data', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                        },
                        body: JSON.stringify({
                            currencies: currencies,
                            form_data: Array.from(formData.entries())
                        })
                    }).then(response => response.json())
                    .then(data => console.log('Debug response:', data))
                    .catch(error => console.error('Debug error:', error));
            } else {
                console.log('CSRF token not found, skipping debug request');
            }
        });

        // Initialize instructions on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - Initializing payment method form');

            try {
                updateInstructions();
                console.log('Instructions updated successfully');
            } catch (error) {
                console.error('Error updating instructions:', error);
            }

            // تأخير بسيط لضمان تحميل جميع العناصر
            setTimeout(function() {
                try {
                    initializeCurrencyMultiSelect();
                    console.log('Currency multi-select initialized');
                } catch (error) {
                    console.error('Error initializing currency multi-select:', error);
                }

                try {
                    // إزالة أي حقول مخفية مكررة موجودة مسبقاً
                    const existingInputs = document.querySelectorAll('input[name="currencies[]"]');
                    console.log('Removing existing duplicate inputs:', existingInputs.length);
                    existingInputs.forEach(input => input.remove());

                    // تهيئة الحقول المخفية للعملات الموجودة
                    updateCurrenciesInput();
                    console.log('Currencies input updated');
                } catch (error) {
                    console.error('Error updating currencies input:', error);
                }
            }, 100);
        });

        // Global variables for currency management
        let currencyInput, addCurrencyBtn, suggestions, currenciesInput;

        // Currency Multi-Select Functions
        function initializeCurrencyMultiSelect() {
            console.log('Initializing currency multi-select...');

            currencyInput = document.getElementById('currency-input');
            addCurrencyBtn = document.getElementById('add-currency-btn');
            suggestions = document.getElementById('currency-suggestions');
            currenciesInput = document.getElementById('currencies-input');

            console.log('Elements found:', {
                currencyInput: !!currencyInput,
                addCurrencyBtn: !!addCurrencyBtn,
                suggestions: !!suggestions,
                currenciesInput: !!currenciesInput
            });

            // Check if elements exist
            if (!currencyInput || !addCurrencyBtn || !suggestions || !currenciesInput) {
                console.error('Currency elements not found:', {
                    currencyInput: !!currencyInput,
                    addCurrencyBtn: !!addCurrencyBtn,
                    suggestions: !!suggestions,
                    currenciesInput: !!currenciesInput
                });
                return;
            }

            console.log('All elements found, setting up event listeners...');

            // Show suggestions when input is focused
            currencyInput.addEventListener('focus', function() {
                suggestions.classList.add('show');
                updateSuggestions();
            });

            // Hide suggestions when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.multi-select-container')) {
                    suggestions.classList.remove('show');
                }
            });

            // Add currency when button is clicked
            addCurrencyBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Add button clicked, input value:', currencyInput.value);
                console.log('Button element:', addCurrencyBtn);
                console.log('Input element:', currencyInput);
                addCurrency(currencyInput.value.trim().toUpperCase());
            });

            // Alternative: Add currency when button is clicked (mousedown)
            addCurrencyBtn.addEventListener('mousedown', function(e) {
                e.preventDefault();
                console.log('Add button mousedown, input value:', currencyInput.value);
                addCurrency(currencyInput.value.trim().toUpperCase());
            });

            console.log('Event listeners added successfully');

            // Add currency when Enter is pressed
            currencyInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    console.log('Enter pressed, input value:', currencyInput.value);
                    addCurrency(currencyInput.value.trim().toUpperCase());
                }
            });

            // Filter suggestions as user types
            currencyInput.addEventListener('input', function() {
                updateSuggestions();
            });

            // Add currency from suggestion
            suggestions.addEventListener('click', function(e) {
                if (e.target.classList.contains('suggestion-item')) {
                    const currency = e.target.getAttribute('data-currency');
                    addCurrency(currency);
                }
            });

            // Initial update
            updateSuggestions();
        }

        // Global addCurrency function
        function addCurrency(currency) {
            console.log('addCurrency called with:', currency);

            if (!currency || currency.length < 2) {
                console.log('Currency too short or empty');
                return;
            }

            // Check if currency already exists
            const existingTags = document.querySelectorAll('.currency-tag');
            for (let tag of existingTags) {
                if (tag.getAttribute('data-currency') === currency) {
                    console.log('Currency already exists:', currency);
                    if (currencyInput) currencyInput.value = '';
                    return;
                }
            }

            // Add currency tag
            const selectedCurrencies = document.getElementById('selected-currencies');
            if (!selectedCurrencies) {
                console.error('selected-currencies element not found');
                return;
            }

            const tag = document.createElement('span');
            tag.className = 'currency-tag';
            tag.setAttribute('data-currency', currency);
            tag.innerHTML =
                `${currency} <button type="button" class="remove-currency" onclick="removeCurrency('${currency}')">&times;</button>`;
            selectedCurrencies.appendChild(tag);

            console.log('Currency tag added:', currency);

            // Clear input
            if (currencyInput) currencyInput.value = '';

            // Update hidden input
            updateCurrenciesInput();

            // Update suggestions
            updateSuggestions();
        }

        function removeCurrency(currency) {
            console.log('removeCurrency called with:', currency);
            const tag = document.querySelector(`.currency-tag[data-currency="${currency}"]`);
            if (tag) {
                tag.remove();
                // تنظيف الحقول المخفية وإعادة إنشائها
                const existingInputs = document.querySelectorAll('input[name="currencies[]"]');
                existingInputs.forEach(input => input.remove());
                updateCurrenciesInput();
                updateSuggestions();
            }
        }

        function updateCurrenciesInput() {
            const tags = document.querySelectorAll('.currency-tag');
            const currencies = Array.from(tags).map(tag => tag.getAttribute('data-currency'));

            // إزالة التكرار من المصفوفة
            const uniqueCurrencies = [...new Set(currencies)];

            console.log('updateCurrenciesInput called:', {
                'tags_found': tags.length,
                'currencies': currencies,
                'unique_currencies': uniqueCurrencies
            });

            // إزالة جميع الحقول المخفية القديمة للعملات
            const existingInputs = document.querySelectorAll('input[name="currencies[]"]');
            console.log('Removing existing inputs:', existingInputs.length);
            existingInputs.forEach(input => input.remove());

            // إضافة حقول جديدة لكل عملة
            const container = document.querySelector('.multi-select-container');
            if (!container) {
                console.error('Container not found!');
                return;
            }

            uniqueCurrencies.forEach(currency => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'currencies[]';
                hiddenInput.value = currency;
                hiddenInput.setAttribute('data-currency', currency); // إضافة معرف للتحقق
                container.appendChild(hiddenInput);
                console.log('Added hidden input for currency:', currency);
            });

            // إضافة حقل إضافي للتأكد من الإرسال
            const backupInput = document.createElement('input');
            backupInput.type = 'hidden';
            backupInput.name = 'currencies_backup';
            backupInput.value = uniqueCurrencies.join(',');
            container.appendChild(backupInput);
            console.log('Added backup input:', uniqueCurrencies.join(','));

            // التحقق من أن الحقول أُضيفت بشكل صحيح
            const finalInputs = document.querySelectorAll('input[name="currencies[]"]');
            console.log('Final verification - inputs added:', finalInputs.length);
            finalInputs.forEach(input => {
                console.log('Input:', input.name, '=', input.value);
            });

            console.log('Updated currencies input:', uniqueCurrencies);
        }

        function updateSuggestions() {
            if (!suggestions || !currencyInput) return;

            const inputValue = currencyInput.value.toLowerCase();
            const suggestionItems = suggestions.querySelectorAll('.suggestion-item');
            const selectedCurrencies = Array.from(document.querySelectorAll('.currency-tag')).map(tag => tag.getAttribute(
                'data-currency'));

            suggestionItems.forEach(item => {
                const currency = item.getAttribute('data-currency');
                const text = item.textContent.toLowerCase();
                const isSelected = selectedCurrencies.includes(currency);
                const matchesInput = !inputValue || text.includes(inputValue);

                if (matchesInput && !isSelected) {
                    item.style.display = 'block';
                    item.classList.remove('selected');
                } else if (isSelected) {
                    item.style.display = 'block';
                    item.classList.add('selected');
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Make functions globally available
        window.addCurrency = addCurrency;
        window.removeCurrency = removeCurrency;

        // Global function for onclick
        function addCurrencyFromInput() {
            console.log('addCurrencyFromInput called');
            const input = document.getElementById('currency-input');
            if (input && input.value.trim()) {
                console.log('Adding currency from input:', input.value.trim().toUpperCase());
                addCurrency(input.value.trim().toUpperCase());
            } else {
                console.log('Input is empty or not found');
            }
        }
    </script>
@endpush
