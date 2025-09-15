@extends('layout')

@section('title', 'ورود یا ثبت‌نام')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-sm p-4" style="width: 100%; max-width: 500px;">
        <h4 class="mb-4 text-center">ورود یا ثبت‌نام</h4>

        <form method="POST" action="{{ route('auth.requestOtp') }}">
            @csrf
            <div class="mb-3">
                <label for="phone" class="form-label">شماره تلفن همراه</label>
                <input type="string" id="phone" name="phone" placeholder="مثال: ۰۹۱۲۱۲۳۴۵۶۷"
                       class="form-control @error('phone') is-invalid @enderror"
                       value="{{ old('phone') }}" required autofocus>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <button type="submit" class="btn btn-primary w-100">ارسال کد تایید</button>

        </form>
    </div>
</div>
@endsection
