@extends('core::layouts.app')

@section('title', __('Store Settings') . ' - ' . $store->name)

@push('styles')
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Cairo', sans-serif;
        }

        body {
            background: #f5f5f5;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: 100px auto;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #10b981;
            margin-bottom: 25px;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 25px;
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
            color: #555;
        }

        input[type="text"],
        input[type="url"],
        select,
        input[type="file"] {
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: 0.2s;
        }

        input[type="color"] {
            width: 100px;
            height: 50px;
            padding: 0;
            border: none;
            background: none;
            cursor: pointer;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 5px rgba(16, 185, 129, 0.3);
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
            background: #10b981;
            color: #fff;
        }

        .btn-primary:hover {
            background: #0f766e;
        }

        .btn-outline-secondary {
            background: #fff;
            border: 2px solid #ccc;
            color: #555;
        }

        .btn-outline-secondary:hover {
            border-color: #10b981;
            color: #10b981;
        }

        .preview-img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-top: 10px;
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
            border: 1px solid #ccc;
        }

        .banner-preview::-webkit-scrollbar {
            height: 6px;
        }

        .banner-preview::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="container mt-5">
        <h2>{{ __('Store Settings') }}</h2>

        <form method="POST" action="{{ route('store.settings.update', $store->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Social Links --}}
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

            {{-- Font & Color --}}
            <div class="form-grid">
                <div class="form-group">
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

                <div class="form-group">
                    <label for="primary_color">{{ __('Primary Color') }}</label>
                    <input type="color" id="primary_color" name="primary_color"
                        value="{{ old('primary_color', $settings['primary_color'] ?? '#10b981') }}">
                </div>
            </div>

            {{-- Banner Images (Full Row with Preview) --}}
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

            {{-- Logo (At the End) --}}
            <div class="form-group">
                <label for="logo">{{ __('Store Logo') }}</label>
                <input type="file" id="logo" name="logo" accept="image/*">
                @if (!empty($settings['logo']))
                    <img src="{{ asset('storage/' . $settings['logo']) }}" class="preview-img" alt="Logo Preview">
                @endif
            </div>

            {{-- Submit --}}
            <div style="display:flex; gap:10px; margin-top:10px; justify-content:center;">
                <button type="submit" class="btn btn-primary">{{ __('Update Settings') }}</button>
                <a href="{{ route('stores.index') }}" class="btn btn-outline-secondary">{{ __('Back') }}</a>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        const bannersInput = document.getElementById('banners');
        const bannerPreview = document.getElementById('banner-preview');

        bannersInput.addEventListener('change', function() {
            bannerPreview.innerHTML = ''; // مسح الصور القديمة
            const files = Array.from(this.files);

            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Banner';
                    img.style.width = '150px';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '8px';
                    img.style.border = '1px solid #ccc';
                    img.style.marginRight = '10px';
                    bannerPreview.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        });
    </script>
@endpush
