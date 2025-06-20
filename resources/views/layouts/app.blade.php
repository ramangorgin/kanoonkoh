<!DOCTYPE html>
<html lang="fa" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'کانون کوه')</title>
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />  
        <link href="https://cdn.jsdelivr.net/gh/rastikerdar/sahel-font@v3.4.0/dist/font-face.css" rel="stylesheet" type="text/css" />        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
      
        @stack('styles')
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
                <div class="container">
                    {{-- ستون راست (لوگو) --}}
                    <a class="navbar-brand order-1 order-lg-1" href="{{ route('home') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="کانون کوه" style="height: 60px;">
                    </a>

                    {{-- ستون وسط (منو اصلی - فقط در لپ‌تاپ) --}}
                    <div class="d-none d-lg-block order-2 w-100 text-center">
                        <ul class="navbar-nav justify-content-center flex-row gap-4">
                            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}" style="font-size: 15pt;">خانه</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('programs.index') }}" style="font-size: 15pt;">برنامه‌ها</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('courses.index') }}" style="font-size: 15pt;">دوره‌ها</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('reports.index') }}" style="font-size: 15pt;">گزارش‌ها</a></li>
                        </ul>
                    </div>

                    {{-- ستون چپ (ورود/ثبت‌نام - فقط در لپ‌تاپ) --}}
                    <div class="d-none d-lg-block order-3">
                        @auth
                        <a href="{{ route('dashboard.index') }}" class="btn btn-outline-secondary py-3 px-5">داشبورد</a>
                        @else
                        <div class="row">
                            <div class="col-md-6"><a href="{{ route('login') }}" class="btn btn-outline-primary ms-2" style="width: 70px;"> ورود </a></div>
                            <div class="col-md-6"> <a href="{{ route('register') }}" class="btn btn-primary" style="width: 80px;">ثبت‌نام</a></div>
                        </div>
                        @endauth
                    </div>

                    {{-- همبرگر موبایل --}}
                    <button class="navbar-toggler d-lg-none order-2" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#mobileMenu" aria-controls="mobileMenu">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </nav>

            {{-- موبایل منو --}}
            <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title">منو</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">خانه</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('programs.index') }}">برنامه‌ها</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('courses.index') }}">دوره‌ها</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('reports.index') }}">گزارش‌ها</a></li>
                    </ul>

                    <hr>

                    @auth
                        <a href="{{ route('dashboard.index') }}" class="btn btn-outline-secondary w-100 mb-2">ورود به داشبورد</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 mb-2">ورود</a>
                        <a href="{{ route('register') }}" class="btn btn-primary w-100">ثبت‌نام</a>
                    @endauth
                </div>
            </div>
        </header>

        <main>
            @include('layouts.partials.alerts')
            @yield('content')
        </main>

        <footer class="bg-dark text-light pt-5 mt-5 border-top font-vazirmatn">
        <div class="container">
            <div class="row gy-4">

                {{-- ستون ۱: آدرس و نقشه --}}
                <div class="col-md-3">
                    <h5 class="fw-bold text-center mb-3">آدرس</h5>
                    <div id="map" style="height: 220px; border-radius: 8px; margin-bottom: 1rem;"></div>
                    <p class="mb-1"><i class="bi bi-geo-alt-fill me-2"></i>کرج، گلشهر، بلوار گلزار غربی، خیابان یاس، ساختمان سینا، طبقه سوم، واحد شش</p>
                    <p><i class="bi bi-mailbox me-2"></i>کد پستی: ۳۱۹۸۷۱۷۸۱۵</p>
                </div>

                {{-- ستون ۲: تماس با ما --}}
                <div class="col-md-3">
                    <h5 class="fw-bold mb-3">تماس با ما</h5>
                    <ul class="list-unstyled ps-1 fs-6">
                        <li class="mb-5 mt-5"><i class="bi bi-telephone-fill me-2"></i>۰۲۶۳۳۵۰۸۰۱۸</li>
                        <li class="mb-5"><i class="bi bi-phone-fill me-2"></i>۰۹۱۰۶۸۷۱۱۸۵</li>
                        <li class="mb-5"><i class="bi bi-envelope-fill me-2"></i>
                            <a href="mailto:info@kanoonkoh.ir" class="text-info text-decoration-none">info@kanoonkoh.ir</a>
                        </li>
                        <li class="mb-5"><i class="bi bi-instagram me-2"></i>
                            <a href="https://instagram.com/kanoonkooh" class="text-info text-decoration-none" target="_blank">@kanoonkooh</a>
                        </li>
                        <li><i class="bi bi-telegram me-2"></i>
                            <a href="https://t.me/kanoonkoohgroup" class="text-info text-decoration-none" target="_blank">t.me/kanoonkoohgroup</a>
                        </li>
                    </ul>
                </div>

                {{-- ستون ۳: لینک‌ها --}}
                <div class="col-md-3 font-sahel">
                    <h5 class="fw-bold mb-3">لینک‌های مهم</h5>
                    <ul class="list-unstyled fs-6 ps-1">
                        <li class="mb-5 mt-5"><a href="{{ route('courses.index') }}" class="text-light text-decoration-none">آخرین دوره‌ها</a></li>
                        <li class="mb-5"><a href="{{ route('programs.index') }}" class="text-light text-decoration-none">آخرین برنامه‌ها</a></li>
                        <li class="mb-5"><a href="{{ route('reports.index') }}" class="text-light text-decoration-none">آخرین گزارش‌ها</a></li>
                        <li><a href="{{ route('conditions') }}" class="text-light text-decoration-none">شرایط عضویت</a></li>
                    </ul>
                </div>

                {{-- ستون ۴: درباره باشگاه --}}
                <div class="col-md-3">
                    <h5 class="fw-bold text-center mb-3">درباره باشگاه</h5>
                    <img src="{{ asset('images/logo-blue.png') }}" alt="کانون کوه" class="mb-3 d-block mx-auto" style="width: 100%;">
                    <p class="text-justify small" style="text-align: justify;">
                        در اواخر دهه ۸۰، جمعی از بازنشستگان علاقه‌مند به کوهنوردی گروهی منسجم تشکیل دادند که بعدها به باشگاه کوهنوردی کانون کوه تبدیل شد.
                        این باشگاه با برگزاری دوره‌ها و برنامه‌های منظم، به یکی از فعال‌ترین باشگاه‌های کوهنوردی، طبیعت‌گردی و حامی محیط‌زیست در البرز تبدیل شده است.
                    </p>
                </div>

            </div>

            <hr class="border-light my-4">

            {{-- سطر پایانی --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pb-3">
            <div>
                <span>© <span id="shamsi-year" class="persian-number"></span> تمامی حقوق برای باشگاه کانون کوه محفوظ است.</span>
            </div>
                <div class="text-center text-md-end">
                    طراحی شده با ❤️ توسط
                    <a href="https://linkedin.com/in/ramangorgin" target="_blank" class="text-info text-decoration-none fw-bold">رامان گرگین پاوه</a>
                </div>
            </div>
        </div>
    </footer>



        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js"></script>

        {{-- نقشه و تاریخ --}}
        <script>
            // سال شمسی به فارسی
            const date = new persianDate();
            document.getElementById('shamsi-year').innerText = date.year().toString().replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]);

            // نقشه
            var map = L.map('map').setView([35.8232941, 50.9331318], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            L.marker([35.8232941, 50.9331318]).addTo(map);
        </script>


        @stack('scripts')
    </body>
</html>
