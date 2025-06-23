@extends('admin.layout')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">دوره‌ها</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $course->title }}</li>
    </ol>
</nav>
@endsection

@section('content')
<h3 class="mb-4">{{ $course->title }}</h3>

<div class="row mb-4 text-center">
    <div class="col-md-4 mb-2">
        <i class="bi bi-calendar2-week h5"></i>
        <p class="mt-2 mb-0">از تاریخ</p>
        <strong>{{ $course->start_date }}</strong>
        <p class="mt-1 mb-0">تا تاریخ</p>
        <strong>{{ $course->end_date }}</strong>
    </div>
    <div class="col-md-4 mb-2">
        <i class="bi bi-clock h5"></i>
        <p class="mt-2 mb-0">از ساعت</p>
        <strong>{{ $course->start_time }}</strong>
        <p class="mt-1 mb-0">تا ساعت</p>
        <strong>{{ $course->end_time }}</strong>
    </div>
    <div class="col-md-4 mb-2">
        <i class="bi bi-geo-alt h5"></i>
        <p class="mt-2 mb-0">در محل:</p>
        <strong>{{ $course->place }}</strong>
    </div>
</div>

<div id="map_place" class="mb-4 rounded" style="height: 300px;"></div>

<div class="mb-4">
    <i class="bi bi-people h5"></i>
    <strong> ظرفیت:</strong> {{ $course->capacity }}
</div>

<div class="mb-4">
    <i class="bi bi-door-open h5"></i>
    <strong> وضعیت ثبت‌نام:</strong>
    {!! $course->is_registration_open ? '<span class="text-success">باز</span>' : '<span class="text-danger">بسته</span>' !!}
</div>

@if($course->is_registration_open)
    <div class="mb-4">
        <i class="bi bi-hourglass-split h5"></i>
        <strong> مهلت ثبت‌نام:</strong> {{ $course->registration_deadline }}
    </div>
@endif

<div class="mb-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body d-flex align-items-center">
            <img src="{{ asset('images/default_avatar.png') }}" class="rounded-circle me-3" width="70" height="70" alt="مدرس">
            <div>
                <div class="fw-bold">{{ $course->teacher }}</div>
                <div class="text-muted">مدرس</div>
            </div>
        </div>
    </div>
</div>

@if($course->is_free)
    <div class="mb-4 text-success">
        <i class="bi bi-cash-coin h5"></i>
        <strong> این دوره رایگان است.</strong>
    </div>
@else
    <div class="mb-4">
        <i class="bi bi-credit-card h5"></i>
        <strong>هزینه‌ها:</strong><br>
        <span class="ms-3">اعضا: {{ number_format($course->member_cost) }} ریال</span><br>
        <span class="ms-3">مهمان: {{ number_format($course->guest_cost) }} ریال</span>
    </div>

    <div class="mb-4">
        <i class="bi bi-bank h5"></i>
        <strong>مشخصات بانکی:</strong><br>
        <span class="ms-3">شماره کارت: {{ $course->card_number }}</span><br>
        <span class="ms-3">شماره شبا: {{ $course->sheba_number }}</span><br>
        <span class="ms-3">نام دارنده: {{ $course->card_holder }}</span><br>
        <span class="ms-3">بانک: {{ $course->bank_name }}</span>
    </div>
@endif

<div class="mb-4">
    <i class="bi bi-info-circle h5"></i>
    <strong>توضیحات:</strong>
    <div class="mt-2">
        {!! $course->description !!}
    </div>
</div>


{{-- وضعیت ثبت‌نام --}}
<div class="container my-4">
    @if($course->is_registration_open)
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>
                ثبت‌نام باز است تا تاریخ:
                <strong>{{ $course->registration_deadline }}</strong>
            </div>
        </div>
    @else
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div>ثبت‌نام برای این برنامه بسته شده است.</div>
        </div>
    @endif
</div>

@auth
    @if($userHasParticipated && !$userHasSubmittedSurvey)
        <div class="mt-4">
            <a href="{{ route('surveys.course.form', ['course' => $course->id]) }}" class="btn btn-primary w-100">
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
