@extends('core::store.main-dashboard.layouts.app')

@section('title', 'إضافة ثيم جديد - لوحة التحكم')

@push('styles')
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #10b981;
            margin-bottom: 25px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        label {
            font-weight: 600;
            margin-bottom: 6px;
            color: #555;
        }

        input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: 0.2s;
        }

        input[type="text"]:focus {
            border-color: #10b981;
            box-shadow: 0 0 5px rgba(16, 185, 129, 0.3);
            outline: none;
        }

        .btn {
            padding: 12px;
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
    </style>
@endpush

@section('content')
    <div class="container">
        <h2>إضافة ثيم جديد</h2>

        <form action="{{ route('admin.themes.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">اسم الثيم</label>
                <input type="text" id="name" name="name" placeholder="Default" required>
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


            <button type="submit" class="btn btn-primary">حفظ الثيم</button>
        </form>
    </div>
@endsection
