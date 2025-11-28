@extends('user.layout')


@section('title', 'داشبورد')


@section('content')

@php
    $user = $user ?? auth()->user();

    // safety: relationship checks (use whichever relationship names exist in your models)
    $hasProfile = (bool) ($user->profile ?? false);
    $hasMedical = (bool) ($user->medicalRecord ?? false);

    $educationCount = 0;
    try {
        $educationCount = method_exists($user, 'educationalHistories') ? $user->educationalHistories()->count() : (method_exists($user, 'educational_histories') ? $user->educational_histories()->count() : 0);
    } catch (\Throwable $e) {
        $educationCount = 0;
    }
    $hasEducation = $educationCount > 0;

    $completedSteps = ($hasProfile ? 1 : 0) + ($hasMedical ? 1 : 0) + ($hasEducation ? 1 : 0);

    $registrationStatus = $user->profile->membership_status ?? null; // expected values: null / 'pending' / 'approved' / 'rejected'
    $rejectionReason = $user->profile->rejection_reason ?? null;
@endphp

<div class="container py-4">

    {{-- Registration status / onboarding progress --}}
    <div class="mb-4">
        @if($completedSteps < 3)
            <div class="alert alert-info">
                <strong>ثبت‌نام نیمه‌تمام</strong>
                <div>شما در مرحله {{ $completedSteps }} از 3 قرار دارید. برای تکمیل ثبت‌نام مراحل زیر را انجام دهید:</div>
                <ul class="mt-2 mb-0">
                    <li>
                        مشخصات پایه:
                        @if($hasProfile) <span class="badge bg-success">تکمیل</span>
                        @else <a href="{{ route('dashboard.profile.edit') }}" class="btn btn-sm btn-outline-primary">تکمیل کنید</a> @endif
                    </li>
                    <li>
                        پرونده پزشکی:
                        @if($hasMedical) <span class="badge bg-success">تکمیل</span>
                        @else <a href="{{ route('dashboard.medicalRecord.edit') }}" class="btn btn-sm btn-outline-primary">تکمیل کنید</a> @endif
                    </li>
                    <li>
                        سوابق آموزشی:
                        @if($hasEducation) <span class="badge bg-success">تکمیل ({{ $educationCount }})</span>
                        @else <a href="{{ route('dashboard.educationalHistory.index') }}" class="btn btn-sm btn-outline-primary">افزودن سابقه</a> @endif
                    </li>
                </ul>
                <div class="mt-2 small text-muted">بعد از تکمیل همه مراحل، درخواست شما برای تایید ارسال می‌شود و نتیجه از طریق داشبورد و پیامک اطلاع داده خواهد شد.</div>
            </div>
        @else
            {{-- All steps complete: show registration approval state --}}
            @if($registrationStatus === 'approved')
                <div class="alert alert-success">
                    <strong>ثبت‌نام شما تایید شده است.</strong>
                    <div class="small">خوش آمدید! اکنون می‌توانید از تمامی امکانات حساب کاربری استفاده کنید.</div>
                </div>
            @elseif($registrationStatus === 'rejected')
                <div class="alert alert-danger">
                    <strong>ثبت‌نام شما رد شده است.</strong>
                    @if($rejectionReason)
                        <div class="mt-1">دلیل: {{ $rejectionReason }}</div>
                    @endif
                    <div class="mt-2">برای اصلاح اطلاعات، فرم‌ها را ویرایش کنید یا با پشتیبانی تماس بگیرید.</div>
                    <div class="mt-2">
                        <a href="{{ route('dashboard.profile.edit') }}" class="btn btn-sm btn-outline-primary">ویرایش مشخصات</a>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    <strong>در انتظار بررسی</strong>
                    <div class="small">ثبت‌نام شما تکمیل شده و در انتظار بررسی و تایید مدیریت است. نتیجه به زودی اعلام خواهد شد.</div>
                    <div class="mt-2 small text-muted">می‌توانید وضعیت را از همین صفحه پیگیری کنید.</div>
                </div>
            @endif
        @endif
    </div>

    {{-- existing dashboard content (profile card, payments, settings) --}}
    <div class="card mb-4">
        <div class="card-body d-flex align-items-center">
            <img src="{{ $user->profile && $user->profile->photo ? asset('storage/photos/' . $user->profile->photo) : asset('images/default-avatar.png') }}" alt="عکس کاربر" class="img-thumbnail me-3" style="max-height: 120px;">

            <div>
                <h5 style="font-family: Vazirmatn;" class="mb-3">
                    {{ $user->profile->first_name ?? '' }} {{ $user->profile->last_name ?? '' }}
                </h5>
                <small class="text-muted mb-2">
                    وضعیت عضویت:
                    {{ $user->profile->membership_status ? ($user->profile->membership_status == 'approved' ? 'تایید شده' : ($user->profile->membership_status == 'pending' ? 'در انتظار' : ($user->profile->membership_status == 'rejected' ? 'رد شده' : $user->profile->membership_status))) : ($completedSteps < 3 ? 'نیمه‌تمام' : 'در انتظار') }}
                </small><br>
                <small class="text-muted">
                    تاریخ عضویت:
                    {{ $user->profile->membership_date ? jdate($user->profile->membership_date)->format('Y/m/d') : 'تنظیم نشده' }}
                </small>
            </div>
        </div>
    </div>

    {{-- rest of existing dashboard cards and links --}}
    <div class="row g-4">

        {{-- مشخصات کاربری --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">مشخصات کاربری</div>
                <div class="card-body">
                    <p><strong>نام:</strong> {{ $user->profile->first_name ?? '' }} {{ $user->profile->last_name ?? '' }}</p>
                    <p><strong>شماره تلفن:</strong> {{ $user->phone }}</p>
                    <a href="{{ route('dashboard.profile.edit') }}" class="btn btn-sm btn-outline-primary">ویرایش مشخصات</a>
                </div>
            </div>
        </div>


        {{-- پرداخت‌ها --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">پرداخت‌ها</div>
                <div class="card-body">
                    <p>لیست تراکنش‌های اخیر شما در این بخش نمایش داده خواهد شد.</p>
                    <a href="{{ route('dashboard.payments.index') }}" class="btn btn-sm btn-outline-primary">پرداخت جدید</a>
                </div>
            </div>
        </div>




        {{-- تنظیمات --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">تنظیمات حساب</div>
                <div class="card-body">
                    <a href="{{ route('dashboard.settings') }}" class="btn btn-sm btn-outline-secondary">تغییر رمز عبور</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
