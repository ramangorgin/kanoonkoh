<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'داشبورد')</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/persian-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/leaflet.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Vazirmatn-font-face.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/Sahel-font-face.css') }}" type="text/css">
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

        #userpanel {
            background-color: #007bff;
            color: #ffffff;
            padding: 20px;
            justify-content: center;
            font-family: 'Vazirmatn', sans-serif;
            font-weight: 900;
        }

        @media (max-width: 991.98px) {
            .dashboard-container {
                flex-direction: column;
            }

            .sidebar {
                position: fixed;
                top: 0;
                right: -260px;
                width: 250px;
                height: 100%;
                z-index: 1050;
                background-color: #fff;
                transition: right 0.3s ease;
                box-shadow: -2px 0 8px rgba(0, 0, 0, 0.1);
            }

            .sidebar.show {
                right: 0;
            }

            .main-content {
                padding: 20px 15px;
            }

            .sidebar a {
                font-size: 0.95rem;
                padding: 8px 12px;
            }

            #userpanel {
                font-size: 1rem;
                padding: 15px;
            }
        }

        @media (max-width: 575.98px) {
            body {
                font-size: 14px;
            }

            .navbar-brand img {
                height: 45px;
            }

            .nav-link {
                font-size: 13pt !important;
            }

            .sidebar {
                width: 220px;
            }

            .main-content {
                padding: 15px;
            }

            .breadcrumb {
                padding: 8px 12px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
<header>
    <nav class="navbar bg-white shadow-sm py-3 px-4" style="font-family: 'Vazirmatn', sans-serif;">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap">

            <!-- لوگو (همیشه سمت راست) -->
            <a class="navbar-brand d-flex align-items-center order-1" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="کانون کوه" style="height: 65px;">
            </a>

            <!-- دکمه‌ها (همیشه سمت چپ) -->
            <div class="d-flex align-items-center gap-3 order-2 ms-auto">
                <!-- فقط در موبایل: دکمه تاگل سایدبار -->
                <button class="btn btn-primary d-inline-block d-lg-none" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>

                <!-- صفحه اصلی سایت -->
                <a href="{{ url('/') }}" class="btn btn-outline-primary d-none d-lg-inline-flex px-3">
                    <i class="bi bi-house-door-fill me-1"></i> صفحه اصلی
                </a>

                <!-- خروج از حساب -->
                <form method="POST" action="{{ route('logout') }}" class="d-none d-lg-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger px-3">
                        <i class="bi bi-box-arrow-right me-1"></i> خروج
                    </button>
                </form>
            </div>

        </div>
    </nav>
</header>

<div class="dashboard-container">
    <aside class="sidebar">
        <h5 class="text-center" id="userpanel">پنل کاربری</h5>
        <a href="{{ route('dashboard.index') }}" class="{{ request()->routeIs('dashboard.index') ? 'active-link' : '' }}">
            <i class="bi bi-house-door-fill me-2"></i> خانه داشبورد
        </a>
        <a href="{{ route('dashboard.profile') }}" class="{{ request()->routeIs('dashboard.profile') ? 'active-link' : '' }}">
            <i class="bi bi-person-lines-fill me-2"></i> ویرایش مشخصات
        </a>
        <a href="{{ route('dashboard.insurance') }}" class="{{ request()->routeIs('dashboard.insurance') ? 'active-link' : '' }}">
            <i class="bi bi-people-fill me-2"></i> بیمه ورزشی
        </a>
        <a href="{{ route('dashboard.payments') }}" class="{{ request()->routeIs('dashboard.payments') ? 'active-link' : '' }}">
            <i class="bi bi-credit-card-2-front-fill me-2"></i> پرداخت‌ها
        </a>
        <a href="{{ route('dashboard.programs') }}" class="{{ request()->routeIs('dashboard.programs') ? 'active-link' : '' }}">
            <i class="bi bi-calendar-event-fill me-2"></i> برنامه‌ها
        </a>
        <a href="{{ route('dashboard.courses') }}" class="{{ request()->routeIs('dashboard.courses') ? 'active-link' : '' }}">
            <i class="bi bi-book-fill me-2"></i> دوره‌ها
        </a>
        <a href="{{ route('dashboard.reports.index') }}" class="{{ request()->routeIs('dashboard.reports.*') ? 'active-link' : '' }}">
            <i class="bi bi-pencil-square me-2"></i> گزارش‌ها
        </a>
        <a href="{{ route('dashboard.settings') }}" class="{{ request()->routeIs('dashboard.settings') ? 'active-link' : '' }}">
            <i class="bi bi-gear-fill me-2"></i> تنظیمات
        </a>
    </aside>

    <div class="main-content">
        @hasSection('breadcrumb')
        <div class="breadcrumb">
            @yield('breadcrumb')
        </div>
        @endif
        @yield('content')
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        toggleButton.addEventListener('click', function () {
            sidebar.classList.toggle('show');
        });
    });
</script>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/persian-date.min.js') }}"></script>
<script src="{{ asset('js/persian-datepicker.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/leaflet.js') }}"></script>
<script src="{{ asset('js/ckeditor.js') }}"></script>



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
