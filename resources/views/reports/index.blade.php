@extends('admin.layout')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>مدیریت گزارش‌ها</h1>
        <a href="{{ route('admin.reports.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> گزارش جدید
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($reports->isEmpty())
        <div class="alert alert-info">هیچ گزارشی ثبت نشده است.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>عنوان گزارش</th>
                        <th>نویسنده</th>
                        <th>تاریخ ایجاد</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $report->title }}</td>
                            <td>{{ optional($report->user)->name ?? '—' }}</td>
                            <td>{{ jdate($report->created_at)->format('Y/m/d H:i') }}</td>
                            <td>
                                @if($report->is_approved === true)
                                    <span class="badge bg-success">تأیید شده</span>
                                @elseif($report->is_approved === false)
                                    <span class="badge bg-danger">رد شده</span>
                                @else
                                    <span class="badge bg-secondary">در انتظار بررسی</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-sm btn-info">
                                        مشاهده
                                    </a>

                                    <form action="{{ route('admin.reports.approve', $report->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            تایید
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.reports.reject', $report->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            رد
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- صفحه‌بندی --}}
        <div class="mt-3">
            {{ $reports->links() }}
        </div>
    @endif
</div>
@endsection
