<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'پنل مدیریت')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    {{-- SweetAlert & Animate --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    {{-- DataTables (برای جستجو و خروجی Excel) --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    {{-- فونت فارسی --}}
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css"/>

    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
            background-color: #f5f7fa;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #007bff 0%, #0056b3 100%);
            color: white;
            min-height: 100vh;
            position: fixed;
            top: 0;
            right: 0;
            overflow-y: auto;
            transition: all 0.3s;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            transition: background 0.3s;
            border-radius: 6px;
        }

        .sidebar a:hover, .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .sidebar .menu-header {
            font-weight: bold;
            font-size: 0.9rem;
            opacity: 0.8;
            padding: 10px 20px;
        }

        .topbar {
            position: fixed;
            top: 0;
            right: 250px;
            left: 0;
            background-color: #fff;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            z-index: 1000;
            transition: right 0.3s;
        }

        .main-content {
            margin-right: 250px;
            margin-top: 80px;
            padding: 20px;
            transition: all 0.3s;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                right: -250px;
            }
            .sidebar.show {
                right: 0;
            }
            .topbar {
                right: 0;
            }
            .main-content {
                margin-right: 0;
            }
        }

        .sidebar-toggle {
            display: none;
        }

        @media (max-width: 991.98px) {
            .sidebar-toggle {
                display: inline-block;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- Sidebar --}}
    <nav class="sidebar animate__animated animate__fadeInRight">
        <div class="p-3 text-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 60px;">
            <h5 class="mt-2">پنل مدیریت</h5>
        </div>

        <div class="menu">
            <div class="menu-header">مدیریت کاربران</div>
            <a href="#userSubmenu" data-bs-toggle="collapse" class="d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people-fill me-2"></i> کاربران</span>
                <i class="bi bi-chevron-down"></i>
            </a>
            <div class="collapse" id="userSubmenu">
                <a href="{{ route('admin.users.index') }}" class="ms-3"><i class="bi bi-list-ul me-2"></i> لیست کاربران</a>
                <a href="{{ route('admin.users.create') }}" class="ms-3"><i class="bi bi-person-plus-fill me-2"></i> افزودن کاربر جدید</a>
                <a href="{{ route('admin.memberships.pending') }}" class="ms-3"><i class="bi bi-person-check-fill me-2"></i> تایید عضویت‌ها</a>
            </div>

            <div class="menu-header mt-4">مدیریت پرداخت‌ها</div>
            <a href="{{ route('admin.payments.index') }}"><i class="bi bi-credit-card-2-front-fill me-2"></i> لیست پرداخت‌ها</a>
        </div>
    </nav>

    {{-- Topbar --}}
    <header class="topbar animate__animated animate__fadeInDown">
        <div class="d-flex align-items-center">
            <button class="btn btn-outline-primary sidebar-toggle me-3" id="sidebarToggle"><i class="bi bi-list"></i></button>
            <h5 class="m-0 fw-bold"><i class="bi bi-speedometer2 me-2"></i> داشبورد مدیریت</h5>
        </div>

        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-light position-relative">
                <i class="bi bi-bell-fill text-warning fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
            </button>
            <a href="#" class="btn">
                <i class="bi bi-person-circle fs-4"></i>
            </a>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.querySelector('.sidebar');
            const toggle = document.getElementById('sidebarToggle');

            toggle?.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        });
    </script>

    @stack('scripts')

</body>
</html>
