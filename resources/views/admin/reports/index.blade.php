@extends('layouts.admin')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">گزارش‌ها</a></li>
            <li class="breadcrumb-item active" aria-current="page">لیست گزارش‌ها</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h3>لیست گزارش‌ها</h3>

    <a href="{{ route('admin.reports.create') }}" class="btn btn-success mb-3">+ گزارش جدید</a>


    <table class="table table-bordered table-hover" id="reportsTable">
        <thead class="table-light">
            <tr>
                <th>عنوان</th>
                <th>برنامه</th>
                <th>نویسنده</th>
                <th>تاریخ شروع</th>
                <th>وضعیت تایید</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
                <tr>
                    <td>{{ $report->title }}</td>
                    <td>{{ $report->program->title ?? '---' }}</td>
                    <td>
                        @if ($report->user)
                            <a href="{{ route('admin.users.show', $report->user_id) }}">
                                {{ $report->user->full_name }}
                            </a>
                        @elseif ($report->writer_name)
                            <span class="text-warning">{{ $report->writer_name }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>                    
                    <td>{{ $report->start_date }}</td>
                    <td>
                        @if($report->approved)
                            <span class="badge bg-success">تایید شده</span>
                        @else
                            <span class="badge bg-warning">در انتظار تایید</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-sm btn-info">مشاهده</a>
                        <a href="{{ route('admin.reports.edit', $report->id) }}" class="btn btn-sm btn-primary">ویرایش</a>
                        <form action="{{ route('admin.reports.destroy', $report->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('آیا از حذف این گزارش مطمئن هستید؟')">
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
            $('#reportsTable').DataTable({
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