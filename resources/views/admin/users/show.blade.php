@extends('admin.layout')

@section('title', 'مشاهده کاربر')

@section('content')
<div class="container-fluid py-4 animate__animated animate__fadeIn">

    {{-- هدر صفحه --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">
            <i class="bi bi-person-badge-fill text-primary me-2"></i> جزئیات کاربر
        </h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-secondary">
                <i class="bi bi-pencil-square"></i> ویرایش
            </a>
            <button class="btn btn-danger delete-user" data-id="{{ $user->id }}">
                <i class="bi bi-trash3"></i> حذف
            </button>
        </div>
    </div>

    {{-- اطلاعات پروفایل --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3 text-primary">
                <i class="bi bi-person-lines-fill"></i> اطلاعات شخصی
            </h5>

            <div class="row g-3">
                <div class="col-md-3"><strong>نام:</strong> {{ $user->profile->first_name ?? '-' }}</div>
                <div class="col-md-3"><strong>نام خانوادگی:</strong> {{ $user->profile->last_name ?? '-' }}</div>
                <div class="col-md-3"><strong>شماره تماس:</strong> {{ toPersianNumber($user->phone) }}</div>
                <div class="col-md-3"><strong>کد ملی:</strong> {{ toPersianNumber($user->profile->national_id ?? '-') }}</div>

                <div class="col-md-3"><strong>تاریخ تولد:</strong> {{ isset($user->profile->birth_date) ? toPersianNumber(jdate($user->profile->birth_date)->format('Y/m/d')) : '-' }}</div>
                <div class="col-md-3"><strong>وضعیت تأهل:</strong> {{ $user->profile->marital_status ?? '-' }}</div>
                <div class="col-md-3"><strong>تحصیلات:</strong> {{ $user->profile->education ?? '-' }}</div>
                <div class="col-md-3"><strong>شغل:</strong> {{ $user->profile->job ?? '-' }}</div>
            </div>
        </div>
    </div>

    {{-- اطلاعات عضویت --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3 text-success">
                <i class="bi bi-patch-check-fill"></i> وضعیت عضویت
            </h5>

            @if($user->profile)
                <div class="row g-3">
                    <div class="col-md-3"><strong>شناسه عضویت:</strong> {{ toPersianNumber($user->profile->membership_id ?? '-') }}</div>
                    <div class="col-md-3"><strong>نوع عضویت:</strong> {{ $user->profile->membership_type ?? '-' }}</div>
                    <div class="col-md-3"><strong>تاریخ شروع:</strong> {{ isset($user->profile->membership_start) ? toPersianNumber(jdate($user->profile->membership_start)->format('Y/m/d')) : '-' }}</div>
                    <div class="col-md-3"><strong>تاریخ پایان:</strong> {{ isset($user->profile->membership_expiry) ? toPersianNumber(jdate($user->profile->membership_expiry)->format('Y/m/d')) : '-' }}</div>
                </div>

                <div class="mt-3">
                    <strong>وضعیت فعلی:</strong>
                    @if($user->profile->membership_status === 'approved')
                        <span class="badge bg-success">تایید شده</span>
                    @elseif($user->profile->membership_status === 'pending')
                        <span class="badge bg-warning text-dark">در انتظار</span>
                    @elseif($user->profile->membership_status === 'rejected')
                        <span class="badge bg-danger">رد شده</span>
                    @endif
                </div>

                {{-- دکمه‌های تایید یا رد عضویت --}}
                @if($user->profile->membership_status === 'pending')
                    <div class="mt-4">
                        <button class="btn btn-success approve-user" data-id="{{ $user->profile->id }}">
                            <i class="bi bi-check-circle"></i> تایید عضویت
                        </button>
                        <button class="btn btn-danger reject-user" data-id="{{ $user->profile->id }}">
                            <i class="bi bi-x-circle"></i> رد عضویت
                        </button>
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- پرونده پزشکی --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3 text-danger">
                <i class="bi bi-heart-pulse-fill"></i> پرونده پزشکی
            </h5>
            @if($user->medicalRecord)
                <div class="row g-3">
                    <div class="col-md-3"><strong>گروه خونی:</strong> {{ $user->medicalRecord->blood_type ?? '-' }}</div>
                    <div class="col-md-3"><strong>قد:</strong> {{ toPersianNumber($user->medicalRecord->height ?? '-') }}</div>
                    <div class="col-md-3"><strong>وزن:</strong> {{ toPersianNumber($user->medicalRecord->weight ?? '-') }}</div>
                </div>
            @else
                <p class="text-muted">پرونده پزشکی هنوز ثبت نشده است.</p>
            @endif
        </div>
    </div>

    {{-- سوابق آموزشی --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3 text-info">
                <i class="bi bi-book-fill"></i> سوابق آموزشی
            </h5>
            @if($user->educationalHistories->count())
                <ul class="list-group">
                    @foreach($user->educationalHistories as $edu)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            دوره {{ $edu->federation_course_id }} - تاریخ صدور: 
                            <span>{{ toPersianNumber(jdate($edu->issue_date)->format('Y/m/d')) }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">هیچ سابقه آموزشی ثبت نشده است.</p>
            @endif
        </div>
    </div>

    {{-- پرداخت‌ها --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3 text-primary">
                <i class="bi bi-credit-card-2-front"></i> آخرین پرداخت‌ها
            </h5>
            @if($user->payments->count())
                <table class="table table-striped text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>مبلغ</th>
                            <th>نوع پرداخت</th>
                            <th>وضعیت</th>
                            <th>تاریخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->payments->take(5) as $p)
                            <tr>
                                <td>{{ number_format($p->amount) }} تومان</td>
                                <td>{{ $p->type === 'membership' ? 'حق عضویت' : ($p->type === 'course' ? 'دوره' : 'برنامه') }}</td>
                                <td>
                                    @if($p->status === 'approved')
                                        <span class="badge bg-success">تایید شده</span>
                                    @elseif($p->status === 'pending')
                                        <span class="badge bg-warning text-dark">در انتظار</span>
                                    @else
                                        <span class="badge bg-danger">رد شده</span>
                                    @endif
                                </td>
                                <td>{{ toPersianNumber(jdate($p->created_at)->format('Y/m/d')) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">هیچ پرداختی یافت نشد.</p>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function() {
    // حذف کاربر
    $('.delete-user').click(function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'آیا مطمئن هستید؟',
            text: "کاربر برای همیشه حذف خواهد شد.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'بله حذف شود',
            cancelButtonText: 'انصراف',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/users/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function() {
                        Swal.fire('حذف شد!', 'کاربر با موفقیت حذف شد.', 'success')
                            .then(() => window.location.href = '{{ route("admin.users.index") }}');
                    },
                    error: function() {
                        Swal.fire('خطا', 'مشکلی در حذف کاربر پیش آمد.', 'error');
                    }
                });
            }
        });
    });

    // تایید عضویت
    $('.approve-user').click(function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'تایید عضویت؟',
            text: 'آیا از تایید این کاربر مطمئن هستید؟',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'بله، تایید شود',
            cancelButtonText: 'انصراف',
            confirmButtonColor: '#198754'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(`/admin/users/${id}/approve`, {_token: '{{ csrf_token() }}'}, function() {
                    Swal.fire('تایید شد!', 'عضویت کاربر تایید شد ✅', 'success')
                        .then(() => location.reload());
                });
            }
        });
    });

    // رد عضویت
    $('.reject-user').click(function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'رد عضویت؟',
            text: 'آیا مطمئن هستید؟',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'بله، رد شود',
            cancelButtonText: 'انصراف',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(`/admin/users/${id}/reject`, {_token: '{{ csrf_token() }}'}, function() {
                    Swal.fire('رد شد!', 'عضویت کاربر رد شد ❌', 'success')
                        .then(() => location.reload());
                });
            }
        });
    });
});
</script>
@endpush
