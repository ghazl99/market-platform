@extends('core::dashboard.layouts.app')

@section('title', 'Stores Management - Dashboard')

@push('styles')
    <style>
        /* Layout */
        .container {
            max-width: 1400px;
            margin: 60px auto;
            padding: 0 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn {
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
            text-decoration: none;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1e40af;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #334155;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-card h4 {
            font-size: 13px;
            text-transform: uppercase;
            margin-bottom: 6px;
            color: #6b7280;
        }

        .stat-card p {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }

        .stat-icon {
            font-size: 28px;
            color: #cbd5e1;
        }

        /* Table */
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .card-header {
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
        }

        .card-header h6 {
            font-size: 16px;
            font-weight: 700;
            color: #2563eb;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: #f1f5f9;
        }

        table th,
        table td {
            padding: 12px 15px;
            font-size: 14px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        table th {
            font-weight: 600;
            color: #475569;
        }

        table td {
            color: #1e293b;
        }

        /* Badges */
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-warning {
            background: #fef9c3;
            color: #92400e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-secondary {
            background: #e2e8f0;
            color: #475569;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        /* Table actions */
        .table-actions {
            display: flex;
            gap: 6px;
        }

        .btn-sm {
            padding: 6px 8px;
            font-size: 12px;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-store"></i> Stores Management</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.stores.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create New Store
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div>
                    <h4>Total Stores</h4>
                    <p>{{ $stores->count() }}</p>
                </div>
                <i class="fas fa-store stat-icon"></i>
            </div>
            <div class="stat-card">
                <div>
                    <h4>Active Stores</h4>
                    <p>{{ $stores->where('status', 'active')->count() }}</p>
                </div>
                <i class="fas fa-check-circle stat-icon"></i>
            </div>
            <div class="stat-card">
                <div>
                    <h4>Pending Review</h4>
                    <p>{{ $stores->where('status', 'pending')->count() }}</p>
                </div>
                <i class="fas fa-clock stat-icon"></i>
            </div>
            <div class="stat-card">
                <div>
                    <h4>Inactive Stores</h4>
                    <p>{{ $stores->where('status', 'inactive')->count() }}</p>
                </div>
                <i class="fas fa-times-circle stat-icon"></i>
            </div>
        </div>

        <!-- Stores Table -->
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-list"></i> Stores List</h6>
                <a href="{{ route('admin.stores.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New Store
                </a>
            </div>
            <div class="card-body">
                @if ($stores->count() > 0)
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Store</th>
                                    <th>Owner</th>
                                    <th>Domain</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Theme</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stores as $store)
                                    <tr>
                                        <td>{{ $store->name }}</td>
                                        <td>{{ $store->owners->first()->name }}</td>
                                        <td>{{ $store->store_url }}</td>
                                        <td><span class="badge badge-secondary">{{ $store->type }}</span></td>
                                        <td>
                                            <span
                                                class="badge
                                                {{ $store->status == 'active' ? 'badge-success' : ($store->status == 'pending' ? 'badge-warning' : 'badge-danger') }}">
                                                {{ $store->status }}
                                            </span>
                                        </td>
                                        <td><span class="badge badge-info"> {{ $store->theme?->name }}</span></td>
                                        <td>{{ $store->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="{{ route('admin.stores.show', $store) }}"
                                                    class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></a>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-store fa-3x text-gray-300 mb-3"></i>
                        <h5>No stores yet</h5>
                        <p>Start by creating your first store</p>
                        <a href="{{ route('admin.stores.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create New Store
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
