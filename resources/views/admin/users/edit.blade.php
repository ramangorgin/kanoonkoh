@extends('layouts.admin')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">کاربران</a></li>
            <li class="breadcrumb-item active" aria-current="page">ویرایش کاربر</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h3>ویرایش کاربر</h3>

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- اطلاعات پایه‌ای کاربر --}}
        <div class="mb-3">
            <label>ایمیل</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-3">
            <label>رمز عبور (در صورت نیاز تغییر دهید)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="mb-3">
            <label>نقش کاربر</label>
            <select name="role" class="form-control">
                <option value="user" @selected($user->role === 'user')>کاربر عادی</option>
                <option value="admin" @selected($user->role === 'admin')>ادمین</option>
            </select>
        </div>

        <hr>

        {{-- اطلاعات پروفایل --}}
        <h5 class="mt-4">مشخصات فردی</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>نام</label>
                <input type="text" name="first_name" value="{{ old('first_name', $profile->first_name ?? '') }}" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>نام خانوادگی</label>
                <input type="text" name="last_name" value="{{ old('last_name', $profile->last_name ?? '') }}" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>جنسیت</label>
                <select name="gender" class="form-select" required>
                    <option value="">انتخاب کنید</option>
                    <option value="male" @selected(old('gender', $profile->gender ?? '') === 'male')>مرد</option>
                    <option value="female" @selected(old('gender', $profile->gender ?? '') === 'female')>زن</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>تاریخ تولد</label>
                <input type="text" name="birth_date" id="birth_date" value="{{ old('birth_date', $profile->birth_date ?? '') }}" class="form-control datepicker" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>نام پدر</label>
                <input type="text" name="father_name" value="{{ old('father_name', $profile->father_name ?? '') }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label>کد ملی</label>
                <input type="text" name="national_id" value="{{ old('national_id', $profile->national_id ?? '') }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label>عکس پرسنلی</label>
                <input type="file" name="personal_photo" class="form-control">
                <small class="form-text text-muted">عکس باید واضح و از چهره روبرو باشد. فرمت مجاز: JPG یا PNG. حداکثر حجم: ۲ مگابایت.</small>
            </div>
        </div>

        {{-- اطلاعات تماس --}}
        <h5 class="mt-4">اطلاعات تماس</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>تلفن همراه</label>
                <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label>استان</label>
                <input type="text" name="province" value="{{ old('province', $profile->province ?? '') }}" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label>شهر</label>
                <input type="text" name="city" value="{{ old('city', $profile->city ?? '') }}" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label>کد پستی</label>
                <input type="text" name="postal_code" value="{{ old('postal_code', $profile->postal_code ?? '') }}" class="form-control">
            </div>
            <div class="col-md-12 mb-3">
                <label>آدرس</label>
                <textarea name="address" class="form-control" rows="2">{{ old('address', $profile->address ?? '') }}</textarea>
            </div>
        </div>

        {{-- اطلاعات پزشکی --}}
        <h5 class="mt-4">وضعیت جسمانی</h5>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label>قد (cm)</label>
                <input type="number" name="height" value="{{ old('height', $profile->height ?? '') }}" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label>وزن (kg)</label>
                <input type="number" name="weight" value="{{ old('weight', $profile->weight ?? '') }}" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label>گروه خونی</label>
                <select name="blood_type" class="form-select">
                    <option value="">انتخاب کنید</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                        <option value="{{ $type }}" @selected(old('blood_type', $profile->blood_type ?? '') === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label>عمل جراحی داشته‌اید؟</label>
                <select name="has_surgery" class="form-select">
                    <option value="0" @selected(old('has_surgery', $profile->has_surgery ?? '') == 0)>خیر</option>
                    <option value="1" @selected(old('has_surgery', $profile->has_surgery ?? '') == 1)>بله</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>وضعیت جسمی خاص</label>
                <input type="text" name="physical_condition" value="{{ old('physical_condition', $profile->physical_condition ?? '') }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label>آلرژی‌ها</label>
                <input type="text" name="allergies" value="{{ old('allergies', $profile->allergies ?? '') }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label>داروی مورد استفاده</label>
                <input type="text" name="medications" value="{{ old('medications', $profile->medications ?? '') }}" class="form-control">
            </div>
        </div>

        {{-- اطلاعات تکمیلی --}}
        <h5 class="mt-4">اطلاعات تکمیلی</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>شغل</label>
                <input type="text" name="job" value="{{ old('job', $profile->job ?? '') }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label>معرف</label>
                <input type="text" name="referrer" value="{{ old('referrer', $profile->referrer ?? '') }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label>تلفن شخص اضطراری</label>
                <input type="text" name="emergency_phone" value="{{ old('emergency_phone', $profile->emergency_phone ?? '') }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label>نام شخص اضطراری</label>
                <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $profile->emergency_contact_name ?? '') }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label>نسبت با شخص اضطراری</label>
                <input type="text" name="emergency_contact_relation" value="{{ old('emergency_contact_relation', $profile->emergency_contact_relation ?? '') }}" class="form-control">
            </div>
        </div>

        {{-- 📘 دوره‌های گذرانده‌شده پیش از عضویت --}}
        <h5 class="mb-3 mt-4">دوره‌های قبلی</h5>
        <div class="mb-3">
            <label for="has_previous_courses" class="form-label">آیا کاربر پیش از عضویت دوره‌ای گذرانده است؟</label>
            <select class="form-select" id="has_previous_courses" name="has_previous_courses">
            <option value="no" {{ ($user->courseCertificates && $user->courseCertificates->count()) ? '' : 'selected' }}>خیر</option>
        <option value="yes" {{ ($user->courseCertificates && $user->courseCertificates->count()) ? 'selected' : '' }}>بله</option>
            </select>
        </div>

{{-- فرم پویا برای دوره‌ها --}}
<div id="previous_courses_section" style="{{ optional($user->courseCertificates)->count() ? '' : 'display: none;' }}">
    <label class="form-label">مشخصات دوره‌ها</label>
    <div id="courses_container">
        @foreach($user->courseCertificates ?? [] as $i => $certificate)
            <div class="course-entry mb-3 border p-3 rounded">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <label class="form-label">نام دوره</label>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-course-btn">حذف</button>
                </div>
                <input type="text" name="courses[{{ $i }}][title]" value="{{ $certificate->course_name }}" class="form-control mb-2">

                <label class="form-label">فایل مدرک</label>
                <input type="file" name="courses[{{ $i }}][file]" class="form-control">

                @if($certificate->file_path)
                    <a href="{{ asset('storage/' . $certificate->file_path) }}" target="_blank" class="btn btn-link mt-1">مشاهده فایل فعلی</a>
                @endif
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-sm btn-secondary mt-2 me-2" id="add_course_btn">افزودن دوره دیگر</button>
    </div>
</div>



        <button class="btn btn-primary mt-3" style="width: 100%;">ویرایش کاربر</button>
    </form>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
<script>
    $(document).ready(function() {
        $("#birth_date").persianDatepicker({
            format: 'YYYY-MM-DD',
            autoClose: true,
            initialValue: false,
            observer: true,
            calendar: {
                persian: {
                    locale: 'fa'
                }
            }
        });
    });
</script>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectBox = document.getElementById('has_previous_courses');
        const section = document.getElementById('previous_courses_section');
        const container = document.getElementById('courses_container');
        const addBtn = document.getElementById('add_course_btn');

        selectBox.addEventListener('change', function () {
            section.style.display = this.value === 'yes' ? 'block' : 'none';
        });

        let index = {{ optional($user->courseCertificates)->count() ?? 0 }};

        addBtn.addEventListener('click', function () {
            const div = document.createElement('div');
            div.className = 'course-entry mb-3 border p-3 rounded';
            div.innerHTML = `
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <label class="form-label">نام دوره</label>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-course-btn">حذف</button>
                </div>
                <input type="text" name="courses[${index}][title]" class="form-control mb-2">
                <label class="form-label">فایل مدرک</label>
                <input type="file" name="courses[${index}][file]" class="form-control">
            `;
            container.appendChild(div);
            index++;
        });

        container.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-course-btn')) {
                e.target.closest('.course-entry').remove();
            }
        });
    });
</script>
@endpush


@endsection
