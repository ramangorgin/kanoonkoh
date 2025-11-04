@extends('layout')

@section('content')
<div class="container">

    <div class="mb-4">
        <h2 class="text-center mb-3">
            <i class="bi bi-journal-bookmark"></i> مرحله سوم: سوابق آموزشی
        </h2>
        <p class="text-muted text-center">
            لطفاً دوره‌هایی که قبلاً گذرانده‌اید را وارد کنید. 
            برای هر دوره لازم است مدرک و تاریخ اخذ مدرک را ثبت نمایید.
        </p>
    </div>

    <!-- Wizard Progress Bar -->
    <div class="mb-5">
        <div class="progress" style="height: 25px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                 role="progressbar" style="width: 100%;">
                مرحله 3 از 3
            </div>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('auth.register.storeStep3') }}" enctype="multipart/form-data">
        @csrf

        <div id="courses-wrapper">
            <!-- دوره نمونه -->
            <div class="course-item border rounded p-3 mb-3">
                <div class="row g-3 align-items-end">
                    <!-- انتخاب دوره -->
                    <div class="col-md-5">
                        <label class="form-label">نام دوره</label>
                        <select class="form-select course-select" name="courses[0][course]" required>
                            <option value="">انتخاب کنید...</option>
                            <option value="4">کارگاه آموزشی نفته خوانی و کار با قطب نما</option>
                            <option value="1">کار آموزی کوهپیمایی</option>
                            <option value="2">کارگاه آموزشی حفظ محیط کوهستان</option>
                            <option value="3">کارگاه آموزشی هواشناسی کوهستان</option>
                            <option value="14">کارگاه آموزشی کار با GPS</option>
                            <option value="7">کارآموزی برف و یخ</option>
                            <option value="5">کارگاه آموزشی مبانی جستجو در کوه</option>
                            <option value="6">کارآموزی سنگنوردی</option>
                            <option value="8">کارگاه آموزشی پزشکی کوهستان</option>
                            <option value="18">پیشرفته برف و یخ</option>
                            <option value="19">کارآموزی کوهنوردی با اسکی</option>
                            <option value="9">کارآموزی دره نوردی</option>
                            <option value="15">پیشرفته سنگنوردی</option>
                            <option value="20">دوره آبشار یخی</option>
                            <option value="21">کارگاه آموشی نجات در برف</option>
                            <option value="17">کارگاه آموزشی مبانی نجات فنی</option>
                            <option value="16">دوره دیواره نوردی</option>
                            <option value="25">پیشرفته کوهنوردی با اسکی</option>
                            <option value="22">پیشرفته دره نوردی</option>
                            <option value="10">دوره غارپیمایی</option>
                            <option value="12">کار آموزی غارنوردی</option>
                            <option value="13">کارگاه آموزشی نقشه برداری غار</option>
                            <option value="11">کارگاه آموزشی زمین‌شناسی و مورفولوژی کارست</option>
                            <option value="23">پیشرفته غارنوردی</option>
                            <option value="24">امداد و نجات در غار</option>
                            <option value="26">راهنمایان کوهستان سطح باشگاه های کوهپیمایی و کوهنوردی</option>
                            <option value="27">راهنمایان کوهستان سطح باشگاه های غارنوردی</option>
                            <option value="28">راهنمایان کوهستان سطح باشگاه های سنگ نوردی طبیعت</option>
                            <option value="29">راهنمایان کوهستان سطح باشگاه های دره نوردی</option>
                            <option value="30">راهنمایان کوهستان سطح باشگاه های یخ نوردی</option>
                            <option value="31">راهنمایان کوهستان سطح باشگاه های کوهنوردی بالسکی</option>

                        </select>
                    </div>

                    <!-- تاریخ اخذ مدرک -->
                    <div class="col-md-3">
                        <label class="form-label">تاریخ اخذ مدرک</label>
                        <input type="text" class="form-control datepicker" name="courses[0][date]" required>
                    </div>

                    <!-- فایل مدرک -->
                    <div class="col-md-3">
                        <label class="form-label">فایل مدرک</label>
                        <input type="file" class="form-control" name="courses[0][certificate]" accept="image/*,application/pdf">
                        <div class="form-text">فرمت: jpg, png, pdf — حداکثر ۲ مگابایت</div>
                        <div class="form-text">آپلود مدرک اختیاریست اما اکیدا توصیه می‌شود بارگذاری نمایید.</div>
                    </div>

                    <!-- دکمه حذف -->
                    <div class="col-md-1 text-end">
                        <button type="button" class="btn btn-danger remove-course">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- دکمه افزودن -->
        <div class="mb-3">
            <button type="button" id="add-course" class="btn btn-outline-primary">
                <i class="bi bi-plus-circle"></i> افزودن دوره دیگر
            </button>
        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-info">
                ثبت نهایی <i class="bi bi-check2-circle"></i>
            </button>
        </div>
    </form>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-date@1.0.6/dist/persian-date.min.js"></script>

<script>
let courseIndex = 1;

// فعال کردن select2 و datepicker
function initPlugins(context) {
    $(context).find('.course-select').select2({
        placeholder: "انتخاب دوره...",
        width: '100%'
    });
    $(context).find('.datepicker').persianDatepicker({
        format: 'YYYY/MM/DD',
        initialValueType: 'persian',
        autoClose: true,
        observer: true,
        calendar: { persian: { locale: 'fa' } }
    });
}

// اولین بار
$(document).ready(function() {
    initPlugins($('#courses-wrapper'));

    // افزودن دوره
    $('#add-course').click(function() {
        let newCourse = $('.course-item:first').clone();
        newCourse.find('input, select').each(function() {
            let name = $(this).attr('name');
            if (name) {
                let newName = name.replace(/\[\d+\]/, '[' + courseIndex + ']');
                $(this).attr('name', newName).val('');
            }
        });
        newCourse.find('.select2-container').remove();
        $('#courses-wrapper').append(newCourse);
        initPlugins(newCourse);
        courseIndex++;
    });

    // حذف دوره
    $(document).on('click', '.remove-course', function() {
        if ($('.course-item').length > 1) {
            $(this).closest('.course-item').remove();
        }
    });
});
</script>
@endsection
