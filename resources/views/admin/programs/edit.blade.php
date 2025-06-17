@extends('layouts.admin')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.programs.index') }}">برنامه‌ها</a></li>
            <li class="breadcrumb-item active" aria-current="page">ویرایش برنامه</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h3>ویرایش برنامه</h3>
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

    <form method="POST" action="{{ route('admin.programs.update', $program->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-2">
            <label>عنوان برنامه</label>
            <input type="text" name="title" class="form-control" value="{{ $program->title }}" required>
        </div>

        <div class="mb-2">
            <label>تاریخ برنامه</label>
            <input type="text" name="date" class="form-control datepicker" value="{{ $program->date }}" required>
        </div>

        {{-- مسئولین --}}
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
                    <input type="text" name="{{ $field }}" class="form-control" value="{{ $program->$field }}">
                </div>
            @endforeach
        </div>

        {{-- حمل و نقل --}}
        <div class="mb-2">
            <label>آیا حمل و نقل دارد؟</label>
            <select name="has_transport" id="has_transport" class="form-control">
                <option value="1" {{ $program->has_transport ? 'selected' : '' }}>بله</option>
                <option value="0" {{ !$program->has_transport ? 'selected' : '' }}>خیر</option>
            </select>
        </div>

        <div id="transport_section">
            {{-- تهران --}}
            <h5 class="mt-3">حرکت از تهران</h5>
            <div class="mb-2">
                <label>ساعت حرکت</label>
                <input type="time" name="departure_time_tehran" class="form-control" value="{{ $program->departure_time_tehran }}">
            </div>
            <div class="mb-2">
                <label>محل حرکت</label>
                <input type="text" name="departure_place_tehran" class="form-control" value="{{ $program->departure_place_tehran }}">
            </div>
            <div class="mb-2">
                <label>موقعیت روی نقشه</label>
                <div id="map_tehran" style="height: 300px;"></div>
                <input type="hidden" name="departure_lat_tehran" id="lat_tehran" value="{{ $program->departure_lat_tehran }}">
                <input type="hidden" name="departure_lon_tehran" id="lon_tehran" value="{{ $program->departure_lon_tehran }}">
            </div>

            {{-- کرج --}}
            <h5 class="mt-4">حرکت از کرج</h5>
            <div class="mb-2">
                <label>ساعت حرکت</label>
                <input type="time" name="departure_time_karaj" class="form-control" value="{{ $program->departure_time_karaj }}">
            </div>
            <div class="mb-2">
                <label>محل حرکت</label>
                <input type="text" name="departure_place_karaj" class="form-control" value="{{ $program->departure_place_karaj }}">
            </div>
            <div class="mb-2">
                <label>موقعیت روی نقشه</label>
                <div id="map_karaj" style="height: 300px;"></div>
                <input type="hidden" name="departure_lat_karaj" id="lat_karaj" value="{{ $program->departure_lat_karaj }}">
                <input type="hidden" name="departure_lon_karaj" id="lon_karaj" value="{{ $program->departure_lon_karaj }}">
            </div>
        </div>

        {{-- تجهیزات --}}
        <div class="mb-2">
            <label>تجهیزات مورد نیاز</label>
            <select name="required_equipment[]" class="form-control select2" multiple>
                @foreach(['کوله پشتی', 'کیسه خواب', 'باتوم کوهنوردی', 'لباس گرم', 'هدلامپ', 'زیرانداز', 'قمقمه آب', 'کفش کوهنوردی'] as $item)
                    <option value="{{ $item }}" {{ in_array($item, $program->required_equipment ?? []) ? 'selected' : '' }}>{{ $item }}</option>
                @endforeach
            </select>
        </div>

        {{-- وعده‌ها --}}
        <div class="mb-2">
            <label>وعده‌های مورد نیاز</label>
            @foreach(['صبحانه', 'ناهار', 'شام', 'میانوعده'] as $meal)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="required_meals[]" value="{{ $meal }}" id="meal_{{ $meal }}" {{ in_array($meal, $program->required_meals ?? []) ? 'checked' : '' }}>
                    <label class="form-check-label" for="meal_{{ $meal }}">{{ $meal }}</label>
                </div>
            @endforeach
        </div>

        {{-- رایگان بودن --}}
        <div class="mb-2">
            <label>آیا برنامه رایگان است؟</label>
            <select name="is_free" id="is_free" class="form-control">
                <option value="1" {{ $program->is_free ? 'selected' : '' }}>بله</option>
                <option value="0" {{ !$program->is_free ? 'selected' : '' }}>خیر</option>
            </select>
        </div>

        <div id="payment_section">
            <div class="mb-2">
                <label>هزینه عضو</label>
                <input type="number" name="member_cost" class="form-control" value="{{ $program->member_cost }}">
            </div>
            <div class="mb-2">
                <label>هزینه مهمان</label>
                <input type="number" name="guest_cost" class="form-control" value="{{ $program->guest_cost }}">
            </div>
            <div class="mb-2">
                <label>شماره کارت</label>
                <input type="text" name="card_number" class="form-control" value="{{ $program->card_number }}">
            </div>
            <div class="mb-2">
                <label>شماره شبا</label>
                <input type="text" name="sheba_number" class="form-control" value="{{ $program->sheba_number }}">
            </div>
            <div class="mb-2">
                <label>نام دارنده کارت</label>
                <input type="text" name="card_holder" class="form-control" value="{{ $program->card_holder }}">
            </div>
            <div class="mb-2">
                <label>نام بانک</label>
                <input type="text" name="bank_name" class="form-control" value="{{ $program->bank_name }}">
            </div>
        </div>

        <div class="mb-2">
            <label>ثبت‌نام باز است؟</label>
            <select name="is_registration_open" id="is_registration_open" class="form-control">
                <option value="1" {{ $program->is_registration_open ? 'selected' : '' }}>بله</option>
                <option value="0" {{ !$program->is_registration_open ? 'selected' : '' }}>خیر</option>
            </select>
        </div>

        <div class="mb-2" id="registration_section">
            <label>مهلت ثبت‌نام</label>
            <input type="date" name="registration_deadline" class="form-control" value="{{ $program->registration_deadline }}">
        </div>

        <div class="mb-2">
            <label>آپلود عکس‌های جدید (حداکثر ۱۰ عدد)</label>
            <input type="file" name="report_photos[]" class="form-control" multiple accept="image/*">
        </div>

        <div class="mb-2">
            <label>توضیحات</label>
            <textarea name="description" class="form-control ckeditor">{{ $program->description }}</textarea>
        </div>

        <button class="btn btn-primary" style="width: 100%;">بروزرسانی برنامه</button>
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
