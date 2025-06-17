@extends('layouts.dashboard')

@section('title', 'برنامه‌های گذرانده شده')

@section('breadcrumb')
    <a href="{{ route('dashboard.index') }}">داشبورد</a> / <span>برنامه‌های گذرانده‌شده</span>
@endsection

@section('content')

<h5 class="mb-4">برنامه‌های گذرانده‌شده</h5>

@if(isset($completedPrograms) && count($completedPrograms))
    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>نام برنامه</th>
                    <th>تاریخ شروع</th>
                    <th>تاریخ پایان</th>
                    <th>سرپرست</th>
                    <th>کمک‌سرپرست</th>
                    <th>مسئول فنی</th>
                    <th>پشتیبان</th>
                    <th>راهنما</th>
                    <th>لینک اطلاعیه</th>
                </tr>
            </thead>
            <tbody>
                @foreach($completedPrograms as $program)
                    <tr>
                        <td>{{ $program->title }}</td>
                        <td>{{ jdate($program->start_date)->format('Y/m/d') }}</td>
                        <td>{{ jdate($program->end_date)->format('Y/m/d') }}</td>
                        <td>{{ $program->leader_name ?? '—' }}</td>
                        <td>{{ $program->assistant_leader_name ?? '—' }}</td>
                        <td>{{ $program->technical_manager_name ?? '—' }}</td>
                        <td>{{ $program->support_name ?? '—' }}</td>
                        <td>{{ $program->guide_name ?? '—' }}</td>
                        <td>
                            @if($program->program_id)
                                <a href="{{ route('programs.show', $program->program_id) }}" target="_blank" class="btn btn-sm btn-primary">مشاهده</a>
                            @else
                                <span class="text-muted">ندارد</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-info">هیچ برنامه‌ای ثبت نشده است.</div>
@endif

@endsection
