{{-- create_admin_report.blade.php --}}
@extends('layouts.admin')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">گزارش‌ها</a></li>
        <li class="breadcrumb-item active" aria-current="page">ایجاد گزارش جدید</li>
    </ol>
</nav>
@endsection

@section('content')
<h3>ایجاد گزارش جدید</h3>

<form method="POST" action="{{ route('admin.reports.store') }}" enctype="multipart/form-data">
    @csrf

    {{-- نوع برنامه --}}
<div class="mb-3">
<label class="form-label">نوع برنامه</label>
<select name="type" class="form-select" required>
    <option value="کوهنوردی">کوهنوردی</option>
    <option value="طبیعت‌گردی">طبیعت‌گردی</option>
    <option value="فرهنگی">فرهنگی</option>
</select>
</div>

{{-- برنامه مرتبط --}}
<div class="mb-3">
<label class="form-label">برنامه مرتبط</label>
<select name="program_id" class="form-select select2" required>
    <option value="">انتخاب کنید</option>
    @foreach(App\Models\Program::latest()->get() as $program)
        <option value="{{ $program->id }}">{{ $program->title }}</option>
    @endforeach
</select>
</div>

{{-- عنوان --}}
<div class="mb-3">
<label class="form-label">عنوان گزارش</label>
<input type="text" name="title" class="form-control" required>
</div>

{{-- تاریخ شروع و پایان با رنج انتخاب --}}
<div class="row mb-3">
    <div class="col-md-6">
        <div class="form-group">
            <label for="start_date" class="form-label">تاریخ شروع برنامه</label>
            <div class="input-group">
                <input type="text" class="form-control" id="start_date" placeholder="انتخاب تاریخ">
                <span class="input-group-text" id="start-date-icon">
                    <i class="bi bi-calendar"></i>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            <label for="end_date" class="form-label">تاریخ پایان برنامه</label>
            <div class="input-group">
                <input type="text" class="form-control" id="end_date" placeholder="انتخاب تاریخ">
                <span class="input-group-text" id="end-date-icon">
                    <i class="bi bi-calendar"></i>
                </span>
            </div>
        </div>
    </div>
</div>

{{-- نقش‌ها --}}
@foreach (['leader', 'assistant_leader', 'technical_manager', 'support', 'guide'] as $role)
    <div class="mb-3">
        <label class="form-label">{{ __('نام ') . __("roles.$role") }}</label>
        <input type="text" name="{{ $role }}_name" class="form-control"
               placeholder="مثلاً: رامان گرگین پاوه">
    </div>
@endforeach


{{-- ویژگی فنی --}}
<div class="mb-3">
<label>ویژگی فنی برنامه</label>
<select name="technical_level" class="form-select" required>
    <option value="عمومی">عمومی</option>
    <option value="تخصصی">تخصصی</option>
</select>
</div>

{{-- مشخصات منطقه --}}
<div class="mb-3">
<label>منطقه جغرافیایی</label>
<input type="text" name="area" class="form-control" required>
</div>

<div class="mb-3">
<label>محل شروع برنامه</label>
<input type="text" name="start_location" class="form-control">
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label>ارتفاع مبدا (متر)</label>
        <input type="number" name="start_altitude" class="form-control">
    </div>
    <div class="col-md-6">
        <label>ارتفاع قله (متر)</label>
        <input type="number" name="peak_height" class="form-control">
    </div>
</div>

{{-- نقشه مختصات مبدا و قله --}}
<div class="row mb-3">
    <div class="col-md-6">
        <label>مختصات مبدا</label>
        <input type="text" name="start_location_coords" id="start_coords" class="form-control" readonly>
        <div id="start_map" class="mt-2 border rounded" style="height: 200px;"></div>
    </div>
    <div class="col-md-6">
        <label>مختصات قله</label>
        <input type="text" name="peak_coords" id="peak_coords" class="form-control" readonly>
        <div id="peak_map" class="mt-2 border rounded" style="height: 200px;"></div>
    </div>
</div>

{{-- نوع جاده --}}
<div class="mb-3">
    <label>نوع جاده</label>
    <select name="road_type" class="form-select">
        <option value="آسفالت">آسفالت</option>
        <option value="خاکی">خاکی</option>
    </select>
</div>

{{-- حمل و نقل --}}
<div class="mb-3">
    <label>حمل و نقل منطقه</label>
    <select name="transportation[]" class="form-select select2-tags" multiple>
    <option value="اتوبوس">اتوبوس</option>
    <option value="مینی‌بوس">مینی‌بوس</option>
    <option value="سواری">سواری</option>
    <option value="تاکسی">تاکسی</option>
    <option value="خودرو شخصی">خودرو شخصی</option>
</select>

</div>

{{-- امکانات رفاهی --}}
<div class="mb-3">
    <label>امکانات رفاهی منطقه</label>
    <select name="water_type[]" class="form-select select2-tags" multiple>
        <option value="آب لوله‌کشی">آب لوله‌کشی</option>
        <option value="چشمه دائم">چشمه دائم</option>
        <option value="چشمه فصلی">چشمه فصلی</option>
        <option value="برق">برق</option>
        <option value="تلفن">تلفن</option>
        <option value="مدرسه">مدرسه</option>
        <option value="پست">پست</option>
        <option value="آنتن‌دهی موبایل">آنتن‌دهی موبایل</option>
    </select>
</div>

{{-- وسایل فنی --}}
<div class="mb-3">
    <label>وسایل فنی مورد نیاز</label>
    <select name="required_equipment[]" class="form-select select2-tags" multiple>
        <option value="طناب">طناب</option>
        <option value="کلنگ یخ">کلنگ یخ</option>
        <option value="هارنس">هارنس</option>
        <option value="کرامپون">کرامپون</option>
        <option value="بیل برف">بیل برف</option>
    </select>
</div>

{{-- مهارت‌ها --}}
<div class="mb-3">
    <label>پیش‌نیازهای شرکت</label>
    <select name="required_skills[]" class="form-select select2-tags" multiple>
        <option value="مدرک کوهپیمایی مقدماتی">مدرک کوهپیمایی مقدماتی</option>
        <option value="آشنایی با کار با طناب">آشنایی با کار با طناب</option>
        <option value="کار با یخ و برف">کار با یخ و برف</option>
    </select>
</div>

{{-- طبیعت و محیط --}}
<div class="row g-3">
    <div class="col-md-6">
        <label>پوشش گیاهی</label>
        <textarea name="natural_description" class="form-control" rows="2"></textarea>
    </div>
    <div class="col-md-6">
        <label>تنوع جانوری</label>
        <textarea name="wildlife" class="form-control" rows="2"></textarea>
    </div>
    <div class="col-md-6">
        <label>آب و هوای منطقه</label>
        <textarea name="weather" class="form-control" rows="2"></textarea>
    </div>
    <div class="col-md-3">
        <label>سرعت باد (کیلومتر بر ساعت)</label>
        <input type="number" name="wind_speed" class="form-control">
    </div>
    <div class="col-md-3">
        <label>دمای هوا (سانتی‌گراد)</label>
        <input type="number" name="temperature" class="form-control">
    </div>
    <div class="col-md-6">
        <label>زبان محلی</label>
        <input type="text" name="local_language" class="form-control">
    </div>
    <div class="col-md-6">
        <label>آثار باستانی و دیدنی‌ها</label>
        <textarea name="historical_sites" class="form-control" rows="2"></textarea>
    </div>
    <div class="col-md-6">
        <label>امکان تامین مواد غذایی</label>
        <textarea name="food_availability" class="form-control" rows="2"></textarea>
    </div>
    <div class="col-md-12">
        <label>ملاحظات و توضیحات ضروری</label>
        <textarea name="important_notes" class="form-control" rows="3"></textarea>
    </div>
</div>
<h5 class="mt-4">مسیر صعود و نقاط</h5>
<table class="table table-bordered" id="routePointsTable">
    <thead>
        <tr>
            <th>نام نقطه</th>
            <th>مختصات UTM</th>
            <th>عملیات</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<button type="button" class="btn btn-success btn-sm mb-3" onclick="addRoutePoint()">افزودن نقطه</button>

<h5 class="mt-4">زمانبندی اجرای برنامه</h5>
<table class="table table-bordered" id="executionScheduleTable">
    <thead>
        <tr>
            <th>نام رویداد</th>
            <th>زمان رویداد</th>
            <th>عملیات</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<button type="button" class="btn btn-success btn-sm mb-3" onclick="addExecutionEvent()">افزودن رویداد</button>
{{-- شرکت‌کنندگان --}}
<div class="mb-4">
    <label>تعداد کل شرکت‌کنندگان</label>
    <input type="number" name="participant_count" class="form-control" required>
</div>

<div class="mb-3">
    <label>اعضای شرکت‌کننده</label>
    <select name="member_ids[]" class="form-select select2" multiple>
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
        @endforeach
    </select>
</div>

{{-- مهمانان --}}
<h5 class="mt-4">مهمانان</h5>
<table class="table table-bordered" id="guestsTable">
    <thead>
        <tr>
            <th>نام مهمان</th>
            <th>عملیات</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<button type="button" class="btn btn-success btn-sm mb-3" onclick="addGuest()">افزودن مهمان</button>

<div class="mb-3">
    <label>فایل کروکی مسیر (GPX/KML/KMZ)</label>
    <input type="file" name="track_file" class="form-control" accept=".gpx,.kml,.kmz">
</div>

<div class="mb-3">
    <label>گالری تصاویر برنامه (حداکثر ۱۰ عکس)</label>
    <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
</div>

<div class="mb-3">
    <label>متن کامل گزارش</label>
    <textarea name="full_report" id="full_report" class="form-control" rows="20"></textarea>
</div>

<button class="btn btn-primary mt-3" style="width: 100%;">ثبت گزارش</button>
</form>
</div>
</form>


@push('scripts')
<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

<script>
    ClassicEditor
        .create(document.querySelector('#full_report'), {
            language: 'fa',
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'bulletedList', 'numberedList', '|',
                    'alignment', '|',
                    'link', 'blockQuote', 'insertTable', '|',
                    'undo', 'redo'
                ],
                shouldNotGroupWhenFull: true
            }
        })
        .catch(error => {
            console.error(error);
        });
</script>

<script>
$(document).ready(function () {
    $('.select2').select2({ dir: "rtl", width: '100%' });
    $('.select2-tags').select2({ tags: true, dir: "rtl", width: '100%' });

    
    $(document).ready(function() {
    // تقویم برای تاریخ شروع
    $('#start_date').persianDatepicker({
        format: 'YYYY/MM/DD',
        initialValue: false,
        autoClose: true,
        observer: true,
        calendar: {
            persian: {
                locale: 'fa'
            }
        },
        onSelect: function(unixDate) {
            // اعتبارسنجی: تاریخ پایان نباید قبل از تاریخ شروع باشد
            const startDate = new persianDate(unixDate);
            const endDate = $('#end_date').val() ? new persianDate($('#end_date').val()) : null;
            
            if (endDate && startDate > endDate) {
                $('#end_date').val('');
                alert('تاریخ پایان نمی‌تواند قبل از تاریخ شروع باشد');
            }
        }
    });

    // تقویم برای تاریخ پایان
    $('#end_date').persianDatepicker({
        format: 'YYYY/MM/DD',
        initialValue: false,
        autoClose: true,
        observer: true,
        calendar: {
            persian: {
                locale: 'fa'
            }
        },
        onSelect: function(unixDate) {
            // اعتبارسنجی: تاریخ پایان نباید قبل از تاریخ شروع باشد
            const endDate = new persianDate(unixDate);
            const startDate = $('#start_date').val() ? new persianDate($('#start_date').val()) : null;
            
            if (startDate && endDate < startDate) {
                $('#end_date').val('');
                alert('تاریخ پایان نمی‌تواند قبل از تاریخ شروع باشد');
            }
        }
    });
});

   

    $('#member_ids, [name$="_id"]').select2({
        dir: "rtl",
        width: '100%',
        placeholder: "انتخاب کنید"
    });
});

// Leaflet maps
function initMap(divId, inputId) {
    const map = L.map(divId).setView([35.7, 51.4], 9);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
    }).addTo(map);

    let marker;
    map.on('click', function (e) {
        const latlng = `${e.latlng.lat},${e.latlng.lng}`;
        document.getElementById(inputId).value = latlng;
        if (marker) map.removeLayer(marker);
        marker = L.marker(e.latlng).addTo(map);
    });
}
initMap('start_map', 'start_coords');
initMap('peak_map', 'peak_coords');
</script>

<script>
function addRoutePoint() {
    let table = document.getElementById('routePointsTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow();
    newRow.innerHTML = `
        <td><input type="text" name="route_points[][point]" class="form-control" required></td>
        <td><input type="text" name="route_points[][utm]" class="form-control" required></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">حذف</button></td>
    `;
}

function addExecutionEvent() {
    let table = document.getElementById('executionScheduleTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow();
    newRow.innerHTML = `
        <td><input type="text" name="execution_schedule[][event]" class="form-control" required></td>
        <td><input type="time" name="execution_schedule[][time]" class="form-control" required></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">حذف</button></td>
    `;
}

function addGuest() {
    let table = document.getElementById('guestsTable').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow();
    newRow.innerHTML = `
        <td><input type="text" name="guests[]" class="form-control" required></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">حذف</button></td>
    `;
}

function deleteRow(button) {
    button.closest('tr').remove();
}
</script>
@endpush
@endsection