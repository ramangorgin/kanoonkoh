@extends('layouts.dashboard')

@section('content')
<h2 class="mb-4">نظرسنجی درباره برنامه</h2>

<form action="{{ route('surveys.program.submit', $program->id) }}" method="POST">
@csrf

<div class="form-group">
<label>آیا مایلید هویت شما فاش شود؟</label><br>
<input type="checkbox" name="is_anonymous" value="1"> نه، نظرم ناشناس باشد
</div>

<div class="form-group">
<label>کیفیت برنامه‌ریزی:</label>
<select name="planning_quality" class="form-control" required>
@for ($i = 5; $i >= 1; $i--)
<option value="{{ $i }}">{{ $i }} ستاره</option>
@endfor
</select>
</div>

<div class="form-group">
<label>اجرای برنامه:</label>
<select name="execution_quality" class="form-control" required>
@for ($i = 5; $i >= 1; $i--)
<option value="{{ $i }}">{{ $i }} ستاره</option>
@endfor
</select>
</div>

<div class="form-group">
<label>مدیریت سرپرست:</label>
<select name="leadership_quality" class="form-control" required>
@for ($i = 5; $i >= 1; $i--)
<option value="{{ $i }}">{{ $i }} ستاره</option>
@endfor
</select>
</div>

<div class="form-group">
<label>نظرات و پیشنهادات:</label>
<textarea name="comments" class="form-control" rows="4" placeholder="اگر نظری دارید وارد کنید..."></textarea>
</div>

<button type="submit" class="btn btn-primary">ارسال نظرسنجی</button>
</form>
@endsection
