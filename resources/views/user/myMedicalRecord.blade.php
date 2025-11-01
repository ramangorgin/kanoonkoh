@extends('user.layout')

@section('title', 'ویرایش پرونده پزشکی')

@section('breadcrumb')
    <a href="{{ route('dashboard.index') }}">داشبورد</a> / <span>پرونده پزشکی</span>
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

    <h4 class="mb-4">ویرایش پرونده پزشکی</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('dashboard.medicalRecord.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-calendar-event"></i> تاریخ صدور بیمه ورزشی</label>
                <input type="text" id="insurance_issue_date" name="insurance_issue_date"
                    class="form-control"
                    value="{{ old('insurance_issue_date', toPersianDate($medical->insurance_issue_date)) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-calendar-check"></i> تاریخ اعتبار بیمه ورزشی</label>
                <input type="text" id="insurance_expiry_date" name="insurance_expiry_date"
                    class="form-control"
                    value="{{ old('insurance_expiry_date', toPersianDate($medical->insurance_expiry_date)) }}">
            </div>


            <div class="col-md-12">
                <label class="form-label"><i class="bi bi-file-earmark-medical"></i> فایل بیمه ورزشی</label>
                <input type="file" class="form-control" name="insurance_file" accept="image/*,application/pdf">
                @if($medical->insurance_file)
                    <div class="mt-2">
                        <a href="{{ asset('storage/' . $medical->insurance_file) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            مشاهده فایل فعلی
                        </a>
                    </div>
                @endif
            </div>

            <div class="col-md-3">
                <label class="form-label"><i class="bi bi-droplet-half"></i> گروه خونی</label>
                <select class="form-select" name="blood_type">
                    <option value="">انتخاب کنید...</option>
                    @foreach(['O+','O-','A+','A-','B+','B-','AB+','AB-'] as $type)
                        <option value="{{ $type }}" {{ $medical->blood_type == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label"><i class="bi bi-rulers"></i> قد (سانتی‌متر)</label>
                <input type="number" class="form-control" name="height" value="{{ old('height', $medical->height) }}">
            </div>

            <div class="col-md-3">
                <label class="form-label"><i class="bi bi-person-lines-fill"></i> وزن (کیلوگرم)</label>
                <input type="number" class="form-control" name="weight" value="{{ old('weight', $medical->weight) }}">
            </div>

            <div class="col-12 mt-4">
                <h5><i class="bi bi-clipboard-pulse"></i> سؤالات پزشکی</h5>
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
                        <input class="form-check-input toggle-explain" type="radio"
                               name="{{ $name }}" value="1" id="{{ $name }}_yes"
                               {{ $medical->$name === 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="{{ $name }}_yes">بله</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input toggle-explain" type="radio"
                               name="{{ $name }}" value="0" id="{{ $name }}_no"
                               {{ $medical->$name === 0 ? 'checked' : '' }}>
                        <label class="form-check-label" for="{{ $name }}_no">خیر</label>
                    </div>
                </div>
                <textarea class="form-control mt-2 explain-field"
                          name="{{ $name }}_details"
                          placeholder="توضیحات..."
                          style="{{ $medical->$name === 1 ? '' : 'display:none;' }}">{{ old($name.'_details', $medical->{$name.'_details'}) }}</textarea>
            </div>
            @endforeach

            <div class="col-12 mt-3">
                <label class="form-label"><i class="bi bi-journal-text"></i> بیماری‌های دیگر یا توضیحات تکمیلی</label>
                <textarea class="form-control" name="other_conditions" rows="3">{{ old('other_conditions', $medical->other_conditions) }}</textarea>
            </div>

            <div class="col-12 mt-4">
                <div class="form-check">
                    <input type="hidden" name="commitment_signed" value="0">
                    <input type="checkbox" name="commitment_signed" value="1"
                           {{ $medical->commitment_signed ? 'checked' : '' }}>
                    <label class="form-check-label">
                        ضمن تأیید مطالب فوق، مسئولیت ناشی از تمامی پیش‌آمدهای ممکن برای خود در برنامه را می‌پذیرم.
                    </label>
                </div>
            </div>
        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-success w-100 py-2">
                به‌روزرسانی پرونده پزشکی <i class="bi bi-check2-circle"></i>
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-date@1.0.6/dist/persian-date.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>

<script>
$(document).ready(function() {
    // Date pickers
    $("#insurance_issue_date, #insurance_expiry_date").each(function(){
        $(this).persianDatepicker({
            format: 'YYYY/MM/DD',
            initialValue: false,
            autoClose: true,
            calendar: { persian: { locale: 'fa' } }
        });
    });

    // Toggle explanation fields
    $('.toggle-explain').on('change', function() {
        const parent = $(this).closest('.col-md-6');
        if ($(this).val() === "1") {
            parent.find('.explain-field').show();
        } else {
            parent.find('.explain-field').hide().val('');
        }
    });
});
</script>
@endpush
