@extends('layouts.admin')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">دوره‌ها</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $course->title }}</li>
    </ol>
</nav>
@endsection

@section('content')
<h3>{{ $course->title }}</h3>

<div class="mb-4">
    <strong><i class="bi bi-calendar"></i> تاریخ شروع:</strong> {{ $course->start_date }}<br>
    <strong><i class="bi bi-calendar2-check"></i> تاریخ پایان:</strong> {{ $course->end_date }}<br>
    <strong><i class="bi bi-clock"></i> ساعت:</strong> {{ $course->start_time }} تا {{ $course->end_time }}
</div>

<div class="mb-4">
    <strong>مدرس:</strong> {{ $course->teacher }}
</div>

<div class="mb-4">
    <strong>محل برگزاری:</strong> {{ $course->place }}
    <div id="map_place" style="height: 300px;"></div>
</div>

<div class="mb-4">
    <strong>ظرفیت:</strong> {{ $course->capacity }}
</div>

<div class="mb-4">
    <strong>وضعیت ثبت‌نام:</strong> {!! $course->is_registration_open ? '<span class="text-success">باز</span>' : '<span class="text-danger">بسته</span>' !!}
</div>

@if($course->is_registration_open)
<div class="mb-4">
    <strong>مهلت ثبت‌نام:</strong> {{ $course->registration_deadline }}
</div>
@endif

@if($course->is_free)
<div class="mb-4 text-success"><strong>رایگان</strong></div>
@else
<div class="mb-4 text-danger">
    <strong>هزینه اعضا:</strong> {{ number_format($course->member_cost) }} ریال<br>
    <strong>هزینه مهمان:</strong> {{ number_format($course->guest_cost) }} ریال
</div>
<div class="mb-4">
    <strong>شماره کارت:</strong> {{ $course->card_number }}<br>
    <strong>شماره شبا:</strong> {{ $course->sheba_number }}<br>
    <strong>نام دارنده کارت:</strong> {{ $course->card_holder }}<br>
    <strong>بانک:</strong> {{ $course->bank_name }}
</div>
@endif

<div class="mb-4">
    <strong>توضیحات:</strong>
    <div>{!! $course->description !!}</div>
</div>


@auth
    @if($userHasParticipated && !$userHasSubmittedSurvey)
        <div class="mt-4">
            <a href="{{ route('survey.course.form', ['course' => $course->id]) }}" class="btn btn-primary">
                تکمیل فرم نظرسنجی دوره
            </a>
        </div>
    @elseif($userHasSubmittedSurvey)
        <p class="text-success mt-4">شما قبلاً در این نظرسنجی شرکت کرده‌اید. با تشکر!</p>
    @endif
@endauth

@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
    var map = L.map('map_place').setView([{{ $course->place_lat }}, {{ $course->place_lon }}], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    L.marker([{{ $course->place_lat }}, {{ $course->place_lon }}]).addTo(map);
</script>
@endsection
