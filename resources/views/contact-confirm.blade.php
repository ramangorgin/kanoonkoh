@extends('app')

@section('title', 'پیام شما ارسال شد')

@section('content')
<div class="container py-5 text-center">

    <div class="alert alert-success shadow-sm" role="alert">
        <h4 class="alert-heading">✅ پیام شما با موفقیت ارسال شد</h4>
        <p class="mt-3">
            از اینکه با ما در ارتباط هستید متشکریم. پیام شما ثبت شد و در اسرع وقت توسط مسئولین باشگاه بررسی خواهد شد.
        </p>
    </div>

    <a href="{{ route('home') }}" class="btn btn-outline-primary mt-4 px-4">
        بازگشت به صفحه اصلی
    </a>

</div>
@endsection
