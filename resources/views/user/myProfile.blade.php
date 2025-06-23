@extends('user.layout')

@section('title', 'تکمیل مشخصات کاربری')

@section('breadcrumb')
    <a href="{{ route('dashboard.index') }}">داشبورد</a> / <span>مشخصات کاربری</span>
@endsection

@section('content')


<div class="container py-4">
    <h4 class="mb-4">مشخصات کاربری</h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    @php
        use Morilog\Jalali\Jalalian;
    @endphp

    <form method="POST" action="{{ route('dashboard.profile.store') }}" enctype="multipart/form-data">
    @csrf

        {{-- مشخصات اولیه --}}
        <h5 class="mt-4">مشخصات اولیه</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>نام</label>
                <input type="text" name="first_name" class="form-control" required
                       value="{{ old('first_name', $profile->first_name ?? '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>نام خانوادگی</label>
                <input type="text" name="last_name" class="form-control" required
                       value="{{ old('last_name', $profile->last_name ?? '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>جنسیت</label>
                <select name="gender" class="form-select" required>
                    <option value="">انتخاب کنید</option>
                    <option value="male" @selected(old('gender', $profile->gender ?? '') == 'male')>مرد</option>
                    <option value="female" @selected(old('gender', $profile->gender ?? '') == 'female')>زن</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>تاریخ تولد</label>
                <input type="text" id="birth_date" class="form-control" name="birth_date" value="{{ old('birth_date', isset($profile) ? Jalalian::fromCarbon($profile->birth_date)->format('Y/m/d') : '') }}" />
    
            </div>
            <div class="col-md-6 mb-3">
                <label>نام پدر</label>
                <input type="text" name="father_name" class="form-control"
                       value="{{ old('father_name', $profile->father_name ?? '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>کد ملی</label>
                <input type="text" name="national_id" class="form-control"
                       value="{{ old('national_id', $profile->national_id ?? '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>عکس پرسنلی</label>
                <input type="file" name="personal_photo" class="form-control">
                <small class="form-text text-muted">عکس باید واضح و از چهره روبرو باشد. فرمت مجاز: JPG یا PNG. حداکثر حجم: ۲ مگابایت.</small>
                @if(!empty($profile->personal_photo))
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $profile->personal_photo) }}" alt="عکس پرسنلی" width="100">
                    </div>
                @endif
            </div>
        </div>

        {{-- اطلاعات تماس --}}
        <h5 class="mt-4">اطلاعات تماس</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>تلفن همراه</label>
                <input type="text" name="phone" class="form-control"
                       value="{{ old('phone', $profile->phone ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label>استان</label>
                <input type="text" name="province" class="form-control"
                       value="{{ old('province', $profile->province ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label>شهر</label>
                <input type="text" name="city" class="form-control"
                       value="{{ old('city', $profile->city ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label>کد پستی</label>
                <input type="text" name="postal_code" class="form-control"
                       value="{{ old('postal_code', $profile->postal_code ?? '') }}">
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
                <input type="number" name="height" class="form-control"
                       value="{{ old('height', $profile->height ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label>وزن (kg)</label>
                <input type="number" name="weight" class="form-control"
                       value="{{ old('weight', $profile->weight ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label>گروه خونی</label>
                <select name="blood_type" class="form-select">
                    <option value="">انتخاب کنید</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                        <option value="{{ $type }}" @selected(old('blood_type', $profile->blood_type ?? '') == $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label>عمل جراحی داشته‌اید؟</label>
                <select name="had_surgery" class="form-select">
                    <option value="0" @selected(old('had_surgery', $profile->had_surgery ?? '') == '0')>خیر</option>
                    <option value="1" @selected(old('had_surgery', $profile->had_surgery ?? '') == '1')>بله</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>وضعیت جسمی خاص</label>
                <input type="text" name="medical_conditions" class="form-control"
                       value="{{ old('medical_conditions', $profile->medical_conditions ?? '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>آلرژی‌ها</label>
                <input type="text" name="allergies" class="form-control"
                       value="{{ old('allergies', $profile->allergies ?? '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>داروی مورد استفاده</label>
                <input type="text" name="medications" class="form-control"
                       value="{{ old('medications', $profile->medications ?? '') }}">
            </div>
        </div>

        {{-- اطلاعات تکمیلی --}}
        <h5 class="mt-4">اطلاعات تکمیلی</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>شغل</label>
                <input type="text" name="job" class="form-control"
                       value="{{ old('job', $profile->job ?? '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>معرف</label>
                <input type="text" name="referrer" class="form-control"
                       value="{{ old('referrer', $profile->referrer ?? '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>تلفن شخص اضطراری</label>
                <input type="text" name="emergency_phone" class="form-control"
                       value="{{ old('emergency_phone', $profile->emergency_phone ?? '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>نام شخص اضطراری</label>
                <input type="text" name="emergency_contact_name" class="form-control"
                       value="{{ old('emergency_contact_name', $profile->emergency_contact_name ?? '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>نسبت با شخص اضطراری</label>
                <input type="text" name="emergency_contact_relation" class="form-control"
                       value="{{ old('emergency_contact_relation', $profile->emergency_contact_relation ?? '') }}">
            </div>
        </div>

        <button class="btn btn-primary py-2" style="width: 100%;" type="submit">ثبت مشخصات</button>
    </form>
</div>

@endsection

@push('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>

<script>
function fixPersianNumbers(str) {
    var persian = [/۰/g, /۱/g, /۲/g, /۳/g, /۴/g, /۵/g, /۶/g, /۷/g, /۸/g, /۹/g],
        english = ['0','1','2','3','4','5','6','7','8','9'];
    for(var i = 0; i < 10; i++) {
        str = str.replace(persian[i], english[i]);
    }
    return str;
}

$(document).ready(function() {
    // مقدار اولیه رو اصلاح کن
    $("#birth_date").val(fixPersianNumbers($("#birth_date").val()));

    // فعال‌سازی تقویم شمسی
    $("#birth_date").persianDatepicker({
        format: 'YYYY/MM/DD',
        initialValueType: 'persian',
        autoClose: true,
        observer: true,
        calendar: {
            persian: {
                locale: 'fa'
            }
        }
    });

    // هنگام ارسال فرم، دوباره اعداد رو انگلیسی کن
    $('form').on('submit', function() {
        var birthDate = $('#birth_date').val();
        $('#birth_date').val(fixPersianNumbers(birthDate));
    });
});
</script>
@endpush