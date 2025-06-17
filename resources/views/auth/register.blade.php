@extends('layouts.app')

@section('title', 'ثبت‌نام')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-sm p-4" style="width: 100%; max-width: 500px;">
        <h4 class="mb-4 text-center">ایجاد حساب کاربری</h4>

        <form method="POST" action="{{ route('register') }}">
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

            {{-- تکرار رمز عبور --}}
            <div class="mb-4">
                <label for="password_confirmation" class="form-label">تکرار رمز عبور</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">ثبت‌نام</button>

            <div class="text-center mt-3">
                <small>حساب دارید؟ <a href="{{ route('login') }}">ورود</a></small>
            </div>
        </form>
    </div>
</div>
@endsection
