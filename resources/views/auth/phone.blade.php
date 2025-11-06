@extends('layout')

@section('title', 'ورود یا ثبت‌نام')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-sm p-4" style="width: 100%; max-width: 500px;">
        <h4 class="mb-4 text-center">ورود یا ثبت‌نام</h4>

        <form method="POST" action="{{ route('auth.requestOtp') }}">
            @csrf
                  <!--
            <div class="mb-3">
                <label for="phone" class="form-label">شماره تلفن همراه</label>
                <input type="string" id="phone" name="phone" placeholder="مثال: ۰۹۱۲۱۲۳۴۵۶۷"
                       class="form-control @error('phone') is-invalid @enderror"
                       value="{{ old('phone') }}" required autofocus>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
      
            <div class="mb-3">
                @arcaptchaWidget
            </div>
-->
            <button type="submit" class="btn btn-primary w-100">ارسال کد تایید</button>

        </form>
    </div>
</div>

@push('scripts')
<script>
// Normalizing the Number
function normalizeDigits(str) {
    const persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
    const arabic  = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
    let output = str;
    for (let i = 0; i < 10; i++) {
        output = output.replace(new RegExp(persian[i], 'g'), i)
                       .replace(new RegExp(arabic[i], 'g'), i);
    }
    return output;
}

document.addEventListener("DOMContentLoaded", function() {
    const phoneInput = document.querySelector('input[name="phone"]');
    const form = phoneInput.closest('form');

    // موقع تایپ: اعداد رو اصلاح کن
    phoneInput.addEventListener('input', function() {
        this.value = normalizeDigits(this.value);
    });

    // قبل از ارسال فرم هم یکبار اصلاح کن
    form.addEventListener('submit', function() {
        phoneInput.value = normalizeDigits(phoneInput.value);
    });
});
</script>

@endpush
@endsection
