@extends('layouts.dashboard')


@section('title', 'داشبورد')


@section('content')

@auth
    @if(auth()->user()->role === 'admin')
        <div class="alert alert-info mt-3">
            <strong>شما ادمین هستید.</strong>
            <a href="{{ url('/admin') }}" class="btn btn-sm btn-primary ml-2">ورود به پنل مدیریت</a>
        </div>
    @endif
@endauth


<div class="container py-4">
    {{-- نمایش پیام خوش‌آمدگویی و درخواست تکمیل پروفایل --}}
    @if($needsCompletion)
        <div class="alert alert-warning">
            <h5 class="mb-2">کاربر گرامی، عضویت شما هنوز کامل نشده است</h5>
            <ul class="mb-0">
                <li>لطفاً <a href="{{ route('dashboard.profile') }}">مشخصات کاربری</a> خود را کامل کنید.</li>
                <li> <a href="{{ route('dashboard.insurance') }}">بیمه ورزشی</a> خود را بارگذاری نمایید.</li>
                <li> <a href="{{ route('dashboard.payments') }}">حق عضویت سال جاری</a> را پرداخت نمایید.</li>
            </ul>
        </div>
    @endif

    {{-- اطلاعات کلی کاربر، فقط وقتی عضویت تکمیل شده باشد --}}
    @if (!$needsCompletion)
        <div class="card mb-4">
            <div class="card-body d-flex align-items-center">
                <img src="{{ $user->profile && $user->profile->personal_photo ? asset('storage/' . $user->profile->personal_photo) : asset('images/default-avatar.png') }}"
                    class="rounded-circle me-3" width="80" height="80" alt="avatar">
                <div>
                    <h5 class="mb-0">
                        {{ $user->profile->first_name ?? '' }} {{ $user->profile->last_name ?? '' }}
                    </h5>
                    <small class="text-muted">
                        سطح عضویت: {{ $user->profile->membership_status ?? 'تعریف نشده' }}
                    </small><br>
                    <small class="text-muted">
                        تاریخ عضویت:
                        {{ $user->profile->membership_date ? jdate($user->profile->membership_date)->format('Y/m/d') : '---' }}
                    </small>
                </div>
            </div>
        </div>
    @endif



    <div class="row g-4">

        {{-- مشخصات کاربری --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">مشخصات کاربری</div>
                <div class="card-body">
                    <p><strong>نام:</strong> {{ auth()->user()->name }}</p>
                    <p><strong>ایمیل:</strong> {{ auth()->user()->email }}</p>
                    <a href="{{ route('dashboard.profile') }}" class="btn btn-sm btn-outline-primary">ویرایش مشخصات</a>
                </div>
            </div>
        </div>

        {{-- بیمه ورزشی --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">بیمه ورزشی</div>
                <div class="card-body">
                    <p>وضعیت آخرین بیمه شما:</p>
                    {{-- اطلاعات بیمه از مدل insurance بارگزاری بشه --}}
                    <a href="{{ route('dashboard.insurance') }}" class="btn btn-sm btn-outline-primary">مشاهده / ویرایش بیمه</a>
                </div>
            </div>
        </div>

        {{-- پرداخت‌ها --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">پرداخت‌ها</div>
                <div class="card-body">
                    <p>لیست تراکنش‌های اخیر شما در این بخش نمایش داده خواهد شد.</p>
                    <a href="{{ route('dashboard.payments') }}" class="btn btn-sm btn-outline-primary">پرداخت جدید</a>
                </div>
            </div>
        </div>

        {{-- برنامه‌های من --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">برنامه‌های من</div>
                <div class="card-body">
                    <a href="{{ route('dashboard.programs') }}" class="btn btn-sm btn-outline-primary">مشاهده برنامه‌ها</a>
                </div>
            </div>
        </div>

        {{-- دوره‌های من --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">دوره‌های من</div>
                <div class="card-body">
                    <a href="{{ route('dashboard.courses') }}" class="btn btn-sm btn-outline-primary">مشاهده دوره‌ها</a>
                </div>
            </div>
        </div>

        {{-- گزارش‌های من --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">گزارش‌های من</div>
                <div class="card-body">
                    <a href="{{ route('dashboard.reports.index') }}" class="btn btn-sm btn-outline-primary">مشاهده گزارش‌ها</a>
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
