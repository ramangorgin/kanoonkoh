@extends('layouts.admin')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">نظرسنجی‌ها</a></li>
            <li class="breadcrumb-item active" aria-current="page">نمایش برنامه نظرخواهی‌شده</li>
        </ol>
    </nav>
@endsection

@section('title', 'جزئیات نظرسنجی برنامه')

@section('content')
<div class="container py-4">
<h4 class="mb-4">جزئیات نظرسنجی برنامه: {{ $survey->program->title ?? '—' }}</h4>

<div class="card mb-4">
<div class="card-body">
<p><strong>کاربر:</strong> {{ $survey->user->full_name ?? '—' }}</p>
<p><strong>تاریخ ارسال:</strong> {{ jdate($survey->created_at)->format('Y/m/d') }}</p>
<p><strong>نمایش هویت:</strong> {{ $survey->is_anonymous ? 'خیر' : 'بله' }}</p>
<hr>
<p><strong>رضایت کلی:</strong> {{ $survey->overall_satisfaction ?? '—' }}</p>
<p><strong>مدیریت و برنامه‌ریزی:</strong> {{ $survey->organization ?? '—' }}</p>
<p><strong>عملکرد سرپرستان:</strong> {{ $survey->leader_performance ?? '—' }}</p>
<p><strong>ایمنی برنامه:</strong> {{ $survey->safety ?? '—' }}</p>
<p><strong>تجهیزات و آمادگی:</strong> {{ $survey->equipment_quality ?? '—' }}</p>
<p><strong>میزان چالش‌برانگیزی:</strong> {{ $survey->difficulty_level ?? '—' }}</p>
<p><strong>نظرات و پیشنهادات:</strong></p>
<div class="border rounded p-3" style="white-space: pre-line;">{{ $survey->suggestions ?? '—' }}</div>
</div>
</div>

<a href="{{ route('admin.surveys.programs.index') }}" class="btn btn-secondary">بازگشت</a>
</div>
@endsection
