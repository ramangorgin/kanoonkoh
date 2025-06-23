@extends('user.layout')

@section('title', 'پرداخت‌ها')

@section('breadcrumb')
    <a href="{{ route('dashboard.index') }}">داشبورد</a> / <span>پرداخت‌ها</span>
@endsection

@section('content')

@php
    // سال جاری شمسی
    $currentYear = jdate()->getYear();

    // تابع تبدیل اعداد انگلیسی به فارسی
    function toPersianNumber($number) {
        $farsiDigits = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        return str_replace(range(0, 9), $farsiDigits, $number);
    }
@endphp

<div class="container my-5">
    <h5 class="mb-4">ثبت پرداخت جدید</h5>

    {{-- نمایش پیام موفقیت یا خطا --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('dashboard.payment.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            {{-- موضوع پرداخت --}}
            <div class="col-md-4">
                <label class="form-label">موضوع پرداخت</label>
                <select name="type" id="payment_type" class="form-select" required>
                    <option value="">انتخاب کنید...</option>
                    <option value="membership" {{ old('type')=='membership'?'selected':'' }}>حق عضویت</option>
                    <option value="program"    {{ old('type')=='program'   ?'selected':'' }}>هزینه برنامه</option>
                    <option value="course"     {{ old('type')=='course'    ?'selected':'' }}>هزینه دوره</option>
                </select>
            </div>

            {{-- سال عضویت --}}
            <div class="col-md-4 d-none" id="membership_year_wrapper">
                <label class="form-label">سال عضویت</label>
                <select name="year" id="membership_year" class="form-select">
                    <option value="">انتخاب سال</option>
                    @for($y = $currentYear - 5; $y <= $currentYear + 5; $y++)
                        <option value="{{ $y }}" {{ old('year')==$y?'selected':'' }}>
                            {{ toPersianNumber($y) }}
                        </option>
                    @endfor
                </select>
            </div>

            {{-- انتخاب برنامه یا دوره --}}
            <div class="col-md-4 d-none" id="related_item_wrapper">
                <label id="related_item_label" class="form-label">انتخاب آیتم</label>
                <select name="related_id" id="related_item" class="form-select">
                    <option value="">انتخاب کنید...</option>
                </select>
            </div>

            <div class="row mt-4">
                {{-- مبلغ پرداخت (ریال) --}}
                <div class="col-md-6">
                    <label class="form-label">مبلغ (ریال)</label>
                    <input type="number" name="amount" class="form-control"
                        value="{{ old('amount') }}"
                        placeholder="مبلغ را وارد کنید" required>
                </div>

                {{-- تاریخ تراکنش  --}}
                <div class="col-md-6">
                    <label class="form-label">تاریخ تراکنش</label>
                    <input type="text" id="payment_date" class="form-control" name="payment_date" value="{{ old('payment_date') }}" />
                </div>

            </div>

            <div class="row mt-3">
                {{-- کد پیگیری تراکنش --}}
                <div class="col-md-6">
                    <label class="form-label">کد پیگیری تراکنش</label>
                    <input type="text" name="transaction_code" id="transaction_code" class="form-control"
                        value="{{ old('transaction_code') }}"
                        placeholder="مثلاً ۱۲۳۴۵" required>
                </div>

                {{-- آپلود رسید --}}
                <div class="col-md-6">
                    <label class="form-label">رسید پرداخت (اختیاری)</label>
                    <input type="file" name="receipt_file" class="form-control">
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-4 w-100">ارسال</button>
    </form>

    {{-- سوابق پرداخت --}}
    <hr class="my-5">
    <h5>سوابق پرداخت شما</h5>
    <div class="table-responsive">
        <table class="table table-bordered mt-3">
            <thead class="table-light">
                <tr>
                    <th>مبلغ</th>
                    <th>کد پیگیری</th>
                    <th>رسید</th>
                    <th>وضعیت</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $p)
                    <tr>
                        <td>{{ number_format($p->amount) }} ریال</td>
                        <td>{{ $p->transaction_code }}</td>
                        <td>
                            @if($p->receipt_file)
                                <a href="{{ asset('storage/'.$p->receipt_file) }}" target="_blank">مشاهده</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="{{ $p->approved ? 'text-success' : 'text-danger' }}">
                            {{ $p->approved ? 'تأیید شده' : 'در انتظار بررسی' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">هیچ پرداختی ثبت نشده است.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')

<!-- لود کتابخانه‌ها اول -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>

<!-- اسکریپت اصلی -->
<script>
    // تبدیل اعداد فارسی به انگلیسی
    function fixPersianNumbers(str) {
        const persian = [/۰/g, /۱/g, /۲/g, /۳/g, /۴/g, /۵/g, /۶/g, /۷/g, /۸/g, /۹/g];
        const english = ['0','1','2','3','4','5','6','7','8','9'];
        for (let i = 0; i < 10; i++) {
            str = str.replace(persian[i], english[i]);
        }
        return str;
    }

    $(document).ready(function () {
        // فعال‌سازی تقویم شمسی
        $('#payment_date').val(fixPersianNumbers($('#payment_date').val()));
        $('#payment_date').persianDatepicker({
            format: 'YYYY/MM/DD',
            initialValue: false,
            initialValueType: 'persian',
            autoClose: true,
            observer: true,
            calendar: {
                persian: { locale: 'fa' }
            }
        });

        // ارسال فرم: تبدیل اعداد فارسی به انگلیسی
        $('form').on('submit', function () {
            let val = $('#payment_date').val();
            $('#payment_date').val(fixPersianNumbers(val));
        });

        // نمایش آیتم‌ها بر اساس نوع پرداخت
        const programs = @json($recentPrograms);
        const courses  = @json($recentCourses);

        $('#payment_type').on('change', function () {
            const t = $(this).val();
            $('#membership_year_wrapper, #related_item_wrapper').addClass('d-none');
            $('#related_item').empty();

            if (t === 'membership') {
                $('#membership_year_wrapper').removeClass('d-none');
            } else if (t === 'program') {
                $('#related_item_label').text('انتخاب برنامه');
                programs.forEach(p => {
                    $('#related_item').append(`<option value="${p.id}">${p.title}</option>`);
                });
                $('#related_item_wrapper').removeClass('d-none');
            } else if (t === 'course') {
                $('#related_item_label').text('انتخاب دوره');
                courses.forEach(c => {
                    $('#related_item').append(`<option value="${c.id}">${c.title}</option>`);
                });
                $('#related_item_wrapper').removeClass('d-none');
            }
        });
    });
</script>
@endpush
@endsection
