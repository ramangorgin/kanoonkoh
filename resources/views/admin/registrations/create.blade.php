@extends('layout')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">

            @guest
                <div class="alert alert-warning">
                    اگر عضو باشگاه هستید ابتدا <a href="{{ route('auth.phone') }}">وارد شوید</a> و سپس فرم را تکمیل کنید.
                </div>
            @endguest

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    ثبت‌نام در {{ $type == 'program' ? 'برنامه' : 'دوره' }}
                </div>

                <div class="card-body">
                    <form action="{{ $type === 'program' ? route('registration.program.store', $program->id) : route('registration.course.store', $course->id) }}" method="POST" enctype="multipart/form-data" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- hidden fields --}}
                        <input type="hidden" name="type" value="{{ $type }}">
                        <input type="hidden" name="related_id" value="{{ $related_id }}">

                        @guest
                        <div class="mb-3">
                            <label>نام و نام خانوادگی</label>
                            <input type="text" name="guest_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>کد ملی</label>
                            <input type="text" name="guest_national_id" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>تاریخ تولد</label>
                            <input type="text" name="guest_birth_date" class="form-control persian-date" required>
                        </div>
                        <div class="mb-3">
                            <label>نام پدر</label>
                            <input type="text" name="guest_father_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>شماره تماس</label>
                            <input type="text" name="guest_phone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>شماره تماس اضطراری</label>
                            <input type="text" name="guest_emergency_phone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>بارگذاری فایل بیمه ورزشی معتبر</label>
                            <input type="file" name="guest_insurance_file" class="form-control" required>
                        </div>
                        @endguest

                        {{-- اگر رایگان نباشد بخش پرداخت نمایش داده شود --}}
                        @if (!$is_free)
                        <div class="alert alert-info d-flex justify-content-between align-items-center">
                            <span>
                                مبلغ قابل پرداخت:
                                <strong class="text-danger">
                                    @auth
                                        {{ number_format($member_cost) }} ﷼
                                    @else
                                        {{ number_format($guest_cost) }} ﷼
                                    @endauth
                                </strong>
                            </span>   
                        </div>

                            {{-- تاریخ تراکنش  --}}
                            <div class="col-md-12">
                                <label class="form-label">تاریخ تراکنش</label>
                                <input type="text" id="payment_date" class="form-control" name="payment_date" value="{{ old('payment_date') }}" />
                            </div>
                        </div>

                        <div class="row mt-3">
                            {{-- کد پیگیری تراکنش --}}
                            <div class="col-md-5 ms-3">
                                <label class="form-label">کد پیگیری تراکنش</label>
                                <input type="text" name="transaction_code" id="transaction_code" class="form-control"
                                    value="{{ old('transaction_code') }}"
                                    placeholder="مثلاً ۱۲۳۴۵" required>
                            </div>

                            {{-- آپلود رسید --}}
                            <div class="col-md-5 me-3">
                                <label class="form-label">رسید پرداخت (اختیاری)</label>
                                <input type="file" name="receipt_file" class="form-control">
                            </div>
                        </div>
                        @endif

                        {{-- سوال فقط برای برنامه‌هایی با حمل و نقل --}}
                        @if ($type == 'program' && $has_transport)
                        <div class="m-3 mt-4">
                            <label>از کجا سوار می‌شوید؟</label>
                            <select name="pickup_location" class="form-control" required>
                                <option value="">انتخاب کنید</option>
                                <option value="tehran">تهران</option>
                                <option value="karaj">کرج</option>
                            </select>
                        </div>
                        @endif

                        <button type="submit" class="btn btn-success mt-5" style="width: 100%;">ثبت‌نام</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>

<script>
    function fixPersianNumbers(str) {
        const persian = [/۰/g, /۱/g, /۲/g, /۳/g, /۴/g, /۵/g, /۶/g, /۷/g, /۸/g, /۹/g];
        const english = ['0','1','2','3','4','5','6','7','8','9'];
        for (let i = 0; i < 10; i++) {
            str = str.replace(persian[i], english[i]);
        }
        return str;
    }
    $(document).ready(function () {
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

        $('form').on('submit', function () {
            let val = $('#payment_date').val();
            $('#payment_date').val(fixPersianNumbers(val));
        });
    });
</script>
@endpush
