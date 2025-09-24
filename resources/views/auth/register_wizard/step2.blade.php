@extends('layout')

@section('content')
<div class="container">

    <div class="mb-4">
        <h2 class="text-center mb-3">
            <i class="bi bi-heart-pulse"></i> مرحله دوم: پرونده پزشکی
        </h2>
        <p class="text-muted text-center">
            لطفاً اطلاعات پزشکی خود را دقیق و کامل وارد کنید. 
            این اطلاعات به صورت محرمانه ذخیره می‌شوند و صرفاً جهت اطمینان از سلامت شما در فعالیت‌های ورزشی مورد استفاده قرار می‌گیرند.
        </p>
    </div>

    <!-- Wizard Progress Bar -->
    <div class="mb-5">
        <div class="progress" style="height: 25px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                 role="progressbar" style="width: 66%;">
                مرحله 2 از 3
            </div>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('auth.register.storeStep2') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">

            <!-- بیمه ورزشی -->
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-calendar-event"></i> تاریخ صدور بیمه ورزشی</label>
                <input type="text" id="insurance_issue_date" name="insurance_issue_date" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-calendar-check"></i> تاریخ اعتبار بیمه ورزشی</label>
                <input type="text" id="insurance_expiry_date" name="insurance_expiry_date" class="form-control">
            </div>
            <div class="col-md-12">
                <label class="form-label"><i class="bi bi-file-earmark-medical"></i> فایل بیمه ورزشی</label>
                <input type="file" class="form-control" name="insurance_file" accept="image/*,application/pdf">
            </div>

            <!-- مشخصات فیزیکی -->
            <div class="col-md-3">
                <label class="form-label"><i class="bi bi-droplet-half"></i> گروه خونی</label>
                <select class="form-select" name="blood_type">
                    <option value="">انتخاب کنید...</option>
                    <option>O+</option><option>O-</option>
                    <option>A+</option><option>A-</option>
                    <option>B+</option><option>B-</option>
                    <option>AB+</option><option>AB-</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label"><i class="bi bi-rulers"></i> قد (سانتی‌متر)</label>
                <input type="number" class="form-control" name="height">
            </div>
            <div class="col-md-3">
                <label class="form-label"><i class="bi bi-person-lines-fill"></i> وزن (کیلوگرم)</label>
                <input type="number" class="form-control" name="weight">
            </div>
            <div class="col-md-3">
                <label class="form-label"><i class="bi bi-calendar2-date"></i> سن</label>
                <input type="text" id="age_display" class="form-control" value="{{ $age ?? '' }}" readonly>
                {{-- اگر می‌خواهی سن همراه فرم ارسال شود (اختیاری) --}}
                {{-- <input type="hidden" name="age" id="age_hidden" value="{{ $age ?? '' }}"> --}}
                <div class="form-text">این مقدار به صورت خودکار از تاریخ تولد محاسبه می‌شود.</div>
            </div>


            <!-- سؤالات پزشکی -->
            <div class="col-12">
                <h5 class="mt-4"><i class="bi bi-clipboard-pulse"></i> سؤالات پزشکی</h5>
            </div>

            @php
                $questions = [
                    'head_injury' => 'آیا سابقه ضربه مغزی یا آسیب به سر دارید؟',
                    'eye_ear_problems' => 'مشکلات چشمی یا گوشی (بیماری یا جراحی)',
                    'seizures' => 'حملات گیج‌کننده، غش یا تشنج',
                    'respiratory' => 'بیماری‌های عفونی، گوارشی، آسم یا برونشیت',
                    'heart' => 'مشکلات قلبی یا تب روماتیسمی',
                    'blood_pressure' => 'فشار خون بالا یا پایین',
                    'blood_disorders' => 'آنمی، لوسمی یا اختلالات خونی',
                    'diabetes_hepatitis' => 'دیابت، هپاتیت یا زردی',
                    'stomach' => 'زخم معده یا مشکلات گوارشی',
                    'kidney' => 'مشکلات کلیه یا مثانه، فتق یا پارگی',
                    'mental' => 'بیماری‌های ذهنی یا ضعف عصبی',
                    'addiction' => 'اعتیاد به دارو یا الکل',
                    'surgery' => 'آیا جراحی داشته‌اید یا توصیه به جراحی شده‌اید؟',
                    'skin_allergy' => 'مشکلات پوستی یا آلرژی',
                    'drug_allergy' => 'حساسیت به دارو',
                    'insect_allergy' => 'حساسیت به گزیدگی حشرات',
                    'dust_allergy' => 'حساسیت به گرد و غبار',
                    'medications' => 'آیا به طور منظم دارو مصرف می‌کنید؟',
                    'bone_joint' => 'بیماری‌های استخوانی یا مفصلی (شکستگی، دررفتگی، آرتریت)',
                    'hiv' => 'HIV یا بیماری‌های مشابه',
                    'treatment' => 'آیا تحت درمان هستید؟'
                ];
            @endphp

            @foreach($questions as $name => $label)
            <div class="col-md-6">
                <label class="form-label">{{ $label }}</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input toggle-explain" type="radio" name="{{ $name }}" value="1" id="{{ $name }}_yes">
                        <label class="form-check-label" for="{{ $name }}_yes">بله</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input toggle-explain" type="radio" name="{{ $name }}" value="0" id="{{ $name }}_no">
                        <label class="form-check-label" for="{{ $name }}_no">خیر</label>
                    </div>
                </div>
                <!-- توضیح شرطی -->
                <textarea class="form-control mt-2 explain-field" name="{{ $name }}_details" placeholder="توضیحات..." style="display:none;"></textarea>
            </div>
            @endforeach

            <!-- سایر توضیحات -->
            <div class="col-12 mt-3">
                <label class="form-label"><i class="bi bi-journal-text"></i> بیماری‌های دیگر یا توضیحات تکمیلی</label>
                <textarea class="form-control" name="other_conditions" rows="3"></textarea>
            </div>

            <!-- تعهدنامه -->
            <div class="col-12 mt-4">
                <div class="form-check">
                    <input type="hidden" name="commitment_signed" value="0">
                    <input type="checkbox" name="commitment_signed" value="1" {{ old('commitment_signed') ? 'checked' : '' }}>
                   <!-- <input class="form-check-input" type="checkbox" name="commitment_signed" id="commitment_signed" required> -->
                    <label class="form-check-label" for="commitment_signed">
                        ضمن تأیید مطالب فوق، مسئولیت ناشی از تمامی پیش‌آمدهای ممکن برای خود در برنامه را می‌پذیرم.
                    </label>
                </div>
            </div>

        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-success">
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
    // تاریخ بیمه
    $("#insurance_issue_date, #insurance_expiry_date").each(function(){
        $(this).persianDatepicker({
            format: 'YYYY/MM/DD',
            initialValueType: 'persian',
            autoClose: true,
            observer: true,
            calendar: { persian: { locale: 'fa' } }
        });
    });

    // شرطی کردن نمایش توضیحات
    $('.toggle-explain').on('change', function() {
        const parent = $(this).closest('.col-md-6');
        if ($(this).val() === "1") {
            parent.find('.explain-field').show();
        } else {
            parent.find('.explain-field').hide().val('');
        }
    });

    // تبدیل اعداد فارسی به انگلیسی قبل از ارسال
    $('form').on('submit', function() {
        $('#insurance_issue_date, #insurance_expiry_date').each(function(){
            $(this).val(fixPersianNumbers($(this).val()));
        });
    });
});
</script>
<script>
function calculateAge(birthDateStr) {
    if (!birthDateStr) return '';
    // تاریخ تولد رو به میلادی یا شمسی بگیر (بستگی به ورودی فرم داره)
    const parts = birthDateStr.split('/');
    if (parts.length < 3) return '';

    let year = parseInt(parts[0], 10);
    let month = parseInt(parts[1], 10) - 1;
    let day = parseInt(parts[2], 10);

    // اگر تاریخ شمسی باشه باید به میلادی تبدیل کنی (مثلا با morilog/jalali در سرور).
    // برای فرانت میشه فعلا همین رو ساده محاسبه کرد:
    const birthDate = new Date(year, month, day);
    const diffMs = Date.now() - birthDate.getTime();
    const ageDt = new Date(diffMs);
    return Math.abs(ageDt.getUTCFullYear() - 1970);
}

document.addEventListener("DOMContentLoaded", function() {
    const birthInput = document.querySelector('#birth_date'); // همون فیلد تاریخ تولد
    const ageDisplay = document.querySelector('#age_display');
    const ageHidden = document.querySelector('#age_hidden');

    if (birthInput) {
        birthInput.addEventListener('change', function() {
            const age = calculateAge(this.value);
            ageDisplay.value = age;
            ageHidden.value = age;
        });
    }
});
</script>

@endsection
