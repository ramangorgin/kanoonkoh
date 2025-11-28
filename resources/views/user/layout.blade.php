<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'داشبورد')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Faveico meta tags -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Fonts -->
    <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- JalaliDatePicker -->
    <link rel="stylesheet" href="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
    <style>

        body {
            margin: 0;
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
    <nav class="navbar bg-white shadow-sm py-3 px-4">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap">

            <!-- لوگو (همیشه سمت راست) -->
            <a class="navbar-brand d-flex align-items-center order-1" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="کانون کوه" style="height: 65px;">
            </a>

            <!-- دکمه‌ها (همیشه سمت چپ) -->
            <div class="d-flex align-items-center gap-3 order-2">
                <!-- فقط در موبایل: دکمه تاگل سایدبار -->
                <button class="btn btn-secondary d-inline-block d-lg-none" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
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
        <a href="{{ route('dashboard.profile.show') }}" class="{{ request()->routeIs('dashboard.profile.show') ? 'active-link' : '' }}">
            <i class="bi bi-person-lines-fill me-2"></i> ویرایش مشخصات
        </a>

        <a href="{{ route('dashboard.medicalRecord.show') }}" class="{{ request()->routeIs('dashboard.medicalRecord.show') ? 'active-link' : '' }}">
            <i class="bi bi-clipboard2-pulse-fill me-2"></i>  پرونده پزشکی  
        </a>

        <a href="{{ route('dashboard.educationalHistory.index') }}" class="{{ request()->routeIs('dashboard.educationalHistory.index') ? 'active-link' : '' }}">
            <i class="bi bi-book-fill me-2"></i>  سوابق آموزشی
        </a>

        <a href="{{ route('dashboard.payments.index') }}" class="{{ request()->routeIs('dashboard.payments.index') ? 'active-link' : '' }}">
            <i class="bi bi-credit-card-2-front-fill me-2"></i> پرداخت‌ها
        </a>

        </a>
        <a href="{{ route('dashboard.settings') }}" class="{{ request()->routeIs('dashboard.settings') ? 'active-link' : '' }}">
            <i class="bi bi-gear-fill me-2"></i> تنظیمات
        </a>
        @auth
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit"
                    style="display:block; width:100%; text-align:right; padding:10px 15px; margin-bottom:10px; border-radius:5px; color:#212529; background-color:#f8f9fa; border:0; font-family: inherit;"
                    class="{{ request()->routeIs('logout') ? 'active-link' : '' }}">
                    <i class="bi bi-box-arrow-right me-2"></i> خروج
                </button>
            </form>
        @endauth
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



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script src="{{ asset('js/jalali-datepicker-init.js') }}"></script>
<script type="text/javascript" src="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"></script>


<script>AOS.init();</script>

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
