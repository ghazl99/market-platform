@extends('themes.app')

@section('title', __('Profile'))

@push('styles')
    <style>
        :root {
            --primary-color: #7C3AED;
            /* Purple 600 */
            --primary-dark: #5B21B6;
            /* Purple 800 */
            --primary-light: #C4B5FD;
            /* Purple 300 */
            --secondary-color: #8B5CF6;
            --secondary-dark: #6D28D9;
            --success-color: #10B981;
            --error-color: #EF4444;
            --warning-color: #F59E0B;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
            --text-light: #9CA3AF;
            --bg-primary: #F9FAFB;
            --bg-secondary: #FFFFFF;
            --bg-accent: #F3E8FF;
            --border-color: #E5E7EB;
            --border-light: #EDE9FE;
            --gradient-primary: linear-gradient(135deg, #8B5CF6, #7C3AED);
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
@endpush

@section('content')
    <div class="profile-container">
        <!-- Profile Main Content -->
        <div class="profile-main">
            <!-- Personal Information Section -->
            <h2 class="section-title">
                <i class="fas fa-user"></i>{{ __('Personal Information') }}
            </h2>

            <form method="POST" action="{{ route('auth.profile.update', Auth::user()->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="profile-avatar">
                    <div class="avatar-upload">
                        <img src="{{ Auth::user()->profilePhotoUrl ?? 'https://via.placeholder.com/120' }}"
                            alt="{{ Auth::user()->name }}" class="avatar-image" id="avatarImage">
                        <input type="file" id="avatarInput" name="profile_photo" accept="image/*" hidden>
                        <button type="button" class="avatar-upload-btn"
                            onclick="document.getElementById('avatarInput').click()">
                            <i class="fas fa-camera"></i> {{ __('Change Image') }}
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('Name') }}</label>
                    <input type="text" class="form-input" id="name" name="name"
                        value="{{ old('city', Auth::user()->name) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('Email') }}</label>
                    <input type="email" class="form-input" id="email" name="email"
                        value="{{ old('email', Auth::user()->email) }}">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    {{ __('Save') }}
                </button>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.getElementById('avatarInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarImage').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
