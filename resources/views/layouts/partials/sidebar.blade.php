<div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse border-end" id="sidebarMenu">
    <div class="position-sticky pt-3 px-2">
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-2 mb-3 text-muted">
            <span>داشبورد کاربر</span>
        </h6>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard.index') }}">
                    پیشخوان
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard.profile') }}">
                    مشخصات عضویت
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard.membership') }}">
                    پرداخت حق عضویت
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard.insurance') }}">
                    بیمه ورزشی
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard.programs') }}">
                    برنامه‌های من
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard.courses') }}">
                    دوره‌های من
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard.reports') }}">
                    گزارش‌های من
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard.settings') }}">
                    تنظیمات
                </a>
            </li>
        </ul>
    </div>
</div>
