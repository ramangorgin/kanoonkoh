<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('admin.index') }}">
    <i class="fas fa-tools me-1"></i> داشبورد مدیریت
    </a>

    <div class="collapse navbar-collapse justify-content-end">
    <ul class="navbar-nav">
    <li class="nav-item me-3">
    <span class="nav-link">
    <i class="fas fa-user-circle"></i>
    {{ auth()->user()->name }}
    </span>
    </li>
    <li class="nav-item me-3">
    <a class="nav-link" href="{{ route('dashboard.index') }}">
    <i class="fas fa-arrow-left"></i> بازگشت به داشبورد کاربر
    </a>
    </li>
    <li class="nav-item">
    <form action="{{ route('logout') }}" method="POST" class="d-inline">
    @csrf
    <button class="btn btn-sm btn-outline-danger">
    <i class="fas fa-sign-out-alt"></i> خروج
    </button>
    </form>
    </li>
    </ul>
    </div>
    </div>
</nav>
