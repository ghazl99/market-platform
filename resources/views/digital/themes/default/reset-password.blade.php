@extends('digital.themes.app')

@section('title', __('Change Password'))
@push('styles')
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
