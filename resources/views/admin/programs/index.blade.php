@extends('layouts.admin')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.programs.index') }}">برنامه‌ها</a></li>
            <li class="breadcrumb-item active" aria-current="page">لیست برنامه‌ها</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h3>لیست برنامه‌ها</h3>

    <a href="{{ route('admin.programs.create') }}" class="btn btn-success mb-3">+ برنامه جدید</a>

    <table id="programs-table" class="table table-bordered table-hover nowrap">
        <thead class="table-light">
            <tr>
                <th>عنوان</th>
                <th>تاریخ</th>
                <th>ثبت‌نام</th>
                <th>رایگان؟</th>
                <th>سرپرست</th>
                <th>عکس‌ها</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($programs as $program)
                <tr>
                    {{-- عنوان و لینک --}}
                    <td>
                        <a href="{{ route('admin.programs.show', $program->id) }}">
                            {{ $program->title }}
                        </a>
                    </td>

                    {{-- تاریخ برنامه --}}
                    <td>
                        <i class="bi bi-calendar-event"></i>
                        {{ $program->date }}
                    </td>

                    {{-- وضعیت ثبت‌نام --}}
                    <td>
                        @if($program->is_registration_open)
                            <span class="badge bg-success">باز</span>
                        @else
                            <span class="badge bg-secondary">بسته</span>
                        @endif
                    </td>

                    {{-- رایگان یا خیر --}}
                    <td>
                        @if($program->is_free)
                            <span class="text-success fw-bold">رایگان</span>
                        @else
                            <span class="text-danger fw-bold">
                                {{ number_format($program->member_cost) }} ریال
                            </span>
                        @endif
                    </td>

                    {{-- سرپرست --}}
                    <td>
                        @php
                            $leader = \App\Models\User::where('name', $program->leader_name)->first();
                        @endphp
                        @if($leader && $leader->profile && $leader->profile->photo)
                            <img src="{{ asset('storage/' . $leader->profile->photo) }}" width="40" class="rounded-circle me-1">
                        @endif
                        {{ $program->leader_name }}
                    </td>

                    {{-- تعداد عکس‌ها --}}
                    <td>
                        @php $count = is_array($program->report_photos) ? count($program->report_photos) : 0; @endphp
                        {{ $count }} عکس
                    </td>

                    <td>
                        <a href="{{ route('admin.programs.show', $program->id) }}" class="btn btn-sm btn-info">جزئیات</a>
                        <a href="{{ route('admin.programs.registrations', $program->id) }}" class="btn btn-sm btn-warning">مشاهده ثبت‌نامی‌ها</a>
                    </td>

                    {{-- دکمه‌ها --}}
                    <td>
                        <a href="{{ route('admin.programs.edit', $program->id) }}" class="btn btn-sm btn-primary">ویرایش</a>

                        <form action="{{ route('admin.programs.destroy', $program->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('آیا از حذف این برنامه مطمئن هستید؟');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
@section('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables Core + Bootstrap Integration -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- Buttons Extension -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <!-- Responsive Extension -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <!-- ColReorder & SearchPanes -->
    <link rel="stylesheet" href="https://cdn.datatables.net/colreorder/1.6.2/css/colReorder.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/colreorder/1.6.2/js/dataTables.colReorder.min.js"></script>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.2.0/css/searchPanes.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/searchpanes/2.2.0/js/dataTables.searchPanes.min.js"></script>
    <script src="https://cdn.datatables.net/searchpanes/2.2.0/js/searchPanes.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#programs-table').DataTable({
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
                order: [[1, 'desc']]
            });
        });
    </script>
@endsection
