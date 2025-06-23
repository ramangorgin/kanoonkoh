@extends('user.layout')

@section('title', 'دوره‌های گذرانده‌شده')

@section('breadcrumb')
    <a href="{{ route('dashboard.index') }}">داشبورد</a> / <span>دوره‌های گذرانده‌شده</span>
@endsection

@section('content')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">دوره‌های گذرانده‌شده</h4>

    @if(auth()->user()->courseCertificates->isEmpty())
        <div class="alert alert-info">هنوز دوره‌ای برای شما ثبت نشده است.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ردیف</th>
                        <th>نام دوره</th>
                        <th>مدرس</th>
                        <th>تاریخ شروع</th>
                        <th>تاریخ پایان</th>
                        <th>مدرک</th>
                        <th>اطلاعیه دوره</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(auth()->user()->courseCertificates as $index => $cert)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $cert->course_name ?? '-' }}</td>
                            <td>{{ $cert->instructor ?? '-' }}</td>
                            <td>{{ $cert->start_date ? jdate($cert->start_date)->format('Y/m/d') : '-' }}</td>
                            <td>{{ $cert->end_date ? jdate($cert->end_date)->format('Y/m/d') : '-' }}</td>
                            <td>
                                @if($cert->certificate_path)
                                    <a href="{{ asset('storage/' . $cert->certificate_path) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                        دانلود
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($cert->course)
                                    <a href="{{ route('courses.show', $cert->course_id) }}" class="btn btn-sm btn-link text-info">
                                        مشاهده
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
