@extends('layouts.app')

@section('title', 'خانه')

@section('content')

<div id="heroSlider" class="hero-slider">
    <div class="carousel-container">
        @for ($i = 1; $i <= 10; $i++)
            <div class="carousel-slide {{ $i === 1 ? 'active' : '' }}">
                <img src="{{ asset('images/slider/slide' . $i . '.jpg') }}" 
                     alt="Slide {{ $i }}" 
                     class="slide-image">
            </div>
        @endfor
    </div>

    <div class="hero-overlay"></div>

    <div class="hero-content text-center text-white">
        <img src="{{ asset('images/logo-white.png') }}" class="rounded-circle shadow mb-4" style="width: 120px; height: 120px; object-fit: cover;" alt="کانون کوه">
        <h2 class="fw-bold mb-2">مؤسسه فرهنگی ورزشی باشگاه دوستداران قله‌ها و طبیعت</h2>
        <h4 class="mb-4">معروف به باشگاه کانون کوه</h4>
        <a href="{{ route('conditions') }}" class="btn btn-primary px-4 ms-2">شرایط عضویت</a>
        <a href="{{ url('/register') }}" class="btn btn-outline-light px-4">ثبت‌نام در باشگاه</a>
    </div>
</div>



{{-- بخش دوم: آخرین برنامه‌ها --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">آخرین برنامه‌ها</h3>
            <a href="{{ route('programs.index') }}" class="btn btn-outline-primary btn-sm">مشاهده همه</a>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($latestPrograms as $program)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $program->title }}</h5>
                            <p class="card-text text-muted">
                                تاریخ برگزاری: {{ jdate($program->date)->format('Y/m/d') }}
                            </p>
                            <p class="card-text flex-grow-1">
                                {{ Str::limit(strip_tags($program->description), 100, '...') }}
                            </p>
                            <a href="{{ route('programs.show', $program->id) }}" class="btn btn-sm btn-outline-primary mt-2">
                                مشاهده جزئیات
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- بخش سوم: آخرین دوره‌ها --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">آخرین دوره‌ها</h3>
            <a href="{{ route('courses.index') }}" class="btn btn-outline-primary btn-sm">مشاهده همه</a>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($latestCourses as $course)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $course->title }}</h5>
                            <p class="card-text text-muted">
                                تاریخ برگزاری: {{ jdate($course->date)->format('Y/m/d') }}
                            </p>
                            <p class="card-text flex-grow-1">
                                {{ Str::limit(strip_tags($course->description), 100, '...') }}
                            </p>
                            <a href="{{ route('courses.show', $course->id) }}" class="btn btn-sm btn-outline-primary mt-2">
                                مشاهده جزئیات
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- بخش چهارم: آخرین گزارش‌ها --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">آخرین گزارش‌ها</h3>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-primary btn-sm">مشاهده همه</a>
        </div>

        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach($latestReports as $report)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $report->program->title ?? 'گزارش بدون عنوان' }}</h5>
                            <p class="card-text text-muted">
                                تاریخ برنامه: {{ optional($report->program)->date ? jdate($report->program->date)->format('Y/m/d') : '-' }}
                            </p>
                            <p class="card-text flex-grow-1">
                                {{ Str::limit(strip_tags($report->content), 120, '...') }}
                            </p>
                            <a href="{{ route('reports.show', $report->id) }}" class="btn btn-sm btn-outline-primary mt-2">
                                مشاهده گزارش کامل
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@push('scripts')
<script>
 document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll('.carousel-slide');
    let currentSlide = 0;
    
    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        slides[index].classList.add('active');
    }
    
    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }
    
    // شروع اسلایدر
    showSlide(currentSlide);
    setInterval(nextSlide, 6000);
});
</script>

@endpush

@push('styles')
<style>

@media (max-width: 768px) {
    .hero-slider {
        height: 80vh;
    }
    
    .hero-content {
        padding: 0 20px;
    }
    
    .slide-image {
        object-position: center center;
    }
}
.hero-slider {
    position: relative;
    width: 100%;
    height: 100vh;
    overflow: hidden;
}

.carousel-container {
    position: relative;
    width: 100%;
    height: 100%;
}

.carousel-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 1s ease-in-out;
    z-index: 1;
}

.carousel-slide.active {
    opacity: 1;
    z-index: 2;
}

.slide-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transform: scale(1.05);
    transition: transform 8s ease-in-out;
}

.carousel-slide.active .slide-image {
    transform: scale(1.15);
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 3;
}

.hero-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 4;
    width: 90%;
    max-width: 1200px;
    text-align: center;
    color: white;
}
</style>

@endpush
@endsection
