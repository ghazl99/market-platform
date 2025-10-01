@extends('core::store.layouts.app')
@section('title', __('Profile'))
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
@endpush
@section('content')
    <div class="profile-container">
        <!-- Security Section -->
        <div class="profile-main">

            <h2 class="section-title">
                <i class="fas fa-shield-alt"></i>
                الأمان
            </h2>

            <div class="security-item">
                <div class="security-info">
                    <h4>{{ __('Password') }}</h4>
                    <p>آخر تحديث:{{Auth::user()->last_updated_at_password_in_store_timezone?->diffForHumans() ?? 'غير محدد' }}
                    </p>
                </div>
                <div class="security-status">
                    <span class="status-badge status-active">آمن</span>
                    <a href="{{ Route('auth.change-password') }}" class="btn btn-secondary">تغيير</a>
                </div>
            </div>

            <div class="security-item">
                <div class="security-info">
                    <h4>المصادقة الثنائية</h4>
                    <p>إضافة طبقة حماية إضافية لحسابك</p>
                </div>
                <div class="security-status">
                    <span class="status-badge status-inactive">غير مفعل</span>
                    <button class="btn btn-primary">تفعيل</button>
                </div>
            </div>

            <div class="security-item">
                <div class="security-info">
                    <h4>جلسات نشطة</h4>
                    <p>إدارة الأجهزة المتصلة بحسابك</p>
                </div>
                <div class="security-status">
                    <span class="status-badge status-active">3 أجهزة</span>
                    <button class="btn btn-secondary">إدارة</button>
                </div>
            </div>
        </div>
    </div>
@endsection
