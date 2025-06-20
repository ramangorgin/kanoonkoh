@extends('layouts.dashboard')

@section('title', 'مشاهده گزارش')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">داشبورد</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.reports.index') }}">گزارش‌ها</a></li>
            <li class="breadcrumb-item active" aria-current="page">نمایش گزارش</li>
        </ol>
    </nav>
@endsection

@push('styles')
<style>
    .report-card {
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }
    .report-card:hover {
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }
    .report-header {
        border-radius: 10px 10px 0 0 !important;
    }
    .team-member {
        transition: all 0.3s ease;
        padding: 10px;
    }
    .team-member:hover {
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    .report-content {
        line-height: 1.8;
        text-align: justify;
    }
    .info-badge {
        font-size: 0.85rem;
    }
    .map-container {
        height: 300px;
        border-radius: 8px;
        overflow: hidden;
    }
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline:before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    .timeline-item:before {
        content: '';
        position: absolute;
        left: -30px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #0d6efd;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    <!-- هدر گزارش -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="report-card card">
                <div class="card-header report-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h3 mb-0">{{ $report->title }}</h1>
                        <div>
                            <span class="badge bg-light text-dark me-2">
                                <i class="bi bi-calendar me-1"></i>
                                {{ \Morilog\Jalali\Jalalian::fromDateTime($report->start_date)->format('Y/m/d') }}
                            </span>
                            @if($report->approved)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    تایید شده
                                </span>
                            @else
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-hourglass-split me-1"></i>
                                    در انتظار تایید
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="lead">{{ $report->content }}</p>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-secondary info-badge">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ $report->area }}
                                </span>
                                <span class="badge bg-secondary info-badge">
                                    <i class="bi bi-mountain me-1"></i>
                                    ارتفاع: {{ $report->peak_height }} متر
                                </span>
                                <span class="badge bg-secondary info-badge">
                                    <i class="bi bi-people me-1"></i>
                                    {{ $report->participant_count }} نفر
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- گالری تصاویر -->
    @if($report->gallery && is_array(json_decode($report->gallery, true)))
    <div class="row mb-4">
        <div class="col-12">
            <div class="report-card card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-images me-2"></i>گالری تصاویر</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3" id="lightgallery">
                        @foreach(json_decode($report->gallery, true) as $index => $image)
                        <div class="col-6 col-md-4 col-lg-3">
                            <a href="{{ asset('storage/' . $image) }}" class="gallery-item">
                                <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded" alt="تصویر گزارش" loading="lazy">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- اطلاعات کلی -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="report-card card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>اطلاعات کلی</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th width="40%">نوع برنامه</th>
                                    <td>{{ $report->type }}</td>
                                </tr>
                                <tr>
                                    <th>محل شروع</th>
                                    <td>{{ $report->start_location }}</td>
                                </tr>
                                <tr>
                                    <th>ارتفاع مبدا</th>
                                    <td>{{ $report->start_height }} متر</td>
                                </tr>
                                <tr>
                                    <th>تاریخ شروع</th>
                                    <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($report->start_date)->format('Y/m/d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>تاریخ پایان</th>
                                    <td>{{ $report->end_date ? \Morilog\Jalali\Jalalian::fromDateTime($report->end_date)->format('Y/m/d H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>نویسنده گزارش</th>
                                    <td>{{ $report->writer_name ?? $report->user->name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="report-card card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-geo me-2"></i>موقعیت جغرافیایی</h5>
                </div>
                <div class="card-body">
                    @if($report->start_coords || $report->peak_coords)
                        <div class="map-container mb-3" id="map"></div>
                        <div class="row">
                            @if($report->start_coords)
                            <div class="col-md-6">
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" class="form-control" value="{{ $report->start_coords }}" readonly>
                                </div>
                            </div>
                            @endif
                            @if($report->peak_coords)
                            <div class="col-md-6">
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="bi bi-mountain"></i></span>
                                    <input type="text" class="form-control" value="{{ $report->peak_coords }}" readonly>
                                </div>
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">مختصاتی ثبت نشده است</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- تیم اجرایی -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="report-card card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i>تیم اجرایی</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($report->userRoles as $role)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="team-member text-center p-3">
                                <div class="avatar mb-2 mx-auto">
                                    @if($role->user)
                                        <img src="{{ $role->user->profile_photo_url }}" 
                                             class="rounded-circle shadow" 
                                             width="80" 
                                             height="80" 
                                             alt="{{ $role->user->name }}">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light rounded-circle shadow" 
                                             style="width: 80px; height: 80px;">
                                            <i class="bi bi-person fs-3 text-secondary"></i>
                                        </div>
                                    @endif
                                </div>
                                <h6 class="mb-1">{{ $role->user_name ?? ($role->user ? $role->user->name : 'نامشخص') }}</h6>
                                <small class="text-muted">{{ $role->role_title }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- مشخصات فنی -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="report-card card h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-tools me-2"></i>مشخصات فنی</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <th width="40%">سطح فنی</th>
                                    <td>{{ $report->technical_level }}</td>
                                </tr>
                                <tr>
                                    <th>نوع مسیر</th>
                                    <td>{{ $report->road_type }}</td>
                                </tr>
                                <tr>
                                    <th>حمل و نقل</th>
                                    <td>
                                        @foreach(json_decode($report->transportation ?? '[]') as $item)
                                            <span class="badge bg-light text-dark me-1">{{ $item }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>امکانات رفاهی</th>
                                    <td>
                                        @foreach(json_decode($report->water_type ?? '[]') as $item)
                                            <span class="badge bg-light text-dark me-1">{{ $item }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="report-card card h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-clipboard2-check me-2"></i>نیازمندی‌ها</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-3"><i class="bi bi-backpack me-2"></i>تجهیزات مورد نیاز:</h6>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @foreach(json_decode($report->required_equipment ?? '[]') as $item)
                            <span class="badge bg-light text-dark">{{ $item }}</span>
                        @endforeach
                    </div>

                    <h6 class="mb-3"><i class="bi bi-person-check me-2"></i>مهارت‌های لازم:</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(json_decode($report->required_skills ?? '[]') as $item)
                            <span class="badge bg-light text-dark">{{ $item }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- مسیر و زمانبندی -->
    <div class="row mb-4">
        @if($report->route_points)
        <div class="col-md-6">
            <div class="report-card card h-100">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-signpost-split me-2"></i>نقاط مسیر</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach(json_decode($report->route_points, true) as $point)
                        <div class="timeline-item">
                            <h6>{{ $point['point'] ?? 'نقطه نامشخص' }}</h6>
                            <p class="text-muted small mb-0">{{ $point['utm'] ?? 'مختصات نامشخص' }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($report->execution_schedule)
        <div class="col-md-6">
            <div class="report-card card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>زمان‌بندی اجرا</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach(json_decode($report->execution_schedule, true) as $schedule)
                        <div class="timeline-item">
                            <h6>{{ $schedule['event'] ?? 'رویداد نامشخص' }}</h6>
                            <p class="text-muted small mb-0">{{ $schedule['time'] ?? 'زمان نامشخص' }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- توضیحات و نکات -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="report-card card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-tree me-2"></i>ویژگی‌های طبیعی</h5>
                </div>
                <div class="card-body">
                    <h6><i class="bi bi-cloud-sun me-2"></i>آب و هوا:</h6>
                    <p>{{ $report->weather ?? 'ثبت نشده' }}</p>

                    <h6><i class="bi bi-wind me-2"></i>سرعت باد:</h6>
                    <p>{{ $report->wind_speed ?? 'ثبت نشده' }} کیلومتر بر ساعت</p>

                    <h6><i class="bi bi-thermometer-half me-2"></i>دمای هوا:</h6>
                    <p>{{ $report->temperature ?? 'ثبت نشده' }} درجه سانتیگراد</p>

                    <h6><i class="bi bi-flower1 me-2"></i>پوشش گیاهی:</h6>
                    <p>{{ $report->natural_description ?? 'ثبت نشده' }}</p>

                    <h6><i class="bi bi-bug me-2"></i>حیات وحش:</h6>
                    <p>{{ $report->wildlife ?? 'ثبت نشده' }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="report-card card h-100">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-square me-2"></i>اطلاعات تکمیلی</h5>
                </div>
                <div class="card-body">
                    <h6><i class="bi bi-translate me-2"></i>زبان محلی:</h6>
                    <p>{{ $report->local_language ?? 'ثبت نشده' }}</p>

                    <h6><i class="bi bi-building me-2"></i>آثار تاریخی:</h6>
                    <p>{{ $report->historical_sites ?? 'ثبت نشده' }}</p>

                    <h6><i class="bi bi-egg-fried me-2"></i>امکانات غذایی:</h6>
                    <p>{{ $report->food_availability ?? 'ثبت نشده' }}</p>

                    <h6><i class="bi bi-exclamation-triangle me-2"></i>نکات مهم:</h6>
                    <p>{{ $report->important_notes ?? 'ثبت نشده' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- متن کامل گزارش -->
    @if($report->content)
    <div class="row mb-4">
        <div class="col-12">
            <div class="report-card card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-journal-text me-2"></i>متن کامل گزارش</h5>
                </div>
                <div class="card-body report-content">
                    {!! $report->content !!}
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- فایل‌های ضمیمه -->
    <div class="row">
        <div class="col-12">
            <div class="report-card card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-paperclip me-2"></i>فایل‌های ضمیمه</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3">
                        @if($report->pdf_path)
                        <a href="{{ asset('storage/' . $report->pdf_path) }}" class="btn btn-outline-primary" target="_blank">
                            <i class="bi bi-file-earmark-pdf me-2"></i>دانلود PDF گزارش
                        </a>
                        @endif

                        @if($report->track_file_path)
                        <a href="{{ asset('storage/' . $report->track_file_path) }}" class="btn btn-outline-success" target="_blank">
                            <i class="bi bi-map me-2"></i>دانلود مسیر (GPX)
                        </a>
                        @endif

                        <a href="{{ route('dashboard.reports.index') }}" class="btn btn-outline-secondary ms-auto">
                            <i class="bi bi-arrow-right me-2"></i>بازگشت به لیست گزارش‌ها
                        </a>

                        @can('update', $report)
                        <a href="{{ route('dashboard.reports.edit', $report->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>ویرایش گزارش
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Lightgallery -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/css/lightgallery.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/lightgallery.min.js"></script>

<!-- Leaflet Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
// Initialize lightGallery
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('lightgallery')) {
        lightGallery(document.getElementById('lightgallery'), {
            selector: '.gallery-item',
            download: false,
            share: false
        });
    }

    // Initialize map if coordinates exist
    @if($report->start_coords || $report->peak_coords)
    const map = L.map('map').setView([35.7, 51.4], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Add start point marker
    @if($report->start_coords)
    const startCoords = @json(explode(',', $report->start_coords));
    const startMarker = L.marker([startCoords[0], startCoords[1]]).addTo(map)
        .bindPopup('محل شروع: {{ $report->start_location }}');
    @endif

    // Add peak point marker
    @if($report->peak_coords)
    const peakCoords = @json(explode(',', $report->peak_coords));
    const peakMarker = L.marker([peakCoords[0], peakCoords[1]]).addTo(map)
        .bindPopup('قله: {{ $report->peak_name ?? "نقطه نهایی" }}')
        .openPopup();
    @endif

    // Fit bounds to show all markers
    @if($report->start_coords && $report->peak_coords)
    map.fitBounds([
        [startCoords[0], startCoords[1]],
        [peakCoords[0], peakCoords[1]]
    ]);
    @elseif($report->start_coords)
    map.setView([startCoords[0], startCoords[1]], 12);
    @elseif($report->peak_coords)
    map.setView([peakCoords[0], peakCoords[1]], 12);
    @endif
    @endif
});
</script>
@endpush