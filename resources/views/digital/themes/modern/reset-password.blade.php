@extends('themes.app')

@section('title', __('Change Password'))
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
        <div class="profile-main">
            <h2 class="section-title">
                <i class="fas fa-lock"></i>
                {{ __('Change Password') }}
            </h2>

            <form method="POST" action="{{ route('auth.password.update') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">{{ __('Current Password') }}</label>
                    <input type="password" class="form-input" name="current_password">
                    @error('current_password')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('New Password') }}</label>
                    <input type="password" class="form-input" name="password">
                    @error('password')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('Confirm New Password') }}</label>
                    <input type="password" class="form-input" name="password_confirmation">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    {{ __('Save') }}
                </button>
            </form>
        </div>
    </div>
@endsection
