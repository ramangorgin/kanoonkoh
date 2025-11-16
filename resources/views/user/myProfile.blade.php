@extends('user.layout')

@section('title', 'ویرایش مشخصات کاربری')

@section('breadcrumb')
    <a href="{{ route('dashboard.index') }}">داشبورد</a> / <span>ویرایش مشخصات کاربری</span>
@endsection

@section('content')

@php
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;

function toPersianDate($date)
{
    if (!$date) return '';

    $enToFa = function ($num) {
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $english = ['0','1','2','3','4','5','6','7','8','9'];
        return str_replace($english, $persian, $num);
    };

    try {
        if ($date instanceof Carbon) {
            $jalali = Jalalian::forge($date)->format('Y/m/d');
        } else {
            $jalali = Jalalian::forge(strtotime($date))->format('Y/m/d');
        }
        return $enToFa($jalali);
    } catch (\Exception $e) {
        return '';
    }
}
@endphp


<div class="container py-4">
    <h4 class="mb-4">ویرایش مشخصات کاربری</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('dashboard.profile.update', $user->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3">

            <!-- نام -->
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-person"></i> نام</label>
                <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $profile->first_name) }}" required>
            </div>

            <!-- نام خانوادگی -->
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-people"></i> نام خانوادگی</label>
                <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $profile->last_name) }}" required>
            </div>

            <!-- نام پدر -->
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-person-lines-fill"></i> نام پدر</label>
                <input type="text" class="form-control" name="father_name" value="{{ old('father_name', $profile->father_name) }}">
            </div>

            <!-- شماره شناسنامه -->
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-card-heading"></i> شماره شناسنامه</label>
                <input type="text" class="form-control" name="id_number" value="{{ old('id_number', $profile->id_number) }}">
            </div>

            <!-- محل صدور -->
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-geo-alt"></i> محل صدور</label>
                <input type="text" class="form-control" name="id_place" value="{{ old('id_place', $profile->id_place) }}">
            </div>

            <!-- تاریخ تولد -->
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-calendar-event"></i> تاریخ تولد</label>
                <input type="text" id="birth_date" name="birth_date"
                    class="form-control"
                    value="{{ old('birth_date', toPersianDate($profile->birth_date)) }}">
            </div>

            <!-- کدملی -->
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-person-vcard"></i> کد ملی</label>
                <input type="text" class="form-control" name="national_id"
                       value="{{ old('national_id', $profile->national_id) }}">
            </div>

            <!-- عکس پرسنلی -->
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-image"></i> عکس پرسنلی</label>
                <input type="file" class="form-control" name="photo" accept="image/*">
                @if($profile && $profile->photo)
                    <div class="mt-3 text-center">
                        <img src="{{ asset('storage/' . $profile->photo) }}"
                            alt="عکس پرسنلی"
                            class="img-thumbnail shadow-sm"
                            style="max-width: 160px; border-radius: 10px;">
                    </div>
                @endif
            </div>

            <!-- کارت ملی -->
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-credit-card"></i> اسکن کارت ملی</label>
                <input type="file" class="form-control" name="national_card" accept="image/*,application/pdf">
                @if($profile && $profile->national_card)
                    <div class="mt-3 text-center">
                        <img src="{{ asset('storage/' . $profile->national_card) }}"
                            alt="کارت ملی"
                            class="img-thumbnail shadow-sm"
                            style="max-width: 160px; border-radius: 10px;">
                    </div>
                @endif
            </div>

            <!-- تاهل -->
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-heart"></i> وضعیت تأهل</label>
                <select class="form-select" name="marital_status">
                    <option value="مجرد" {{ old('marital_status', $profile->marital_status) == 'مجرد' ? 'selected' : '' }}>مجرد</option>
                    <option value="متاهل" {{ old('marital_status', $profile->marital_status) == 'متاهل' ? 'selected' : '' }}>متاهل</option>
                </select>
            </div>

            <!-- تلفن ضروری -->
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-telephone"></i> تلفن ضروری</label>
                <input type="text" class="form-control" name="emergency_phone" value="{{ old('emergency_phone', $profile->emergency_phone) }}">
            </div>

            <!-- معرف -->
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-person-badge"></i> نام معرف</label>
                <input type="text" class="form-control" name="referrer" value="{{ old('referrer', $profile->referrer) }}">
            </div>

            <!-- تحصیلات -->
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-book"></i> تحصیلات</label>
                <input type="text" class="form-control" name="education" value="{{ old('education', $profile->education) }}">
            </div>

            <!-- شغل -->
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-briefcase"></i> شغل</label>
                <input type="text" class="form-control" name="job" value="{{ old('job', $profile->job) }}">
            </div>

            <!-- آدرس منزل -->
            <div class="col-12">
                <label class="form-label"><i class="bi bi-house"></i> نشانی محل سکونت</label>
                <textarea class="form-control" name="home_address" rows="2">{{ old('home_address', $profile->home_address) }}</textarea>
            </div>

            <!-- آدرس محل کار -->
            <div class="col-12">
                <label class="form-label"><i class="bi bi-building"></i> نشانی محل کار</label>
                <textarea class="form-control" name="work_address" rows="2">{{ old('work_address', $profile->work_address) }}</textarea>
            </div>

        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-success w-100 py-2">
                به‌روزرسانی مشخصات <i class="bi bi-check2-circle"></i>
            </button>
        </div>

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
    // فعال‌سازی تاریخ شمسی
    $("#birth_date").persianDatepicker({
        format: 'YYYY/MM/DD',
        initialValueType: 'persian',
        autoClose: true,
        observer: true,
        persianDigit: true,
        calendar: { persian: { locale: 'fa' } }
    });

    // تبدیل اعداد فارسی به انگلیسی هنگام ارسال فرم
    $('form').on('submit', function() {
        $('#birth_date').val(fixPersianNumbers($('#birth_date').val()));
    });
});
</script>
@endpush
