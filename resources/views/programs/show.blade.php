@extends('admin.layout')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.programs.index') }}">برنامه‌ها</a></li>
            <li class="breadcrumb-item active" aria-current="page">نمایش برنامه</li>
        </ol>
    </nav>
@endsection

@section('content')

    <div id="first-section" class="container mt-3">
        <div class="row">
            <div class="col-md-6 col-sm-12">
              {{-- اسلایدشو عکس‌ها --}}
                @if($program->photos && count($program->photos) > 0)
                    <div id="programCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($program->photos as $index => $photo)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $photo) }}" class="d-block w-100" alt="program photo">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#programCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#programCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                @endif
            </div>
            <div class="col-md-6 col-sm-12">
                <h3 class="mb-4">{{ $program->title }}</h3>
                {{-- توضیحات --}}
                @if($program->description)
                    <div>
                        <h5>توضیحات برنامه</h5>
                        <p class="text-justify">{!! $program->description !!}</p>
                    </div>
                @endif
                <div class="row mt-4">
                    <!-- تاریخ شروع -->
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center bg-light border rounded p-3 shadow-sm">
                            <i class="bi bi-calendar-event-fill text-primary fs-4 me-3"></i>
                            <div>
                                <div class="fw-bold">تاریخ شروع برنامه</div>
                                <div class="text-muted">
                                    {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::createFromTimestampMs($program->start_date))->format('Y/m/d H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تاریخ پایان -->
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center bg-light border rounded p-3 shadow-sm">
                            <i class="bi bi-calendar-check-fill text-success fs-4 me-3"></i>
                            <div>
                                <div class="fw-bold">تاریخ پایان برنامه</div>
                                <div class="text-muted">
                                    {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::createFromTimestampMs($program->end_date))->format('Y/m/d H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>


     {{-- اطلاعات مسئولین برنامه --}}
    @if($program->roles->count())
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 my-4">

            @foreach($program->roles as $role)
                @php
                    $user = $role->user;
                    $profile = $user?->profile;
                    $fullName = $user && $profile
                        ? $profile->first_name . ' ' . $profile->last_name
                        : $role->user_name;
                    $photo = $profile && $profile->personal_photo
                        ? asset('storage/' . $profile->personal_photo)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($fullName) . '&background=0D8ABC&color=fff';
                @endphp

                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <img src="{{ $photo }}" alt="Profile" class="rounded-circle mb-3" width="80" height="80">
                            <h6 class="mb-1">{{ $fullName }}</h6>
                            <small class="text-muted">{{ $role->role_title }}</small>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    @else
        <p class="text-muted">هیچ مسئولی برای این برنامه ثبت نشده است.</p>
    @endif

    {{-- حمل و نقل --}}
    @if($program->has_transport)
    <div class="container my-4">
        <div class="row g-4">

            {{-- ستون حرکت از تهران --}}
            <div class="col-md-6 col-12">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-geo-alt-fill me-1"></i> حرکت از تهران
                    </div>
                    <div class="card-body">
                        <p><i class="bi bi-calendar-event-fill text-secondary"></i> تاریخ و ساعت حرکت:
                            <strong id="departure_dateTime_tehran" data-ts="{{ $program->departure_dateTime_tehran }}"></strong></p>
                        <p><i class="bi bi-geo-fill text-secondary"></i> محل حرکت:
                            <strong>{{ $program->departure_place_tehran ?? '—' }}</strong></p>
                        @if($program->departure_lat_tehran && $program->departure_lon_tehran)
                            <div id="map_tehran" style="height: 300px;" class="rounded shadow-sm"></div>
                        @else
                            <p class="text-muted">موقعیت جغرافیایی مشخص نشده است.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ستون حرکت از کرج --}}
            <div class="col-md-6 col-12">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <i class="bi bi-geo-alt-fill me-1"></i> حرکت از کرج
                    </div>
                    <div class="card-body">
                        <p><i class="bi bi-calendar-event-fill text-secondary"></i> تاریخ و ساعت حرکت:
                            <strong id="departure_dateTime_karaj" data-ts="{{ $program->departure_dateTime_karaj }}"></strong></p>
                        <p><i class="bi bi-geo-fill text-secondary"></i> محل حرکت:
                            <strong>{{ $program->departure_place_karaj ?? '—' }}</strong></p>
                        @if($program->departure_lat_karaj && $program->departure_lon_karaj)
                            <div id="map_karaj" style="height: 300px;" class="rounded shadow-sm"></div>
                        @else
                            <p class="text-muted">موقعیت جغرافیایی مشخص نشده است.</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    @else
        <div class="alert alert-info">برنامه با حمل‌ونقل شخصی برگزار می‌شود.</div>
    @endif

   

   {{-- وعده‌ها و تجهیزات --}}
<div class="container my-4">
    <div class="row g-4">

        {{-- تجهیزات --}}
        <div class="col-md-6 col-sm-12">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <i class="bi bi-tools me-1 text-primary"></i> تجهیزات مورد نیاز
                </div>
                <div class="card-body">
                    @php
                        $equipments = is_array($program->required_equipment) ? $program->required_equipment : json_decode($program->required_equipment ?? '[]', true);
                    @endphp
                    @if(!empty($equipments))
                        @foreach($equipments as $item)
                            <span class="badge bg-secondary me-1 mb-1">{{ $item }}</span>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">تجهیزاتی ثبت نشده است.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- وعده‌های غذایی --}}
        <div class="col-md-6 col-sm-12">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <i class="bi bi-egg-fried me-1 text-success"></i> وعده‌های غذایی
                </div>
                <div class="card-body">
                    @php
                        $meals = is_array($program->required_meals) ? $program->required_meals : json_decode($program->required_meals ?? '[]', true);
                    @endphp
                    @if(!empty($meals))
                        @foreach($meals as $item)
                            <span class="badge bg-info text-dark me-1 mb-1">{{ $item }}</span>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">وعده‌ای ثبت نشده است.</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

{{-- هزینه‌ها --}}
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <i class="bi bi-cash-coin me-1 text-warning"></i> هزینه‌ها و اطلاعات پرداخت
        </div>
        <div class="card-body">
            @if($program->is_free)
                <span class="badge bg-success">این برنامه رایگان است</span>
            @else
                <div class="row mb-3">
                    <div class="col-md-6 col-sm-12">
                        <p><strong><i class="bi bi-person-check-fill text-primary"></i> هزینه عضو:</strong> <span class="text-danger">{{ number_format($program->member_cost) }} ریال</span></p>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <p><strong><i class="bi bi-person-fill text-warning"></i> هزینه مهمان:</strong> <span class="text-danger">{{ number_format($program->guest_cost) }} ریال</span></p>
                    </div>
                </div>
                <div class="row text-muted small">
                    <div class="col-md-6 col-sm-12">
                        <p><i class="bi bi-credit-card-2-front"></i> شماره کارت: {{ $program->card_number }}</p>
                        <p><i class="bi bi-bank2"></i> نام بانک: {{ $program->bank_name }}</p>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <p><i class="bi bi-hash"></i> شماره شبا: {{ $program->sheba_number }}</p>
                        <p><i class="bi bi-person"></i> نام دارنده کارت: {{ $program->card_holder }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- وضعیت ثبت‌نام --}}
<div class="container my-4">
    @if($program->is_registration_open)
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>
                ثبت‌نام باز است تا تاریخ:
                <strong>{{ $program->registration_deadline }}</strong>
            </div>
        </div>
    @else
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div>ثبت‌نام برای این برنامه بسته شده است.</div>
        </div>
    @endif
</div>

<div class="mt-5 text-center">
    <a href="{{ route('registrations.program.create', $program->id) }}" class="btn btn-primary btn-lg">
        <i class="bi bi-pencil-square me-2"></i> ثبت‌نام در برنامه
    </a>
</div>


@auth
    @if($userHasParticipated && !$userHasSubmittedSurvey)
        <div class="mt-4">
            <a href="{{ route('surveys.program.form', ['program' => $program->id]) }}" class="btn btn-primary">
                تکمیل فرم نظرسنجی برنامه
            </a>
        </div>
    @elseif($userHasSubmittedSurvey)
        <p class="text-success mt-4">شما قبلاً در این نظرسنجی شرکت کرده‌اید. با تشکر!</p>
    @endif
@endauth


@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const ts = document.getElementById("departure_dateTime_tehran").dataset.ts;

    const dateStr = new persianDate(parseInt(ts)).format('YYYY/MM/DD HH:mm');
    document.getElementById("departure_dateTime_tehran").innerText = dateStr;
</script>
<script>
    const ts = document.getElementById("departure_dateTime_karaj").dataset.ts;

    const dateStr = new persianDate(parseInt(ts)).format('YYYY/MM/DD HH:mm');
    document.getElementById("departure_dateTime_karaj").innerText = dateStr;
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if($program->departure_lat_tehran && $program->departure_lon_tehran)
            var mapTehran = L.map('map_tehran').setView([{{ $program->departure_lat_tehran }}, {{ $program->departure_lon_tehran }}], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
            }).addTo(mapTehran);
            L.marker([{{ $program->departure_lat_tehran }}, {{ $program->departure_lon_tehran }}]).addTo(mapTehran);
        @endif

        @if($program->departure_lat_karaj && $program->departure_lon_karaj)
            var mapKaraj = L.map('map_karaj').setView([{{ $program->departure_lat_karaj }}, {{ $program->departure_lon_karaj }}], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
            }).addTo(mapKaraj);
            L.marker([{{ $program->departure_lat_karaj }}, {{ $program->departure_lon_karaj }}]).addTo(mapKaraj);
        @endif
    });
</script>
@endpush
@endsection
