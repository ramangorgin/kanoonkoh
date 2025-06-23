@extends('user.layout')

@section('title', 'ویرایش گزارش')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">داشبورد</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.reports.index') }}">گزارش‌ها</a></li>
            <li class="breadcrumb-item active" aria-current="page">ویرایش گزارش</li>
        </ol>
    </nav>
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
<div class="container py-4">
    <h4 class="mb-4">ویرایش گزارش</h4>

    <form action="{{ route('dashboard.reports.update', $report->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- نوع برنامه --}}
        <div class="mb-3">
            <label class="form-label">نوع برنامه</label>
            <select name="type" class="form-select">
                <option value="کوهنوردی" {{ $report->type == 'کوهنوردی' ? 'selected' : '' }}>کوهنوردی</option>
                <option value="طبیعت‌گردی" {{ $report->type == 'طبیعت‌گردی' ? 'selected' : '' }}>طبیعت‌گردی</option>
                <option value="فرهنگی" {{ $report->type == 'فرهنگی' ? 'selected' : '' }}>فرهنگی</option>
            </select>
        </div>

        {{-- برنامه مرتبط --}}
        <div class="mb-3">
            <label class="form-label">برنامه مرتبط</label>
            <select name="program_id" class="form-select select2">
                <option value="">انتخاب کنید</option>
                @foreach(App\Models\Program::latest()->get() as $program)
                    <option value="{{ $program->id }}" {{ $report->program_id == $program->id ? 'selected' : '' }}>
                        {{ $program->title }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- عنوان --}}
        <div class="mb-3">
            <label class="form-label">عنوان گزارش</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $report->title) }}" required>
        </div>

        {{-- تاریخ شروع و پایان --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="start_date" class="form-label">تاریخ شروع برنامه</label>
                    <div class="input-group">
                        <input type="text" id="start_date" class="form-control" 
                               value="{{ $report->start_date ? \Morilog\Jalali\Jalalian::fromDateTime($report->start_date)->format('Y/m/d') : '' }}" 
                               placeholder="تاریخ شروع">
                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="end_date" class="form-label">تاریخ پایان برنامه</label>
                    <div class="input-group">
                        <input type="text" id="end_date" class="form-control" 
                               value="{{ $report->end_date ? \Morilog\Jalali\Jalalian::fromDateTime($report->end_date)->format('Y/m/d') : '' }}" 
                               placeholder="تاریخ پایان">
                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- مسئولین برنامه --}}
        <h5 class="mt-4">مسئولین برنامه</h5>
        <div id="roles-wrapper">
            @foreach($report->userRoles as $index => $role)
                <div class="role-row mb-3 border p-3 rounded">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">سمت</label>
                            <input type="text" name="roles[{{$index}}][role_title]" class="form-control" 
                                   value="{{ $role->role_title }}" placeholder="مثلاً: سرپرست">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">شناسه کاربر</label>
                            <select name="roles[{{$index}}][user_id]" class="form-select user-select">
                                <option value="">— انتخاب کاربر —</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $role->user_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">نام فرد (در صورت نبودن اکانت)</label>
                            <input type="text" name="roles[{{$index}}][user_name]" class="form-control" 
                                   value="{{ $role->user_name }}" placeholder="مثلاً: علی رضایی">
                        </div>
                        <div class="col-md-12 mt-2 text-end">
                            <button type="button" class="btn btn-danger btn-sm remove-role">حذف</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-outline-primary mt-2 mb-5" id="add-role">افزودن سمت جدید</button>

        {{-- ویژگی فنی --}}
        <div class="mb-3">
            <label>ویژگی فنی برنامه</label>
            <select name="technical_level" class="form-select">
                <option value="عمومی" {{ $report->technical_level == 'عمومی' ? 'selected' : '' }}>عمومی</option>
                <option value="تخصصی" {{ $report->technical_level == 'تخصصی' ? 'selected' : '' }}>تخصصی</option>
            </select>
        </div>

        {{-- مشخصات منطقه --}}
        <div class="mb-3">
            <label>منطقه جغرافیایی</label>
            <input type="text" name="area" class="form-control" value="{{ old('area', $report->area) }}">
        </div>

        <div class="mb-3">
            <label>محل شروع برنامه</label>
            <input type="text" name="start_location" class="form-control" value="{{ old('start_location', $report->start_location) }}">
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>ارتفاع مبدا (متر)</label>
                <input type="number" name="start_height" class="form-control" value="{{ old('start_height', $report->start_height) }}">
            </div>
            <div class="col-md-6">
                <label>ارتفاع قله (متر)</label>
                <input type="number" name="peak_height" class="form-control" value="{{ old('peak_height', $report->peak_height) }}">
            </div>
        </div>

        {{-- نقشه مختصات --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label>مختصات مبدا</label>
                <input type="text" name="start_coords" id="start_coords" class="form-control" value="{{ old('start_coords', $report->start_coords) }}">
                <div id="start_map" class="mt-2 border rounded" style="height: 200px;"></div>
            </div>
            <div class="col-md-6">
                <label>مختصات قله</label>
                <input type="text" name="peak_coords" id="peak_coords" class="form-control" value="{{ old('peak_coords', $report->peak_coords) }}">
                <div id="peak_map" class="mt-2 border rounded" style="height: 200px;"></div>
            </div>
        </div>

        {{-- نوع جاده --}}
        <div class="mb-3">
            <label>نوع جاده</label>
            <select name="road_type" class="form-select">
                <option value="آسفالت" {{ $report->road_type == 'آسفالت' ? 'selected' : '' }}>آسفالت</option>
                <option value="خاکی" {{ $report->road_type == 'خاکی' ? 'selected' : '' }}>خاکی</option>
            </select>
        </div>

        {{-- حمل و نقل --}}
        <div class="mb-3">
            <label>حمل و نقل منطقه</label>
            <select name="transportation[]" class="form-select select2-tags" multiple>
                @foreach(['اتوبوس', 'مینی‌بوس', 'سواری', 'تاکسی', 'خودرو شخصی'] as $item)
                    <option value="{{ $item }}" {{ in_array($item, json_decode($report->transportation ?? '[]', true)) ? 'selected' : '' }}>
                        {{ $item }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- امکانات رفاهی --}}
        <div class="mb-3">
            <label>امکانات رفاهی منطقه</label>
            <select name="water_type[]" class="form-select select2-tags" multiple>
                @foreach(['آب لوله‌کشی', 'چشمه دائم', 'چشمه فصلی', 'برق', 'تلفن', 'مدرسه', 'پست', 'آنتن‌دهی موبایل'] as $item)
                    <option value="{{ $item }}" {{ in_array($item, json_decode($report->water_type ?? '[]', true)) ? 'selected' : '' }}>
                        {{ $item }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- وسایل فنی --}}
        <div class="mb-3">
            <label>وسایل فنی مورد نیاز</label>
            <select name="required_equipment[]" class="form-select select2-tags" multiple>
                @foreach(['طناب', 'کلنگ یخ', 'هارنس', 'کرامپون', 'بیل برف'] as $item)
                    <option value="{{ $item }}" {{ in_array($item, json_decode($report->required_equipment ?? '[]', true)) ? 'selected' : '' }}>
                        {{ $item }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- مهارت‌ها --}}
        <div class="mb-3">
            <label>پیش‌نیازهای شرکت</label>
            <select name="required_skills[]" class="form-select select2-tags" multiple>
                @foreach(['مدرک کوهپیمایی مقدماتی', 'آشنایی با کار با طناب', 'کار با یخ و برف'] as $item)
                    <option value="{{ $item }}" {{ in_array($item, json_decode($report->required_skills ?? '[]', true)) ? 'selected' : '' }}>
                        {{ $item }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- طبیعت و محیط --}}
        <div class="row g-3">
            <div class="col-md-6">
                <label>پوشش گیاهی</label>
                <textarea name="natural_description" class="form-control" rows="2">{{ old('natural_description', $report->natural_description) }}</textarea>
            </div>
            <div class="col-md-6">
                <label>تنوع جانوری</label>
                <textarea name="wildlife" class="form-control" rows="2">{{ old('wildlife', $report->wildlife) }}</textarea>
            </div>
            <div class="col-md-6">
                <label>آب و هوای منطقه</label>
                <textarea name="weather" class="form-control" rows="2">{{ old('weather', $report->weather) }}</textarea>
            </div>
            <div class="col-md-3">
                <label>سرعت باد (کیلومتر بر ساعت)</label>
                <input type="number" name="wind_speed" class="form-control" value="{{ old('wind_speed', $report->wind_speed) }}">
            </div>
            <div class="col-md-3">
                <label>دمای هوا (سانتی‌گراد)</label>
                <input type="number" name="temperature" class="form-control" value="{{ old('temperature', $report->temperature) }}">
            </div>
            <div class="col-md-6">
                <label>زبان محلی</label>
                <input type="text" name="local_language" class="form-control" value="{{ old('local_language', $report->local_language) }}">
            </div>
            <div class="col-md-6">
                <label>آثار باستانی و دیدنی‌ها</label>
                <textarea name="historical_sites" class="form-control" rows="2">{{ old('historical_sites', $report->historical_sites) }}</textarea>
            </div>
            <div class="col-md-6">
                <label>امکان تامین مواد غذایی</label>
                <textarea name="food_availability" class="form-control" rows="2">{{ old('food_availability', $report->food_availability) }}</textarea>
            </div>
            <div class="col-md-12">
                <label>ملاحظات و توضیحات ضروری</label>
                <textarea name="important_notes" class="form-control" rows="3">{{ old('important_notes', $report->important_notes) }}</textarea>
            </div>
        </div>

        {{-- مسیر صعود --}}
        <h5 class="mt-4">مسیر صعود و نقاط</h5>
        <table class="table table-bordered" id="routePointsTable">
            <thead>
                <tr>
                    <th>نام نقطه</th>
                    <th>مختصات UTM</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @if($report->route_points)
                    @foreach(json_decode($report->route_points, true) as $point)
                        <tr>
                            <td><input type="text" name="route_points[][point]" class="form-control" value="{{ $point['point'] ?? '' }}"></td>
                            <td><input type="text" name="route_points[][utm]" class="form-control" value="{{ $point['utm'] ?? '' }}"></td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">حذف</button></td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <button type="button" class="btn btn-success btn-sm mb-3" onclick="addRoutePoint()">افزودن نقطه</button>

        {{-- زمانبندی اجرا --}}
        <h5 class="mt-4">زمانبندی اجرای برنامه</h5>
        <table class="table table-bordered" id="executionScheduleTable">
            <thead>
                <tr>
                    <th>نام رویداد</th>
                    <th>زمان رویداد</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @if($report->execution_schedule)
                    @foreach(json_decode($report->execution_schedule, true) as $event)
                        <tr>
                            <td><input type="text" name="execution_schedule[][event]" class="form-control" value="{{ $event['event'] ?? '' }}"></td>
                            <td><input type="time" name="execution_schedule[][time]" class="form-control" value="{{ $event['time'] ?? '' }}"></td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">حذف</button></td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <button type="button" class="btn btn-success btn-sm mb-5" onclick="addExecutionEvent()">افزودن رویداد</button>

        {{-- شرکت‌کنندگان --}}
        <div class="row">
            <div class="mb-1 col-md-4">
                <label>تعداد کل شرکت‌کنندگان</label>
                <input type="number" name="participant_count" class="form-control" value="{{ old('participant_count', $report->participant_count) }}">
            </div>
            <div class="mb-3 col-md-8">
                <label for="participants">اعضای شرکت‌کننده</label>
                <select id="participants" name="participants[]" class="form-select select2-tags" multiple>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ in_array($user->id, $report->participants->pluck('user_id')->toArray()) ? 'selected' : '' }}>
                            {{ $user->profile->first_name }} {{ $user->profile->last_name }}
                        </option>
                    @endforeach
                </select>
                <span class="text-muted d-block mt-1">
                    شرکت‌کنندگان در این برنامه را از لیست اعضای باشگاه جستجو و انتخاب کنید.
                </span>
            </div>
        </div>

        {{-- فایل‌ها --}}
        <div class="mb-3">
            <label>فایل کروکی مسیر (GPX/KML/KMZ)</label>
            <input type="file" name="track_file" class="form-control" accept=".gpx,.kml,.kmz">
            @if($report->track_file_path)
                <div class="mt-2">
                    <span>فایل فعلی: </span>
                    <a href="{{ asset('storage/' . $report->track_file_path) }}" target="_blank">مشاهده فایل</a>
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label>گالری تصاویر برنامه (حداکثر ۱۰ عکس)</label>
            <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
            @if($report->gallery)
                <div class="row mt-2">
                    @foreach(json_decode($report->gallery, true) as $image)
                        <div class="col-md-2 mb-2">
                            <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail" style="width:100%; height:100px; object-fit:cover;">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- نویسنده گزارش --}}
        <div class="mb-3">
            <label class="form-label">نویسنده گزارش:</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="author_type" id="author_self" value="self" 
                    {{ $report->user_id == Auth::id() ? 'checked' : '' }}>
                <label class="form-check-label" for="author_self">نویسنده خودم هستم</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="author_type" id="author_other" value="other"
                    {{ $report->user_id != Auth::id() ? 'checked' : '' }}>
                <label class="form-check-label" for="author_other">نویسنده فرد دیگری است</label>
            </div>
        </div>

        <div id="author_fields" class="row mb-3" style="{{ $report->user_id == Auth::id() ? 'display: none;' : '' }}">
            <div class="col-md-6">
                <label for="user_id" class="form-label">انتخاب از اعضای سایت</label>
                <select name="user_id" id="user_id" class="form-select">
                    <option value="">انتخاب کنید</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $report->user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="writer_name" class="form-label">یا وارد کردن دستی نام نویسنده</label>
                <input type="text" name="writer_name" id="writer_name" class="form-control" 
                       value="{{ $report->writer_name }}" placeholder="نام را وارد کنید">
            </div>
        </div>

        {{-- متن کامل گزارش --}}
        <div class="mb-3">
            <label>متن کامل گزارش</label>
            <textarea name="content" id="content" class="form-control" rows="20">{{ old('content', $report->content) }}</textarea>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('dashboard.reports.index') }}" class="btn btn-secondary">انصراف</a>
            <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
        </div>
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

        let roleIndex = {{ $report->userRoles->count() }};

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

    $(document).ready(function () {
        // Select2
        $('.select2').select2({ dir: "rtl", width: '100%' });
        $('.select2-tags').select2({ tags: true, dir: "rtl", width: '100%' });

        // تقویم شمسی - تاریخ شروع
        $('#start_date').persianDatepicker({
            format: 'YYYY/MM/DD',
            initialValue: false,
            autoClose: true,
            observer: true,
            calendar: { persian: { locale: 'fa' } }
        });

        // تقویم شمسی - تاریخ پایان
        $('#end_date').persianDatepicker({
            format: 'YYYY/MM/DD',
            initialValue: false,
            autoClose: true,
            observer: true,
            calendar: { persian: { locale: 'fa' } }
        });

        // Leaflet نقشه
        function initMap(divId, inputId, initialValue) {
            const map = L.map(divId).setView([35.7, 51.4], 9);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18 }).addTo(map);

            let marker;
            
            if (initialValue) {
                const coords = initialValue.split(',');
                if (coords.length === 2) {
                    const lat = parseFloat(coords[0]);
                    const lng = parseFloat(coords[1]);
                    marker = L.marker([lat, lng]).addTo(map);
                    map.setView([lat, lng], 12);
                }
            }
            
            map.on('click', function (e) {
                const latlng = `${e.latlng.lat},${e.latlng.lng}`;
                document.getElementById(inputId).value = latlng;
                if (marker) map.removeLayer(marker);
                marker = L.marker(e.latlng).addTo(map);
            });
        }

        initMap('start_map', 'start_coords', '{{ $report->start_coords }}');
        initMap('peak_map', 'peak_coords', '{{ $report->peak_coords }}');
    });

    // افزودن / حذف ردیف‌ها
    function addRoutePoint() {
        $('#routePointsTable tbody').append(`
            <tr>
                <td><input type="text" name="route_points[][point]" class="form-control"></td>
                <td><input type="text" name="route_points[][utm]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">حذف</button></td>
            </tr>`);
    }

    function addExecutionEvent() {
        $('#executionScheduleTable tbody').append(`
            <tr>
                <td><input type="text" name="execution_schedule[][event]" class="form-control"></td>
                <td><input type="time" name="execution_schedule[][time]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">حذف</button></td>
            </tr>`);
    }

    function deleteRow(button) {
        $(button).closest('tr').remove();
    }

    window.addRoutePoint = addRoutePoint;
    window.addExecutionEvent = addExecutionEvent;
    window.deleteRow = deleteRow;
</script>
@endpush