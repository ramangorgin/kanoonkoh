@extends('layouts.app')

@section('title', 'ویرایش گزارش')


@section('breadcrumb')
    <a href="{{ route('dashboard.index') }}">داشبورد</a> /
    <a href="{{ route('dashboard.reports.index') }}">گزارش‌ها</a> /
    <span>ویرایش گزارش</span>
@endsection

@section('content')
<div class="px-5 py-5">

<h5 class="mb-3">ویرایش گزارش</h5>

<form action="{{ route('dashboard.reports.update', $report->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- نوع برنامه --}}
    <div class="mb-3">
        <label>نوع برنامه</label>
        <select name="type" class="form-select" required>
            <option value="کوهنوردی" @selected($report->type == 'کوهنوردی')>کوهنوردی</option>
            <option value="طبیعت‌گردی" @selected($report->type == 'طبیعت‌گردی')>طبیعت‌گردی</option>
            <option value="فرهنگی" @selected($report->type == 'فرهنگی')>فرهنگی</option>
        </select>
    </div>

    {{-- برنامه مرتبط --}}
    <div class="mb-3">
        <label>برنامه</label>
        <select name="program_id" class="form-select">
            @foreach(App\Models\Program::all() as $program)
                <option value="{{ $program->id }}" @selected($report->program_id == $program->id)>
                    {{ $program->title }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- عنوان --}}
    <div class="mb-3">
        <label>عنوان</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $report->title) }}" required>
    </div>

    {{-- تاریخ شروع و پایان --}}
    <div class="row mb-3">
        <div class="col">
            <label>تاریخ شروع</label>
            <input type="text" name="start_date" class="form-control datepicker" value="{{ \Morilog\Jalali\Jalalian::fromDateTime($report->start_date)->format('Y/m/d') }}">
        </div>
        <div class="col">
            <label>تاریخ پایان</label>
            <input type="text" name="end_date" class="form-control datepicker" value="{{ \Morilog\Jalali\Jalalian::fromDateTime($report->end_date)->format('Y/m/d') }}">
        </div>
    </div>

    {{-- مسئولان --}}
    @foreach (['leader', 'assistant_leader', 'technical_manager', 'support', 'guide'] as $role)
        <div class="mb-3">
            <label>{{ __("انتخاب " . __("roles.$role")) }}</label>
            <select name="{{ $role }}_id" class="form-select">
                <option value="">-- انتخاب کنید --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected($report->{$role . '_id'} == $user->id)>
                        {{ $user->first_name }} {{ $user->last_name }}
                    </option>
                @endforeach
            </select>
        </div>
    @endforeach

    {{-- بقیه فیلدها --}}
    @foreach([
        'area', 'peak_name', 'peak_height', 'start_altitude',
        'duration', 'technical_level', 'road_type', 'difficulty',
        'slope_angle', 'average_backpack_weight', 'natural_description',
        'weather', 'wind_speed', 'temperature', 'vegetation', 'wildlife',
        'local_language', 'historical_sites', 'important_notes', 'food_availability'
    ] as $field)
        <div class="mb-3">
            <label>{{ __("fields.$field") }}</label>
            <input type="text" name="{{ $field }}" class="form-control" value="{{ old($field, $report->$field) }}">
        </div>
    @endforeach

    {{-- مقادیر boolean --}}
    @foreach([
        'signal_status' => 'آنتن‌دهی',
        'has_stone_climbing' => 'سنگ‌نوردی',
        'has_ice_climbing' => 'یخ‌نوردی',
    ] as $field => $label)
        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="{{ $field }}" value="1" @checked($report->$field)>
            <label class="form-check-label">{{ $label }}</label>
        </div>
    @endforeach

    {{-- فایل PDF جدید --}}
    <div class="mb-3">
        <label>فایل PDF گزارش (اختیاری)</label>
        <input type="file" name="pdf_file" class="form-control">
    </div>

    {{-- فایل کروکی مسیر --}}
    <div class="mb-3">
        <label>فایل مسیر (GPX/KML/KMZ)</label>
        <input type="file" name="track_file" class="form-control">
    </div>

    {{-- گالری جدید --}}
    <div class="mb-3">
        <label>تصاویر جدید</label>
        <input type="file" name="gallery[]" multiple class="form-control">
    </div>

    {{-- گزارش کامل --}}
    <div class="mb-3">
        <label>متن کامل گزارش</label>
        <input id="full_report" type="hidden" name="full_report" value="{{ old('full_report', $report->full_report) }}">
        <trix-editor input="full_report"></trix-editor>
    </div>

    <button type="submit" class="btn btn-primary mt-3">ذخیره تغییرات</button>
</form>
</div>

@push('scripts')
<script>
    $(".datepicker").MdPersianDateTimePicker({
        enableTimePicker: false,
        textFormat: 'yyyy/MM/dd',
        englishNumber: true
    });
</script>
@endpush

@endsection
