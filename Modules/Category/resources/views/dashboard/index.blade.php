@extends('core::dashboard.layouts.app')

@section('title', __('Dashboard') . ' - ' . __('Manage Sections'))

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('modules/category/css/categories.css') }}">
@endpush




@section('content')
    <!-- Notifications -->
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
                    <a href="{{ route('dashboard.category.index') }}" class="back-btn">
                        <i class="fas fa-arrow-right"></i>
                        {{ __('Back') }}
                    </a>
                @endif
                <a href="{{ route('dashboard.category.create') }}" class="add-category-btn">
                    <i class="fas fa-plus"></i>
                    {{ __('Add New Section') }}
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            {{ __('Categories List') }}
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <table id="categoriesTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Category Name') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Subcategories') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $index => $category)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            @php
                                                $media = $category->getFirstMedia('category_images');
                                            @endphp
                                            @if ($media)
                                                <img src="{{ route('category.image', $media->id) }}"
                                                    alt="{{ $category->name }}" width="50">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($category->children->count() > 0)
                                                <button class="btn btn-link p-0" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#subcats-{{ $category->id }}" aria-expanded="false"
                                                    aria-controls="subcats-{{ $category->id }}">
                                                    {{ __('Show :count Subcategories', ['count' => $category->children->count()]) }}
                                                </button>
                                                <div class="collapse mt-2" id="subcats-{{ $category->id }}">
                                                    <ul style="padding-right: 15px; margin: 0; list-style-type: disc;">
                                                        @foreach ($category->children as $child)
                                                            <li>{{ $child->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @else
                                                <span class="text-muted">{{ __('No Subcategories') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('dashboard.category.edit', $category->id) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Category Name') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Subcategories') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </tfoot>
                        </table>

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
            color: #ffffff;
            font-size: 0.875rem;
            font-weight: 500;
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
            color: #ffffff !important;
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

        /* CSS للأقسام الفرعية والـ placeholder */
        .subcategories-section {
            margin-top: 1rem;
            padding: 0.75rem;
            background: rgba(59, 130, 246, 0.05);
            border-radius: 8px;
            border: 1px solid rgba(59, 130, 246, 0.1);
        }

        .subcategories-title {
            color: #ffffff;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .subcategories-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .subcategory-tag {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
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
            background: #2d2d2d;
            border-radius: 16px;
            padding: 0;
            max-width: 500px;
            width: 90%;
            position: relative;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border: 1px solid #404040;
            animation: modalSlideIn 0.3s ease-out;
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
            border-bottom: 1px solid #404040;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .delete-modal-header h3 {
            color: #ffffff;
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .delete-modal-close {
            background: none;
            border: none;
            color: #888888;
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
            color: #ffffff;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .delete-warning {
            color: #888888;
            font-size: 0.9rem;
            margin-bottom: 0;
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
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
        let table = $('#categoriesTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
            },
            order: [[0, 'asc']],
            pageLength: 10
        });

        // تحديث عند تغيير الثيم
        function updateTableTheme() {
            const isDark = document.body.getAttribute('data-theme') === 'dark';
            if (isDark) {
                $('#categoriesTable').addClass('table-dark-mode');
            } else {
                $('#categoriesTable').removeClass('table-dark-mode');
            }
        }

        // راقب التغيير على data-theme
        const observer = new MutationObserver(updateTableTheme);
        observer.observe(document.body, { attributes: true, attributeFilter: ['data-theme'] });

        // شغّل أول مرة
        updateTableTheme();
    });
</script>

@endpush
