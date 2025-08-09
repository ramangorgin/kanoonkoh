@extends('user.layout')

@section('title', 'بیمه ورزشی')

@section('breadcrumb')
    <a href="{{ route('dashboard.index') }}">داشبورد</a> / <span>بیمه ورزشی</span>
@endsection

@section('content')
<div class="container my-5">
    <h5 class="mb-3">اطلاعات بیمه ورزشی</h5>

    @php $insurance = auth()->user()->insurance; @endphp

    @if($insurance && $insurance->expires_at && $insurance->expires_at < now())
        <div class="alert alert-danger">
            بیمه شما منقضی شده است. لطفاً اطلاعات جدید را وارد نمایید.
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('dashboard.insurance.store') }}" enctype="multipart/form-data">
    @csrf

        <div class="row">
            <div class="mb-3 col-md-6">
                <label class="form-label">تاریخ صدور بیمه</label>
                <input type="text" name="issued_at" class="form-control datepicker" id="issued_at"
                 value="{{ old('issued_at', isset($insurance->issued_at) ? jdate($insurance->issued_at)->format('Y-m-d') : '') }}" required>

            </div>

            <div class="mb-3 col-md-6">
                <label class="form-label">تاریخ انقضا بیمه</label>
                <input type="text" name="expires_at" class="form-control datepicker" id="expires_at"
                value="{{ old('expires_at', isset($insurance->expires_at) ? jdate($insurance->expires_at)->format('Y-m-d') : '') }}" required>            </div>

            @if($insurance && $insurance->file_path)
                <div class="mb-3 col-12">
                    <label class="form-label">فایل بیمه فعلی</label><br>
                    <a href="{{ asset('storage/' . $insurance->file_path) }}" target="_blank" class="btn btn-sm btn-outline-info">مشاهده فایل فعلی</a>
                </div>
            @endif

            <div class="mb-3 col-12">
                <label class="form-label">بارگذاری فایل جدید</label>
                <input type="file" name="file_path" class="form-control" accept=".pdf,image/*">
            </div>
        </div>

        <button type="submit" style="width: 100%;" class="btn btn-primary">ذخیره اطلاعات بیمه</button>
    </form>
</div>


@push('scripts')
<script>
function fixPersianNumbers(str) {
    const persian = [/۰/g, /۱/g, /۲/g, /۳/g, /۴/g, /۵/g, /۶/g, /۷/g, /۸/g, /۹/g];
    const english = ['0','1','2','3','4','5','6','7','8','9'];
    for (let i = 0; i < 10; i++) {
        str = str.replace(persian[i], english[i]);
    }
    return str;
}

$(document).ready(function() {
    // مقدار اولیه رو به انگلیسی اصلاح کن
    $('#issued_at').val(fixPersianNumbers($('#issued_at').val()));
    $('#expires_at').val(fixPersianNumbers($('#expires_at').val()));

    // تقویم شمسی برای issued_at
    $('#issued_at').persianDatepicker({
        format: 'YYYY/MM/DD',
        initialValue: false,
        initialValueType: 'persian',
        autoClose: true,
        observer: true,
        calendar: {
            persian: { locale: 'fa' }
        }
    });

    // تقویم شمسی برای expires_at
    $('#expires_at').persianDatepicker({
        format: 'YYYY/MM/DD',
        initialValue: false, 
        initialValueType: 'persian',
        autoClose: true,
        observer: true,
        calendar: {
            persian: { locale: 'fa' }
        }
    });

    // هنگام ارسال فرم، تبدیل اعداد فارسی
    $('form').on('submit', function() {
        $('#issued_at').val(fixPersianNumbers($('#issued_at').val()));
        $('#expires_at').val(fixPersianNumbers($('#expires_at').val()));
    });
});
</script>
@endpush


@endsection
