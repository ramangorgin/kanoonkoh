@extends('layouts.admin')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">کاربران</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $user->email }}</li>
        </ol>
    </nav>
@endsection

@section('content')



    <h3 class="mb-3">مشخصات کامل کاربر</h3>

    <div class="card mb-4">
        <div class="card-header">اطلاعات کاربری</div>
        <div class="card-body">
            <p><strong>ایمیل:</strong> {{ $user->email }}</p>
            <p><strong>نقش:</strong> {{ $user->role === 'admin' ? 'ادمین' : 'کاربر عادی' }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">مشخصات فردی</div>
            <div class="col-md-9 ps-3 py-3">
                @if($user->profile?->personal_photo)
                    <a href="{{ asset('storage/' . $user->profile->personal_photo) }}" target="_blank">
                        <img src="{{ asset('storage/' . $user->profile->personal_photo) }}" class="img-thumbnail">
                    </a>
                @else
                    <p class="text-muted">تصویری ثبت نشده است</p>
                @endif
            </div>
            <div class="ps-3">
                <p><strong>نام:</strong> {{ $user->profile->first_name ?? '---'}}</p>
                <p><strong>نام خانوادگی:</strong> {{ $user->profile->last_name ?? '---'}}</p>
                <p><strong>نام پدر:</strong> {{ $user->profile->father_name ?? '---'}}</p>
                <p><strong>کد ملی:</strong> {{ $user->profile->national_id ?? '---'}}</p>
                <p><strong>تاریخ تولد:</strong> {{ $user->profile->birth_date ?? '---'}}</p>
                <p><strong>جنسیت:</strong> {{ $user->profile->gender ?? '---'}}</p>
                <p><strong>شماره تماس:</strong> {{ $user->profile->phone ?? '---'}}</p>
                <p><strong>تلفن اضطراری:</strong> {{ $user->profile->emergency_phone ?? '---'}}</p>
                <p><strong>استان:</strong> {{ $user->profile->province ?? '---'}}</p>
                <p><strong>شهر:</strong> {{ $user->profile->city ?? '---'}}</p>
                <p><strong>آدرس:</strong> {{ $user->profile->address ?? '---'}}</p>
                <p><strong>کد پستی:</strong> {{ $user->profile->postal_code ?? '---'}}</p>
                <p><strong>تاریخ عضویت:</strong> {{ $user->profile->membership_date ?? '---'}}</p>
                <p><strong>سطح عضویت:</strong> {{ $user->profile->membership_level ?? '---'}}</p>
                <p><strong>وضعیت عضویت:</strong> {{ $user->profile->membership_status ?? '---'}}</p>
                <p><strong>امتیاز:</strong> {{ $user->profile->points ?? '---'}}</p>
                <p><strong>قد:</strong> {{ $user->profile->height_cm ?? '---'}} cm</p>
                <p><strong>وزن:</strong> {{ $user->profile->weight_kg ?? '---'}} kg</p>
                <p><strong>بیماری‌ها:</strong> {{ $user->profile->medical_conditions ?? '---'}}</p>
                <p><strong>آلرژی‌ها:</strong> {{ $user->profile->allergies ?? '---'}}</p>
                <p><strong>شغل:</strong> {{ $user->profile->job ?? '---'}}</p>
                <p><strong>معرف:</strong> {{ $user->profile->referrer ?? '---'}}</p>
                <p><strong>گروه خونی:</strong> {{ $user->profile->blood_type ?? '---'}}</p>
                <p><strong>سابقه عمل جراحی:</strong> {{ $user->profile->had_surgery ?? '---'}}</p>
                <p><strong>داروهای مصرفی:</strong> {{ $user->profile->medications ?? '---'}}</p>
                <p><strong>نام تماس اضطراری:</strong> {{ $user->profile->emergency_contact_name ?? '---'}}</p>
                <p><strong>نسبت تماس اضطراری:</strong> {{ $user->profile->emergency_contact_relation ?? '---'}}</p>
            </div>
        </div>
    </div>
  

    <div class="container-fluid px-4 mx-5">

    {{-- بیمه ورزشی --}}
    <div class="card mb-4" style="max-width: 900px; margin: auto;">
        <div class="card-header fw-bold">بیمه ورزشی</div>
        <div class="card-body">
            @if($user->insurance)
                <p><strong>کد بیمه:</strong> {{ $user->insurance->code ?? '---' }}</p>
                <p><strong>تاریخ شروع:</strong> {{ $user->insurance->start_date ? \Morilog\Jalali\Jalalian::fromDateTime($user->insurance->start_date)->format('Y/m/d') : '---' }}</p>
                <p><strong>تاریخ پایان:</strong> {{ $user->insurance->end_date ? \Morilog\Jalali\Jalalian::fromDateTime($user->insurance->end_date)->format('Y/m/d') : '---' }}</p>
            @else
                <p class="text-muted">اطلاعات بیمه ثبت نشده است.</p>
            @endif
        </div>
    </div>

    {{-- سوابق پرداخت --}}
    <div class="card mb-4" style="max-width: 900px; margin: auto;">
        <div class="card-header fw-bold">سوابق پرداخت</div>
        <div class="card-body">
            @if($user->payments && $user->payments->count())
                <ul class="list-group">
                    @foreach($user->payments as $payment)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $payment->type === 'program' ? 'برنامه' : ($payment->type === 'course' ? 'دوره' : 'عضویت') }}</span>
                            <span>{{ \Morilog\Jalali\Jalalian::fromDateTime($payment->date)->format('Y/m/d') }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">هیچ پرداختی ثبت نشده است.</p>
            @endif
        </div>
    </div>

</div>


@endsection


@push('styles')
<style>
    .user-info-box p {
        margin-bottom: 0.5rem;
        padding-right: 0.5rem;
    }
</style>
@endpush
