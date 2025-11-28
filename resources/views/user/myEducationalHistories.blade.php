@extends('layout')

@section('title', 'سوابق آموزشی من')

@section('content')
@php
$user = $user ?? auth()->user();
$histories = $histories ?? collect();
$federationCourses = $federationCourses ?? collect();
@endphp

<div class="container py-4">
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <strong>مرحله ۳ از ۳</strong>
                <div class="text-muted small">سوابق آموزشی — حداقل یک سابقه جهت تکمیل ثبت‌نام اضافه کنید یا بعداً اضافه کنید.</div>
            </div>
            <div style="min-width:220px;">
                <div class="progress" style="height:10px; border-radius:8px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width:100%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm" style="background: rgba(255,255,255,0.92); border-radius:12px;">
        <div class="card-body">
            <h4 class="mb-3"><i class="bi bi-book-half"></i> سوابق آموزشی من</h4>

            <div class="text-end mb-3">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bi bi-plus-circle"></i> افزودن سابقه جدید
                </button>
            </div>

            @if($histories->isEmpty())
                <div class="alert alert-light text-center">هیچ سابقه آموزشی ثبت نشده است. می‌توانید با کلیک روی «افزودن سابقه جدید» شروع کنید.</div>
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
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $history->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

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
                                                        <small class="form-text text-muted">از لیست دوره مرتبط را انتخاب کنید</small>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">تاریخ صدور مدرک</label>
                                                        <input type="text" class="form-control persian-datepicker"
                                                               name="issue_date"
                                                               value="{{ $history->issue_date_jalali }}">
                                                        <small class="form-text text-muted">تاریخ به فرمت شمسی</small>
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

                <div class="d-flex justify-content-center mt-4">
                    {{ $histories->links() }}
                </div>
            @endif
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
                            <small class="form-text text-muted">در صورتی که دوره مورد نظر حضور ندارد، بعداً می‌توانید اضافه کنید</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">تاریخ صدور مدرک</label>
                            <input type="text" name="issue_date" class="form-control persian-datepicker">
                            <small class="form-text text-muted">تاریخ به فرمت شمسی</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">فایل مدرک</label>
                            <input type="file" name="certificate_file" class="form-control" accept="image/*,application/pdf">
                            <small class="form-text text-muted">اختیاری — آپلود بعدی نیز ممکن است</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">ذخیره</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="mt-3 text-muted small">
        راهنما: بهتر است حداقل یک سابقه وارد کنید تا ثبت‌نام کامل شود. اما می‌توانید بعداً نیز اضافه کنید.
    </div>
</div>

@push('scripts')
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

    $(document).on('shown.bs.modal', '.modal', function() {
        $(this).find('.persian-datepicker').persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            calendar: { persian: { locale: 'fa' } }
        });
    });
</script>
@endpush

@endsection