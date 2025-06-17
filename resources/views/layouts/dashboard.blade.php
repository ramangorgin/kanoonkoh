<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'داشبورد')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css" rel="stylesheet" >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />  
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/sahel-font@v3.4.0/dist/font-face.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css" rel="stylesheet">


    @stack('styles')
    <style>

        body {
            margin: 0;
            font-family: 'Sahel', sans-serif;
            direction: rtl;
            background-color: #f9f9f9;
        }

        .dashboard-container {
            display: flex;
            flex-direction: row; 
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #ffffff;
            border-left: 1px solid #ddd;
            padding: 25px 20px;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.05);
        }

        .sidebar a {
            display: block;
            padding: 10px 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            color: #212529;
            text-decoration: none;
            background-color: #f8f9fa;
            transition: background-color 0.2s ease;
        }

        .sidebar a:hover {
            background-color: #e9ecef;
        }

        .main-content {
            flex: 1;
            padding: 30px;
        }

        .breadcrumb {
            background-color: #e2e6ea;
            padding: 10px 20px;
            border-radius: 6px;
            margin-bottom: 25px;
            font-size: 0.9rem;
        }

        .breadcrumb a {
            color: #007bff;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .active-link {
            background-color: #e7f1ff;
            color: #0d6efd;
            font-weight: bold;
            border-radius: 5px;
        }
        #userpanel{
            background-color: #007bff;
            color: #ffffff;
            padding: 20px;
            justify-content: center;
            font-family: 'Vazirmatn', sans-serif;
            font-weight: 900;
        }
    </style>
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

                    <div class="d-none d-lg-flex order-3 align-items-center gap-3">
                        {{-- دکمه خروج --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button style="width: 100px;" id="exitButton" type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-box-arrow-left"></i> خروج
                            </button>
                        </form>
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

                     {{-- دکمه خروج در موبایل --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-box-arrow-left"></i> خروج از سیستم
                        </button>
                    </form>
                </div>
            </div>
        </header>

        
    <div class="dashboard-container">

        {{-- Sidebar --}}
        <aside class="sidebar">
            <h5 class="text-center" id="userpanel" >پنل کاربری</h5>
            <a href="{{ route('dashboard.index') }}"
            class="{{ request()->routeIs('dashboard.index') ? 'active-link' : '' }}">
            🏠 خانه داشبورد
            </a>

            <a href="{{ route('dashboard.profile') }}"
            class="{{ request()->routeIs('dashboard.profile') ? 'active-link' : '' }}">
            🧑 ویرایش مشخصات
            </a>

            <a href="{{ route('dashboard.insurance') }}"
            class="{{ request()->routeIs('dashboard.insurance') ? 'active-link' : '' }}">
            👥 بیمه ورزشی
            </a>

            <a href="{{ route('dashboard.payments') }}"
            class="{{ request()->routeIs('dashboard.payments') ? 'active-link' : '' }}">
            💳 پرداخت‌ها
            </a>

            <a href="{{ route('dashboard.programs') }}"
            class="{{ request()->routeIs('dashboard.programs') ? 'active-link' : '' }}">
            📅 برنامه‌ها
            </a>

            <a href="{{ route('dashboard.courses') }}"
            class="{{ request()->routeIs('dashboard.courses') ? 'active-link' : '' }}">
            📘 دوره‌ها
            </a>

            <a href="{{ route('dashboard.reports.index') }}"
            class="{{ request()->routeIs('dashboard.reports.*') ? 'active-link' : '' }}">
            📝 گزارش‌ها
            </a>

            <a href="{{ route('dashboard.settings') }}"
            class="{{ request()->routeIs('dashboard.settings') ? 'active-link' : '' }}">
            ⚙️ تنظیمات
            </a>

            <a href="{{ route('logout') }}">
                🚪 خروج
            </a>
        </aside>

        {{-- Main Content --}}
        <div class="main-content">
            {{-- Breadcrumb --}}
            @hasSection('breadcrumb')
                <div class="breadcrumb">
                    @yield('breadcrumb')
                </div>
            @endif

            {{-- Page Content --}}
            @yield('content')
        </div>
    </div>
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


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
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

