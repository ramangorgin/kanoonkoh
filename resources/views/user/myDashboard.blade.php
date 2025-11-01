@extends('user.layout')


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

    {{-- اطلاعات کلی کاربر، فقط وقتی عضویت تکمیل شده باشد --}}

    <div class="card mb-4">
        <div class="card-body d-flex align-items-center">
            <img src="{{ $user->profile && $user->profile->photo ? asset('storage/' . $user->profile->photo) : asset('images/default-avatar.png') }}" alt="عکس کاربر" class="img-thumbnail me-3" style="max-height: 120px;">
            <div>
                <h5 style="font-family: Vazirmatn;" class="mb-3">
                    {{ $user->profile->first_name ?? '' }} {{ $user->profile->last_name ?? '' }}
                </h5>
                <small class="text-muted mb-2">
                    وضعیت عضویت: {{ $user->profile->membership_status ?? 'تعریف نشده' }}
                </small><br>
                <small class="text-muted">
                    تاریخ عضویت:
                    {{ $user->profile->membership_date ? jdate($user->profile->membership_date)->format('Y/m/d') : 'تنظیم نشده' }}
                </small>
            </div>
        </div>
    </div>



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
