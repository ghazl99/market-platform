@extends('core::dashboard.'. current_store()->type .'.layouts.app')

@section('title', __('Store Settings') . ' - ' . $store->name)

@push('styles')
    <style>
        :root {
            --primary-color: {{ $settings['primary_color'] ?? '#10b981' }};
            --primary-color-rgb: {{ !empty($settings['primary_color']) ? implode(', ', sscanf($settings['primary_color'], '#%02x%02x%02x')) : '16, 185, 129' }};
            --secondary-color: {{ $settings['secondary_color'] ?? '#059669' }};
            --success-color: {{ $settings['success_color'] ?? '#3ce551' }};
            --warning-color: {{ $settings['warning_color'] ?? '#ffd42d' }};
            --danger-color: {{ $settings['danger_color'] ?? '#ef7575' }};
            --light-bg: #fcfcfc;
            --dark-bg: #252f4a;
            --text-primary: {{ $settings['text_primary_light'] ?? '#252f4a' }};
            --text-secondary: {{ $settings['text_secondary'] ?? '#66718e' }};
            --border-color: {{ $settings['border_color'] ?? '#F1F1F4' }};
            --card-bg: #ffffff;
            --input-bg: #f9f9f9;
            --header-bg: #ffffff;
            --footer-bg: #252f4a;
            --footer-text: #ffffff;
            --footer-link: rgba(255, 255, 255, 0.8);
            --shadow-color: rgba(0, 0, 0, 0.1);
            --transition-base: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --gradient-primary: linear-gradient(135deg, var(--primary-color), var(--secondary-color));

            /* Legacy support */
            --primary-dark: {{ $settings['primary_dark'] ?? '#0f766e' }};
            --primary-light: {{ $settings['primary_light'] ?? '#34d399' }};
            --accent-color: {{ $settings['accent_color'] ?? ($settings['primary_color'] ?? '#10b981') }};
            --secondary-light: {{ $settings['secondary_light'] ?? '#34d399' }};
            --secondary-dark: {{ $settings['secondary_dark'] ?? '#047857' }};
        }

        /* Dark Theme Variables */
        [data-theme="dark"] {
            --primary-color: {{ $settings['primary_color_dark'] ?? ($settings['primary_color'] ?? '#10b981') }};
            --primary-color-rgb: {{ !empty($settings['primary_color_dark']) ? implode(', ', sscanf($settings['primary_color_dark'], '#%02x%02x%02x')) : (!empty($settings['primary_color']) ? implode(', ', sscanf($settings['primary_color'], '#%02x%02x%02x')) : '16, 185, 129') }};
            --secondary-color: {{ $settings['secondary_color'] ?? '#059669' }};
            --success-color: {{ $settings['success_color'] ?? '#4ae55e' }};
            --warning-color: {{ $settings['warning_color'] ?? '#ffe048' }};
            --danger-color: {{ $settings['danger_color'] ?? '#ff8585' }};
            --light-bg: #1a1f35;
            --dark-bg: #1a1f35;
            --text-primary: {{ $settings['text_primary_dark'] ?? '#e5e7eb' }};
            --text-secondary: {{ $settings['text_secondary'] ?? '#9ca3af' }};
            --border-color: {{ $settings['border_color'] ?? '#374151' }};
            --card-bg: #252f4a;
            --input-bg: #1a1f35;
            --header-bg: #252f4a;
            --footer-bg: #1a1f35;
            --footer-text: {{ $settings['text_primary_dark'] ?? '#e5e7eb' }};
            --footer-link: rgba(255, 255, 255, 0.7);
            --shadow-color: rgba(0, 0, 0, 0.3);
            --gradient-primary: linear-gradient(135deg, var(--primary-color), var(--secondary-color));

            /* Legacy support */
            --primary-dark: {{ $settings['primary_dark'] ?? '#059669' }};
            --primary-light: {{ $settings['primary_light'] ?? '#10b981' }};
            --accent-color: {{ $settings['accent_color'] ?? ($settings['primary_color_dark'] ?? ($settings['primary_color'] ?? '#10b981')) }};
            --secondary-light: {{ $settings['secondary_light'] ?? '#10b981' }};
            --secondary-dark: {{ $settings['secondary_dark'] ?? '#059669' }};
        }

        .settings-container {
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 5px 20px var(--shadow-color);
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
            transition: var(--transition-base);
        }

        [data-theme="dark"] .settings-container {
            background: var(--card-bg);
            box-shadow: 0 5px 20px var(--shadow-color);
        }

        h2 {
            color: var(--primary-color);
            margin-bottom: 25px;
            text-align: center;
        }

        [data-theme="dark"] h2 {
            color: var(--primary-color);
        }

        h3 {
            color: var(--primary-color);
        }

        [data-theme="dark"] h3 {
            color: var(--primary-color);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--text-primary);
        }

        [data-theme="dark"] label {
            color: var(--text-primary);
        }

        input[type="text"],
        input[type="url"],
        select,
        input[type="file"] {
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.95rem;
            transition: var(--transition-base);
            background: var(--input-bg);
            color: var(--text-primary);
        }

        [data-theme="dark"] input[type="text"],
        [data-theme="dark"] input[type="url"],
        [data-theme="dark"] select,
        [data-theme="dark"] input[type="file"] {
            background: var(--input-bg);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        input[type="color"] {
            width: 100%;
            height: 80px;
            padding: 0;
            border: none;
            background: none;
            cursor: pointer;
            border-radius: 8px;
            max-width: 120px;
        }

        .color-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .color-grid {
                grid-template-columns: 1fr;
            }

            input[type="color"] {
                max-width: 100%;
            }
        }

        .file-input-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        @media (max-width: 768px) {
            .file-input-grid {
                grid-template-columns: 1fr;
            }
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 5px rgba(var(--primary-color-rgb), 0.3);
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: 0.2s;
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--footer-text);
            transition: var(--transition-base);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-outline-secondary {
            background: var(--card-bg);
            border: 2px solid var(--border-color);
            color: var(--text-primary);
            transition: var(--transition-base);
        }

        .btn-outline-secondary:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        [data-theme="dark"] .btn-outline-secondary {
            background: var(--card-bg);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        [data-theme="dark"] .btn-outline-secondary:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .preview-img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            margin-top: 10px;
            transition: var(--transition-base);
        }

        [data-theme="dark"] .preview-img {
            border-color: var(--border-color);
        }

        /* معرض صور البانر */
        .banner-preview {
            display: flex;
            gap: 10px;
            flex-wrap: nowrap;
            overflow-x: auto;
            margin-top: 10px;
            padding-bottom: 5px;
        }

        .banner-preview img {
            width: 150px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            transition: var(--transition-base);
        }

        [data-theme="dark"] .banner-preview img {
            border-color: var(--border-color);
        }

        .banner-preview::-webkit-scrollbar {
            height: 6px;
        }

        .banner-preview::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }

        [data-theme="dark"] .banner-preview::-webkit-scrollbar-thumb {
            background: var(--border-color);
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-content">
        <div class="page-header">
            <h1 class="page-title">{{ __('Store Settings') }}</h1>
        </div>
        
        <div class="settings-container">

            <form method="POST" action="{{ route('store.settings.update', $store->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Contact Phone --}}
                <h3 style="margin: 20px 0 15px 0; font-size: 1.1rem;">{{ __('Contact Information') }}</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="contact_phone">{{ __('Contact Phone Number') }}</label>
                        <input type="text" id="contact_phone" name="contact_phone"
                            value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}"
                            placeholder="963992609703">
                        <small style="color: var(--text-secondary); margin-top: 5px;">
                            {{ __('Enter phone number with country code (e.g., 963992609703)') }}
                        </small>
                    </div>
                </div>

                {{-- Social Links --}}
                <h3 style="margin: 20px 0 15px 0; font-size: 1.1rem;">{{ __('Social Media Links') }}</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="facebook">{{ __('Facebook Link') }}</label>
                        <input type="text" id="facebook" name="facebook"
                            value="{{ old('facebook', $settings['facebook'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label for="instagram">{{ __('Instagram Link') }}</label>
                        <input type="text" id="instagram" name="instagram"
                            value="{{ old('instagram', $settings['instagram'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label for="linkedin">{{ __('LinkedIn Link') }}</label>
                        <input type="text" id="linkedin" name="linkedin"
                            value="{{ old('linkedin', $settings['linkedin'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label for="twitter">{{ __('X / Twitter Link') }}</label>
                        <input type="text" id="twitter" name="twitter"
                            value="{{ old('twitter', $settings['twitter'] ?? '') }}">
                    </div>
                </div>

                {{-- Font & Color Settings --}}
                <div class="form-group full-width">
                    <label for="font_family">{{ __('Font Family') }}</label>
                    <select id="font_family" name="font_family">
                        @php
                            $fonts = ['Cairo', 'Poppins', 'Roboto', 'Tajawal', 'Nunito', 'Open Sans'];
                        @endphp
                        @foreach ($fonts as $font)
                            <option value="{{ $font }}"
                                {{ old('font_family', $settings['font_family'] ?? '') == $font ? 'selected' : '' }}>
                                {{ $font }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Light Mode Colors --}}
                <h3 style="margin: 20px 0 15px 0; font-size: 1.1rem;">{{ __('Light Mode Colors') }}</h3>
                <div class="color-grid">
                    <div class="form-group">
                        <label for="primary_color">{{ __('Primary Color') }}</label>
                        <input type="color" id="primary_color" name="primary_color"
                            value="{{ old('primary_color', $settings['primary_color'] ?? '#ff6f1e') }}">
                    </div>

                    <div class="form-group">
                        <label for="secondary_color">{{ __('Secondary Color') }}</label>
                        <input type="color" id="secondary_color" name="secondary_color"
                            value="{{ old('secondary_color', $settings['secondary_color'] ?? '#ff8533') }}">
                    </div>

                    <div class="form-group">
                        <label for="success_color">{{ __('Success Color') }}</label>
                        <input type="color" id="success_color" name="success_color"
                            value="{{ old('success_color', $settings['success_color'] ?? '#3ce551') }}">
                    </div>

                    <div class="form-group">
                        <label for="warning_color">{{ __('Warning Color') }}</label>
                        <input type="color" id="warning_color" name="warning_color"
                            value="{{ old('warning_color', $settings['warning_color'] ?? '#ffd42d') }}">
                    </div>

                    <div class="form-group">
                        <label for="danger_color">{{ __('Danger Color') }}</label>
                        <input type="color" id="danger_color" name="danger_color"
                            value="{{ old('danger_color', $settings['danger_color'] ?? '#ef7575') }}">
                    </div>

                    <div class="form-group">
                        <label for="text_primary">{{ __('Text Primary') }}</label>
                        <input type="color" id="text_primary" name="text_primary"
                            value="{{ old('text_primary', $settings['text_primary'] ?? '#252f4a') }}">
                    </div>

                    <div class="form-group">
                        <label for="text_secondary">{{ __('Text Secondary') }}</label>
                        <input type="color" id="text_secondary" name="text_secondary"
                            value="{{ old('text_secondary', $settings['text_secondary'] ?? '#66718e') }}">
                    </div>

                    <div class="form-group">
                        <label for="border_color">{{ __('Border Color') }}</label>
                        <input type="color" id="border_color" name="border_color"
                            value="{{ old('border_color', $settings['border_color'] ?? '#F1F1F4') }}">
                    </div>

                    <div class="form-group">
                        <label for="card_bg">{{ __('Card Background') }}</label>
                        <input type="color" id="card_bg" name="card_bg"
                            value="{{ old('card_bg', $settings['card_bg'] ?? '#ffffff') }}">
                    </div>

                    <div class="form-group">
                        <label for="input_bg">{{ __('Input Background') }}</label>
                        <input type="color" id="input_bg" name="input_bg"
                            value="{{ old('input_bg', $settings['input_bg'] ?? '#f9f9f9') }}">
                    </div>

                    <div class="form-group">
                        <label for="shadow_color">{{ __('Shadow Color') }}</label>
                        <input type="color" id="shadow_color" name="shadow_color"
                            value="{{ old('shadow_color', $settings['shadow_color'] ?? 'rgba(0,0,0,0.1)') }}">
                    </div>
                </div>

                {{-- Dark Mode Colors --}}
                <h3 style="margin: 30px 0 15px 0; font-size: 1.1rem;">{{ __('Dark Mode Colors') }}</h3>
                <div class="color-grid">
                    <div class="form-group">
                        <label for="primary_color_dark">{{ __('Primary Color (Dark Mode)') }}</label>
                        <input type="color" id="primary_color_dark" name="primary_color_dark"
                            value="{{ old('primary_color_dark', $settings['primary_color_dark'] ?? '#ff8533') }}">
                    </div>

                    <div class="form-group">
                        <label for="secondary_color_dark">{{ __('Secondary Color (Dark Mode)') }}</label>
                        <input type="color" id="secondary_color_dark" name="secondary_color_dark"
                            value="{{ old('secondary_color_dark', $settings['secondary_color_dark'] ?? '#ff9847') }}">
                    </div>

                    <div class="form-group">
                        <label for="text_primary_dark">{{ __('Text Primary (Dark Mode)') }}</label>
                        <input type="color" id="text_primary_dark" name="text_primary_dark"
                            value="{{ old('text_primary_dark', $settings['text_primary_dark'] ?? '#f8f9fa') }}">
                    </div>

                    <div class="form-group">
                        <label for="text_secondary_dark">{{ __('Text Secondary (Dark Mode)') }}</label>
                        <input type="color" id="text_secondary_dark" name="text_secondary_dark"
                            value="{{ old('text_secondary_dark', $settings['text_secondary_dark'] ?? '#adb5bd') }}">
                    </div>

                    <div class="form-group">
                        <label for="light_bg_dark">{{ __('Light Background (Dark Mode)') }}</label>
                        <input type="color" id="light_bg_dark" name="light_bg_dark"
                            value="{{ old('light_bg_dark', $settings['light_bg_dark'] ?? '#301c0f') }}">
                    </div>

                    <div class="form-group">
                        <label for="dark_bg_dark">{{ __('Dark Background (Dark Mode)') }}</label>
                        <input type="color" id="dark_bg_dark" name="dark_bg_dark"
                            value="{{ old('dark_bg_dark', $settings['dark_bg_dark'] ?? '#1e0f08') }}">
                    </div>

                    <div class="form-group">
                        <label for="border_color_dark">{{ __('Border Color (Dark Mode)') }}</label>
                        <input type="color" id="border_color_dark" name="border_color_dark"
                            value="{{ old('border_color_dark', $settings['border_color_dark'] ?? '#3d2513') }}">
                    </div>

                    <div class="form-group">
                        <label for="card_bg_dark">{{ __('Card Background (Dark Mode)') }}</label>
                        <input type="color" id="card_bg_dark" name="card_bg_dark"
                            value="{{ old('card_bg_dark', $settings['card_bg_dark'] ?? '#301c0f') }}">
                    </div>

                    <div class="form-group">
                        <label for="shadow_color_dark">{{ __('Shadow Color (Dark Mode)') }}</label>
                        <input type="color" id="shadow_color_dark" name="shadow_color_dark"
                            value="{{ old('shadow_color_dark', $settings['shadow_color_dark'] ?? 'rgba(0,0,0,0.7)') }}">
                    </div>
                </div>



                {{-- Store Images Section --}}
                <h3 style="margin: 20px 0 15px 0; font-size: 1.1rem;">{{ __('Store Images') }}</h3>

                <div class="file-input-grid">
                    <div class="form-group">
                        <label for="logo">{{ __('Store Logo') }}</label>
                        <input type="file" id="logo" name="logo" accept="image/*">
                        @if (!empty($settings['logo']))
                            <img src="{{ asset('storage/' . $settings['logo']) }}" class="preview-img"
                                alt="Logo Preview">
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="banners">{{ __('Banner Images (Slideshow)') }}</label>
                        <input type="file" id="banners" name="banners[]" accept="image/*" multiple>

                        {{-- معرض الصور --}}
                        <div class="banner-preview" id="banner-preview">
                            @if (!empty($settings['banners']) && is_array($settings['banners']))
                                @foreach ($settings['banners'] as $banner)
                                    <img src="{{ asset('storage/' . $banner) }}" alt="Banner">
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div style="display:flex; gap:10px; margin-top:10px; justify-content:center;">
                    <button type="submit" class="btn btn-primary">{{ __('Update Settings') }}</button>
                    @php
                        use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
                    @endphp
                    <a href="{{ LaravelLocalization::localizeURL('dashboard') }}"
                        class="btn btn-outline-secondary">{{ __('Back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const bannersInput = document.getElementById('banners');
        const bannerPreview = document.getElementById('banner-preview');

        if (bannersInput && bannerPreview) {
            bannersInput.addEventListener('change', function() {
                bannerPreview.innerHTML = ''; // مسح الصور القديمة
                const files = Array.from(this.files);

                files.forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Banner Preview';
                        img.style.width = '150px';
                        img.style.height = '100px';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '8px';
                        img.style.border = '1px solid var(--border-color, #ccc)';
                        img.style.marginRight = '10px';
                        img.style.marginBottom = '10px';
                        bannerPreview.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                });
            });
        }
    </script>
@endpush
