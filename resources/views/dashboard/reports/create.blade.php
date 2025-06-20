@extends('layouts.dashboard')

@section('title', 'ثبت گزارش')


@section('breadcrumb')
    <a href="{{ route('dashboard.index') }}">داشبورد</a> /
    <a href="{{ route('dashboard.reports.index') }}">گزارش‌ها</a> /
    <span>ثبت گزارش</span>
@endsection

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



@section('content')

@if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
@endif


<div class="container py-4">
    <h4 class="mb-4">ثبت گزارش جدید</h4>

    <form action="{{ route('dashboard.reports.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- نوع برنامه --}}
    <div class="mb-3">
        <label class="form-label">نوع برنامه</label>
        <select name="type" class="form-select">
            <option value="کوهنوردی">کوهنوردی</option>
            <option value="طبیعت‌گردی">طبیعت‌گردی</option>
            <option value="فرهنگی">فرهنگی</option>
        </select>
    </div>

    {{-- برنامه مرتبط --}}
    <div class="mb-3">
    <label class="form-label">برنامه مرتبط</label>
    <select name="program_id" class="form-select select2">
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
                <input type="text" id="start_date" class="form-control" placeholder="تاریخ شروع" >
                <span class="input-group-text" id="start-date-icon"><i class="bi bi-calendar"></i></span>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="end_date" class="form-label">تاریخ پایان برنامه</label>
                <div class="input-group">
                <input type="text" id="end_date" class="form-control" placeholder="تاریخ پایان" >
                <span class="input-group-text" id="end-date-icon"><i class="bi bi-calendar"></i></span>
                </div>
            </div>
        </div>
    </div>

    <h5 class="mt-4">مسئولین برنامه</h5>
    <div id="roles-wrapper">
        <div class="role-row mb-3 border p-3 rounded">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">سمت</label>
                    <input type="text" name="roles[0][role_title]" class="form-control" placeholder="مثلاً: سرپرست">
                </div>
                <div class="col-md-4">
                    <label class="form-label">شناسه کاربر</label>
                    <select name="roles[0][user_id]" class="form-select user-select">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">نام فرد (در صورت نبودن اکانت)</label>
                    <input type="text" name="roles[0][user_name]" class="form-control" placeholder="مثلاً: علی رضایی">
                </div>
                <div class="col-md-12 mt-2 text-end">
                    <button type="button" class="btn btn-danger btn-sm remove-role">حذف</button>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-outline-primary mt-2 mb-5" id="add-role">افزودن سمت جدید</button>


    {{-- ویژگی فنی --}}
    <div class="mb-3">
    <label>ویژگی فنی برنامه</label>
    <select name="technical_level" class="form-select">
        <option value="عمومی">عمومی</option>
        <option value="تخصصی">تخصصی</option>
    </select>
    </div>

    {{-- مشخصات منطقه --}}
    <div class="mb-3">
    <label>منطقه جغرافیایی</label>
    <input type="text" name="area" class="form-control">
    </div>

    <div class="mb-3">
    <label>محل شروع برنامه</label>
    <input type="text" name="start_location" class="form-control">
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label>ارتفاع مبدا (متر)</label>
            <input type="number" name="start_height" class="form-control">
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
            <input type="text" name="start_coords" id="start_coords" class="form-control">
            <div id="start_map" class="mt-2 border rounded" style="height: 200px;"></div>
        </div>
        <div class="col-md-6">
            <label>مختصات قله</label>
            <input type="text" name="peak_coords" id="peak_coords" class="form-control">
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
    <button type="button" class="btn btn-success btn-sm mb-5" onclick="addExecutionEvent()">افزودن رویداد</button>


    {{-- شرکت‌کنندگان --}}
    <div class="row">
        <div class="mb-1 col-md-4">
            <label>تعداد کل شرکت‌کنندگان</label>
            <input type="number" name="participant_count" class="form-control">
        </div>
            <div class="mb-3 col-md-8">
                <label for="participants">اعضای شرکت‌کننده</label>
                <select id="participants" name="participants[]" class="form-select select2-tags" multiple>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->profile->first_name }} {{ $user->profile->last_name }}
                        </option>
                    @endforeach
                </select>
                <span class="text-muted d-block mt-1">
                    شرکت‌کنندگان در این برنامه را از لیست اعضای باشگاه جستجو و انتخاب کنید. اگر نام فرد موردنظر در لیست نیست، می‌توانید با تایپ کردن نام او، به‌عنوان مهمان ثبتش کنید.
                </span>
            </div>
    
    </div>

    <div class="mb-3">
        <label>فایل کروکی مسیر (GPX/KML/KMZ)</label>
        <input type="file" name="track_file" class="form-control" accept=".gpx,.kml,.kmz">
    </div>

    <div class="mb-3">
        <label>گالری تصاویر برنامه (حداکثر ۱۰ عکس)</label>
        <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
    </div>

<div class="mb-3">
    <label class="form-label">نویسنده گزارش:</label><br>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="author_type" id="author_self" value="self" checked>
        <label class="form-check-label" for="author_self">نویسنده خودم هستم</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="author_type" id="author_other" value="other">
        <label class="form-check-label" for="author_other">نویسنده فرد دیگری است</label>
    </div>
</div>

<div id="author_fields" class="row mb-3" style="display: none;">
    <div class="col-md-6">
        <label for="user_id" class="form-label">انتخاب از اعضای سایت</label>
        <select name="user_id" id="user_id" class="form-select">
            <option value="">انتخاب کنید</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="writer_name" class="form-label">یا وارد کردن دستی نام نویسنده</label>
        <input type="text" name="writer_name" id="writer_name" class="form-control" placeholder="نام را وارد کنید">
    </div>
</div>


    <div class="mb-3">
        <label>متن کامل گزارش</label>
        <textarea name="content" id="content" class="form-control" rows="20"></textarea>
    </div>

    <button type="submit" class="btn btn-primary mt-3" style="Width: 100%;">ثبت گزارش</button>
    </form>
</div>
@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-date@1.0.6/dist/persian-date.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selfRadio = document.getElementById('author_self');
        const otherRadio = document.getElementById('author_other');
        const authorFields = document.getElementById('author_fields');

        function toggleAuthorFields() {
            if (otherRadio.checked) {
                authorFields.style.display = 'flex';
            } else {
                authorFields.style.display = 'none';
                document.getElementById('user_id').value = '';
                document.getElementById('writer_name').value = '';
            }
        }

        selfRadio.addEventListener('change', toggleAuthorFields);
        otherRadio.addEventListener('change', toggleAuthorFields);

        // Initialize on page load
        toggleAuthorFields();
    });
</script>

<script>
    function refreshUserSelects() {
        $('.user-select').select2({
            placeholder: "جستجوی نام کاربر...",
            allowClear: true,
            dir: "rtl",
            width: '100%'
        });
    }

    $(document).ready(function () {
        refreshUserSelects();

        let roleIndex = 1;

        $('#add-role').on('click', function () {
            let newRow = `
            <div class="role-row mb-3 border p-3 rounded">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">سمت</label>
                        <input type="text" name="roles[${roleIndex}][role_title]" class="form-control" placeholder="مثلاً: سرپرست">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">شناسه کاربر (اختیاری)</label>
                        <select name="roles[${roleIndex}][user_id]" class="form-select user-select">
                            <option value="">— انتخاب کاربر —</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">نام فرد (در صورت نبودن اکانت)</label>
                        <input type="text" name="roles[${roleIndex}][user_name]" class="form-control" placeholder="مثلاً: علی رضایی">
                    </div>
                    <div class="col-md-12 mt-2 text-end">
                        <button type="button" class="btn btn-danger btn-sm remove-role">حذف</button>
                    </div>
                </div>
            </div>
            `;
            $('#roles-wrapper').append(newRow);
            refreshUserSelects();
            roleIndex++;
        });

        $('#roles-wrapper').on('click', '.remove-role', function () {
            $(this).closest('.role-row').remove();
        });
    });
</script>


<script>
    ClassicEditor
        .create(document.querySelector('#content'), {
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
        .catch(error => console.error(error));

    // فارسی به انگلیسی
    function toEnglishDigits(str) {
        return str.replace(/[۰-۹]/g, w => String.fromCharCode(w.charCodeAt(0) - 1728));
    }

    $(document).ready(function () {
        // Select2
        $('.select2').select2({ dir: "rtl", width: '100%' });
        $('.select2-tags').select2({ tags: true, dir: "rtl", width: '100%' });
        $('#member_ids, [name$="_id"]').select2({
            dir: "rtl", width: '100%', placeholder: "انتخاب کنید"
        });

        // تقویم شمسی - تاریخ شروع
        $('#start_date').persianDatepicker({
            format: 'YYYY/MM/DD',
            initialValue: false,
            autoClose: true,
            observer: true,
            calendar: { persian: { locale: 'fa' } },
            onSelect: function (unix) {
                const selected = new persianDate(unix).format('YYYY/MM/DD');
                $('#start_date').val(selected);
                
            }
        });

        // تقویم شمسی - تاریخ پایان
        $('#end_date').persianDatepicker({
            format: 'YYYY/MM/DD',
            initialValue: false,
            autoClose: true,
            observer: true,
            calendar: { persian: { locale: 'fa' } },
            onSelect: function (unix) {
                const selected = new persianDate(unix).format('YYYY/MM/DD');
                $('#end_date').val(selected);
            }
        });

    });

    // Leaflet نقشه
    function initMap(divId, inputId) {
        const map = L.map(divId).setView([35.7, 51.4], 9);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18 }).addTo(map);

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

    // افزودن / حذف ردیف‌ها
    function addRoutePoint() {
        $('#routePointsTable tbody').append(`
            <tr>
                <td><input type="text" name="route_points[][point]" class="form-control" ></td>
                <td><input type="text" name="route_points[][utm]" class="form-control" ></td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">حذف</button></td>
            </tr>`);
    }

    function addExecutionEvent() {
        $('#executionScheduleTable tbody').append(`
            <tr>
                <td><input type="text" name="execution_schedule[][event]" class="form-control" ></td>
                <td><input type="time" name="execution_schedule[][time]" class="form-control" ></td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">حذف</button></td>
            </tr>`);
    }

    function deleteRow(button) {
        $(button).closest('tr').remove();
    }

    // Expose globally
    window.addRoutePoint = addRoutePoint;
    window.addExecutionEvent = addExecutionEvent;
    window.deleteRow = deleteRow;
</script>
@endpush
