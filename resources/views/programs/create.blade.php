@extends('admin.layout')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.programs.index') }}">برنامه‌ها</a></li>
            <li class="breadcrumb-item active" aria-current="page">ایجاد برنامه جدید</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h3>ایجاد برنامه جدید</h3>

    <div class="container mt-4">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.programs.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-2">
            <label>عنوان برنامه</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="start_date" class="form-label">تاریخ شروع برنامه</label>
                    <div class="input-group">
                    <input type="text" id="start_date" name="start_date" class="form-control" placeholder="تاریخ شروع" >
                    <span class="input-group-text" id="start-date-icon"><i class="bi bi-calendar"></i></span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="end_date" class="form-label">تاریخ پایان برنامه</label>
                    <div class="input-group">
                    <input type="text" id="end_date" name="end_date" class="form-control" placeholder="تاریخ پایان" >
                    <span class="input-group-text" id="end-date-icon"><i class="bi bi-calendar"></i></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- مسئولین --}}
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
                        <select name="roles[0][user_id]" class="form-select user-select select2">
                            <option value="">— انتخاب کاربر —</option> 
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


        <hr>
        {{-- حمل و نقل --}}
        <div class="mb-2">
            <label>آیا حمل و نقل دارد؟</label>
            <select name="has_transport" id="has_transport" class="form-control">
                <option value="1">بله</option>
                <option value="0">خیر</option>
            </select>
        </div>
        <div class="container">
        <div id="transport_section" class="row">
            <div class="col-6" >
                <h5 class="mt-3 mb-3">حرکت از تهران</h5>
                <div class="mb-2">
                    <label>تاریخ و ساعت حرکت</label>
                    <input type="text" id="departure_dateTime_tehran_picker" class="form-control" placeholder="انتخاب تاریخ و ساعت">
                    <input type="hidden" name="departure_dateTime_tehran" id="departure_dateTime_tehran">
                </div>
                <div class="mb-2">
                    <label>محل حرکت</label>
                    <input type="text" name="departure_place_tehran" class="form-control">
                </div>
                <div class="mb-2">
                    <label>موقعیت روی نقشه</label>
                    <div id="map_tehran" style="height: 300px;"></div>
                    <input type="hidden" name="departure_lat_tehran" id="lat_tehran">
                    <input type="hidden" name="departure_lon_tehran" id="lon_tehran">
                </div>
            </div>
            <div class="col-6">
                <h5 class="mt-3 mb-3">حرکت از کرج</h5>
                <div class="mb-2">
                    <label>تاریخ و ساعت حرکت</label>
                    <input type="text" id="departure_dateTime_karaj_picker" class="form-control" placeholder="انتخاب تاریخ و ساعت">
                    <input type="hidden" name="departure_dateTime_karaj" id="departure_dateTime_karaj">
                </div>
                <div class="mb-2">
                    <label>محل حرکت</label>
                    <input type="text" name="departure_place_karaj" class="form-control">
                </div>
                <div class="mb-2">
                    <label>موقعیت روی نقشه</label>
                    <div id="map_karaj" style="height: 300px;"></div>
                    <input type="hidden" name="departure_lat_karaj" id="lat_karaj">
                    <input type="hidden" name="departure_lon_karaj" id="lon_karaj">
                </div>
            </div>
        </div>
        <hr>
        <div class="container">
            <div class="row">
                <h5 class="mb-3" >ضروریات</h5>
                {{-- تجهیزات --}}
                <div class="mb-2 col-9">
                    <label>تجهیزات مورد نیاز</label>
                    <select name="required_equipment[]" class="form-select select2-tags" multiple>
                        @foreach(['کوله پشتی', 'کیسه خواب', 'باتوم کوهنوردی', 'لباس گرم', 'هدلامپ', 'زیرانداز', 'قمقمه آب', 'کفش کوهنوردی'] as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- وعده‌ها --}}
                <div class="mb-2 col-3">
                    <label>وعده‌های مورد نیاز</label>
                    @foreach(['صبحانه', 'ناهار', 'شام', 'میانوعده'] as $meal)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="required_meals[]" value="{{ $meal }}" id="meal_{{ $meal }}">
                            <label class="form-check-label" for="meal_{{ $meal }}">{{ $meal }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <hr>

        <h5 class="mb-3" >هزینه</h5>
        <div class="mb-2">
            <label>آیا برنامه رایگان است؟</label>
            <select name="is_free" id="is_free" class="form-control">
                <option value="1">بله</option>
                <option value="0">خیر</option>
            </select>
        </div>

        <div id="pay_section" class="mt-5">
            <div id="costs" class="row">
                <div class="col-md-6 mb-3">
                    <label>هزینه برای اعضا</label>
                    <div class="input-group">
                        <input type="number" name="member_cost" class="form-control">
                        <div class="input-group-append">
                            <span class="input-group-text">ریال</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label>هزینه برای مهمان</label>
                    <div class="input-group">
                        <input type="number" name="guest_cost" class="form-control">
                        <div class="input-group-append">
                            <span class="input-group-text">ریال</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="card" class="mt-5">
                <h5>اطلاعات کارت بانکی</h5>
                <div class="row mb-3">
                    <div class="col-6">
                        <label>شماره کارت</label>
                        <input type="text" name="card_number" class="form-control">
                    </div>
                    <div class="col-6">
                        <label>شماره شبا</label>
                        <input type="text" name="sheba_number" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label>نام دارنده کارت</label>
                        <input type="text" name="card_holder" class="form-control">
                    </div>
                    <div class="col-6">
                            <label>نام بانک</label>
                            <input type="text" name="bank_name" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="mt-5">
            <h5 class="mb-3">ثبت‌نام</h5>
            <div class="row">
                <div class="col-md-6">
                    <label>ثبت‌نام باز است؟</label>
                    <select name="is_registration_open" id="is_registration_open" class="form-control">
                        <option value="1">بله</option>
                        <option value="0">خیر</option>
                    </select>
                </div>

                <div class="col-md-6" id="registration_section">
                    <label>مهلت ثبت‌نام</label>
                    <input id="registration_deadline" name="registration_deadline" class="form-control">
                </div>
            </div>
        </div>
        <hr>
        <div class="mt-5" >
            <h5 class="mb-3">توضیحات و تصاویر</h5>

            <div class="mb-2">
                <label>آپلود عکس‌های برنامه (حداکثر ۱۰ عدد)</label>
                <input type="file" name="report_photos[]" class="form-control" multiple accept="image/*">
            </div>

            <div class="mb-2">
                <label>توضیحات</label>
                <textarea name="description" id="description" class="form-control" rows="10"></textarea>
            </div>
        </div>

        <button class="btn btn-success" style="width: 100%;">ثبت برنامه</button>
    </form>

@push('scripts')
<script>
    // فعال/غیرفعال‌سازی فیلد نام دستی بر اساس انتخاب کاربر
    function toggleUserNameField(selectElement) {
        const userNameInput = selectElement.closest('.role-row').querySelector('input[name*="[user_name]"]');
        if (selectElement.value) {
            userNameInput.setAttribute('disabled', 'disabled');
            userNameInput.value = ''; // خالی کردن فیلد در صورت انتخاب کاربر
        } else {
            userNameInput.removeAttribute('disabled');
        }
    }

    // اجرا روی فرم اولیه
    document.querySelectorAll('.user-select').forEach(select => {
        toggleUserNameField(select); // وضعیت اولیه
        select.addEventListener('change', function () {
            toggleUserNameField(this);
        });
    });

    // برای فیلدهایی که با "افزودن سمت جدید" ایجاد می‌شن هم بعد از اضافه کردن، این رو اجرا کن:
    $('#roles-wrapper').on('change', '.user-select', function () {
        toggleUserNameField(this);
    });

</script>
<script>
    ClassicEditor
        .create(document.querySelector('#description'), {
            language: 'fa'
        })
        .catch(error => {
            console.error(error);
        });
</script>

<script>
$(document).ready(function () {

    function toggleTransportFields() {
        const value = $('#has_transport').val();
        if (value === '1') {
            $('#transport_section').show();
        } else {
            $('#transport_section').hide();
        }
    }
     function toggleCostFields() {
        const value = $('#is_free').val();
        if (value === '1') {
            $('#pay_section').hide();
        } else {
            $('#pay_section').show();
        }
    }

    toggleTransportFields();
    toggleCostFields();

    $('#has_transport').on('change', toggleTransportFields);
    $('#is_free').on('change', toggleCostFields);

});
</script>

<script>
$(document).ready(function () {
    $('.select2').select2({ dir: "rtl", width: '100%' });
    $('.select2-tags').select2({ tags: true, dir: "rtl", width: '100%' });

    // تاریخ شروع و پایان برنامه
    $('#start_date').persianDatepicker({
        format: 'YYYY/MM/DD',
        autoClose: true,
        observer: true,
        initialValue: false,
        calendarType: 'persian'
    });
    $('#end_date').persianDatepicker({
        format: 'YYYY/MM/DD',
        autoClose: true,
        observer: true,
        initialValue: false,
        calendarType: 'persian'
        
    });

    // مهلت ثبت‌نام
    $('#registration_deadline').persianDatepicker({
        format: 'YYYY/MM/DD',
        autoClose: true,
        observer: true,
        initialValue: false,
        calendarType: 'persian',
        timePicker: {
            enabled: true,
            second: false
        },
    });

    // حرکت از تهران - تاریخ و ساعت
    $('#departure_dateTime_tehran_picker').persianDatepicker({
        format: 'YYYY/MM/DD - HH:mm',
        autoClose: true,
        observer: true,
        initialValue: false,
        timePicker: {
            enabled: true,
            second: false
        },
        altField: '#departure_dateTime_tehran'
    });

    // حرکت از کرج - تاریخ و ساعت
    $('#departure_dateTime_karaj_picker').persianDatepicker({
        format: 'YYYY/MM/DD - HH:mm',
        autoClose: true,
        observer: true,
        initialValue: false,
        timePicker: {
            enabled: true,
            second: false
        },
        altField: '#departure_dateTime_karaj'
    });

    // Leaflet Maps
    function initMap(divId, latInputId, lonInputId) {
        const map = L.map(divId).setView([35.7, 51.4], 9);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);

        let marker;
        map.on('click', function (e) {
            if (marker) map.removeLayer(marker);
            marker = L.marker(e.latlng).addTo(map);
            document.getElementById(latInputId).value = e.latlng.lat;
            document.getElementById(lonInputId).value = e.latlng.lng;
        });
    }
    initMap('map_tehran', 'lat_tehran', 'lon_tehran');
    initMap('map_karaj', 'lat_karaj', 'lon_karaj');

    // افزودن مسئول جدید
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
            </div>`;
        $('#roles-wrapper').append(newRow);
        roleIndex++;
    });

    $('#roles-wrapper').on('click', '.remove-role', function () {
        $(this).closest('.role-row').remove();
    });
});
</script>
@endpush
@endsection