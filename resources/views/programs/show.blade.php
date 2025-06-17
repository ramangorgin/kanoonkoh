@extends('layouts.app')

@section('title', $program->title)

@section('content')
<div class="container">

    {{-- عنوان برنامه --}}
    <h2 class="mb-4">{{ $program->title }}</h2>

    {{-- اسلایدر عکس‌ها --}}
    @if($program->report_photos)
        @php $photos = json_decode($program->report_photos, true); @endphp
        <div id="programCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($photos as $index => $photo)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $photo) }}" class="d-block w-100 rounded" alt="عکس {{ $index + 1 }}">
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

    {{-- توضیحات برنامه --}}
    <div class="mb-4">
        <h5>توضیحات برنامه:</h5>
        <p>{{ $program->description }}</p>
    </div>

    {{-- مسئولین برنامه --}}
    <div class="mb-4">
        <h5>مسئولین برنامه:</h5>
        <div class="row">
            @foreach([
                'leader' => 'سرپرست',
                'assistant_leader' => 'کمک‌سرپرست',
                'technical_manager' => 'مسئول فنی',
                'guide' => 'راهنما',
                'support' => 'پشتیبان'
            ] as $key => $role)
                @php $user = $program->{$key}; @endphp
                @if($user)
                    <div class="col-md-2 text-center">
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" class="rounded-circle mb-2" width="80" height="80">
                        <div class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                        <div class="text-muted small">{{ $role }}</div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- جزئیات حرکت از تهران و کرج --}}
    @foreach(['tehran', 'karaj'] as $city)
        @php
            $lat = $program->{'departure_lat_' . $city};
            $lon = $program->{'departure_lon_' . $city};
        @endphp
        @if($lat && $lon)
            <div class="mb-4">
                <h5>حرکت از {{ $city == 'tehran' ? 'تهران' : 'کرج' }}</h5>
                <p>
                    تاریخ: {{ jdate($program->{'departure_date_' . $city})->format('Y/m/d') }} <br>
                    ساعت: {{ $program->{'departure_time_' . $city} }} <br>
                    محل: {{ $program->{'departure_place_' . $city} }}
                </p>
                <div id="map-{{ $city }}" style="height: 300px;" class="mb-3 rounded border"></div>
            </div>
        @endif
    @endforeach

    {{-- تجهیزات و وعده‌های غذایی --}}
    <div class="mb-4">
        <h5>تجهیزات مورد نیاز:</h5>
        <p>{{ $program->required_equipment }}</p>

        <h5>وعده‌های غذایی مورد نیاز:</h5>
        <p>{{ $program->required_meals }}</p>
    </div>

</div>
@endsection

@push('scripts')
    {{-- OpenStreetMap Script --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        @foreach(['tehran', 'karaj'] as $city)
            @php
                $lat = $program->{'departure_lat_' . $city};
                $lon = $program->{'departure_lon_' . $city};
            @endphp
            @if($lat && $lon)
                const map_{{ $city }} = L.map('map-{{ $city }}').setView(@json([$lat, $lon]), 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19
                }).addTo(map_{{ $city }});
                L.marker(@json([$lat, $lon])).addTo(map_{{ $city }});
            @endif
        @endforeach
    </script>
@endpush
