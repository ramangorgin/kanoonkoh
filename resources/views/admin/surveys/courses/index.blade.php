@extends('layouts.admin')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">نظرسنجی‌ها</a></li>
            <li class="breadcrumb-item active" aria-current="page">لیست دوره‌های نظرخواهی‌شده</li>
        </ol>
    </nav>
@endsection

@section('title', 'نتایج نظرسنجی دوره‌ها')

@section('content')
<div class="container py-4">
<h3 class="mb-4">نتایج نظرسنجی‌ دوره‌ها</h3>

<a href="{{ route('admin.surveys.stats', ['type' => $type, 'id' => $model->id]) }}" class="btn btn-sm btn-info">
    مشاهده آمار کلی نظرسنجی
</>


@if ($surveys->isEmpty())
<div class="alert alert-info">تا کنون هیچ نظرسنجی ثبت نشده است.</div>
@else

<div class="table-responsive">
<table class="table table-bordered table-hover text-center align-middle">
<thead class="table-light">
<tr>
<th>#</th>
<th>کاربر</th>
<th>دوره</th>
<th>رضایت کلی</th>
<th>تاریخ ارسال</th>
<th>هویت کاربر</th>
<th>مشاهده</th>
</tr>
</thead>
<tbody>
@foreach ($surveys as $index => $survey)
<tr>
<td>{{ $index + 1 }}</td>
<td>{{ $survey->user->full_name ?? '—' }}</td>
<td>{{ $survey->course->title ?? '—' }}</td>
<td>{{ $survey->overall_satisfaction ?? '—' }}</td>
<td>{{ jdate($survey->created_at)->format('Y/m/d') }}</td>
<td>
{{ $survey->is_anonymous ? 'محرمانه' : 'نمایش داده شده' }}
</td>
<td>
<a href="{{ route('admin.surveys.courses.show', $survey->id) }}" class="btn btn-sm btn-outline-primary">جزئیات</a>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
@endif
</div>
@endsection
