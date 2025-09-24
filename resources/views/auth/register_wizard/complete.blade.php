@extends('layout')

@section('content')
<div class="container text-center">

    <div class="mt-5">
        <!-- آیکون موفقیت -->
        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>

        <h2 class="mt-4">اطلاعات شما با موفقیت ثبت شد</h2>
        <p class="text-muted mt-3">
            عضویت شما در باشگاه <strong>کانون کوه</strong> با موفقیت ثبت گردید.  
            اطلاعات شما در اسرع وقت توسط مدیران بررسی خواهد شد.  
            نتیجه تأیید یا عدم تأیید به شما اطلاع داده خواهد شد.
        </p>

        <div class="mt-4">
            <a href="{{ url('/') }}" class="btn btn-primary">
                <i class="bi bi-house-door"></i> بازگشت به صفحه اصلی
            </a>
        </div>
    </div>

</div>
@endsection
