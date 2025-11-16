@extends('admin.layout')

@section('title', 'داشبورد مدیریت')

@section('content')

<div class="container-fluid py-4 animate__animated animate__fadeIn">

    {{-- عنوان صفحه --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold"><i class="bi bi-speedometer2 text-primary me-2"></i> داشبورد مدیریت</h4>
        <span class="text-muted">{{ toPersianNumber(jdate()->format('Y/m/d')) }}</span>
    </div>

    {{-- کارت‌های آماری بالا --}}
    <div class="row g-4 mb-4">
        {{-- کاربران --}}
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 animate__animated animate__fadeInUp" style="border-top:4px solid #0d6efd;">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">تعداد کاربران</h6>
                        <h4 class="fw-bold mt-1">{{ toPersianNumber($stats['users'] ?? 0) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- عضویت‌های در انتظار --}}
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 animate__animated animate__fadeInUp" style="border-top:4px solid #ffc107;">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3 me-3">
                        <i class="bi bi-person-check-fill fs-3"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">عضویت‌های در انتظار</h6>
                        <h4 class="fw-bold mt-1">{{ toPersianNumber($stats['pending_memberships'] ?? 0) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- پرداخت‌های تایید شده --}}
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 animate__animated animate__fadeInUp" style="border-top:4px solid #198754;">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                        <i class="bi bi-credit-card-fill fs-3"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">پرداخت‌های تایید شده</h6>
                        <h4 class="fw-bold mt-1">{{ toPersianNumber($stats['approved_payments'] ?? 0) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- کل پرداخت‌ها --}}
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 animate__animated animate__fadeInUp" style="border-top:4px solid #0dcaf0;">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 text-info rounded-circle p-3 me-3">
                        <i class="bi bi-cash-stack fs-3"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">کل پرداخت‌ها (تومان)</h6>
                        <h5 class="fw-bold mt-1">{{ toPersianNumber(number_format($stats['total_amount'] ?? 0)) }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- نمودار پرداخت‌ها --}}
    <div class="row mb-5">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm animate__animated animate__fadeInUp">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="bi bi-bar-chart-line text-primary me-2"></i> روند پرداخت‌ها (ماهانه)</h6>
                </div>
                <div class="card-body">
                    <canvas id="paymentsChart" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- پرداخت‌های اخیر --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm animate__animated animate__fadeInUp">
                <div class="card-header bg-white border-0">
                    <h6 class="fw-bold mb-0"><i class="bi bi-clock-history text-secondary me-2"></i> پرداخت‌های اخیر</h6>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($latestPayments as $p)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-credit-card-2-front text-success me-2"></i>
                                <span class="fw-bold">{{ toPersianNumber(number_format($p->amount)) }} تومان</span>
                                <div class="text-muted small mt-1">{{ $p->user->profile->first_name ?? '---' }} {{ $p->user->profile->last_name ?? '' }}</div>
                            </div>
                            <span class="badge bg-light text-dark">{{ toPersianNumber(jdate($p->created_at)->format('m/d')) }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted">پرداختی ثبت نشده است</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- آمار کاربران --}}
    <div class="card border-0 shadow-sm animate__animated animate__fadeInUp">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-people text-primary me-2"></i> کاربران فعال اخیر</h6>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-list"></i> مشاهده همه
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>نام</th>
                        <th>شماره تماس</th>
                        <th>وضعیت عضویت</th>
                        <th>تاریخ عضویت</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestUsers as $u)
                        <tr>
                            <td>{{ $u->profile->first_name ?? '' }} {{ $u->profile->last_name ?? '' }}</td>
                            <td>{{ toPersianNumber($u->phone) }}</td>
                            <td>
                                @if($u->profile->membership_status == 'approved')
                                    <span class="badge bg-success">تایید شده</span>
                                @elseif($u->profile->membership_status == 'pending')
                                    <span class="badge bg-warning text-dark">در انتظار</span>
                                @else
                                    <span class="badge bg-danger">رد شده</span>
                                @endif
                            </td>
                            <td>{{ toPersianNumber(jdate($u->created_at)->format('Y/m/d')) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">کاربری یافت نشد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('paymentsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chart['months'] ?? []) !!},
            datasets: [{
                label: 'مبلغ پرداخت‌ها (تومان)',
                data: {!! json_encode($chart['values'] ?? []) !!},
                backgroundColor: 'rgba(13, 110, 253, 0.5)',
                borderColor: '#0d6efd',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
@endpush
