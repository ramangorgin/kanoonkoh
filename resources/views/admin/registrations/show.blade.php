@php
    use Morilog\Jalali\Jalalian;
    use Carbon\Carbon;

    // نمایش امن تاریخ شمسی
    $showJalaliDate = function ($date) {
        if (!$date) return '—';
        try {
            $c = $date instanceof Carbon ? $date : Carbon::parse($date);
            return Jalalian::fromCarbon($c)->format('Y/m/d');
        } catch (\Throwable $e) {
            return '—';
        }
    };

    // لیبل فارسی محل سوارشو
    $pickupLabel = function ($v) {
        return $v === 'tehran' ? 'تهران' : ($v === 'karaj' ? 'کرج' : '—');
    };
@endphp


@extends('admin.layout')

@section('content')

<div class="container-fluid py-3">

    {{-- پیام‌های فلش --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- سربرگ و اکشن‌ها --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">مدیریت ثبت‌نام‌ها</h4>
        <div class="d-flex gap-2">
            {{-- خروجی اکسل با حفظ فیلترها --}}
            <a
                href="{{ route('admin.registrations.export', array_merge(['type' => $type, 'id' => $model->id], request()->query())) }}"
                class="btn btn-outline-success">
                خروجی Excel
            </a>

        </div>
    </div>


    {{-- جدول نتایج --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>شناسه مرتبط</th>
                            <th>کاربر/مهمان</th>
                            <th>محل سوارشو</th>
                            <th>تاریخ پرداخت</th>
                            <th>کد تراکنش</th>
                            <th>رسید</th>
                            <th>بیمه مهمان</th>
                            <th>وضعیت</th>
                            <th style="min-width:140px;">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $i => $registration)
                            <tr>
                                <td>{{ method_exists($registrations,'firstItem') ? $registrations->firstItem() + $i : ($i+1) }}</td>

                                <td>{{ $registration->related_id }}</td>

                                <td>
                                    @if($registration->user_id && optional($registration->user)->email)
                                        <div class="small text-muted">عضو</div>
                                        <div>
                                            {{ optional($registration->user->profile)->first_name ?? '' }}
                                            {{ optional($registration->user->profile)->last_name ?? '' }}
                                        </div>
                                    @else
                                        <div class="small text-muted">مهمان</div>
                                        <div>{{ $registration->guest_name ?? '—' }}</div>
                                        <div class="text-muted">{{ $registration->guest_phone ?? '—' }}</div>
                                        <div class="text-muted">کدملی: {{ $registration->guest_national_id ?? '—' }}</div>
                                    @endif
                                </td>


                                <td>
                                    @if($registration->pickup_location === 'tehran')
                                        تهران
                                    @elseif($registration->pickup_location === 'karaj')
                                        کرج
                                    @else
                                        ---
                                    @endif
                                </td>


                                <td>{{ $showJalaliDate($registration->payment_date) }}</td>

                                <td>{{ $registration->transaction_code ?? '—' }}</td>

                                <td>
                                    @if($registration->receipt_file)
                                        <a href="{{ asset('storage/'.$registration->receipt_file) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            دانلود
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>
                                    @if($registration->guest_insurance_file)
                                        <a href="{{ asset('storage/'.$registration->guest_insurance_file) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            دانلود
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>
                                    @if($registration->approved)
                                        <span class="badge bg-success">تأیید شده</span>
                                    @else
                                        <span class="badge bg-secondary">در انتظار/رد</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex gap-2">
                                        {{-- تأیید --}}
                                        <form action="{{ route('admin.registrations.approve', $registration->id) }}" method="POST" onsubmit="return confirm('این ثبت‌نام تأیید شود؟');">
                                            @csrf
                                            <button class="btn btn-sm btn-success">تأیید</button>
                                        </form>

                                        {{-- رد --}}
                                        <form action="{{ route('admin.registrations.reject', $registration->id) }}" method="POST" onsubmit="return confirm('این ثبت‌نام رد شود؟');">
                                            @csrf
                                            <button class="btn btn-sm btn-danger">رد</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted">موردی یافت نشد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- صفحه‌بندی (اگر Paginator باشد) --}}
            @if(method_exists($registrations, 'links'))
                <div class="mt-3">
                    {{ $registrations->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
    {{-- فعال‌سازی تقویم شمسی برای فیلترها (در صورت لود بودن persianDatepicker در layout) --}}
    <script>
        (function(){
            function initPicker(id){
                if (typeof $ === 'undefined' || typeof $.fn.persianDatepicker === 'undefined') return;
                $('#' + id).persianDatepicker({
                    format: 'YYYY/MM/DD',
                    autoClose: true,
                    observer: true,
                    initialValue: false,
                    calendar: { persian: { locale: 'fa' } }
                });
            }
            initPicker('from');
            initPicker('to');
        })();
    </script>
@endsection
