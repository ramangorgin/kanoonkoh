@extends('layouts.admin')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">نظرسنجی‌ها</a></li>
            <li class="breadcrumb-item active" aria-current="page">لیست برنامه‌های نظرخواهی‌شده</li>
        </ol>
    </nav>
@endsection

@section('title', 'نظرسنجی‌های برنامه‌ها')

@section('content')
<div class="container py-4">
<h4 class="mb-4">نظرسنجی‌های ثبت‌شده برای برنامه‌ها</h4>

<a href="{{ route('admin.surveys.stats', ['type' => $type, 'id' => $model->id]) }}" class="btn btn-sm btn-info">
    مشاهده آمار کلی نظرسنجی
</a>


@if ($surveys->isEmpty())
<div class="alert alert-info">
هیچ نظرسنجی‌ای برای برنامه‌ها ثبت نشده است.
</div>
@else
<div class="table-responsive">
<table class="table table-bordered table-striped align-middle">
<thead class="table-dark">
<tr>
<th>برنامه</th>
<th>کاربر</th>
<th>تاریخ ارسال</th>
<th>نمایش هویت</th>
<th>عملیات</th>
</tr>
</thead>
<tbody>
@foreach ($surveys as $survey)
<tr>
<td>{{ $survey->program->title ?? '—' }}</td>
<td>{{ $survey->user->full_name ?? '—' }}</td>
<td>{{ jdate($survey->created_at)->format('Y/m/d') }}</td>
<td>{{ $survey->is_anonymous ? 'خیر' : 'بله' }}</td>
<td>
<a href="{{ route('admin.surveys.programs.show', $survey->id) }}" class="btn btn-sm btn-primary">مشاهده</a>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>

<div class="d-flex justify-content-center mt-4">
{{ $surveys->links() }}
</div>
@endif

<a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mt-3">بازگشت به پنل</a>
</div>
@endsection
