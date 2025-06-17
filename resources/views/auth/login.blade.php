@extends('layouts.app')

@section('title', 'ورود')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-sm p-4" style="width: 100%; max-width: 500px;">
        <h4 class="mb-4 text-center">ورود به حساب کاربری</h4>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- ایمیل --}}
            <div class="mb-3">
                <label for="email" class="form-label">ایمیل</label>
                <input type="email" id="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- رمز عبور --}}
            <div class="mb-3">
                <label for="password" class="form-label">رمز عبور</label>
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- یادآوری --}}
            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" id="remember" class="form-check-input"
                       {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">مرا به خاطر بسپار</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">ورود</button>

            <div class="text-center mt-3">
                <small>حساب ندارید؟ <a href="{{ route('register') }}">ثبت‌نام</a></small>
            </div>
        </form>
    </div>
</div>
@endsection
