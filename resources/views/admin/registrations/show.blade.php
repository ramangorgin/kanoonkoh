@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <h4>لیست ثبت‌نامی‌ها برای {{ $title }}</h4>

    {{-- فیلتر وضعیت تایید --}}
    <form method="GET" class="form-inline mb-3">
        <label class="mr-2">فیلتر بر اساس وضعیت:</label>
        <select name="status" class="form-control mr-2">
            <option value="">همه</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>تایید شده</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>در انتظار</option>
        </select>
        <input type="text" name="search" class="form-control mr-2" placeholder="جستجوی نام یا ایمیل..." value="{{ request('search') }}">
        <button class="btn btn-primary">اعمال</button>
    </form>

    {{-- خروجی --}}
    <div class="mb-3">
        <a href="{{ route('admin.registrations.export', ['id' => $entityId, 'type' => $type, 'status' => 'approved']) }}" class="btn btn-success btn-sm">خروجی Excel تایید شده</a>
        <a href="{{ route('admin.registrations.exportPdf', ['id' => $entityId, 'type' => $type, 'status' => 'pending']) }}" class="btn btn-danger btn-sm">خروجی PDF تایید نشده</a>
    </div>

    <table id="registrationTable" class="table table-bordered table-hover table-sm">
        <thead class="thead-light">
            <tr>
                <th>نام</th>
                <th>کد ملی</th>
                <th>تاریخ تولد</th>
                <th>شماره تماس</th>
                <th>وضعیت</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registrations as $reg)
            <tr>
                <td>
                    @if($reg->user_id)
                        <a href="{{ route('admin.users.show', $reg->user_id) }}">{{ $reg->name }}</a>
                    @else
                        {{ $reg->name }}
                    @endif
                </td>
                <td>{{ $reg->national_code }}</td>
                <td>{{ jdate($reg->birthdate)->format('Y/m/d') }}</td>
                <td>{{ $reg->phone }}</td>
                <td>
                    @if($reg->status == 'approved')
                        <span class="badge badge-success">تایید شده</span>
                    @else
                        <span class="badge badge-warning">در انتظار</span>
                    @endif
                </td>
                <td>
                    <form method="POST" action="{{ route('admin.registrations.approve', $reg->id) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-success btn-sm">تایید</button>
                    </form>
                    <form method="POST" action="{{ route('admin.registrations.reject', $reg->id) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-danger btn-sm">رد</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $registrations->links() }}
    </div>

</div>
@endsection