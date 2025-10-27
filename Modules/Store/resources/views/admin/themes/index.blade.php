@extends('core::dashboard.layouts.app')

@section('title', 'الثيمات - لوحة التحكم')

@push('styles')
<style>
    .container {
        max-width: 900px;
        margin: 50px auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        color: #10b981;
        margin-bottom: 25px;
    }
    .top-actions {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
    }
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.95rem;
        transition: 0.2s;
    }
    .btn-primary {
        background: #10b981;
        color: #fff;
    }
    .btn-primary:hover {
        background: #0f766e;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    table th, table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    table th {
        background: #f3f3f3;
    }
    .action-btns button {
        margin-right: 5px;
    }
</style>
@endpush

@section('content')
<div class="container">
    <h2>الثيمات</h2>

    <div class="top-actions">
        <a href="{{ route('admin.themes.create') }}" class="btn btn-primary">إضافة ثيم جديد</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم </th>
          
                {{-- <th>الإجراءات</th> --}}
            </tr>
        </thead>
        <tbody>
            @forelse($themes as $theme)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $theme->name}}</td>

                    {{-- <td class="action-btns">
                        <a href="{{ route('dashboard.themes.edit', $theme->id) }}" class="btn btn-primary">تعديل</a>
                        <form action="{{ route('dashboard.themes.destroy', $theme->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا الثيم؟')">حذف</button>
                        </form>
                    </td> --}}
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">لا توجد ثيمات بعد</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
