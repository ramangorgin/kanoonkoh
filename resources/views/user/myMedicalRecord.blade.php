@extends('user.layout')

@section('title', 'ویرایش پرونده پزشکی')

@section('breadcrumb')
    <a href="{{ route('dashboard.index') }}">داشبورد</a> / <span>پرونده پزشکی</span>
@endsection

@section('content')

@php
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;

$user = $user ?? auth()->user();
$medical = $medical ?? ($user ? $user->medicalRecord : (object)[]);

if (! function_exists('toPersianDate')) {
    function toPersianDate($date)
    {
        if (!$date) return '';
        try {
            return Jalalian::fromCarbon(Carbon::parse($date))->format('Y/m/d');
        } catch (\Throwable $e) {
            return '';
        }
    }
}
@endphp

<div class="container py-4">
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <strong>مرحله ۲ از ۳</strong>
                <div class="text-muted small">پرونده پزشکی — لطفاً اطلاعات سلامت و بیمه ورزشی را وارد کنید.</div>
            </div>
            <div style="min-width:220px;">
                <div class="progress" style="height:10px; border-radius:8px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width:66%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm" style="background: rgba(255,255,255,0.92); border-radius:12px;">
        <div class="card-body">
            <h4 class="mb-3">ویرایش پرونده پزشکی</h4>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('dashboard.medicalRecord.update') }}" enctype="multipart/form-data" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-calendar-event"></i> تاریخ صدور بیمه ورزشی</label>
                    <input type="text" id="insurance_issue_date" name="insurance_issue_date"
                        class="form-control"
                        value="{{ old('insurance_issue_date', toPersianDate($medical->insurance_issue_date ?? null)) }}">
                    <small class="form-text text-muted">تاریخ شروع پوشش بیمه (شمسی)</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-calendar-check"></i> تاریخ اعتبار بیمه ورزشی</label>
                    <input type="text" id="insurance_expiry_date" name="insurance_expiry_date"
                        class="form-control"
                        value="{{ old('insurance_expiry_date', toPersianDate($medical->insurance_expiry_date ?? null)) }}">
                    <small class="form-text text-muted">تا چه تاریخی بیمه معتبر است</small>
                </div>

                <div class="col-md-12">
                    <label class="form-label"><i class="bi bi-file-earmark-medical"></i> فایل بیمه ورزشی</label>
                    <input type="file" class="form-control" name="insurance_file" accept="image/*,application/pdf">
                    <small class="form-text text-muted">اسکن یا عکس بیمه ورزشی (JPEG/PNG/PDF)</small>
                    @if(!empty($medical->insurance_file))
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
                            <option value="{{ $type }}" {{ (old('blood_type', $medical->blood_type ?? '') == $type) ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">گروه خونی خود را انتخاب کنید یا خالی بگذارید</small>
                </div>

                <div class="col-md-3">
                    <label class="form-label"><i class="bi bi-rulers"></i> قد (سانتی‌متر)</label>
                    <input type="number" class="form-control" name="height" value="{{ old('height', $medical->height ?? '') }}">
                    <small class="form-text text-muted">مثلاً: 175</small>
                </div>

                <div class="col-md-3">
                    <label class="form-label"><i class="bi bi-person-lines-fill"></i> وزن (کیلوگرم)</label>
                    <input type="number" class="form-control" name="weight" value="{{ old('weight', $medical->weight ?? '') }}">
                    <small class="form-text text-muted">مثلاً: 70</small>
                </div>

                <div class="col-12 mt-3">
                    <h6 class="mb-2">سؤالات پزشکی</h6>
                    <div class="text-muted small mb-3">در صورت پاسخ «بله»، توضیحات را در کادر مربوطه وارد کنید.</div>
                </div>

                @php
                    $questions = [
                        'head_injury' => 'آیا سابقه ضربه مغزی یا آسیب به سر دارید؟',
                        'eye_ear_problems' => 'مشکلات چشمی یا گوشی (بیماری یا جراحی)',
                        'seizures' => 'حملات گیج‌کننده، غش یا تشنج',
                        'respiratory' => 'بیماری‌های تنفسی (آسم، برونشیت)',
                        'heart' => 'مشکلات قلبی یا تب روماتیسمی'
                    ];
                @endphp

                @foreach($questions as $name => $label)
                <div class="col-md-6">
                    <label class="form-label">{{ $label }}</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input toggle-explain" type="radio"
                                   name="{{ $name }}" value="1" id="{{ $name }}_yes"
                                   {{ old($name, $medical->{$name} ?? null) === 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="{{ $name }}_yes">بله</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input toggle-explain" type="radio"
                                   name="{{ $name }}" value="0" id="{{ $name }}_no"
                                   {{ old($name, $medical->{$name} ?? null) === 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="{{ $name }}_no">خیر</label>
                        </div>
                    </div>
                    <textarea class="form-control mt-2 explain-field"
                              name="{{ $name }}_details"
                              placeholder="توضیحات..."
                              style="{{ (old($name, $medical->{$name} ?? null) === 1) ? '' : 'display:none;' }}">{{ old($name.'_details', $medical->{$name.'_details'} ?? '') }}</textarea>
                    <small class="form-text text-muted">در صورت «بله»، موارد و تاریخچه را وارد کنید (مثلاً تاریخ جراحی)</small>
                </div>
                @endforeach

                <div class="col-12 mt-3">
                    <label class="form-label"><i class="bi bi-journal-text"></i> بیماری‌های دیگر یا توضیحات تکمیلی</label>
                    <textarea class="form-control" name="other_conditions" rows="3">{{ old('other_conditions', $medical->other_conditions ?? '') }}</textarea>
                    <small class="form-text text-muted">در صورت وجود موارد دیگر، وارد کنید</small>
                </div>

                <div class="col-12 mt-2">
                    <div class="form-check">
                        <input type="hidden" name="commitment_signed" value="0">
                        <input type="checkbox" name="commitment_signed" value="1"
                               {{ old('commitment_signed', $medical->commitment_signed ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label">
                            ضمن تأیید مطالب فوق، مسئولیت ناشی از تمامی پیش‌آمدهای ممکن برای خود در برنامه را می‌پذیرم.
                        </label>
                        <small class="form-text text-muted">برای ادامه لازم است این تائید را بزنید</small>
                    </div>
                </div>

                <div class="col-12 mt-3 text-end">
                    <button type="submit" class="btn btn-success px-4 py-2">
                        ذخیره و رفتن به مرحله بعد
                    </button>
                </div>
            </form>

            <div class="mt-3 text-muted small">
                توضیح سریع: اطلاعات پزشکی به‌صورت محرمانه نگهداری می‌شوند. اگر شک دارید، فیلدها را پر نکنید و بعداً پزشک مراجعه کنید.
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-date@1.0.6/dist/persian-date.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>

<script>
$(document).ready(function() {
    $("#insurance_issue_date, #insurance_expiry_date").each(function(){
        $(this).persianDatepicker({
            format: 'YYYY/MM/DD',
            initialValueType: 'persian',
            autoClose: true,
            calendar: { persian: { locale: 'fa' } }
        });
    });

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
