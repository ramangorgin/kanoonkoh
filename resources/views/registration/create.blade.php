@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">

            @guest
                <div class="alert alert-warning">
                    اگر عضو باشگاه هستید ابتدا <a href="{{ route('login') }}">وارد شوید</a> و سپس فرم را تکمیل کنید.
                </div>
            @endguest

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    ثبت‌نام در {{ $type == 'program' ? 'برنامه' : 'دوره' }}
                </div>

                <div class="card-body">
                    <form action="{{ route('registration.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- hidden fields --}}
                        <input type="hidden" name="type" value="{{ $type }}">
                        <input type="hidden" name="related_id" value="{{ $related_id }}">
                        <input type="hidden" name="amount" value="{{ $amount }}"> {{-- برای ذخیره در جدول payments --}}

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
                            <span>مبلغ قابل پرداخت: <strong class="text-danger">{{ number_format($amount) }} ﷼</strong></span>
                        </div>

                        <div class="mb-3">
                            <label>تاریخ پرداخت</label>
                            <input type="text" name="paid_at" class="form-control persian-date" required>
                        </div>

                        <div class="mb-3">
                            <label>کد رهگیری پرداخت</label>
                            <input type="text" name="transaction_code" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>آپلود رسید پرداخت</label>
                            <input type="file" name="receipt_file" class="form-control" required>
                        </div>
                        @endif

                        {{-- سوال فقط برای برنامه‌هایی با حمل و نقل --}}
                        @if ($type == 'program' && $has_transportation)
                        <div class="mb-3">
                            <label>از کجا سوار می‌شوید؟</label>
                            <select name="pickup_location" class="form-control" required>
                                <option value="">انتخاب کنید</option>
                                <option value="تهران">تهران</option>
                                <option value="کرج">کرج</option>
                            </select>
                        </div>
                        @endif

                        <button type="submit" class="btn btn-success">ثبت‌نام</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css" />

<script>
    $(document).ready(function() {
        $('.persian-date').persianDatepicker({
            format: 'YYYY/MM/DD',
            observer: true
        });
    });
</script>
@endpush
