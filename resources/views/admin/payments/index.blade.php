@extends('layouts.admin')


@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">پرداخت‌ها</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container">
    <h3 class="mb-4">مدیریت پرداخت‌ها</h3>

    <table id="paymentsTable" class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>نام کاربر</th>
                <th>نوع پرداخت</th>
                <th>موضوع مرتبط</th>
                <th>تاریخ پرداخت</th>
                <th>کد پیگیری</th>
                <th>فایل رسید</th>
                <th>وضعیت</th>
                <th>تاریخ ثبت</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->user?->full_name ?? '---' }}</td>
                    <td>
                        @if($payment->type === 'program')
                            برنامه
                        @elseif($payment->type === 'course')
                            دوره
                        @elseif($payment->type === 'membership')
                            حق عضویت
                        @else
                            ---
                        @endif
                    </td>
                    <td>
                        @if($payment->type === 'program')
                            {{ $payment->relatedProgram?->title ?? '---' }}
                        @elseif($payment->type === 'course')
                            {{ $payment->relatedCourse?->title ?? '---' }}
                        @elseif($payment->type === 'membership')
                            {{ $payment->date ?? 'حق عضویت' }}
                        @else
                            ---
                        @endif
                    </td>

                    <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($payment->date)->format('Y/m/d') }}</td>
                    <td>{{ $payment->transaction_code }}</td>
                    <td>
                        @if($payment->receipt_file)
                            <a href="{{ asset('storage/' . $payment->receipt_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">مشاهده</a>
                        @else
                            ---
                        @endif
                    </td>
                    <td>
                        @if($payment->approved === true)
                            <span class="badge bg-success">تایید شده</span>
                        @elseif($payment->approved === false)
                            <span class="badge bg-warning text-dark">در انتظار بررسی</span>
                        @else
                            <span class="badge bg-danger">رد شده</span>
                        @endif
                    </td>
                    <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($payment->created_at)->format('Y/m/d') }}</td>
                    <td>
                        @if(is_null($payment->approved) || $payment->approved === false)
                            <form method="POST" action="{{ route('admin.payments.approve', $payment->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success">تایید</button>
                            </form>
                        @endif

                        @if($payment->approved !== false)
                            <form method="POST" action="{{ route('admin.payments.reject', $payment->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-danger">رد</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#paymentsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fa.json'
            }
        });
    });
</script>
@endpush
