@extends('core::layouts.app')

@section('title', __('Store Settings') . ' - ' . $store->name)

@push('styles')
<style>
    * { box-sizing:border-box; margin:0; padding:0; font-family:'Cairo',sans-serif; }
    body { background:#f5f5f5; color:#333; }
    .container { max-width:800px; margin:50px auto; padding:20px; background:#fff; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,0.1); }
    h2 { color:#10b981; margin-bottom:20px; text-align:center; }
    form { display:flex; flex-direction:column; gap:20px; }
    .form-group { display:flex; flex-direction:column; }
    label { font-weight:600; margin-bottom:5px; color:#555; }
    input[type="text"], input[type="email"], textarea, input[type="color"] { padding:10px 12px; border:1px solid #ccc; border-radius:8px; font-size:0.95rem; transition:0.2s; }
    input:focus, textarea:focus { outline:none; border-color:#10b981; box-shadow:0 0 5px rgba(16,185,129,0.3); }
    textarea { resize:vertical; min-height:80px; }
    .btn { padding:12px 25px; border:none; border-radius:8px; cursor:pointer; font-size:1rem; transition:0.2s; }
    .btn-primary { background:#10b981; color:#fff; }
    .btn-primary:hover { background:#0f766e; }
    .btn-outline-secondary { background:#fff; border:2px solid #ccc; color:#555; }
    .btn-outline-secondary:hover { border-color:#10b981; color:#10b981; }
    .text-danger { color:red; font-size:0.85rem; margin-top:3px; }
</style>
@endpush

@section('content')
<div class="container">
    <h2>{{ __('Store Settings') }}</h2>

    <form method="POST" action="{{ route('setting.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Description --}}
        <div class="form-group">
            <label for="description">{{ __('Store Description') }}</label>
            <textarea id="description" name="description" required>{{ old('description', $store->description) }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Address --}}
        <div class="form-group">
            <label for="address">{{ __('Store Address') }}</label>
            <input type="text" id="address" name="address" value="{{ old('address', $store->address) }}" required>
            @error('address')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label for="email">{{ __('Store Email') }}</label>
            <input type="email" id="email" name="email" value="{{ old('email', $store->email) }}" required>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Theme Color --}}
        <div class="form-group">
            <label for="theme_color">{{ __('Theme Color') }}</label>
            <input type="color" id="theme_color" name="theme_color" value="{{ old('theme_color', $store->theme_color ?? '#10b981') }}">
            @error('theme_color')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Submit Buttons --}}
        <div style="display:flex; gap:10px; margin-top:20px;">
            <button type="submit" class="btn btn-primary">{{ __('Update Settings') }}</button>
            <a href="{{ route('stores.index') }}" class="btn btn-outline-secondary">{{ __('Back') }}</a>
        </div>
    </form>
</div>
@endsection
