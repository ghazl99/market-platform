@extends('core::store.layouts.app')

@section('title', __('تغيير كلمة المرور'))
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
@endpush
@section('content')
    <div class="profile-container">
        <div class="profile-main">
            <h2 class="section-title">
                <i class="fas fa-lock"></i>
                تغيير كلمة المرور
            </h2>

            <form method="POST" action="{{ route('auth.password.update') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">كلمة المرور الحالية</label>
                    <input type="password" class="form-input" name="current_password">
                    @error('current_password')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">كلمة المرور الجديدة</label>
                    <input type="password" class="form-input" name="password">
                    @error('password')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">تأكيد كلمة المرور الجديدة</label>
                    <input type="password" class="form-input" name="password_confirmation">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    حفظ التغييرات
                </button>
            </form>
        </div>
    </div>
@endsection
