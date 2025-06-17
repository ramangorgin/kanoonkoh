@extends('layouts.dashboard')

@section('content')
<h2 class="mb-4">نظرسنجی درباره دوره</h2>

<form action="{{ route('surveys.course.submit', $course->id) }}" method="POST">
@csrf

<div class="form-group">
<label>آیا مایلید هویت شما فاش شود؟</label><br>
<input type="checkbox" name="is_anonymous" value="1"> نه، نظرم ناشناس باشد
</div>

<div class="form-group">
<label>کیفیت محتوای دوره:</label>
<select name="content_quality" class="form-control" required>
@for ($i = 5; $i >= 1; $i--)
<option value="{{ $i }}">{{ $i }} ستاره</option>
@endfor
</select>
</div>

<div class="form-group">
<label>مهارت مدرس در آموزش:</label>
<select name="teaching_skill" class="form-control" required>
@for ($i = 5; $i >= 1; $i--)
<option value="{{ $i }}">{{ $i }} ستاره</option>
@endfor
</select>
</div>

<div class="form-group">
<label>کیفیت فایل‌ها و مطالب:</label>
<select name="materials_quality" class="form-control" required>
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
