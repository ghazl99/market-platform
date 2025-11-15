@extends('core::dashboard.'. current_store()->type .'.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Add New Section'))

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('modules/category/css/category_create.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/notifications.css') }}">
@endpush

@section('content')
    <!-- Notifications -->
    <div class="notification-container">
        @if (session('success'))
            <div class="notification success">
                <div class="notification-icon">
                    <i class="fas fa-check"></i>
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
            <div class="notification error">
                <div class="notification-icon">
                    <i class="fas fa-times"></i>
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
            <div class="notification warning">
                <div class="notification-icon">
                    <i class="fas fa-exclamation"></i>
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
            <div class="notification info">
                <div class="notification-icon">
                    <i class="fas fa-info"></i>
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
            <div class="notification error">
                <div class="notification-icon">
                    <i class="fas fa-times"></i>
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
            <h1 class="page-title">{{ __('Add New Section') }}</h1>
            <div class="page-actions">
                @php $preselectedParent = request('parent_id'); @endphp
                @if ($preselectedParent)
                    <a href="{{ route('dashboard.category.show', $preselectedParent) }}" class="back-btn professional-back"
                        title="{{ __('Back') }}" aria-label="{{ __('Back') }}">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                @else
                    <a href="{{ route('dashboard.category.index') }}" class="back-btn professional-back"
                        title="{{ __('Back') }}" aria-label="{{ __('Back') }}">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                @endif
            </div>
        </div>

        <form class="form-container" id="categoryForm" action="{{ route('dashboard.category.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <!-- Basic Information -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Basic Information') }}</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label required">{{ __('Section Name') }}</label>
                        <input type="text" class="form-input" name="name"
                            placeholder="{{ __('Enter section name') }}" required>
                        <div class="form-help">{{ __('Section name should be clear and distinctive') }}</div>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    @php
                        $parentCategory = null;
                        if ($preselectedParent) {
                            $parentCategory = collect($categories)->firstWhere('id', (int) $preselectedParent);
                        }
                    @endphp
                    @if ($preselectedParent && $parentCategory)
                        <input type="hidden" name="parent_id" value="{{ $preselectedParent }}">
                        <div class="form-group">
                            <label class="form-label">{{ __('Parent Section') }}</label>
                            <div class="form-input parent-section-display">
                                <i class="fas fa-folder"></i>
                                {{ $parentCategory->getTranslation('name', app()->getLocale()) }}
                            </div>
                            <div class="form-help">
                                {{ __('This section will be created as a subsection of the selected parent') }}</div>
                        </div>
                    @else
                        <div class="form-group">
                            <label class="form-label">{{ __('Parent Section') }}</label>
                            <select class="form-select" name="parent_id">
                                <option value="">{{ __('Main Section') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->getTranslation('name', app()->getLocale()) }}</option>
                                @endforeach
                            </select>
                            <div class="form-help">{{ __('Choose a parent section if this is a subsection') }}</div>
                            @error('parent_id')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="form-label required">{{ __('Section Icon') }}</label>
                        <div class="icon-picker" id="iconPicker">
                            <div class="icon-option" data-icon="fas fa-laptop">
                                <i class="fas fa-laptop"></i>
                            </div>
                            <div class="icon-option" data-icon="fas fa-tshirt">
                                <i class="fas fa-tshirt"></i>
                            </div>
                            <div class="icon-option" data-icon="fas fa-book">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="icon-option" data-icon="fas fa-home">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="icon-option" data-icon="fas fa-dumbbell">
                                <i class="fas fa-dumbbell"></i>
                            </div>
                            <div class="icon-option" data-icon="fas fa-palette">
                                <i class="fas fa-palette"></i>
                            </div>
                            <div class="icon-option" data-icon="fas fa-mobile-alt">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div class="icon-option" data-icon="fas fa-headphones">
                                <i class="fas fa-headphones"></i>
                            </div>
                            <div class="icon-option" data-icon="fas fa-car">
                                <i class="fas fa-car"></i>
                            </div>
                            <div class="icon-option" data-icon="fas fa-gamepad">
                                <i class="fas fa-gamepad"></i>
                            </div>
                            <div class="icon-option" data-icon="fas fa-utensils">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <div class="icon-option" data-icon="fas fa-gift">
                                <i class="fas fa-gift"></i>
                            </div>
                        </div>
                        <input type="hidden" name="icon" id="selectedIcon" required>
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
                        placeholder="{{ __('Enter detailed section description') }}" required></textarea>
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
                            placeholder="{{ __('SEO optimized title') }}">
                        <div class="form-help">{{ __('Title that appears in search results') }}</div>
                        @error('seo_title')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Keywords') }}</label>
                        <input type="text" class="form-input" name="keywords"
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
                        placeholder="{{ __('SEO optimized description') }}"></textarea>
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
                            <option value="1">{{ __('Active') }}</option>
                            <option value="0">{{ __('Inactive') }}</option>
                        </select>
                        @error('is_active')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Display Order') }}</label>
                        <input type="number" class="form-input" name="sort_order" placeholder="0" min="0"
                            value="0">
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
                    <i class="fas fa-plus"></i>
                    {{ __('Add Section') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/notifications.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('modules/category/js/category_create.js') }}?v={{ time() }}"></script>
    <script>
        // Show and auto-hide notifications
        document.addEventListener('DOMContentLoaded', function() {
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => {
                // Show notification with animation
                setTimeout(() => {
                    notification.classList.add('show');
                }, 100);

                // Auto-hide after 5 seconds
                setTimeout(() => {
                    notification.classList.add('hide');
                    setTimeout(() => {
                        notification.remove();
                    }, 400);
                }, 5000);

                // Progress bar animation
                const progressBar = notification.querySelector('.notification-progress');
                if (progressBar) {
                    progressBar.style.width = '100%';
                    progressBar.style.transition = 'width 5s linear';
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
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

        .parent-section-display {
            background: #334155 !important;
            border: 1px solid #475569 !important;
            color: #ffffff !important;
            cursor: default;
            opacity: 0.9;
        }

        html[data-theme="light"] .parent-section-display,
        html[data-theme="light"] body .parent-section-display {
            background: #f9fafb !important;
            border: 1px solid #e5e7eb !important;
            color: #374151 !important;
        }
    </style>
@endpush
