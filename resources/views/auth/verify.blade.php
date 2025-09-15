@extends('layout')

@section('title', 'ورود یا ثبت‌نام')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-sm p-4" style="width: 100%; max-width: 500px;">
        <h4 class="mb-4 text-center">تایید شماره تلفن</h4>

        {{-- نمایش خطاها --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{-- پیام وضعیت --}}
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('auth.verifyOtp') }}" id="otpForm">
            @csrf
            <div class="mb-3">
                <label class="form-label">کد تایید ۴ رقمی را وارد کنید:</label>
                <div class="d-flex gap-2 justify-content-center">
                    <input type="text" maxlength="1" class="otp-input form-control text-center"
                        style="width: 60px; font-size: 24px;" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" maxlength="1" class="otp-input form-control text-center"
                        style="width: 60px; font-size: 24px;" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" maxlength="1" class="otp-input form-control text-center"
                        style="width: 60px; font-size: 24px;" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" maxlength="1" class="otp-input form-control text-center"
                        style="width: 60px; font-size: 24px;" inputmode="numeric" pattern="[0-9]*">
                </div>
            </div>

            <input type="hidden" name="otp" id="otpHidden">


            <div class="mt-3 text-center">
                <button type="submit" class="btn btn-success" style="width: 100%;">ارسال</button>
            </div>
        </form>
    </div>
</div>

<script>
// تبدیل اعداد فارسی و عربی به انگلیسی
function normalizeDigits(str) {
    const persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
    const arabic  = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
    const english = ['0','1','2','3','4','5','6','7','8','9'];

    return str.replace(/[۰-۹٠-٩]/g, d => {
        if (persian.includes(d)) return english[persian.indexOf(d)];
        if (arabic.includes(d))  return english[arabic.indexOf(d)];
        return d;
    });
}

const inputs = document.querySelectorAll('.otp-input');
const otpHidden = document.getElementById('otpHidden');

// حرکت بین باکس‌ها و ذخیره کد
inputs.forEach((input, index) => {
    input.addEventListener('input', function() {
        this.value = normalizeDigits(this.value).replace(/[^0-9]/g, '').slice(0, 1);

        if (this.value && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }

        // ترکیب همه مقادیر در hidden input
        otpHidden.value = Array.from(inputs).map(i => i.value).join('');
    });

    input.addEventListener('keydown', function(e) {
        if (e.key === "Backspace" && !this.value && index > 0) {
            inputs[index - 1].focus();
        }
    });
});

// قبل از ارسال فرم، مطمئن شو hidden مقدار درست داره
document.getElementById('otpForm').addEventListener('submit', function() {
    otpHidden.value = Array.from(inputs).map(i => i.value).join('');
});
</script>
@endsection