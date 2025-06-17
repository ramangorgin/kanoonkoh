@extends('layouts.admin')

@section('title', 'داشبورد مدیریت')

@section('content')
<div class="container-fluid mt-4" style="width: 100%;">
    <div class="row">

        {{-- Main content --}}
        <div class="col-md-9">
            <div class="alert alert-primary" role="alert">
                خوش آمدید {{ auth()->user()->name }}، اینجا داشبورد مدیریت شماست.
            </div>

            <div class="row text-center mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm border-left-primary">
                        <div class="card-body">
                            <h5 class="card-title">کاربران</h5>
                            <p class="card-text display-6">{{ $usersCount }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-left-info">
                        <div class="card-body">
                            <h5 class="card-title">دوره‌ها</h5>
                            <p class="card-text display-6">{{ $coursesCount }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-left-success">
                        <div class="card-body">
                            <h5 class="card-title">برنامه‌ها</h5>
                            <p class="card-text display-6">{{ $programsCount }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-left-warning">
                        <div class="card-body">
                            <h5 class="card-title">گزارش‌ها</h5>
                            <p class="card-text display-6">{{ $reportsCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row mt-4">
        {{-- آخرین پرداختی‌ها --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header fw-bold">آخرین پرداختی‌ها</div>
                <div class="card-body p-2">
                    <ul class="list-group list-group-flush">
                        @foreach($latestPayments as $payment)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $payment->user?->full_name ?? '---' }}</span>
                                <span>{{ jdate($payment->created_at)->format('Y/m/d') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <div class="row mt-3">
        {{-- ثبت‌نام‌های جدید برنامه --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header fw-bold">ثبت‌نام‌های جدید برنامه</div>
                <div class="card-body p-2">
                    <ul class="list-group list-group-flush">
                    @foreach($latestProgramRegs as $reg)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $reg->user?->full_name ?? '---' }}</span>
                            <span>{{ $reg->relatedProgram?->title ?? '---' }}</span>
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- ثبت‌نام‌های جدید دوره --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header fw-bold">ثبت‌نام‌های جدید دوره</div>
                <div class="card-body p-2">
                    <ul class="list-group list-group-flush">
                    @foreach($latestCourseRegs as $reg)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $reg->user?->full_name ?? '---' }}</span>
                            <span>{{ $reg->relatedCourse?->title ?? '---' }}</span>
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    </div>
</div>
@endsection
