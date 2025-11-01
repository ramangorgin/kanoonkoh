@extends('layout')

@section('content')
<div class="container">
    <h2 class="text-center mb-4"><i class="bi bi-book-half"></i> سوابق آموزشی من</h2>

    <!-- دکمه افزودن -->
    <div class="text-end mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-circle"></i> افزودن سابقه جدید
        </button>
    </div>

    <!-- جدول سوابق -->
    <div class="card shadow-sm">
        <div class="card-body">
            @if($histories->isEmpty())
                <p class="text-center text-muted">هیچ سابقه آموزشی ثبت نشده است.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ردیف</th>
                                <th>عنوان دوره</th>
                                <th>مدرک</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($histories as $index => $history)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $history->federationCourse->title ?? '---' }}</td>

                                    <td>
                                        @if($history->certificate_file)
                                            <a href="{{ asset('storage/' . $history->certificate_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-file-earmark-arrow-down"></i> مشاهده
                                            </a>
                                        @else
                                            <span class="text-muted">ندارد</span>
                                        @endif
                                    </td>
                                    <td>
                                        <!-- دکمه ویرایش -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $history->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <!-- دکمه حذف -->
                                    <form action="{{ route('dashboard.educationalHistory.destroy', $history->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('آیا از حذف این سابقه مطمئن هستید؟')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                    </td>
                                </tr>

                                <!-- Modal ویرایش -->
                                <div class="modal fade" id="editModal{{ $history->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning text-white">
                                                <h5 class="modal-title"><i class="bi bi-pencil-square"></i> ویرایش سابقه آموزشی</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="{{ route('dashboard.educationalHistory.update', $history->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">عنوان دوره</label>
                                                        <select class="form-select" name="federation_course_id" required>
                                                            @foreach($federationCourses as $course)
                                                                <option value="{{ $course->id }}"
                                                                    {{ $course->id == $history->federation_course_id ? 'selected' : '' }}>
                                                                    {{ $course->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">تاریخ صدور مدرک</label>
                                                        <input type="text" class="form-control persian-datepicker"
                                                               name="issue_date"
                                                               value="{{ $history->issue_date ? \Morilog\Jalali\Jalalian::fromCarbon($history->issue_date)->format('Y/m/d') : '' }}">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">فایل مدرک</label>
                                                        <input type="file" name="certificate_file" class="form-control" accept="image/*,application/pdf">
                                                        @if($history->certificate_file)
                                                            <small class="text-muted">فایل فعلی: {{ basename($history->certificate_file) }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">ذخیره تغییرات</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- صفحه‌بندی -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $histories->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal افزودن سابقه جدید -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle"></i> افزودن سابقه آموزشی جدید</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('dashboard.educationalHistory.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">عنوان دوره</label>
                        <select class="form-select" name="federation_course_id" required>
                            <option value="">انتخاب کنید...</option>
                            @foreach($federationCourses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">فایل مدرک</label>
                        <input type="file" name="certificate_file" class="form-control" accept="image/*,application/pdf">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">ذخیره</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- اسکریپت تاریخ فارسی -->
<script src="https://cdn.jsdelivr.net/npm/persian-date/dist/persian-date.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-datepicker/dist/js/persian-datepicker.min.js"></script>
<script>
    $(document).ready(function() {
        $('.persian-datepicker').persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            calendar: { persian: { locale: 'fa' } }
        });
    });
</script>
@endsection