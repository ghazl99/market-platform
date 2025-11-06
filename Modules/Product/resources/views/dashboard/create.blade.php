@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Add New Product'))

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('modules/product/css/product_create.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/notifications.css') }}">

    <style>
        /* Force remove any duplicate background images first */
        select.form-select,
        .form-select {
            background-image: none !important;
        }

        /* تحسين مظهر قائمة الأقسام - تنسيق متجاوب - Single Arrow Only */
        select.form-select,
        .form-select {
            background-color: var(--input-bg, #ffffff) !important;
            border: 1px solid var(--border-color, #e5e7eb) !important;
            color: var(--text-primary, #1f2937) !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: right 0.5rem center !important;
            background-repeat: no-repeat !important;
            background-size: 1.5em 1.5em !important;
            padding-right: 2.5rem !important;
        }

        select.form-select:focus,
        .form-select:focus {
            background-color: var(--input-bg, #ffffff) !important;
            border-color: var(--primary-color, #059669) !important;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23059669' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: right 0.5rem center !important;
            background-repeat: no-repeat !important;
            background-size: 1.5em 1.5em !important;
        }

        /* تنسيق خيارات القائمة المنسدلة */
        .form-select option {
            background-color: var(--card-bg, #ffffff) !important;
            color: var(--text-primary, #1f2937) !important;
            padding: 8px 12px !important;
            font-size: 14px !important;
            line-height: 1.5 !important;
            border: none !important;
        }

        .form-select option[value=""] {
            color: var(--text-secondary, #6b7280) !important;
            font-style: italic !important;
        }

        .form-select option:hover {
            background-color: var(--primary-color, #059669) !important;
            color: #ffffff !important;
        }

        .form-select option:checked {
            background-color: var(--primary-color, #059669) !important;
            color: #ffffff !important;
        }

        /* تنسيق الأقسام الرئيسية */
        .form-select option:not([value=""]) {
            color: var(--text-primary, #1f2937) !important;
        }

        /* تنسيق الأقسام الفرعية */
        .form-select option[style*="└─"] {
            color: var(--text-secondary, #6b7280) !important;
            font-weight: 500 !important;
        }

        /* إصلاح مشكلة عدم ظهور القائمة في بعض المتصفحات */
        .form-select::-ms-expand {
            display: none;
        }

        .form-select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        /* إصلاح مشكلة عدم ظهور القائمة في صفحة الإنشاء */
        select.form-select {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* إصلاح مشكلة عدم ظهور القائمة في المتصفحات المختلفة */
        .form-select {
            min-height: 40px !important;
            height: auto !important;
        }

        /* تحسين مظهر القائمة المنسدلة عند الفتح */
        .form-select:focus {
            outline: none !important;
        }

        /* إصلاح مشكلة عدم ظهور الخيارات */
        .form-select option {
            display: block !important;
            visibility: visible !important;
        }

        /* أنماط الوضع الداكن للقوائم المنسدلة - Maximum Priority */
        [data-theme="dark"] .form-select,
        [data-theme="dark"] body .form-select,
        html[data-theme="dark"] .form-select,
        html[data-theme="dark"] body .form-select {
            background-color: #2d2d2d !important;
            border: 1px solid #404040 !important;
            color: #ffffff !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
        }

        [data-theme="dark"] .form-select:focus,
        [data-theme="dark"] body .form-select:focus,
        html[data-theme="dark"] .form-select:focus,
        html[data-theme="dark"] body .form-select:focus {
            background-color: #2d2d2d !important;
            border-color: #059669 !important;
            color: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.2) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23059669' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
        }

        /* أنماط خيارات القائمة المنسدلة في الوضع الداكن */
        [data-theme="dark"] .form-select option,
        [data-theme="dark"] body .form-select option,
        html[data-theme="dark"] .form-select option,
        html[data-theme="dark"] body .form-select option {
            background-color: #2d2d2d !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] .form-select option[value=""],
        [data-theme="dark"] body .form-select option[value=""],
        html[data-theme="dark"] .form-select option[value=""],
        html[data-theme="dark"] body .form-select option[value=""] {
            background-color: #2d2d2d !important;
            color: #9ca3af !important;
        }

        [data-theme="dark"] .form-select option:hover,
        [data-theme="dark"] body .form-select option:hover,
        html[data-theme="dark"] .form-select option:hover,
        html[data-theme="dark"] body .form-select option:hover {
            background-color: #059669 !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] .form-select option:checked,
        [data-theme="dark"] body .form-select option:checked,
        html[data-theme="dark"] .form-select option:checked,
        html[data-theme="dark"] body .form-select option:checked {
            background-color: #059669 !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] .form-select option:not([value=""]),
        [data-theme="dark"] body .form-select option:not([value=""]),
        html[data-theme="dark"] .form-select option:not([value=""]),
        html[data-theme="dark"] body .form-select option:not([value=""]) {
            background-color: #2d2d2d !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] .form-select option[style*="└─"],
        [data-theme="dark"] body .form-select option[style*="└─"],
        html[data-theme="dark"] .form-select option[style*="└─"],
        html[data-theme="dark"] body .form-select option[style*="└─"] {
            background-color: #2d2d2d !important;
            color: #d1d5db !important;
        }

        /* RTL Support للوضع الداكن */
        [dir="rtl"] [data-theme="dark"] .form-select,
        [dir="rtl"] html[data-theme="dark"] .form-select,
        [dir="rtl"] [data-theme="dark"] body .form-select,
        [dir="rtl"] html[data-theme="dark"] body .form-select {
            background-position: left 12px center !important;
            padding-right: 1rem !important;
            padding-left: 40px !important;
        }

        /* ============================================
                           Light Mode Styles - Maximum Priority
                           ============================================ */

        /* Container and Main Elements */
        html[data-theme="light"] body,
        html[data-theme="light"] .product-create-container,
        html[data-theme="light"] body .product-create-container {
            background: #ffffff !important;
            color: #111827 !important;
        }

        /* Page Header */
        html[data-theme="light"] .page-header,
        html[data-theme="light"] body .page-header {
            background: transparent !important;
            border: none !important;
        }

        html[data-theme="light"] .page-title,
        html[data-theme="light"] body .page-title {
            color: #111827 !important;
        }

        html[data-theme="light"] .back-btn,
        html[data-theme="light"] body .back-btn {
            background: #f3f4f6 !important;
            border: 1px solid #e5e7eb !important;
            color: #374151 !important;
        }

        html[data-theme="light"] .back-btn:hover,
        html[data-theme="light"] body .back-btn:hover {
            background: #059669 !important;
            color: #ffffff !important;
            border-color: #059669 !important;
        }

        /* Form Container and Sections - Force White */
        html[data-theme="light"] .form-container,
        html[data-theme="light"] .form-section,
        html[data-theme="light"] body .form-container,
        html[data-theme="light"] body .form-section,
        html[data-theme="light"] .product-create-container .form-container,
        html[data-theme="light"] .product-create-container .form-section {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .section-title,
        html[data-theme="light"] body .section-title,
        html[data-theme="light"] .form-section .section-title,
        html[data-theme="light"] body .form-section .section-title {
            color: #111827 !important;
            background: #ffffff !important;
            background-color: #ffffff !important;
            border-bottom-color: #059669 !important;
        }

        /* Form Groups */
        html[data-theme="light"] .form-group,
        html[data-theme="light"] body .form-group {
            background: transparent !important;
        }

        /* Form Labels */
        html[data-theme="light"] .form-label,
        html[data-theme="light"] body .form-label {
            color: #374151 !important;
        }

        html[data-theme="light"] .form-label.required::after,
        html[data-theme="light"] body .form-label.required::after {
            color: #ef4444 !important;
        }

        /* Form Inputs - Force White Background */
        html[data-theme="light"] .form-input,
        html[data-theme="light"] body .form-input,
        html[data-theme="light"] input.form-input,
        html[data-theme="light"] body input.form-input {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .form-input:focus,
        html[data-theme="light"] body .form-input:focus,
        html[data-theme="light"] input.form-input:focus,
        html[data-theme="light"] body input.form-input:focus {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border-color: #059669 !important;
            color: #111827 !important;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1) !important;
        }

        html[data-theme="light"] .form-input::placeholder,
        html[data-theme="light"] body .form-input::placeholder {
            color: #9ca3af !important;
        }

        html[data-theme="light"] .form-input[readonly],
        html[data-theme="light"] body .form-input[readonly],
        html[data-theme="light"] input.form-input[readonly],
        html[data-theme="light"] body input.form-input[readonly] {
            background: #f3f4f6 !important;
            background-color: #f3f4f6 !important;
            border-color: #e5e7eb !important;
            color: #6b7280 !important;
            cursor: not-allowed !important;
        }

        /* Form Textarea - Force White Background */
        html[data-theme="light"] .form-textarea,
        html[data-theme="light"] body .form-textarea,
        html[data-theme="light"] textarea.form-input,
        html[data-theme="light"] body textarea.form-input {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .form-textarea:focus,
        html[data-theme="light"] body .form-textarea:focus,
        html[data-theme="light"] textarea.form-input:focus,
        html[data-theme="light"] body textarea.form-input:focus {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border-color: #059669 !important;
            color: #111827 !important;
        }

        /* Form Select (Light Mode) - Single Arrow Only */
        html[data-theme="light"] .form-select,
        html[data-theme="light"] body .form-select,
        html[data-theme="light"] select.form-select,
        html[data-theme="light"] body select.form-select {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            color: #111827 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23111827' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: right 0.5rem center !important;
            background-repeat: no-repeat !important;
            background-size: 1.5em 1.5em !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
        }

        html[data-theme="light"] .form-select:focus,
        html[data-theme="light"] body .form-select:focus,
        html[data-theme="light"] select.form-select:focus,
        html[data-theme="light"] body select.form-select:focus {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border-color: #059669 !important;
            color: #111827 !important;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23059669' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: right 0.5rem center !important;
            background-repeat: no-repeat !important;
            background-size: 1.5em 1.5em !important;
        }

        html[data-theme="light"] .form-select option,
        html[data-theme="light"] body .form-select option {
            background: #ffffff !important;
            background-color: #ffffff !important;
            color: #111827 !important;
        }

        /* Form Help Text */
        html[data-theme="light"] .form-help,
        html[data-theme="light"] body .form-help {
            color: #6b7280 !important;
        }

        /* Form Error Messages */
        html[data-theme="light"] .form-error,
        html[data-theme="light"] body .form-error {
            color: #ef4444 !important;
            background: #fef2f2 !important;
            border-color: #fecaca !important;
        }

        /* File Upload Area - Force White */
        html[data-theme="light"] .file-upload,
        html[data-theme="light"] .file-upload-area,
        html[data-theme="light"] .file-upload-label,
        html[data-theme="light"] body .file-upload,
        html[data-theme="light"] body .file-upload-area,
        html[data-theme="light"] body .file-upload-label {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border: 2px dashed #e5e7eb !important;
            color: #6b7280 !important;
        }

        html[data-theme="light"] .file-upload:hover,
        html[data-theme="light"] .file-upload-area:hover,
        html[data-theme="light"] .file-upload-label:hover,
        html[data-theme="light"] body .file-upload:hover,
        html[data-theme="light"] body .file-upload-area:hover,
        html[data-theme="light"] body .file-upload-label:hover {
            background: #f9fafb !important;
            background-color: #f9fafb !important;
            border-color: #059669 !important;
        }

        html[data-theme="light"] .file-upload-icon,
        html[data-theme="light"] .file-upload-text,
        html[data-theme="light"] .file-upload-hint,
        html[data-theme="light"] body .file-upload-icon,
        html[data-theme="light"] body .file-upload-text,
        html[data-theme="light"] body .file-upload-hint {
            color: #6b7280 !important;
        }

        /* Buttons */
        html[data-theme="light"] .btn-primary,
        html[data-theme="light"] .btn-submit,
        html[data-theme="light"] body .btn-primary,
        html[data-theme="light"] body .btn-submit {
            background: #059669 !important;
            color: #ffffff !important;
            border-color: #059669 !important;
        }

        html[data-theme="light"] .btn-primary:hover,
        html[data-theme="light"] .btn-submit:hover,
        html[data-theme="light"] body .btn-primary:hover,
        html[data-theme="light"] body .btn-submit:hover {
            background: #047857 !important;
            border-color: #047857 !important;
        }

        html[data-theme="light"] .btn-secondary,
        html[data-theme="light"] body .btn-secondary {
            background: #f3f4f6 !important;
            color: #374151 !important;
            border-color: #e5e7eb !important;
        }

        html[data-theme="light"] .btn-secondary:hover,
        html[data-theme="light"] body .btn-secondary:hover {
            background: #e5e7eb !important;
            border-color: #d1d5db !important;
        }

        /* Form Actions Container - Force White */
        html[data-theme="light"] .form-actions,
        html[data-theme="light"] body .form-actions,
        html[data-theme="light"] .product-create-container .form-actions {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border-top: 1px solid #e5e7eb !important;
            color: #111827 !important;
        }

        /* Ensure buttons inside form-actions keep their colors */
        html[data-theme="light"] .form-actions .btn-primary,
        html[data-theme="light"] .form-actions .btn.btn-primary,
        html[data-theme="light"] body .form-actions .btn-primary,
        html[data-theme="light"] body .form-actions .btn.btn-primary {
            background: #059669 !important;
            background-color: #059669 !important;
            color: #ffffff !important;
            border-color: #059669 !important;
        }

        html[data-theme="light"] .form-actions .btn-secondary,
        html[data-theme="light"] .form-actions .btn.btn-secondary,
        html[data-theme="light"] body .form-actions .btn-secondary,
        html[data-theme="light"] body .form-actions .btn.btn-secondary {
            background: #f3f4f6 !important;
            background-color: #f3f4f6 !important;
            color: #374151 !important;
            border-color: #e5e7eb !important;
        }

        html[data-theme="light"] .form-actions .btn-primary:hover,
        html[data-theme="light"] .form-actions .btn.btn-primary:hover,
        html[data-theme="light"] body .form-actions .btn-primary:hover,
        html[data-theme="light"] body .form-actions .btn.btn-primary:hover {
            background: #047857 !important;
            background-color: #047857 !important;
            border-color: #047857 !important;
        }

        html[data-theme="light"] .form-actions .btn-secondary:hover,
        html[data-theme="light"] .form-actions .btn.btn-secondary:hover,
        html[data-theme="light"] body .form-actions .btn-secondary:hover,
        html[data-theme="light"] body .form-actions .btn.btn-secondary:hover {
            background: #e5e7eb !important;
            background-color: #e5e7eb !important;
            border-color: #d1d5db !important;
        }

        /* Parent Product Section */
        .parent-product-section {
            background: #2d2d2d;
            border: 1px solid #404040;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .parent-product-title {
            color: #f59e0b;
        }

        .parent-product-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
        }

        .parent-product-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            border: 2px solid #404040;
            object-fit: cover;
        }

        .parent-product-name {
            margin: 0;
            color: #ffffff;
            font-weight: 600;
        }

        .parent-product-details {
            margin: 0.25rem 0 0 0;
            color: #9ca3af;
            font-size: 0.9rem;
        }

        /* Light Mode for Parent Product Section */
        html[data-theme="light"] .parent-product-section,
        html[data-theme="light"] body .parent-product-section {
            background: #fef3c7 !important;
            border: 1px solid #fbbf24 !important;
        }

        html[data-theme="light"] .parent-product-title,
        html[data-theme="light"] body .parent-product-title {
            color: #92400e !important;
        }

        html[data-theme="light"] .parent-product-name,
        html[data-theme="light"] body .parent-product-name {
            color: #78350f !important;
        }

        html[data-theme="light"] .parent-product-details,
        html[data-theme="light"] body .parent-product-details {
            color: #92400e !important;
        }

        html[data-theme="light"] .parent-product-image,
        html[data-theme="light"] body .parent-product-image {
            border-color: #fbbf24 !important;
        }

        /* RTL Support for Light Mode */
        [dir="rtl"] html[data-theme="light"] .form-select,
        [dir="rtl"] html[data-theme="light"] body .form-select {
            background-position: left 12px center !important;
            padding-right: 1rem !important;
            padding-left: 40px !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const theme = document.documentElement.getAttribute('data-theme');
            if (theme === 'light') {
                document.body.offsetHeight;
                document.documentElement.setAttribute('data-theme', 'light');

                // Force apply light theme to all inputs and containers
                const inputs = document.querySelectorAll('.form-input, .form-select, .form-textarea');
                inputs.forEach(input => {
                    if (input.classList.contains('form-select')) {
                        // For selects, ensure single arrow and white background
                        input.style.cssText +=
                            'background: #ffffff !important; background-color: #ffffff !important; border-color: #e5e7eb !important; color: #111827 !important; background-image: url("data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 20 20\'%3e%3cpath stroke=\'%23111827\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'m6 8 4 4 4-4\'/%3e%3c/svg%3e") !important; background-position: right 0.5rem center !important; background-repeat: no-repeat !important; background-size: 1.5em 1.5em !important; -webkit-appearance: none !important; -moz-appearance: none !important; appearance: none !important;';
                    } else if (input.readOnly) {
                        input.style.cssText +=
                            'background: #f3f4f6 !important; background-color: #f3f4f6 !important; border-color: #e5e7eb !important; color: #6b7280 !important;';
                    } else {
                        input.style.cssText +=
                            'background: #ffffff !important; background-color: #ffffff !important; border-color: #e5e7eb !important; color: #111827 !important;';
                    }
                });

                // Force white background for form containers
                const containers = document.querySelectorAll('.form-container, .form-section');
                containers.forEach(container => {
                    container.style.cssText +=
                        'background: #ffffff !important; background-color: #ffffff !important;';
                });

                // Force white for section titles
                const titles = document.querySelectorAll('.section-title');
                titles.forEach(title => {
                    title.style.cssText +=
                        'background: #ffffff !important; background-color: #ffffff !important; color: #111827 !important;';
                });

                // Force white for file upload area
                const fileUploads = document.querySelectorAll(
                    '.file-upload, .file-upload-area, .file-upload-label');
                fileUploads.forEach(upload => {
                    upload.style.cssText +=
                        'background: #ffffff !important; background-color: #ffffff !important; border-color: #e5e7eb !important;';
                });

                // Force white for form actions container
                const formActions = document.querySelectorAll('.form-actions');
                formActions.forEach(action => {
                    action.style.cssText +=
                        'background: #ffffff !important; background-color: #ffffff !important; border-top-color: #e5e7eb !important;';
                });

                // Ensure buttons keep their colors and text is visible
                const primaryButtons = document.querySelectorAll(
                    '.form-actions .btn-primary, .form-actions .btn.btn-primary');
                primaryButtons.forEach(btn => {
                    btn.style.cssText +=
                        'background: #059669 !important; background-color: #059669 !important; color: #ffffff !important; border-color: #059669 !important;';
                });

                const secondaryButtons = document.querySelectorAll(
                    '.form-actions .btn-secondary, .form-actions .btn.btn-secondary');
                secondaryButtons.forEach(btn => {
                    btn.style.cssText +=
                        'background: #f3f4f6 !important; background-color: #f3f4f6 !important; color: #374151 !important; border-color: #e5e7eb !important;';
                });
            }
        });
    </script>
@endpush

@section('content')
    <div class="product-create-container">
        <div class="page-header">
            <h1 class="page-title">{{ __('Add New Product') }}</h1>
            <div class="page-actions">
                <a href="{{ route('dashboard.product.index') }}" class="back-btn">
                    <i class="fas fa-arrow-right"></i>
                    {{ __('Back to Products') }}
                </a>
            </div>
        </div>

        @if (isset($parentProduct))
            <div class="form-section parent-product-section">
                <h3 class="section-title parent-product-title">
                    <i class="fas fa-layer-group"></i>
                    {{ __('Creating Sub-Product') }}
                </h3>
                <div class="parent-product-info">
                    @if ($parentProduct->getFirstMedia('product_images'))
                        @php $parentProductMedia = $parentProduct->getFirstMedia('product_images'); @endphp
                        <img src="{{ route('dashboard.product.image', $parentProductMedia->id) }}"
                            alt="{{ $parentProduct->name }}" class="parent-product-image">
                    @endif
                    <div>
                        <p class="parent-product-name">
                            {{ __('Parent Product') }}: {{ $parentProduct->getTranslation('name', app()->getLocale()) }}
                        </p>
                        <p class="parent-product-details">
                            ID: {{ $parentProduct->id }} | ${{ number_format($parentProduct->price, 2) }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <form class="form-container" id="productForm" method="POST" action="{{ route('dashboard.product.store') }}"
            enctype="multipart/form-data">
            @csrf

            @if (isset($parentProduct))
                <input type="hidden" name="parent_id" value="{{ $parentProduct->id }}">
            @endif

            <!-- Basic Information -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Basic Information') }}</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label required">{{ __('Product Name') }}</label>
                        <input type="text" class="form-input @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name') }}" placeholder="{{ __('Enter product name') }}" required>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('Product name should be clear and distinctive') }}</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">{{ __('Price') }}</label>
                        <input type="number" class="form-input @error('price') is-invalid @enderror" name="price"
                            value="{{ old('price') }}" placeholder="0.00" step="0.01" required>
                        @error('price')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('Enter selling price in USD') }}</div>
                    </div>


                    @if (isset($selectedCategory) && !isset($parentProduct))
                        {{-- إذا كان هناك قسم محدد من صفحة المنتجات الخاصة بقسم --}}
                        <input type="hidden" name="category" value="{{ $selectedCategory->id }}">
                        <div class="form-group">
                            <label class="form-label required">{{ __('Category') }}</label>
                            <input type="text" class="form-input" readonly
                                value="{{ $selectedCategory->getTranslation('name', app()->getLocale()) }}">
                            <div class="form-help">{{ __('Category is set from the section you are adding from') }}</div>
                        </div>
                    @elseif (isset($parentProduct))
                        {{-- إذا كان منتج فرعي، نسخ الفئة من المنتج الأب تلقائيًا --}}
                        @php
                            $parentCategoryId = $parentProduct->categories->first()->id ?? '';
                            $parentCategoryName = $parentProduct->categories->first()
                                ? $parentProduct->categories->first()->getTranslation('name', app()->getLocale())
                                : __('No category');
                        @endphp
                        @if ($parentCategoryId)
                            <input type="hidden" name="category" value="{{ $parentCategoryId }}">
                        @endif
                        <div class="form-group">
                            <label class="form-label required">{{ __('Category') }}</label>
                            <input type="text" class="form-input" readonly value="{{ $parentCategoryName }}">
                            <div class="form-help">{{ __('Category will be inherited from parent product') }}</div>
                        </div>
                    @else
                        {{-- الحالة العادية: عرض قائمة الفئات للاختيار --}}
                        <div class="form-group">
                            <label class="form-label required">{{ __('Category') }}</label>
                            <select class="form-select @error('category') is-invalid @enderror" name="category" required>
                                <option value="">{{ __('Choose Category') }}</option>
                                @if (isset($categories) && $categories->count() > 0)
                                    @foreach ($categories as $category)
                                        @if ($category->parent_id)
                                            {{-- الأقسام الفرعية --}}
                                            <option value="{{ $category->id }}"
                                                {{ old('category') == $category->id ? 'selected' : '' }}>
                                                &nbsp;&nbsp;&nbsp;└─
                                                {{ $category->getTranslation('name', app()->getLocale()) }}
                                            </option>
                                        @else
                                            {{-- الأقسام الرئيسية --}}
                                            <option value="{{ $category->id }}"
                                                {{ old('category') == $category->id ? 'selected' : '' }}>
                                                📁 {{ $category->getTranslation('name', app()->getLocale()) }}
                                            </option>
                                            {{-- عرض الأقسام الفرعية تحت القسم الرئيسي --}}
                                            @if ($category->children && $category->children->count() > 0)
                                                @foreach ($category->children as $child)
                                                    <option value="{{ $child->id }}"
                                                        {{ old('category') == $child->id ? 'selected' : '' }}>
                                                        &nbsp;&nbsp;&nbsp;└─
                                                        {{ $child->getTranslation('name', app()->getLocale()) }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        @endif
                                    @endforeach
                                @else
                                    <option value="" disabled>{{ __('No categories available') }}</option>
                                @endif
                            </select>
                            @error('category')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                            <div class="form-help">{{ __('Select the appropriate category for your product') }}</div>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Description -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Description and Details') }}</h3>
                <div class="form-group full-width">
                    <label
                        class="form-label {{ !isset($parentProduct) ? 'required' : '' }}">{{ __('Product Description') }}</label>
                    <textarea class="form-input form-textarea @error('description') is-invalid @enderror" name="description"
                        placeholder="{{ __('Enter detailed product description') }}" {{ !isset($parentProduct) ? 'required' : '' }}>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="form-help">
                        @if (isset($parentProduct))
                            {{ __('Description is optional for sub-products') }}
                        @else
                            {{ __('Comprehensive product description helps customers understand it better') }}
                        @endif
                    </div>
                </div>

            </div>

            <!-- Images -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Product Images') }}</h3>
                <div class="form-group full-width">
                    <label class="form-label required">{{ __('Product Images') }}</label>
                    <div class="file-upload">
                        <input type="file" class="file-upload-input" id="productImages" name="image"
                            accept="image/*">
                        <label for="productImages" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                            <span class="file-upload-text">{{ __('Drag images here or click to select') }}</span>
                            <span
                                class="file-upload-hint">{{ __('You can upload multiple images (JPG, PNG, GIF)') }}</span>
                        </label>
                    </div>
                    @error('image')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="image-preview" id="imagePreview"></div>
                </div>
            </div>

            <!-- SEO -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Search Engine Optimization (SEO)') }}</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">{{ __('SEO Title') }}</label>
                        <input type="text" class="form-input @error('seo_title') is-invalid @enderror"
                            name="seo_title" value="{{ old('seo_title') }}"
                            placeholder="{{ __('SEO optimized title') }}">
                        @error('seo_title')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('Title that appears in search results') }}</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ __('Keywords') }}</label>
                        <input type="text" class="form-input @error('keywords') is-invalid @enderror" name="keywords"
                            value="{{ old('keywords') }}" placeholder="{{ __('keyword1, keyword2, keyword3') }}">
                        @error('keywords')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <div class="form-help">{{ __('Separated by commas') }}</div>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label class="form-label">{{ __('SEO Description') }}</label>
                    <textarea class="form-input form-textarea @error('seo_description') is-invalid @enderror" name="seo_description"
                        placeholder="{{ __('SEO optimized description') }}">{{ old('seo_description') }}</textarea>
                    @error('seo_description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div class="form-section">
                <h3 class="section-title">{{ __('Status and Settings') }}</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label required">{{ __('Product Status') }}</label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}
                            </option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                {{ __('Inactive') }}</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>{{ __('Draft') }}
                            </option>
                        </select>
                        @error('status')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ __('Featured') }}</label>
                        <select class="form-select @error('featured') is-invalid @enderror" name="featured">
                            <option value="0" {{ old('featured') == '0' ? 'selected' : '' }}>{{ __('Normal') }}
                            </option>
                            <option value="1" {{ old('featured') == '1' ? 'selected' : '' }}>{{ __('Featured') }}
                            </option>
                        </select>
                        @error('featured')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>


            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" class="btn btn-secondary"
                    onclick="window.location.href='{{ route('dashboard.product.index') }}'">
                    <i class="fas fa-times"></i>
                    {{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-secondary" id="saveDraftBtn">
                    <i class="fas fa-save"></i>
                    {{ __('Save as Draft') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    {{ __('Add Product') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/notifications.js') }}"></script>
    <script>
        // Wait for notifications to load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Notifications script loaded');
            if (typeof showSuccess === 'function') {
                console.log('showSuccess function is available');
            } else {
                console.log('showSuccess function is NOT available');
                // Create fallback functions
                window.showSuccess = function(title, message) {
                    console.log('Fallback showSuccess:', title, message);
                    alert(title + ': ' + message);
                };
                window.showError = function(title, message) {
                    console.log('Fallback showError:', title, message);
                    alert(title + ': ' + message);
                };
            }
        });
    </script>
    <script src="{{ asset('modules/product/product_create_simple_fix.js') }}"></script>
    <script>
        // Handle Laravel session messages
        @if (session('success'))
            showSuccess('نجح!', '{{ session('success') }}');
        @endif

        @if (session('error'))
            showError('خطأ!', '{{ session('error') }}');
        @endif

        @if (session('warning'))
            showWarning('تحذير!', '{{ session('warning') }}');
        @endif

        @if (session('info'))
            showInfo('معلومة', '{{ session('info') }}');
        @endif

        // Handle validation errors
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                showError('خطأ في التحقق', '{{ $error }}');
            @endforeach
        @endif

        // التحقق من وجود الأقسام وإصلاح مشاكل القائمة المنسدلة
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.querySelector('select[name="category"]');

            if (categorySelect) {
                console.log('Category select found:', categorySelect);
                console.log('Number of options:', categorySelect.options.length);

                // إصلاح مشكلة عدم ظهور القائمة
                categorySelect.style.display = 'block';
                categorySelect.style.visibility = 'visible';
                categorySelect.style.opacity = '1';

                // إضافة event listener للتأكد من عمل القائمة
                categorySelect.addEventListener('change', function() {
                    console.log('Category changed to:', this.value);
                });

                // إصلاح مشكلة عدم ظهور الخيارات في بعض المتصفحات
                categorySelect.addEventListener('click', function() {
                    this.style.zIndex = '9999';
                });

                categorySelect.addEventListener('blur', function() {
                    this.style.zIndex = 'auto';
                });
            } else {
                console.error('Category select not found!');
            }
        });
    </script>
@endpush
