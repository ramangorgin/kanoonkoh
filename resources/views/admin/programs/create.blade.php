@extends('layouts.admin')

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

        <div class="mb-2">
            <label>تاریخ برنامه</label>
            <input id="date" name="date" class="form-control datepicker" required>
        </div>

        {{-- مسئولین --}}
        <div>
        <hr>
        <h5 class="mb-3">مسئولان اجرایی</h5>
        <div class="row">
            @foreach([
                'leader_name' => 'نام سرپرست',
                'assistant_leader_name' => 'نام کمک‌سرپرست',
                'technical_manager_name' => 'نام مسئول فنی',
                'support_name' => 'نام پشتیبان',
                'guide_name' => 'نام راهنما'
            ] as $field => $label)
                <div class="col-md-6 mb-2">
                    <label>{{ $label }}</label>
                    <input type="text" name="{{ $field }}" class="form-control">
                </div>
            @endforeach
        </div>
        </div>

        <hr>
        {{-- حمل و نقل --}}
        <div class="mb-2">
            <label>آیا حمل و نقل دارد؟</label>
            <select name="has_transport" id="has_transport" class="form-control">
                <option value="1">بله</option>
                <option value="0">خیر</option>
            </select>
        </div>

        <div id="transport_section">
            {{-- تهران --}}
            <hr>
            <h5 class="mt-3 mb-3">حرکت از تهران</h5>
            <div class="mb-2">
                <label>ساعت حرکت</label>
                <input type="time" name="departure_time_tehran" class="form-control">
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

            {{-- کرج --}}
            <hr>
            <h5 class="mt-3 mb-3">حرکت از کرج</h5>
            <div class="mb-2">
                <label>ساعت حرکت</label>
                <input type="time" name="departure_time_karaj" class="form-control">
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

        <div>
            <hr>
            <h5 class="mb-3" >ضروریات</h5>
        {{-- تجهیزات --}}
        <div class="mb-2">
            <label>تجهیزات مورد نیاز</label>
            <select name="required_equipment[]" class="form-select select2-tags" multiple>
                @foreach(['کوله پشتی', 'کیسه خواب', 'باتوم کوهنوردی', 'لباس گرم', 'هدلامپ', 'زیرانداز', 'قمقمه آب', 'کفش کوهنوردی'] as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
        </div>

        {{-- وعده‌ها --}}
        <div class="mb-2">
            <label>وعده‌های مورد نیاز</label>
            @foreach(['صبحانه', 'ناهار', 'شام', 'میانوعده'] as $meal)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="required_meals[]" value="{{ $meal }}" id="meal_{{ $meal }}">
                    <label class="form-check-label" for="meal_{{ $meal }}">{{ $meal }}</label>
                </div>
            @endforeach
        </div>
            </div>
        <div>
            <hr>
            <h5 class="mb-3" >هزینه</h5>
        
            {{-- رایگان بودن --}}
            <div class="mb-2">
                <label>آیا برنامه رایگان است؟</label>
                <select name="is_free" id="is_free" class="form-control">
                    <option value="1">بله</option>
                    <option value="0">خیر</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label>هزینه برای اعضا</label>
                    <div class="input-group">
                        <input type="number" name="member_price" class="form-control">
                        <div class="input-group-append">
                            <span class="input-group-text">ریال</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label>هزینه برای مهمان</label>
                    <div class="input-group">
                        <input type="number" name="guest_price" class="form-control">
                        <div class="input-group-append">
                            <span class="input-group-text">ریال</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <hr>
            <h5>اطلاعات کارت بانکی</h5>
            
            <div class="row mt-3">
                <div class="col-md-6">
                    <label>شماره کارت</label>
                    <input type="text" name="card_number" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>شماره شبا</label>
                    <input type="text" name="sheba_number" class="form-control">
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label>نام دارنده کارت</label>
                    <input type="text" name="card_holder" class="form-control">
                </div>
                <div class="col-md-6">
                        <label>نام بانک</label>
                        <input type="text" name="bank_name" class="form-control">
                </div>
            </div>
        </div>

        <div>
            <hr>
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
        <div>
            <hr>
            <h5 class="mb-3">توضیحات و تصاویر</h5>
            {{-- آپلود عکس --}}
            <div class="mb-2">
                <label>آپلود عکس‌های برنامه (حداکثر ۱۰ عدد)</label>
                <input type="file" name="report_photos[]" class="form-control" multiple accept="image/*">
            </div>

            {{-- توضیحات --}}
            <div class="mb-2">
                <label>توضیحات</label>
                <textarea name="description" id="description" class="form-control" rows="10"></textarea>
            </div>
        </div>

        <button class="btn btn-success" style="width: 100%;">ثبت برنامه</button>
    </form>
</div>

    @push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#description'), {
                language: 'fa'
            })
            .catch(error => {
                console.error(error);
            });
</script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    $(document).ready(function () {

        $('.select2').select2({ dir: "rtl", width: '100%' });
        $('.select2-tags').select2({ tags: true, dir: "rtl", width: '100%' });

        // Select2 with search, multiple, and tag creation (for equipment and meals)
        $('#equipments, #meals').select2({
            tags: true,
            multiple: true,
            placeholder: 'انتخاب یا افزودن مورد جدید...',
            dir: "rtl"
        });  
    // تقویم برای تاریخ شروع
    $('#date').persianDatepicker({
        format: 'YYYY/MM/DD',
        initialValue: false,
        autoClose: true,
        observer: true,
        calendarType: 'persian',
        navigator: {
            enabled: true,
            scroll: {
                enabled: false
            },
            text: {
                btnNextText: ">",
                btnPrevText: "<"
            }
        }
    });
        $('#registration_deadline').persianDatepicker({
        format: 'YYYY/MM/DD',
        initialValue: false,
        autoClose: true,
        observer: true,
        calendarType: 'persian',
        navigator: {
            enabled: true,
            scroll: {
                enabled: false
            },
            text: {
                btnNextText: ">",
                btnPrevText: "<"
            }
        }
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
    initMap('map_karaj');
    initMap('map_tehran');

        });
</script>
@endpush

@endsection