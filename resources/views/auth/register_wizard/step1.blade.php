@extends('layout')

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
            $jalali = \Morilog\Jalali\Jalalian::forge($date)->format('Y/m/d');
        } else {
            $jalali = \Morilog\Jalali\Jalalian::forge(strtotime($date))->format('Y/m/d');
        }
        return $enToFa($jalali);
    } catch (\Exception $e) {
        return '';
    }
}
@endphp

<div class="container">

    <div class="mb-4">
        <h2 class="text-center mb-3">
            <i class="bi bi-person-badge"></i> مرحله اول: مشخصات پایه
        </h2>
        <p class="text-muted text-center">
            در این مرحله، اطلاعات هویتی شما مطابق کارت ملی و شناسنامه پرسیده می‌شود.
            لطفاً دقت کنید که تمام اطلاعات دقیق و صحیح وارد شوند.
        </p>
    </div>

    <!-- Wizard Progress Bar -->
    <div class="mb-5">
        <div class="progress" style="height: 25px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                 role="progressbar" style="width: 33%;">
                مرحله 1 از 3
            </div>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('auth.register.storeStep1') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">

            <!-- نام -->
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-person"></i> نام</label>
                <input type="text" class="form-control" name="first_name" required>
                <div class="form-text">نام مطابق شناسنامه وارد شود.</div>
            </div>

            <!-- نام خانوادگی -->
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-people"></i> نام خانوادگی</label>
                <input type="text" class="form-control" name="last_name" required>
                <div class="form-text">نام خانوادگی کامل مطابق کارت ملی وارد شود.</div>
            </div>

            <!-- نام پدر -->
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-person-lines-fill"></i> نام پدر</label>
                <input type="text" class="form-control" name="father_name">
                <div class="form-text">نام پدر مطابق شناسنامه وارد شود.</div>
            </div>

            <!-- شماره شناسنامه -->
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-card-heading"></i> شماره شناسنامه</label>
                <input type="text" class="form-control" name="id_number" data-normalize="digits" inputmode="numeric" pattern="[0-9]*">
            </div>

            <!-- محل صدور -->
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-geo-alt"></i> محل صدور</label>
                <input type="text" class="form-control" name="id_place">
            </div>

            <!-- تاریخ تولد -->
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-calendar-event"></i> تاریخ تولد</label>
                <input type="text" id="birth_date" name="birth_date" class="form-control" required>
            </div>

            <!-- کدملی -->
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-person-vcard"></i> کد ملی</label>
                <input type="text" class="form-control" name="national_id" data-normalize="digits" inputmode="numeric" pattern="[0-9]*" required>
                <div class="form-text">کد ملی ۱۰ رقمی وارد شود.</div>
            </div>

            <!-- فایل عکس -->
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-image"></i> عکس پرسنلی</label>
                <input type="file" class="form-control" name="photo" accept="image/*" required>
            </div>

            <!-- فایل کارت ملی -->
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-credit-card"></i> اسکن کارت ملی</label>
                <input type="file" class="form-control" name="national_card" accept="image/*,application/pdf" required>
            </div>

            <!-- وضعیت تاهل -->
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-heart"></i> وضعیت تأهل</label>
                <select class="form-select" name="marital_status">
                    <option>مجرد</option>
                    <option>متاهل</option>
                </select>
            </div>

            <!-- تلفن ضروری -->
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-telephone"></i> تلفن تماس ضروری</label>
                <input type="text" class="form-control" name="emergency_phone" data-normalize="digits" inputmode="numeric" pattern="[0-9]*">
            </div>

            <!-- معرف -->
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-person-badge"></i> نام معرف</label>
                <input type="text" class="form-control" name="referrer">
            </div>

            <!-- تحصیلات -->
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-book"></i> میزان تحصیلات</label>
                <input type="text" class="form-control" name="education">
            </div>

            <!-- شغل -->
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-briefcase"></i> شغل</label>
                <input type="text" class="form-control" name="job">
            </div>

            <!-- آدرس منزل -->
            <div class="col-12">
                <label class="form-label"><i class="bi bi-house"></i> نشانی محل سکونت</label>
                <textarea class="form-control" name="home_address" rows="2"></textarea>
                <div class="form-text">شامل استان، شهر، آدرس خطی، تلفن ثابت و کدپستی.</div>
            </div>

            <!-- آدرس محل کار -->
            <div class="col-12">
                <label class="form-label"><i class="bi bi-building"></i> نشانی محل کار</label>
                <textarea class="form-control" name="work_address" rows="2"></textarea>
            </div>
        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary">
                ادامه به مرحله بعد <i class="bi bi-arrow-left-circle"></i>
            </button>
        </div>
    </form>
</div>

{{-- Persian Datepicker --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-date@1.0.6/dist/persian-date.min.js"></script>

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
    // تاریخ‌ها
    $("#birth_date, #membership_expiry, #leave_date").each(function(){
        $(this).persianDatepicker({
            format: 'YYYY/MM/DD',
            initialValueType: 'persian',
            autoClose: true,
            observer: true,
            calendar: { persian: { locale: 'fa' } }
        });
    });

    // اعداد فارسی → انگلیسی هنگام ارسال
    $('form').on('submit', function() {
        $('#birth_date, #membership_expiry, #leave_date').each(function(){
            $(this).val(fixPersianNumbers($(this).val()));
        });
    });
});
</script>

<script>
function normalizeDigits(str){
  const map = {'۰':'0','۱':'1','۲':'2','۳':'3','۴':'4','۵':'5','۶':'6','۷':'7','۸':'8','۹':'9',
               '٠':'0','١':'1','٢':'2','٣':'3','٤':'4','٥':'5','٦':'6','٧':'7','٨':'8','٩':'9'};
  return String(str).replace(/[۰-۹٠-٩]/g, d => map[d] || d);
}

document.addEventListener('DOMContentLoaded', () => {
  const fields = document.querySelectorAll('[data-normalize="digits"]');
  fields.forEach(el => {
    el.addEventListener('input', () => { el.value = normalizeDigits(el.value).replace(/[^0-9]/g,''); });
  });
  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', () => {
      fields.forEach(el => el.value = normalizeDigits(el.value).replace(/[^0-9]/g,''));
    });
  }
});
</script>

@endsection
