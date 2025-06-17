@extends('layouts.admin')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">نظرسنجی‌ها</a></li>
            <li class="breadcrumb-item active" aria-current="page">نمایش دوره نظرخواهی‌شده</li>
        </ol>
    </nav>
@endsection

@section('title', 'جزئیات نظرسنجی دوره')

@section('content')
<div class="container py-4">
<h4 class="mb-4">جزئیات نظرسنجی دوره: {{ $survey->course->title ?? '—' }}</h4>

<div class="card mb-4">
<div class="card-body">
<p><strong>کاربر:</strong> {{ $survey->user->full_name ?? '—' }}</p>
<p><strong>تاریخ ارسال:</strong> {{ jdate($survey->created_at)->format('Y/m/d') }}</p>
<p><strong>نمایش هویت:</strong> {{ $survey->is_anonymous ? 'خیر' : 'بله' }}</p>
<hr>
<p><strong>رضایت کلی:</strong> {{ $survey->overall_satisfaction ?? '—' }}</p>
<p><strong>کیفیت محتوا:</strong> {{ $survey->content_quality ?? '—' }}</p>
<p><strong>عملکرد مدرس:</strong> {{ $survey->instructor_performance ?? '—' }}</p>
<p><strong>مدیریت و نظم:</strong> {{ $survey->organization ?? '—' }}</p>
<p><strong>کاربردی بودن مطالب:</strong> {{ $survey->practical_usefulness ?? '—' }}</p>
<p><strong>نظرات و پیشنهادات:</strong></p>
<div class="border rounded p-3" style="white-space: pre-line;">{{ $survey->suggestions ?? '—' }}</div>
</div>
</div>

<a href="{{ route('admin.surveys.courses.index') }}" class="btn btn-secondary">بازگشت</a>
</div>
@endsection
