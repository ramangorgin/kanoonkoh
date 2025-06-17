@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-3">{{ $course->title }}</h2>

    <div class="mb-3 text-muted">
        تاریخ برگزاری: {{ jdate($course->date)->format('Y/m/d') }}
        @if($course->location)
            | مکان: {{ $course->location }}
        @endif
    </div>

    <div class="mb-3">
        <strong>مدت دوره:</strong> {{ $course->duration }} ساعت
    </div>

    @if($course->file_link)
        <div class="mb-3">
            <a href="{{ $course->file_link }}" target="_blank" class="btn btn-sm btn-outline-info">
                دانلود معرفی دوره
            </a>
        </div>
    @endif

    <div class="mb-4">
        {!! nl2br(e($course->description)) !!}
    </div>

    @if(now() < $course->registration_deadline)
        <a href="{{ route('courses.register', $course->id) }}" class="btn btn-primary">
            ثبت‌نام در دوره
        </a>
    @else
        <div class="alert alert-secondary">مهلت ثبت‌نام در این دوره به پایان رسیده است.</div>
    @endif
</div>
@endsection
