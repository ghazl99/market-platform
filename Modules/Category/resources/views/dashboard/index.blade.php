@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Manage Sections'))

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('modules/category/css/categories.css') }}">
@endpush




@section('content')
    <div class="categories-container">
        <!-- Breadcrumb Navigation -->
        @if ($parentCategory)
            <div class="breadcrumb-nav">

                <i class="fas fa-chevron-right breadcrumb-separator"></i>
                <span class="breadcrumb-current">
                    <i class="fas fa-folder"></i>
                    {{ $parentCategory->getTranslation('name', app()->getLocale()) }}
                </span>
            </div>
        @endif

        <div class="page-header">
            <h1 class="page-title">
                @if ($parentCategory)
                    {{ __('Subsections of') }}: {{ $parentCategory->getTranslation('name', app()->getLocale()) }}
                @else
                    {{ __('Manage Sections') }}
                @endif
            </h1>
            <div class="page-actions">
                @if ($parentCategory)
                    <a href="{{ route('dashboard.category.index') }}" class="back-btn" title="{{ __('Back') }}"
                        aria-label="{{ __('Back') }}">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                @endif
                <a href="{{ $parentCategory ? route('dashboard.category.create', ['parent_id' => $parentCategory->id]) : route('dashboard.category.create') }}"
                    class="add-category-btn">
                    <i class="fas fa-plus"></i>
                    {{ __('Add New Section') }}
                </a>
            </div>
        </div>


        <!-- Categories Grid -->
        <div class="categories-grid" id="categoriesGrid">
            @forelse ($categories as $category)
                <div class="category-card">
                    <div class="category-header">
                        <div class="category-icon {{ $category->slug ?? 'default' }}">
                            @php
                                $media = $category->getFirstMedia('category_images');
                            @endphp
                            @if ($media)
                                <img src="{{ route('dashboard.category.image', $media->id) }}"
                                    alt="{{ $category->getTranslation('name', app()->getLocale()) }}"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <i class="fas fa-tag" style="display: none;"></i>
                            @else
                                <i class="fas fa-tag"></i>
                            @endif
                        </div>
                    </div>
                    <div class="category-info">
                        <h3 class="category-name">{{ $category->getTranslation('name', app()->getLocale()) }}</h3>
                        <p class="category-description">
                            {{ $category->getTranslation('description', app()->getLocale()) ?? __('No description available') }}
                        </p>
                        <div class="category-stats">
                            <div class="stat-item">
                                <span class="stat-number">{{ $category->products_count ?? 0 }}</span>
                                <span class="stat-label">{{ __('Products') }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ $category->children->count() }}</span>
                                <span class="stat-label">{{ __('Subsections') }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ $category->is_active ? '100%' : '0%' }}</span>
                                <span class="stat-label">{{ __('Active') }}</span>
                            </div>
                        </div>

                        <!-- مؤشر ذكي يوضح ما سيحدث عند الضغط على الزر -->
                        @if (!$parentCategory)
                            <div class="smart-indicator">
                                @if ($category->children && $category->children->count() > 0)
                                    <div class="indicator-item">
                                        <i class="fas fa-folder-open indicator-icon"></i>
                                        <span class="indicator-text">{{ __('Click to view') }}
                                            {{ $category->children->count() }} {{ __('subsections') }}</span>
                                    </div>
                                @else
                                    <div class="indicator-item">
                                        <i class="fas fa-box indicator-icon"></i>
                                        <span class="indicator-text">{{ __('Click to view') }}
                                            {{ $category->products_count ?? 0 }} {{ __('products') }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                        @if ($category->children->count() > 0)
                            <div class="subcategories-section">
                                <h4 class="subcategories-title">{{ __('Subsections') }}:</h4>
                                <div class="subcategories-list">
                                    @foreach ($category->children->take(4) as $child)
                                        <span
                                            class="subcategory-tag">{{ $child->getTranslation('name', app()->getLocale()) }}</span>
                                    @endforeach
                                    @if ($category->children->count() > 4)
                                        <span class="subcategory-tag">+{{ $category->children->count() - 4 }}
                                            {{ __('more') }}</span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <!-- placeholder فارغ لضمان محاذاة الأزرار في الأسفل -->
                            <div class="subcategories-placeholder"></div>
                        @endif
                    </div>
                    <div class="category-actions">
                        @if ($parentCategory)
                            <!-- في الأقسام الفرعية: زر عرض المنتجات -->
                            <a href="{{ route('dashboard.product.category', $category->id) }}"
                                class="action-btn products">
                                <i class="fas fa-box"></i>
                                {{ __('View Products') }}
                            </a>
                        @else
                            <!-- في الأقسام الرئيسية: منطق ذكي للعرض -->
                            @if ($category->children && $category->children->count() > 0)
                                <!-- إذا كان للقسم الرئيسي أقسام فرعية: عرض الأقسام الفرعية -->
                                <a href="{{ route('dashboard.category.show', $category->id) }}" class="action-btn view">
                                    <i class="fas fa-folder-open"></i>
                                    {{ __('View Subsections') }}
                                </a>
                            @else
                                <!-- إذا لم يكن له أقسام فرعية: زر عرض المنتجات وزر إضافة قسم فرعي -->
                                <a href="{{ route('dashboard.product.category', $category->id) }}"
                                    class="action-btn products">
                                    <i class="fas fa-box"></i>
                                    {{ __('View Products') }}
                                </a>
                                <a href="{{ route('dashboard.category.create', ['parent_id' => $category->id]) }}"
                                    class="action-btn add-subsection">
                                    <i class="fas fa-plus-circle"></i>
                                    {{ __('Add Subsection') }}
                                </a>
                            @endif
                        @endif
                        <a href="{{ route('dashboard.category.edit', $category->id) }}" class="action-btn edit">
                            <i class="fas fa-edit"></i>
                            {{ __('Edit') }}
                        </a>
                        <form action="{{ route('dashboard.category.destroy', $category->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete delete-btn">
                                <i class="fas fa-trash"></i>
                                {{ __('Delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-tags"></i>
                    <h3>{{ __('No Sections Found') }}</h3>
                    <p>{{ __('Start by adding your first section.') }}</p>
                    <a href="{{ route('dashboard.category.create') }}" class="add-category-btn">
                        <i class="fas fa-plus"></i>
                        {{ __('Add New Section') }}
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Custom Delete Confirmation Modal -->
    <div id="deleteModal" class="delete-modal" style="display: none;">
        <div class="delete-modal-overlay"></div>
        <div class="delete-modal-content">
            <div class="delete-modal-header">
                <h3>{{ __('Confirm Delete') }}</h3>
                <button type="button" class="delete-modal-close">&times;</button>
            </div>
            <div class="delete-modal-body">
                <div class="delete-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <p class="delete-message">
                    {{ __('Are you sure you want to delete the section') }} <strong id="categoryNameToDelete"></strong>?
                </p>
                <p class="delete-warning">
                    {{ __('This action cannot be undone.') }}
                </p>
            </div>
            <div class="delete-modal-footer">
                <button type="button" class="btn btn-secondary delete-modal-cancel">
                    {{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-danger delete-modal-confirm">
                    <i class="fas fa-trash"></i>
                    {{ __('Delete Section') }}
                </button>
            </div>
        </div>
    </div>

    <style>
        /* Smart Indicator Styles */
        .smart-indicator {
            margin-top: 1rem;
            padding: 0.75rem;
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 8px;
            animation: smartIndicatorPulse 2s ease-in-out infinite;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        [data-theme="light"] .smart-indicator {
            background: rgba(59, 130, 246, 0.05);
            border: 1px solid rgba(59, 130, 246, 0.15);
        }

        [data-theme="dark"] .smart-indicator {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .smart-indicator::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        .indicator-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-primary);
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        [data-theme="light"] .indicator-item {
            color: #111827;
        }

        [data-theme="dark"] .indicator-item {
            color: #ffffff;
        }

        .indicator-icon {
            font-size: 1rem;
            animation: iconBounce 1.5s ease-in-out infinite;
        }

        .indicator-text {
            flex: 1;
        }

        @keyframes smartIndicatorPulse {

            0%,
            100% {
                background: rgba(59, 130, 246, 0.1);
                border-color: rgba(59, 130, 246, 0.2);
            }

            50% {
                background: rgba(59, 130, 246, 0.15);
                border-color: rgba(59, 130, 246, 0.3);
            }
        }

        @keyframes iconBounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-2px);
            }
        }

        /* تحسين مظهر الأزرار بناءً على النوع */
        .action-btn {
            transition: all 0.3s ease;
        }

        .action-btn.view {
            background: linear-gradient(135deg, #10b981, #059669);
            border: 1px solid #059669;
            color: #ffffff !important;
        }

        .action-btn.view:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            color: #ffffff !important;
        }

        .action-btn.products {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: 1px solid #2563eb;
            color: #ffffff !important;
        }

        .action-btn.products:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            color: #ffffff !important;
        }

        .action-btn.add-subsection {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            border: 1px solid #7c3aed;
            color: #ffffff !important;
        }

        .action-btn.add-subsection:hover {
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
            color: #ffffff !important;
        }

        /* CSS للأقسام الفرعية والـ placeholder */
        .subcategories-section {
            margin-top: 1rem;
            padding: 0.75rem;
            background: rgba(59, 130, 246, 0.05);
            border-radius: 8px;
            border: 1px solid rgba(59, 130, 246, 0.1);
            transition: all 0.3s ease;
        }

        [data-theme="light"] .subcategories-section {
            background: rgba(59, 130, 246, 0.03);
            border: 1px solid rgba(59, 130, 246, 0.08);
        }

        [data-theme="dark"] .subcategories-section {
            background: rgba(59, 130, 246, 0.05);
            border: 1px solid rgba(59, 130, 246, 0.1);
        }

        .subcategories-title {
            color: var(--text-primary);
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            transition: color 0.3s ease;
        }

        [data-theme="light"] .subcategories-title {
            color: #111827;
        }

        [data-theme="dark"] .subcategories-title {
            color: #ffffff;
        }

        .subcategories-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .subcategory-tag {
            background: var(--bg-secondary);
            color: var(--text-primary);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        [data-theme="light"] .subcategory-tag {
            background: #f9fafb;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        [data-theme="dark"] .subcategory-tag {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Placeholder فارغ لضمان محاذاة الأزرار */
        .subcategories-placeholder {
            margin-top: 1rem;
            height: 60px;
            /* نفس ارتفاع subcategories-section */
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* تحسين محاذاة الأزرار */
        .category-card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .category-info {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .category-actions {
            margin-top: auto;
            /* دفع الأزرار للأسفل */
            padding-top: 1rem;
        }

        /* Custom Delete Modal Styles */
        .delete-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            font-family: 'Cairo', sans-serif;
        }

        .delete-modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .delete-modal-content {
            background: var(--bg-primary);
            border-radius: 16px;
            padding: 0;
            max-width: 500px;
            width: 90%;
            position: relative;
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--border-color);
            animation: modalSlideIn 0.3s ease-out;
            transition: all 0.3s ease;
        }

        [data-theme="light"] .delete-modal-content {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        [data-theme="dark"] .delete-modal-content {
            background: #2d2d2d;
            border: 1px solid #404040;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .delete-modal-header {
            padding: 1.5rem 2rem 1rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: border-color 0.3s ease;
        }

        .delete-modal-header h3 {
            color: var(--text-primary);
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        [data-theme="light"] .delete-modal-header {
            border-bottom: 1px solid #e5e7eb;
        }

        [data-theme="light"] .delete-modal-header h3 {
            color: #111827;
        }

        [data-theme="dark"] .delete-modal-header {
            border-bottom: 1px solid #404040;
        }

        [data-theme="dark"] .delete-modal-header h3 {
            color: #ffffff;
        }

        .delete-modal-close {
            background: none;
            border: none;
            color: var(--text-light);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .delete-modal-close:hover {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        [data-theme="light"] .delete-modal-close {
            color: #6b7280;
        }

        [data-theme="light"] .delete-modal-close:hover {
            background: #f3f4f6;
            color: #111827;
        }

        [data-theme="dark"] .delete-modal-close {
            color: #888888;
        }

        [data-theme="dark"] .delete-modal-close:hover {
            background: #404040;
            color: #ffffff;
        }

        .delete-modal-body {
            padding: 2rem;
            text-align: center;
        }

        .delete-icon {
            margin-bottom: 1rem;
        }

        .delete-icon i {
            font-size: 3rem;
            color: #f59e0b;
        }

        .delete-message {
            color: var(--text-primary);
            font-size: 1.1rem;
            margin-bottom: 1rem;
            line-height: 1.5;
            transition: color 0.3s ease;
        }

        .delete-warning {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 0;
            transition: color 0.3s ease;
        }

        [data-theme="light"] .delete-message {
            color: #111827;
        }

        [data-theme="light"] .delete-warning {
            color: #6b7280;
        }

        [data-theme="dark"] .delete-message {
            color: #ffffff;
        }

        [data-theme="dark"] .delete-warning {
            color: #888888;
        }

        .delete-modal-footer {
            padding: 1rem 2rem 1.5rem;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-secondary {
            background: #6b7280;
            color: #ffffff;
        }

        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: #ef4444;
            color: #ffffff;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .delete-modal-content {
                width: 95%;
                margin: 1rem;
            }

            .delete-modal-header,
            .delete-modal-body,
            .delete-modal-footer {
                padding: 1rem;
            }

            .delete-modal-footer {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <!-- Professional Notification Styles -->
    <style>
        /* Professional Notification Styles */
        .notification-container {
            position: fixed !important;
            top: 20px !important;
            right: 20px !important;
            z-index: 9999 !important;
            max-width: 400px !important;
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
        html[data-theme="light"] .categories-container,
        html[data-theme="light"] body .categories-container,
        body html[data-theme="light"] .categories-container {
            background: #ffffff !important;
        }

        html[data-theme="light"] .category-card,
        html[data-theme="light"] body .category-card,
        body html[data-theme="light"] .category-card {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1) !important;
        }

        html[data-theme="light"] .category-card:hover,
        html[data-theme="light"] body .category-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }

        html[data-theme="light"] .category-name,
        html[data-theme="light"] body .category-name {
            color: #111827 !important;
        }

        html[data-theme="light"] .category-description,
        html[data-theme="light"] body .category-description {
            color: #374151 !important;
        }

        html[data-theme="light"] .stat-number,
        html[data-theme="light"] body .stat-number {
            color: #111827 !important;
        }

        html[data-theme="light"] .stat-label,
        html[data-theme="light"] body .stat-label {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .category-actions,
        html[data-theme="light"] body .category-actions {
            border-top: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .subcategories-section,
        html[data-theme="light"] body .subcategories-section {
            border-top: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .subcategories-title,
        html[data-theme="light"] body .subcategories-title {
            color: #111827 !important;
        }

        html[data-theme="light"] .subcategory-tag,
        html[data-theme="light"] body .subcategory-tag {
            background: #f9fafb !important;
            color: #374151 !important;
            border: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .action-btn.edit,
        html[data-theme="light"] body .action-btn.edit {
            background: #f3f4f6 !important;
            color: #374151 !important;
            border: 1px solid #e5e7eb !important;
        }

        html[data-theme="light"] .action-btn.edit:hover,
        html[data-theme="light"] body .action-btn.edit:hover {
            background: #e5e7eb !important;
            color: #111827 !important;
        }

        html[data-theme="light"] .breadcrumb-nav,
        html[data-theme="light"] body .breadcrumb-nav {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1) !important;
        }

        html[data-theme="light"] .breadcrumb-current,
        html[data-theme="light"] body .breadcrumb-current {
            color: #111827 !important;
        }

        html[data-theme="light"] .page-title,
        html[data-theme="light"] body .page-title {
            color: #111827 !important;
        }

        html[data-theme="light"] .empty-state,
        html[data-theme="light"] body .empty-state {
            color: #6b7280 !important;
        }

        html[data-theme="light"] .empty-state i,
        html[data-theme="light"] body .empty-state i {
            color: #9ca3af !important;
        }

        html[data-theme="light"] .empty-state h3,
        html[data-theme="light"] body .empty-state h3 {
            color: #111827 !important;
        }

        html[data-theme="light"] .empty-state p,
        html[data-theme="light"] body .empty-state p {
            color: #374151 !important;
        }
    </style>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/notifications.js') }}"></script>
    <script src="{{ asset('modules/category/js/categories.js') }}"></script>
    <script>
        // Force apply light theme styles if needed
        document.addEventListener('DOMContentLoaded', function() {
            const theme = document.documentElement.getAttribute('data-theme');
            if (theme === 'light') {
                // Force reflow to apply styles
                document.body.offsetHeight;
                // Re-apply theme to ensure styles are loaded
                document.documentElement.setAttribute('data-theme', 'light');
            }
        });
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

        // Handle delete confirmation with custom modal
        let currentDeleteForm = null;
        let currentDeleteBtn = null;

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const categoryCard = this.closest('.category-card');
                const nameEl = categoryCard ? categoryCard.querySelector('.category-name') : null;
                const categoryName = nameEl ? nameEl.textContent : '';
                const confirmed = confirm(
                    `{{ __('Are you sure you want to delete the section') }} ${categoryName}?`);
                if (!confirmed) {
                    e.preventDefault();
                }
            });
        });

        // Handle modal close
        document.querySelector('.delete-modal-close').addEventListener('click', function() {
            document.getElementById('deleteModal').style.display = 'none';
            currentDeleteForm = null;
            currentDeleteBtn = null;
        });

        // Handle modal cancel
        document.querySelector('.delete-modal-cancel').addEventListener('click', function() {
            document.getElementById('deleteModal').style.display = 'none';
            currentDeleteForm = null;
            currentDeleteBtn = null;
        });

        // Handle modal overlay click
        document.querySelector('.delete-modal-overlay').addEventListener('click', function() {
            document.getElementById('deleteModal').style.display = 'none';
            currentDeleteForm = null;
            currentDeleteBtn = null;
        });

        // Handle delete confirmation
        document.querySelector('.delete-modal-confirm').addEventListener('click', function() {
            if (currentDeleteForm && currentDeleteBtn) {
                const originalText = currentDeleteBtn.innerHTML;

                // Show loading state
                currentDeleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('Deleting...') }}';
                currentDeleteBtn.disabled = true;

                // Hide modal
                document.getElementById('deleteModal').style.display = 'none';

                // Send AJAX delete request
                fetch(currentDeleteForm.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-HTTP-Method-Override': 'DELETE'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success notification
                            showSuccessNotification(data.message, null, 'deleted');

                            // Remove the card from grid
                            const card = currentDeleteBtn.closest('.category-card');
                            if (card) {
                                card.style.transition = 'all 0.3s ease';
                                card.style.opacity = '0';
                                card.style.transform = 'scale(0.8)';
                                setTimeout(() => {
                                    card.remove();
                                }, 300);
                            }
                        } else {
                            showErrorNotification(data.message);
                            // Restore button state
                            currentDeleteBtn.innerHTML = originalText;
                            currentDeleteBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        showErrorNotification('{{ __('An error occurred while deleting the category') }}');
                        // Restore button state
                        currentDeleteBtn.innerHTML = originalText;
                        currentDeleteBtn.disabled = false;
                    });
            }
        });

        // Professional Success Notification - استخدام النظام العام
        function showSuccessNotification(message, categoryId = null, action = 'deleted') {
            // استخدام النظام العام للإشعارات
            if (typeof window.showSuccess === 'function') {
                window.showSuccess('{{ __('Success') }}', message);
            } else if (typeof showSuccess === 'function') {
                showSuccess('{{ __('Success') }}', message);
            } else {
                // Fallback: محاولة بعد 100ms
                setTimeout(() => {
                    if (typeof window.showSuccess === 'function') {
                        window.showSuccess('{{ __('Success') }}', message);
                    }
                }, 100);
            }
        }

        // Professional Error Notification - استخدام النظام العام
        function showErrorNotification(message) {
            // استخدام النظام العام للإشعارات
            if (typeof window.showError === 'function') {
                window.showError('{{ __('Error') }}', message);
            } else if (typeof showError === 'function') {
                showError('{{ __('Error') }}', message);
            } else {
                // Fallback: محاولة بعد 100ms
                setTimeout(() => {
                    if (typeof window.showError === 'function') {
                        window.showError('{{ __('Error') }}', message);
                    }
                }, 100);
            }
        }

        // تم إزالة الكود المحلي للإشعارات - الآن نستخدم النظام العام
    </script>
@endpush
