@extends('core::dashboard.layouts.app')

@section('title', __('Customers List'))

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>{{ __('Customers List') }}</h4>
        </div>
        <div class="d-flex mb-3">
            <input type="text" id="staffs-search" class="form-control" placeholder="{{ __('Search Customer...') }}">
        </div>
        <br>
        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Verification') }}</th>
                            <th>{{ __('Registered at') }}</th>
                            <th>{{ __('Last login') }}</th>
                        </tr>
                    </thead>
                    <tbody id="users-table-wrapper">
                        @include('user::dashboard.customer.dataTables', [
                            'users' => $users,
                            'store' => $store,
                        ])
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Verification') }}</th>
                            <th>{{ __('Registered at') }}</th>
                            <th>{{ __('Last login') }}</th>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>

        <div class="d-flex justify-content-center mt-4" id="users-pagination">
            @if ($users->hasPages())
                {{ $users->links() }}
            @endif
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('staffs-search');
            let typingTimer;
            const typingDelay = 500; // نصف ثانية تأخير قبل البحث
            const usersTableWrapper = document.getElementById('users-table-wrapper');
            const usersPagination = document.getElementById('users-pagination');

            function fetchUsers(page = 1) {
                const search = searchInput.value;

                fetch(`{{ route('dashboard.customer.index') }}?search=${encodeURIComponent(search)}&page=${page}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        usersTableWrapper.innerHTML = data.html;
                        usersPagination.innerHTML = data.pagination;
                    })
                    .catch(err => console.error(err));
            }

            // البحث عند الكتابة
            searchInput.addEventListener('keyup', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    fetchUsers();
                }, typingDelay);
            });

            searchInput.addEventListener('keydown', function() {
                clearTimeout(typingTimer);
            });

            // التعامل مع الباجينيت
            usersPagination.addEventListener('click', function(e) {
                if (e.target.tagName === 'A') {
                    e.preventDefault();
                    const url = new URL(e.target.href);
                    const page = url.searchParams.get('page') || 1;
                    fetchUsers(page);
                }
            });
        });
    </script>
@endpush
