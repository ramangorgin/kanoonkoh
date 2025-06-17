@extends('layouts.admin')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">لیست کاربران</li>
    </ol>
</nav>
@endsection

@section('content')
<h3>مدیریت کاربران</h3>



    <a href="{{ route('admin.users.create') }}" class="btn btn-success m-2 mb-4">+ افزودن کاربر جدید</a>




    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="جستجوی نام کاربر..." value="{{ request('search') }}">
            <button class="btn btn-outline-secondary" type="submit">جستجو</button>
        </div>
    </form>

<table id="usersTable" class="display nowrap table table-bordered text-center" style="width:100%">
    <thead>
        <tr>
            <th style="width: 2%;">شناسه</th>
            <th style="width: 18%;">نام</th>
            <th style="width: 18%;">نام خانوادگی</th>
            <th style="width: 5%;">نقش</th>
            <th style="width: 15%;">شماره تماس</th>
            <th style="width: 15%;">عملیات</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ optional($user->profile)->first_name }}</td>
            <td>{{ optional($user->profile)->last_name }}</td>
            <td>{{ $user->role === 'admin' ? 'ادمین' : 'عضو' }}</td>
            <td>{{ optional($user->profile)->phone }}</td>
            <td>
                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info">مشاهده</a>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">ویرایش</a>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('آیا از حذف کاربر مطمئن هستید؟');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-center">
        {!! $users->appends(['search' => request('search')])->links() !!}
    </div>
@endsection

@section('scripts')


<script>
        $(document).ready(function () {
            $('#usersTable').DataTable({
                responsive: true,
                colReorder: true,
                searchPanes: true,
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'copy', text: 'کپی' },
                    { extend: 'excel', text: 'اکسل' },
                    { extend: 'pdf', text: 'PDF' },
                    { extend: 'print', text: 'چاپ' }
                ],
                language: {
                    "search": "جستجو:",
                    "lengthMenu": "نمایش _MENU_ مورد",
                    "info": "نمایش _START_ تا _END_ از _TOTAL_ مورد",
                    "paginate": {
                        "first": "اول",
                        "last": "آخر",
                        "next": "بعدی",
                        "previous": "قبلی"
                    },
                    "zeroRecords": "موردی پیدا نشد",
                    "buttons": {
                        "copy": "کپی",
                        "excel": "خروجی اکسل",
                        "pdf": "خروجی PDF",
                        "print": "چاپ"
                    }
                },
                pageLength: 10,
                order: [[1, 'desc']]
            });
        });
    </script>
@endsection
