@extends('layouts.admin')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.programs.index') }}">برنامه‌ها</a></li>
            <li class="breadcrumb-item active" aria-current="page">نمایش برنامه</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h3 class="mb-4">{{ $program->title }}</h3>

    {{-- اسلایدشو عکس‌ها --}}
    @if($program->report_photos && count($program->report_photos) > 0)
        <div id="programCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($program->report_photos as $index => $photo)
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

    {{-- توضیحات --}}
    @if($program->description)
        <div class="mb-4">
            <h5>توضیحات برنامه</h5>
            <div>{!! $program->description !!}</div>
        </div>
    @endif

    {{-- نقشه حرکت --}}
    @if($program->has_transport)
        <div class="row mb-4">
            <div class="col-md-6">
                <h6>محل حرکت از تهران</h6>
                <div id="map_tehran" style="height: 300px;"></div>
            </div>
            <div class="col-md-6">
                <h6>محل حرکت از کرج</h6>
                <div id="map_karaj" style="height: 300px;"></div>
            </div>
        </div>
    @else
        <div class="alert alert-info">برنامه با حمل‌ونقل شخصی برگزار می‌شود.</div>
    @endif

    {{-- اطلاعات مسئولین --}}
    <div class="row mb-4">
        @foreach ([
            'leader' => 'سرپرست',
            'assistant_leader' => 'کمک‌سرپرست',
            'technical_manager' => 'مسئول فنی',
            'support' => 'پشتیبان',
            'guide' => 'راهنما'
        ] as $role => $label)
            @php
                $name = $program->{$role . '_name'};
                $user = \App\Models\User::where('name', $name)->first();
            @endphp
            @if($name)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body text-center">
                            @if($user && $user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle mb-2" width="80" height="80">
                            @endif
                            <h6>{{ $name }}</h6>
                            <small class="text-muted">{{ $label }}</small>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    {{-- وعده‌ها و تجهیزات --}}
    <div class="mb-4">
        <h6>تجهیزات مورد نیاز:</h6>
        <ul class="list-inline">
            @foreach($program->required_equipment ?? [] as $item)
                <li class="list-inline-item badge bg-secondary">{{ $item }}</li>
            @endforeach
        </ul>

        <h6 class="mt-3">وعده‌های غذایی:</h6>
        <ul class="list-inline">
            @foreach($program->required_meals ?? [] as $item)
                <li class="list-inline-item badge bg-info">{{ $item }}</li>
            @endforeach
        </ul>
    </div>

    {{-- هزینه‌ها --}}
    <div class="mb-4">
        @if($program->is_free)
            <span class="badge bg-success">رایگان</span>
        @else
            <p><strong>هزینه عضو:</strong> <span class="text-danger">{{ number_format($program->member_cost) }} ریال</span></p>
            <p><strong>هزینه مهمان:</strong> <span class="text-danger">{{ number_format($program->guest_cost) }} ریال</span></p>

            <div class="mt-2">
                <h6>اطلاعات پرداخت:</h6>
                <p>شماره کارت: {{ $program->card_number }}</p>
                <p>شماره شبا: {{ $program->sheba_number }}</p>
                <p>نام دارنده کارت: {{ $program->card_holder }}</p>
                <p>نام بانک: {{ $program->bank_name }}</p>
            </div>
        @endif
    </div>

    {{-- ثبت‌نام --}}
    @if($program->is_registration_open)
        <div class="alert alert-success">
            ثبت‌نام باز است تا تاریخ: <strong>{{ jdate($program->registration_deadline)->format('Y/m/d') }}</strong>
        </div>
    @else
        <div class="alert alert-warning">ثبت‌نام بسته است</div>
    @endif


    @auth
    @if($userHasParticipated && !$userHasSubmittedSurvey)
        <div class="mt-4">
            <a href="{{ route('survey.program.form', ['program' => $program->id]) }}" class="btn btn-primary">
                تکمیل فرم نظرسنجی برنامه
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
    const renderMap = (id, lat, lon) => {
        if (lat && lon) {
            const map = L.map(id).setView([lat, lon], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            L.marker([lat, lon]).addTo(map);
        }
    };
    renderMap('map_tehran', {{ $program->departure_lat_tehran }}, {{ $program->departure_lon_tehran }});
    renderMap('map_karaj', {{ $program->departure_lat_karaj }}, {{ $program->departure_lon_karaj }});
</script>
@endsection
