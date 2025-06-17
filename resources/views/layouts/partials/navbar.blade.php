<nav class="navbar navbar-light bg-white shadow-sm px-3 py-2 d-flex justify-content-between align-items-center">
    <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="d-flex align-items-center">
        <span class="me-2">{{ auth()->user()->name ?? 'کاربر' }}</span>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button class="btn btn-sm btn-outline-danger">خروج</button>
        </form>
    </div>
</nav>
