@extends('layout')

@section('content')
<div class="container text-center">

    <h2 class="mb-4">تأیید شماره تلفن</h2>
    <p class="text-muted">کد چهاررقمی ارسال شده به شماره شما را وارد کنید</p>

    <!-- فرم وارد کردن کد -->
    <form method="POST" action="{{ route('auth.verifyOtp') }}" id="verify-form">
        @csrf
        <div class="d-flex justify-content-center gap-2 mb-3" style="direction:ltr">
            <input type="text"  maxlength="1" class="form-control text-center code-input" style="width:60px; font-size:1.5rem;" required>
            <input type="text"  maxlength="1" class="form-control text-center code-input" style="width:60px; font-size:1.5rem;" required>
            <input type="text"  maxlength="1" class="form-control text-center code-input" style="width:60px; font-size:1.5rem;" required>
            <input type="text"  maxlength="1" class="form-control text-center code-input" style="width:60px; font-size:1.5rem;" required>
        </div>
         <input type="hidden" name="otp" id="otpHidden">

        <button type="submit" class="btn btn-success w-50">تأیید</button>
    </form>

    <!-- شمارش معکوس -->
    <div class="mt-4">
        <p class="text-muted">زمان باقی‌مانده: <span id="timer">02:00</span></p>
        <form method="POST" action="{{ route('auth.requestOtp') }}">
            @csrf
            <input type="hidden" name="phone" value="{{ session('auth_phone') }}">
            <button type="submit" id="resend-btn" class="btn btn-outline-primary" disabled>ارسال مجدد</button>
        </form>
    </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // حرکت خودکار بین inputها
    $('.code-input').on('input', function() {
        if (this.value.length === this.maxLength) {
            $(this).next('.code-input').focus();
        }
    }).on('keydown', function(e) {
        if (e.key === "Backspace" && this.value === "") {
            $(this).prev('.code-input').focus();
        }
    });

    // شمارش معکوس
    let duration = 120; // ثانیه
    let timerDisplay = $('#timer');
    let resendBtn = $('#resend-btn');

    function startTimer() {
        let remaining = duration;
        let interval = setInterval(function() {
            let minutes = String(Math.floor(remaining / 60)).padStart(2, '0');
            let seconds = String(remaining % 60).padStart(2, '0');
            timerDisplay.text(`${minutes}:${seconds}`);
            if (--remaining < 0) {
                clearInterval(interval);
                resendBtn.prop('disabled', false);
            }
        }, 1000);
    }

    startTimer();
});
</script>

<script>
function normalizeDigits(str){
  const p = '۰۱۲۳۴۵۶۷۸۹', a = '٠١٢٣٤٥٦٧٨٩';
  return str.replace(/[۰-۹٠-٩]/g, d => {
    const pi = p.indexOf(d); if (pi > -1) return String(pi);
    const ai = a.indexOf(d); if (ai > -1) return String(ai);
    return d;
  });
}

const inputs = document.querySelectorAll('.code-input');
const hidden = document.getElementById('otpHidden');

function fillHidden(){
  hidden.value = Array.from(inputs).map(i => i.value).join('');
}

inputs.forEach((el, idx) => {
  el.addEventListener('input', e => {
    let v = normalizeDigits(el.value).replace(/[^0-9]/g,'').slice(0,1);
    el.value = v;
    if (v && idx < inputs.length - 1) inputs[idx+1].focus();
    fillHidden();
  });
  el.addEventListener('keydown', e => {
    if (e.key === 'Backspace' && !el.value && idx > 0) inputs[idx-1].focus();
  });
});

// قبل از ارسال، مطمئن شو ۴ رقم پر شده
document.getElementById('verify-form').addEventListener('submit', e => {
  fillHidden();
  if (hidden.value.length !== 4) {
    e.preventDefault();
    alert('کد ۴ رقمی را کامل وارد کنید');
  }
});
</script>

@endsection
