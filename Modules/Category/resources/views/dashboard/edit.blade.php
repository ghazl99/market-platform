@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Edit Section'))

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('modules/category/css/category_create.css') }}">
@endpush

@section('content')
    <!-- Professional Notifications -->
    <div class="notification-container">
        @if (session('success'))
            <div class="notification success professional-notification show">
                <div class="notification-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">{{ __('Success') }}</div>
                    <div class="notification-message">{{ session('success') }}</div>
                </div>
                <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
                <div class="notification-progress"></div>
            </div>
        @endif

        @if (session('error'))
            <div class="notification error professional-notification show">
                <div class="notification-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">{{ __('Error') }}</div>
                    <div class="notification-message">{{ session('error') }}</div>
                </div>
                <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
                <div class="notification-progress"></div>
            </div>
        @endif

        @if (session('warning'))
            <div class="notification warning professional-notification show">
                <div class="notification-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">{{ __('Warning') }}</div>
                    <div class="notification-message">{{ session('warning') }}</div>
                </div>
                <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
                <div class="notification-progress"></div>
            </div>
        @endif

        @if (session('info'))
            <div class="notification info professional-notification show">
                <div class="notification-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">{{ __('Info') }}</div>
                    <div class="notification-message">{{ session('info') }}</div>
                </div>
                <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
                <div class="notification-progress"></div>
            </div>
        @endif

        @if ($errors->any())
            <div class="notification error professional-notification show">
                <div class="notification-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">{{ __('Error') }}</div>
                    <div class="notification-message">{{ __('Please fix the errors below') }}</div>
                </div>
                <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
                <div class="notification-progress"></div>
            </div>
        @endif
    </div>

    <div class="category-create-container">
        <div class="page-header">
            <h1 class="page-title">{{ __('Edit Section') }}</h1>
            <div class="page-actions">
                <a href="{{ route('dashboard.category.index') }}" class="back-btn">
                    <i class="fas fa-arrow-right"></i>
                    {{ __('Back to Sections') }}
                </a>
            </div>
        </div>

        <form class="form-container" id="categoryForm" action="{{ route('dashboard.category.update', $category->id) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Basic Information') }}</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label required">{{ __('Section Name') }}</label>
                        <input type="text" class="form-input" name="name" value="{{ old('name', $category->name) }}"
                            placeholder="{{ __('Enter section name') }}" required>
                        <div class="form-help">{{ __('Section name should be clear and distinctive') }}</div>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Parent Section') }}</label>
                        <select class="form-select" name="parent_id">
                            <option value="">{{ __('Main Section') }}</option>
                            @foreach ($categories as $cat)
                                @if ($cat->id != $category->id)
                                    <option value="{{ $cat->id }}"
                                        {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->getTranslation('name', app()->getLocale()) }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <div class="form-help">{{ __('Choose a parent section if this is a subsection') }}</div>
                        @error('parent_id')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label required">{{ __('Section Icon') }}</label>
                        <div class="icon-picker" id="iconPicker">
                            @php
                                $currentIcon = old('icon', $category->icon ?? 'fas fa-tag');
                            @endphp
                            <div class="icon-option {{ $currentIcon == 'fas fa-laptop' ? 'selected' : '' }}"
                                data-icon="fas fa-laptop">
                                <i class="fas fa-laptop"></i>
                            </div>
                            <div class="icon-option {{ $currentIcon == 'fas fa-tshirt' ? 'selected' : '' }}"
                                data-icon="fas fa-tshirt">
                                <i class="fas fa-tshirt"></i>
                            </div>
                            <div class="icon-option {{ $currentIcon == 'fas fa-book' ? 'selected' : '' }}"
                                data-icon="fas fa-book">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="icon-option {{ $currentIcon == 'fas fa-home' ? 'selected' : '' }}"
                                data-icon="fas fa-home">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="icon-option {{ $currentIcon == 'fas fa-dumbbell' ? 'selected' : '' }}"
                                data-icon="fas fa-dumbbell">
                                <i class="fas fa-dumbbell"></i>
                            </div>
                            <div class="icon-option {{ $currentIcon == 'fas fa-palette' ? 'selected' : '' }}"
                                data-icon="fas fa-palette">
                                <i class="fas fa-palette"></i>
                            </div>
                            <div class="icon-option {{ $currentIcon == 'fas fa-mobile-alt' ? 'selected' : '' }}"
                                data-icon="fas fa-mobile-alt">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div class="icon-option {{ $currentIcon == 'fas fa-headphones' ? 'selected' : '' }}"
                                data-icon="fas fa-headphones">
                                <i class="fas fa-headphones"></i>
                            </div>
                            <div class="icon-option {{ $currentIcon == 'fas fa-car' ? 'selected' : '' }}"
                                data-icon="fas fa-car">
                                <i class="fas fa-car"></i>
                            </div>
                            <div class="icon-option {{ $currentIcon == 'fas fa-gamepad' ? 'selected' : '' }}"
                                data-icon="fas fa-gamepad">
                                <i class="fas fa-gamepad"></i>
                            </div>
                            <div class="icon-option {{ $currentIcon == 'fas fa-utensils' ? 'selected' : '' }}"
                                data-icon="fas fa-utensils">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <div class="icon-option {{ $currentIcon == 'fas fa-gift' ? 'selected' : '' }}"
                                data-icon="fas fa-gift">
                                <i class="fas fa-gift"></i>
                            </div>
                        </div>
                        <input type="hidden" name="icon" id="selectedIcon" value="{{ $currentIcon }}" required>
                        @error('icon')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Description & Details') }}</h3>
                <div class="form-group full-width">
                    <label class="form-label required">{{ __('Section Description') }}</label>
                    <textarea class="form-input form-textarea" name="description"
                        placeholder="{{ __('Enter detailed section description') }}" required>{{ old('description', $category->description) }}</textarea>
                    <div class="form-help">
                        {{ __('Comprehensive description helps customers understand the section better') }}</div>
                    @error('description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Image -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Section Image') }}</h3>
                <div class="form-group full-width">
                    <label class="form-label">{{ __('Section Image') }}</label>
                    @php
                        $media = $category->getFirstMedia('category_images');
                    @endphp
                    @if ($media)
                        <div class="current-image">
                            <img src="{{ route('dashboard.category.image', $media->id) }}" alt="{{ $category->name }}"
                                class="current-image-preview">
                            <div class="current-image-info">
                                <span class="current-image-text">{{ __('Current Image') }}</span>
                                <button type="button" class="remove-image-btn" onclick="removeCurrentImage()">
                                    <i class="fas fa-trash"></i>
                                    {{ __('Remove') }}
                                </button>
                            </div>
                        </div>
                    @endif
                    <div class="file-upload">
                        <input type="file" class="file-upload-input" id="categoryImage" name="image"
                            accept="image/*">
                        <label for="categoryImage" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                            <span class="file-upload-text">{{ __('Drag image here or click to select') }}</span>
                            <span class="file-upload-hint">{{ __('JPG, PNG, GIF (Optimal size: 400x300 pixels)') }}</span>
                        </label>
                    </div>
                    <div class="image-preview" id="imagePreview"></div>
                    @error('image')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- SEO -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Search Engine Optimization (SEO)') }}</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">{{ __('SEO Title') }}</label>
                        <input type="text" class="form-input" name="seo_title"
                            value="{{ old('seo_title', $category->seo_title ?? '') }}"
                            placeholder="{{ __('SEO optimized title') }}">
                        <div class="form-help">{{ __('Title that appears in search results') }}</div>
                        @error('seo_title')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Keywords') }}</label>
                        <input type="text" class="form-input" name="keywords"
                            value="{{ old('keywords', $category->keywords ?? '') }}"
                            placeholder="{{ __('keyword1, keyword2, keyword3') }}">
                        <div class="form-help">{{ __('Separated by commas') }}</div>
                        @error('keywords')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group full-width">
                    <label class="form-label">{{ __('SEO Description') }}</label>
                    <textarea class="form-input form-textarea" name="seo_description"
                        placeholder="{{ __('SEO optimized description') }}">{{ old('seo_description', $category->seo_description ?? '') }}</textarea>
                    @error('seo_description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Status & Settings') }}</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label required">{{ __('Section Status') }}</label>
                        <select class="form-select" name="is_active" required>
                            <option value="1" {{ old('is_active', $category->is_active) == 1 ? 'selected' : '' }}>
                                {{ __('Active') }}</option>
                            <option value="0" {{ old('is_active', $category->is_active) == 0 ? 'selected' : '' }}>
                                {{ __('Inactive') }}</option>
                        </select>
                        @error('is_active')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Display Order') }}</label>
                        <input type="number" class="form-input" name="sort_order"
                            value="{{ old('sort_order', $category->sort_order ?? 0) }}" placeholder="0" min="0">
                        <div class="form-help">{{ __('Lower number = appears first') }}</div>
                        @error('sort_order')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                    <i class="fas fa-times"></i>
                    {{ __('Cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    {{ __('Update Section') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/notifications.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('modules/category/js/category_create.js') }}?v={{ time() }}"></script>
    <script>
        // Initialize icon picker for edit form
        document.addEventListener('DOMContentLoaded', function() {
            const iconPicker = document.getElementById('iconPicker');
            const selectedIconInput = document.getElementById('selectedIcon');

            if (iconPicker && selectedIconInput) {
                iconPicker.addEventListener('click', function(e) {
                    if (e.target.closest('.icon-option')) {
                        const iconOption = e.target.closest('.icon-option');
                        const iconClass = iconOption.getAttribute('data-icon');

                        // Remove selected class from all options
                        iconPicker.querySelectorAll('.icon-option').forEach(option => {
                            option.classList.remove('selected');
                        });

                        // Add selected class to clicked option
                        iconOption.classList.add('selected');

                        // Update hidden input
                        selectedIconInput.value = iconClass;
                    }
                });
            }
        });

        // Remove current image function
        function removeCurrentImage() {
            const currentImageDiv = document.querySelector('.current-image');
            if (currentImageDiv) {
                currentImageDiv.remove();
            }
        }

        // Auto-hide notifications after 6 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const notifications = document.querySelectorAll('.professional-notification');
            notifications.forEach(notification => {
                // Progress bar animation
                const progressBar = notification.querySelector('.notification-progress');
                if (progressBar) {
                    progressBar.style.width = '100%';
                    progressBar.style.transition = 'width 6s linear';
                }

                // Auto-hide after 6 seconds
                setTimeout(() => {
                    notification.classList.add('hide');
                    setTimeout(() => {
                        notification.remove();
                    }, 400);
                }, 6000);
            });
        });
    </script>

    <style>
        /* Professional Notification Styles */
        .notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        }

        .notification {
            position: relative !important;
            background: rgba(17, 24, 39, 0.95) !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
            margin-bottom: 15px !important;
            padding: 20px !important;
            display: flex !important;
            align-items: flex-start !important;
            gap: 15px !important;
            transform: translateX(100%) !important;
            opacity: 0 !important;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
            border-left: 4px solid !important;
            overflow: hidden !important;
            backdrop-filter: blur(10px) !important;
        }

        .notification.show {
            transform: translateX(0) !important;
            opacity: 1 !important;
        }

        .notification.hide {
            transform: translateX(100%) !important;
            opacity: 0 !important;
        }

        .notification-icon {
            flex-shrink: 0 !important;
            margin-top: 2px !important;
        }

        .notification-icon i {
            font-size: 24px !important;
        }

        .notification-content {
            flex: 1 !important;
            color: #ffffff !important;
        }

        .notification-title {
            font-weight: 600 !important;
            font-size: 16px !important;
            margin-bottom: 4px !important;
            color: #ffffff !important;
        }

        .notification-message {
            font-size: 14px !important;
            line-height: 1.5 !important;
            color: #d1d5db !important;
            margin-bottom: 8px !important;
        }

        .notification-close {
            position: absolute !important;
            top: 10px !important;
            right: 10px !important;
            background: none !important;
            border: none !important;
            font-size: 18px !important;
            color: #9ca3af !important;
            cursor: pointer !important;
            padding: 5px !important;
            border-radius: 4px !important;
            transition: color 0.2s !important;
        }

        .notification-close:hover {
            color: #ffffff !important;
        }

        .notification-progress {
            position: absolute !important;
            bottom: 0 !important;
            left: 0 !important;
            height: 3px !important;
            background: rgba(255, 255, 255, 0.3) !important;
            width: 0 !important;
            border-radius: 0 0 12px 12px !important;
        }

        .professional-notification {
            border-left: 4px solid !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3) !important;
            backdrop-filter: blur(10px) !important;
        }

        .professional-notification.success {
            border-left-color: #10b981 !important;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.05) 100%) !important;
        }

        .professional-notification.error {
            border-left-color: #ef4444 !important;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%) !important;
        }

        .professional-notification.warning {
            border-left-color: #f59e0b !important;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.05) 100%) !important;
        }

        .professional-notification.info {
            border-left-color: #3b82f6 !important;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.05) 100%) !important;
        }

        .notification-details {
            margin-top: 0.5rem;
            padding: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 6px;
            font-size: 0.85rem;
            color: #cccccc;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .notification-details i {
            color: #059669;
            font-size: 0.9rem;
        }

        .professional-notification .notification-icon i {
            font-size: 1.5rem !important;
            animation: successPulse 0.6s ease-out !important;
        }

        @keyframes successPulse {
            0% {
                transform: scale(0.8) !important;
                opacity: 0.7 !important;
            }

            50% {
                transform: scale(1.1) !important;
                opacity: 1 !important;
            }

            100% {
                transform: scale(1) !important;
                opacity: 1 !important;
            }
        }

        .professional-notification.show {
            animation: professionalSlideIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
        }

        @keyframes professionalSlideIn {
            0% {
                opacity: 0 !important;
                transform: translateX(100%) scale(0.8) !important;
            }

            100% {
                opacity: 1 !important;
                transform: translateX(0) scale(1) !important;
            }
        }

        /* RTL Support */
        [dir="rtl"] .notification-container {
            right: auto !important;
            left: 20px !important;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .notification-container {
                top: 10px !important;
                right: 10px !important;
                left: 10px !important;
                max-width: none !important;
            }

            .notification {
                padding: 15px !important;
            }
        }

        /* Force Light Mode Styles - Maximum Priority */
        html[data-theme="light"] .category-create-container,
        html[data-theme="light"] body .category-create-container {
            background: #ffffff !important;
        }

        html[data-theme="light"] .page-title,
        html[data-theme="light"] body .page-title {
            color: #111827 !important;
        }

        html[data-theme="light"] .form-container,
        html[data-theme="light"] body .form-container {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1) !important;
        }

        html[data-theme="light"] .section-title,
        html[data-theme="light"] body .section-title {
            color: #111827 !important;
            border-bottom: 2px solid #059669 !important;
        }

        html[data-theme="light"] .form-label,
        html[data-theme="light"] body .form-label {
            color: #111827 !important;
        }

        html[data-theme="light"] .form-input,
        html[data-theme="light"] body .form-input {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .form-input:focus,
        html[data-theme="light"] body .form-input:focus {
            background: #ffffff !important;
            color: #111827 !important;
            border-color: #059669 !important;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1) !important;
        }

        html[data-theme="light"] .form-input::placeholder,
        html[data-theme="light"] body .form-input::placeholder {
            color: #9ca3af !important;
        }

        html[data-theme="light"] .form-textarea,
        html[data-theme="light"] body .form-textarea {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .form-textarea:focus,
        html[data-theme="light"] body .form-textarea:focus {
            background: #ffffff !important;
            color: #111827 !important;
            border-color: #059669 !important;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1) !important;
        }

        html[data-theme="light"] .form-select,
        html[data-theme="light"] body .form-select {
            background: #ffffff url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23111827' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") no-repeat right 0.5rem center !important;
            background-size: 1.5em 1.5em !important;
            border: 1px solid #e5e7eb !important;
            color: #111827 !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
        }

        html[data-theme="light"] .form-select:focus,
        html[data-theme="light"] body .form-select:focus {
            background: #ffffff url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23111827' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") no-repeat right 0.5rem center !important;
            background-size: 1.5em 1.5em !important;
            color: #111827 !important;
            border-color: #059669 !important;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1) !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
        }

        [dir="rtl"] html[data-theme="light"] .form-select,
        [dir="rtl"] html[data-theme="light"] body .form-select {
            background-position: left 0.5rem center !important;
            padding-right: 1rem !important;
            padding-left: 2.5rem !important;
        }

        html[data-theme="light"] .file-upload-label,
        html[data-theme="light"] body .file-upload-label {
            background: #f9fafb !important;
            border: 2px dashed #e5e7eb !important;
            color: #6b7280 !important;
        }

        html[data-theme="light"] .file-upload-label:hover,
        html[data-theme="light"] body .file-upload-label:hover {
            border-color: #059669 !important;
            background: rgba(5, 150, 105, 0.05) !important;
            color: #059669 !important;
        }

        html[data-theme="light"] .icon-option,
        html[data-theme="light"] body .icon-option {
            background: #f9fafb !important;
            border: 2px solid #e5e7eb !important;
            color: #6b7280 !important;
        }

        html[data-theme="light"] .icon-option:hover,
        html[data-theme="light"] body .icon-option:hover {
            border-color: #059669 !important;
            background: rgba(5, 150, 105, 0.1) !important;
            color: #059669 !important;
        }

        html[data-theme="light"] .form-help,
        html[data-theme="light"] body .form-help {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .back-btn,
        html[data-theme="light"] body .back-btn {
            background: #f3f4f6 !important;
            color: #374151 !important;
            border: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .back-btn:hover,
        html[data-theme="light"] body .back-btn:hover {
            background: #059669 !important;
            color: #ffffff !important;
            border-color: #059669 !important;
        }

        html[data-theme="light"] .btn-secondary,
        html[data-theme="light"] body .btn-secondary {
            background: #f3f4f6 !important;
            color: #374151 !important;
            border: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .btn-secondary:hover,
        html[data-theme="light"] body .btn-secondary:hover {
            background: #e5e7eb !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .current-image,
        html[data-theme="light"] body .current-image {
            background: #f9fafb !important;
            border: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .current-image-text,
        html[data-theme="light"] body .current-image-text {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .form-actions,
        html[data-theme="light"] body .form-actions {
            border-top: 1px solid #e5e7eb !important;
        }
    </style>
@endpush
