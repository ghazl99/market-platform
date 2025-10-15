@extends('core::dashboard.layouts.app')

@section('title', __('Edit Payment Method'))

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
            <a href="{{ route('dashboard.dashboard.payment-methods.index') }}" class="close-button">
                <i class="fas fa-times"></i>
            </a>
        </div>

        <!-- Form Content -->
        <div class="form-content">
            <form action="{{ route('dashboard.dashboard.payment-methods.update', $paymentMethod) }}" method="POST"
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
                        <label for="currency" class="form-label">{{ __('Currency') }}</label>
                        <select id="currency" name="currency" class="form-select" required>
                            <option value="">{{ __('Select Currency') }}</option>
                            <option value="USD"
                                {{ old('currency', $paymentMethod->currency ?? '') == 'USD' ? 'selected' : '' }}>
                                {{ __('US Dollar') }}</option>
                            <option value="EUR"
                                {{ old('currency', $paymentMethod->currency ?? '') == 'EUR' ? 'selected' : '' }}>
                                {{ __('Euro') }}</option>
                        </select>
                        @error('currency')
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
        document.querySelector('form').addEventListener('submit', function() {
            updateInstructions();
        });

        // Initialize instructions on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateInstructions();
        });
    </script>
@endpush
