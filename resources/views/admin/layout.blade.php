<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/persian-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/leaflet.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Vazirmatn-font-face.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/Sahel-font-face.css') }}" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/sahel-font@v3.4.0/dist/font-face.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">


<style>
    body {
        direction: rtl;
        font-family: 'Vazirmatn', sans-serif;
        background-color: #f9f9f9;
    }
    .sidebar {
        height: 100vh;
        background: linear-gradient(180deg, #ffffff 0%, #f9f9fb 100%);
        border-left: 1px solid #dee2e6;
        box-shadow: -2px 0 5px rgba(0, 0, 0, 0.05);
        position: fixed;
        right: 0;
        top: 56px;
        width: 240px;
        overflow-y: auto;
        transition: all 0.3s;
    }

    .sidebar h5 {
        padding: 16px 20px;
        font-weight: bold;
        color: #333;
        border-bottom: 1px solid #e0e0e0;
        background-color: #f2f2f2;
    }

    .sidebar a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 20px;
        font-size: 14px;
        color: #333;
        transition: 0.2s ease-in-out;
    }

    .sidebar a:hover,
    .sidebar a.active {
        background-color: #eef4ff;
        font-weight: bold;
        color: #0056b3;
        border-right: 4px solid #0d6efd;
    }

    main {
        padding: 30px 40px;
        min-height: 100vh;
        margin-top: 56px;
        margin-right: 240px;
        width: 100%;
    }


    .top-bar {
        height: 63px;
        background-color: #fff;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
        position: fixed;
        right: 0;
        left: 0;
        z-index: 1030;
    }

    .top-bar a {
        margin-right: 10px;
    }

    .sidebar .nav-link {
        color: #333;
        transition: 0.2s ease-in-out;
        font-size: 15px;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background-color: #e7f1ff;
        color: #0d6efd;
        font-weight: bold;
    }
</style>

    @stack('styles')
</head>

<body>
    <!-- Top Navbar -->
    <div class="top-bar">
    <div>
        <strong>داشبورد مدیریت</strong>
    </div>
    <div>
        <a href="{{ route('dashboard.index') }}" class="btn btn-sm btn-light border"><i class="bi bi-arrow-return-right"></i>بازگشت به داشبورد کاربر</a>
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button class="btn btn-sm btn-danger" type="submit"><i class="bi bi-box-arrow-right"></i>خروج</button>
        </form>
    </div>
    </div>

    <div class="d-flex">
        {{-- Sidebar --}}
        <div class="sidebar bg-light border-start shadow-sm" style="width: 240px; min-height: 100vh; position: fixed; top: 0; right: 0; z-index: 1030;">
            <div class="p-3 border-bottom fw-bold fs-5 text-center bg-white">
                <i class="bi bi-speedometer2 me-1"></i> داشبورد مدیریت
            </div>

            <ul class="nav flex-column mt-3">
                <li class="nav-item"><a href="{{ route('admin.index') }}" class="nav-link"><i class="bi bi-house-door me-2"></i> داشبورد</a></li>
                <li class="nav-item"><a href="{{ route('admin.users.index') }}" class="nav-link"><i class="bi bi-people-fill me-2"></i> مدیریت کاربران</a></li>
                <li class="nav-item"><a href="{{ route('admin.courses.index') }}" class="nav-link"><i class="bi bi-journal-bookmark-fill me-2"></i> مدیریت دوره‌ها</a></li>
                <li class="nav-item"><a href="{{ route('admin.programs.index') }}" class="nav-link"><i class="bi bi-calendar-event-fill me-2"></i> مدیریت برنامه‌ها</a></li>
                <li class="nav-item"><a href="{{ route('admin.reports.index') }}" class="nav-link"><i class="bi bi-clipboard-data-fill me-2"></i> مدیریت گزارش‌ها</a></li>
                <li class="nav-item"><a href="{{ route('admin.registrations.index') }}" class="nav-link"><i class="bi bi-person-check-fill me-2"></i> ثبت‌نام‌ها</a></li>
                <li class="nav-item"><a href="{{ route('admin.insurances.index') }}" class="nav-link"><i class="bi bi-shield-check me-2"></i> بیمه‌ها</a></li>
                <li class="nav-item"><a href="{{ route('admin.payments.index') }}" class="nav-link"><i class="bi bi-credit-card-2-front-fill me-2"></i> پرداخت‌ها</a></li>
                <li class="nav-item"><a href="{{ route('admin.surveys.stats')}}" class="nav-link"><i class="bi bi-bar-chart-line-fill me-2"></i> آمار نظرسنجی‌ها</a></li>
            </ul>
        </div>

        {{-- Main content --}}
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <!-- ✅ jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        window.jQuery || document.write('<script src="{{ asset('js/jquery.min.js') }}"><\/script>');
    </script>

    <!-- ✅ Persian Date -->
    <script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js"></script>
    <script>
        window.persianDate || document.write('<script src="{{ asset('js/persian-date.min.js') }}"><\/script>');
    </script>

    <!-- ✅ Persian Datepicker -->
    <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
    <script>
        window.jQuery.fn.persianDatepicker || document.write('<script src="{{ asset('js/persian-datepicker.min.js') }}"><\/script>');
    </script>

    <!-- ✅ Bootstrap (bundle includes Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        typeof bootstrap === 'undefined' && document.write('<script src="{{ asset('js/bootstrap.bundle.min.js') }}"><\/script>');
    </script>

    <!-- ✅ Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        typeof $.fn.select2 === 'undefined' && document.write('<script src="{{ asset('js/select2.min.js') }}"><\/script>');
    </script>

    <!-- ✅ Leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        typeof L === 'undefined' && document.write('<script src="{{ asset('js/leaflet.js') }}"><\/script>');
    </script>

    <!-- ✅ CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
    <script>
        typeof ClassicEditor === 'undefined' && document.write('<script src="{{ asset('js/ckeditor.js') }}"><\/script>');
    </script>
        
    @stack('scripts')
    </body>
</html>








