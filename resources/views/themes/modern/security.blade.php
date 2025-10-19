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
        <div class="profile-main">

            <h2 class="section-title">
                <i class="fas fa-shield-alt"></i>
                {{ __('Security') }}
            </h2>

            <div class="security-item">
                <div class="security-info">
                    <h4>{{ __('Password') }}</h4>
                    <p>{{ __('Last update') }}:
                        {{ Auth::user()->last_updated_at_password_in_store_timezone?->diffForHumans() ?? 'غير محدد' }}
                    </p>
                </div>
                <div class="security-status">
                    <span class="status-badge status-active">{{ __('Safe') }}</span>
                    <a href="{{ route('auth.change-password') }}" class="btn btn-secondary">{{ __('Change') }}</a>
                </div>
            </div>

            <div class="security-item">
                <div class="security-info">
                    <h4>{{ __('Two Factor Authentication') }}</h4>
                    <p>{{ __('Two Factor Description') }}</p>
                </div>
                <div class="security-status">
                    <span class="status-badge status-inactive">{{ __('Inactive') }}</span>
                    <button class="btn btn-primary">{{ __('Activate') }}</button>
                </div>
            </div>

            <div class="security-item">
                <div class="security-info">
                    <h4>{{ __('Active Sessions') }}</h4>
                    <p>{{ __('Active Sessions Description') }}</p>
                </div>
                <div class="security-status">
                    <span class="status-badge status-active">{{ $activeSessionsCount }} {{ __('Devices') }}</span>
                    <a href="{{ route('auth.sessions') }}" class="btn btn-secondary">{{ __('Manage') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
