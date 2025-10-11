@extends('core::dashboard.layouts.app')

@section('title', __('Manage Permissions'))
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet">
@endpush
@section('content')
    <div class="container mt-4">
        <h4>{{ __('Manage Permissions for') }}{{ $user->name }}</h4>
        <form method="POST" action="{{ route('dashboard.staff.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="permissions" class="form-label">{{ __('Permissions') }}</label>
                <select id="permissions" name="permissions[]" class="form-control" multiple="multiple"
                    data-placeholder="{{ __('Select or add permissions...') }}">
                    @foreach ($permissions as $permission)
                        <option value="{{ $permission->name }}"
                            {{ $user->hasPermissionTo($permission->name) ? 'selected' : '' }}>
                            {{ $permission->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            <a href="{{ route('dashboard.staff.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
        </form>
    </div>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#permissions').select2({
                // theme: 'bootstrap-5',
                placeholder: "{{ __('Select or add permissions...') }}",

                width: '100%',
            });
        });
    </script>
@endpush
