@extends('core::layouts.app')

@section('title', 'إنشاء متجر جديد - لوحة التحكم')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* ==========================
           Store Type Selection
        ========================== */
        .field-label {
            display: block;
            color: #333;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        /* Grid Layout for Store Type Cards */
        .store-type-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        /* Card Wrapper */
        .store-type-option {
            cursor: pointer;
        }

        /* Hidden Radio Input */
        .store-type-input {
            display: none;
        }

        /* Card Style */
        .store-type-card {
            padding: 1.5rem 1rem;
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        /* Gradient Overlay (for hover/selected) */
        .store-type-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1;
        }

        /* Icon Styling */
        .store-icon {
            position: relative;
            z-index: 2;
            width: 50px;
            height: 50px;
            background: #e0e0e0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.25rem;
            color: #667eea;
            transition: all 0.3s ease;
        }

        /* Label Styling */
        .store-label {
            position: relative;
            z-index: 2;
            font-size: 0.9rem;
            font-weight: 500;
            color: #333;
            transition: all 0.3s ease;
        }

        /* Hover Effect */
        .store-type-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.15);
        }

        /* Checked State */
        .store-type-input:checked+.store-type-card {
            border-color: #667eea;
            background: linear-gradient(135deg, #e0eaff 0%, #f3f0ff 100%);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.25);
        }

        .store-type-input:checked+.store-type-card::before {
            opacity: 0.2;
        }

        .store-type-input:checked+.store-type-card .store-icon {
            background: #667eea;
            color: #fff;
        }

        .store-type-input:checked+.store-type-card .store-label {
            color: #667eea;
            font-weight: 600;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .store-type-grid {
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            }

            .store-icon {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }

            .store-type-card {
                padding: 1.2rem 0.8rem;
            }
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus me-2"></i>
                {{ __('Create New Store') }}
            </h1>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.stores.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-right me-2"></i>
                    {{ __('Back to Stores List') }}
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-store me-2"></i>
                            {{ __('New Store Information') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('admin.stores.store') }}" enctype="multipart/form-data">
                            @csrf

                            <!-- Store Information Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-store me-2"></i>
                                        {{ __('Store Information') }}
                                    </h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">
                                            {{ __('Store Name') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small
                                            class="form-text text-muted">{{ __('Store name as shown to customers') }}</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="domain" class="form-label">
                                            {{ __('Domain') }} <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control @error('domain') is-invalid @enderror"
                                                id="domain" name="domain" value="{{ old('domain') }}" required>
                                            <span class="input-group-text" id="domain-suffix">
                                                @if (app()->environment('production'))
                                                    .com
                                                @else
                                                    .localhost
                                                @endif
                                            </span>
                                        </div>
                                        @error('domain')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            @if (app()->environment('production'))
                                                {{ __('Full store domain (e.g. mystore.com)') }}
                                            @else
                                                {{ __('Local test domain (e.g. mystore)') }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="mb-3">
                                <label for="description" class="form-label">{{ __('Store Description') }}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small
                                    class="form-text text-muted">{{ __('Short description about your store and products') }}</small>
                            </div> --}}

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="theme" class="form-label">
                                            {{ __('Theme') }} <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('theme') is-invalid @enderror" id="theme"
                                            name="theme" required>
                                            <option value="">{{ __('Select Theme') }}</option>
                                            <option value="default" {{ old('theme') == 'default' ? 'selected' : '' }}>
                                                {{ __('Default Theme') }}</option>
                                            <option value="modern" {{ old('theme') == 'modern' ? 'selected' : '' }}>
                                                {{ __('Modern Theme') }}</option>
                                            <option value="classic" {{ old('theme') == 'classic' ? 'selected' : '' }}>
                                                {{ __('Classic Theme') }}</option>
                                            <option value="minimal" {{ old('theme') == 'minimal' ? 'selected' : '' }}>
                                                {{ __('Minimal Theme') }}</option>
                                            <option value="elegant" {{ old('theme') == 'elegant' ? 'selected' : '' }}>
                                                {{ __('Elegant Theme') }}</option>
                                        </select>
                                        @error('theme')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">
                                            {{ __('Status') }} <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status"
                                            name="status" required>
                                            <option value="">{{ __('Select Status') }}</option>
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                                {{ __('Active') }}</option>
                                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                                {{ __('Pending Review') }}</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                                {{ __('Inactive') }}</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="logo" class="form-label">{{ __('Store Logo') }}</label>
                                        <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                            id="logo" name="logo">
                                        @error('logo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small
                                            class="form-text text-muted">{{ __('Store logo image link (optional)') }}</small>
                                    </div>
                                </div>

                                {{-- <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="banner" class="form-label">{{ __('Store Banner') }}</label>
                                        <input type="file" class="form-control @error('banner') is-invalid @enderror"
                                            id="banner" name="banner">
                                        @error('banner')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small
                                            class="form-text text-muted">{{ __('Store banner image link (optional)') }}</small>
                                    </div>
                                </div> --}}
                            </div>

                            <!-- Timezone -->
                            <div class="mb-3">
                                <label for="timezone" class="form-label">{{ __('Timezone') }}</label>
                                <select name="timezone" id="timezone"
                                    class="form-select select2 @error('timezone') is-invalid @enderror" required>
                                    @foreach (timezone_identifiers_list() as $tz)
                                        <option value="{{ $tz }}"
                                            {{ old('timezone') == $tz ? 'selected' : '' }}>
                                            {{ $tz }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('timezone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">{{ __('Select store timezone') }}</small>
                            </div>

                            <!-- Store Type -->
                            <div class="form-field-group">
                                <label class="field-label">{{ __('Preferred Store Type') }}</label>
                                <div class="store-type-grid">
                                    <label class="store-type-option">
                                        <input type="radio" name="type" value="traditional"
                                            class="store-type-input">
                                        <div class="store-type-card">
                                            <div class="store-icon">
                                                <i class="fas fa-shopping-bag"></i>
                                            </div>
                                            <span class="store-label">{{ __('Traditional') }}</span>
                                        </div>
                                    </label>
                                    <label class="store-type-option">
                                        <input type="radio" name="type" value="digital" class="store-type-input">
                                        <div class="store-type-card">
                                            <div class="store-icon">
                                                <i class="fas fa-laptop"></i>
                                            </div>
                                            <span class="store-label">{{ __('Digital') }}</span>
                                        </div>
                                    </label>
                                    <label class="store-type-option">
                                        <input type="radio" name="type" value="educational"
                                            class="store-type-input">
                                        <div class="store-type-card">
                                            <div class="store-icon">
                                                <i class="fas fa-graduation-cap"></i>
                                            </div>
                                            <span class="store-label">{{ __('Educational') }}</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <hr class="my-4">

                            <!-- User Account Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-user-plus me-2"></i>
                                        {{ __('Select Store Owner') }}
                                    </h5>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>{{ __('Note:') }}</strong>
                                        {{ __('Select one of the registered store owners.') }}
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="user_id" class="form-label">{{ __('Store Owner') }} <span
                                        class="text-danger">*</span></label>
                                <select name="user_id" id="user_id" class="form-select" required>
                                    <option value="">{{ __('Select a store owner') }}</option>
                                    @foreach ($owners as $owner)
                                        <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small
                                    class="form-text text-muted">{{ __('Choose one of the registered store owners') }}</small>
                            </div>

                            <!-- Environment Info -->
                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>{{ __('Environment Info') }}</strong>
                                @if (app()->environment('production'))
                                    <span class="badge bg-success">{{ __('Production') }}</span> -
                                    {{ __('A separate domain will be created (e.g. mystore.com)') }}
                                @else
                                    <span class="badge bg-info">{{ __('Local Development') }}</span> -
                                    {{ __('For local testing only') }}
                                @endif
                            </div>

                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>{{ __('Important Info') }}</strong>
                                <ul class="mb-0 mt-2">
                                    <li>{{ __('The store and account will be created immediately after clicking \'Create Store\'') }}
                                    </li>
                                    <li>{{ __('You can change the status later from the store management page') }}</li>
                                    <li>{{ __('Domain must be unique and not used before') }}</li>
                                    <li>{{ __('Login details will be sent to the owner via email') }}</li>
                                    <li>{{ __('Owner can access the store from \'My Stores\' page after login') }}</li>
                                    @if (app()->environment('production'))
                                        <li><strong>{{ __('In production:') }}</strong>
                                            {{ __('Make sure to add Virtual Host for the new domain') }}</li>
                                    @endif
                                </ul>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>
                                    {{ __('Save Store') }}
                                </button>
                                <a href="{{ route('admin.stores.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-times me-2"></i>
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-generate domain from store name
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value;
            const domain = name
                .toLowerCase()
                .replace(/[^a-z0-9]/g, '')
                .substring(0, 20);

            if (domain) {
                document.getElementById('domain').value = domain;
            }
        });

        // Validate domain uniqueness (basic client-side validation)
        document.getElementById('domain').addEventListener('blur', function() {
            const domain = this.value;
            if (domain) {
                // Remove special characters and spaces
                const cleanDomain = domain.replace(/[^a-z0-9-]/g, '');
                if (cleanDomain !== domain) {
                    this.value = cleanDomain;
                }
            }
        });

        // Password strength validation
        document.getElementById('owner_password').addEventListener('input', function() {
            const password = this.value;
            const confirmation = document.getElementById('owner_password_confirmation').value;

            if (password.length < 8) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }

            // Check password confirmation
            if (confirmation && password !== confirmation) {
                document.getElementById('owner_password_confirmation').classList.add('is-invalid');
                document.getElementById('owner_password_confirmation').classList.remove('is-valid');
            } else if (confirmation && password === confirmation) {
                document.getElementById('owner_password_confirmation').classList.remove('is-invalid');
                document.getElementById('owner_password_confirmation').classList.add('is-valid');
            }
        });

        // Password confirmation validation
        document.getElementById('owner_password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('owner_password').value;
            const confirmation = this.value;

            if (confirmation && password !== confirmation) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (confirmation && password === confirmation) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    </script>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#timezone').select2({
                placeholder: "ابحث عن المنطقة الزمنية...",
                allowClear: true,
                width: '100%',
                sorter: function(data) {
                    return data.sort((a, b) => a.text.localeCompare(b.text));
                }
            });
        });
    </script>
@endpush
