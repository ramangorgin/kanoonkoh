@extends('layouts.dashboard')


@section('title', 'گزارش‌های من')

@section('breadcrumb')
    <a href="{{ route('dashboard.index') }}">داشبورد</a> /
    <a href="{{ route('dashboard.reports.index') }}">گزارش‌ها</a> /
    <span>گزارش‌های من</span>
@endsection

@section('content')

@php
    $reports = $reports ?? collect(); // جلوگیری از ارور در صورت نبود متغیر
@endphp

<div class="container py-4">
    <h3 class="mb-4">گزارش‌های نوشته‌شده توسط شما</h3>

    @if(isset($reports) && !$reports->isEmpty())

        <div class="alert alert-info">شما تاکنون گزارشی ارسال نکرده‌اید.</div>
    @else
    <a href="{{ route('dashboard.reports.create') }}" class="btn btn-primary mb-4">
        + ثبت گزارش جدید
    </a>

        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>عنوان گزارش</th>
                    <th>مربوط به برنامه</th>
                    <th>وضعیت</th>
                    <th>تاریخ ارسال</th>
                    <th>مشاهده</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>{{ $report->title }}</td>
                        <td>{{ optional($report->program)->title ?? '—' }}</td>
                        <td>
                            @if($report->approved)
                                <span class="badge bg-success">تایید شده</span>
                            @else
                                <span class="badge bg-warning text-dark">در انتظار تایید</span>
                            @endif
                        </td>
                        <td>{{ jdate($report->created_at)->format('Y/m/d') }}</td>
                        <td>
                            <a href="{{ route('dashboard.reports.show', $report->id) }}" class="btn btn-sm btn-outline-primary">مشاهده</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
