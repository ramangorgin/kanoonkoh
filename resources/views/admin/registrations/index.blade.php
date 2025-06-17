@extends('layouts.admin')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">ثبت‌نام‌ها</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container mt-4">

    <h4>برنامه‌ها</h4>
    <table class="table table-bordered table-striped table-sm mt-2">
        <thead class="thead-dark">
            <tr>
                <th>عنوان</th>
                <th>تاریخ شروع</th>
                <th>محل</th>
                <th>ظرفیت</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($programs as $program)
            <tr>
                <td>{{ $program->title }}</td>
                <td>{{ jdate($program->start_date)->format('Y/m/d') }}</td>
                <td>{{ $program->location }}</td>
                <td>{{ $program->capacity }}</td>
                <td>
                    <a href="{{ route('admin.registrations.show', ['type' => 'program', 'id' => $program->id]) }}" class="btn btn-outline-primary btn-sm">مشاهده ثبت‌نامی‌ها</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <h4 class="mt-5">دوره‌ها</h4>
    <table class="table table-bordered table-striped table-sm mt-2">
        <thead class="thead-dark">
            <tr>
                <th>عنوان</th>
                <th>تاریخ شروع</th>
                <th>مدرس</th>
                <th>ظرفیت</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $course)
            <tr>
                <td>{{ $course->title }}</td>
                <td>{{ jdate($course->start_date)->format('Y/m/d') }}</td>
                <td>{{ $course->instructor }}</td>
                <td>{{ $course->capacity }}</td>
                <td>
                    <a href="{{ route('admin.registrations.show', ['type' => 'course', 'id' => $course->id]) }}" class="btn btn-outline-primary btn-sm">مشاهده ثبت‌نامی‌ها</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
