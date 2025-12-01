@extends('user.layout')

@section('title', 'سوابق آموزشی من')

@section('content')
@php
$user = $user ?? auth()->user();
$histories = $histories ?? collect();
$federationCourses = $federationCourses ?? collect();
@endphp

<div class="container py-4">
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <strong>مرحله ۳ از ۳</strong>
                <div class="text-muted small">سوابق آموزشی — حداقل یک سابقه جهت تکمیل ثبت‌نام اضافه کنید یا بعداً اضافه کنید.</div>
            </div>
            <div style="min-width:220px;">
                <div class="progress" style="height:10px; border-radius:8px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width:100%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm" style="background: rgba(255,255,255,0.92); border-radius:12px;">
        <div class="card-body">
            <h4 class="mb-3"><i class="bi bi-book-half"></i> سوابق آموزشی من</h4>

            <div class="text-end mb-3">
                <button class="btn btn-success" data-bs-toggle="collapse" data-bs-target="#addForm" aria-expanded="false" aria-controls="addForm">
                    <i class="bi bi-plus-circle"></i> افزودن سابقه جدید
                </button>
            </div>

            <!-- Add Form -->
            <div id="addForm" class="collapse {{ $histories->isEmpty() || $errors->any() ? 'show' : '' }}">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div id="client-errors-edu" class="alert alert-danger d-none"></div>
                        <form method="POST" action="{{ route('dashboard.educationalHistory.store') }}" enctype="multipart/form-data" id="multi-course-form">
                            @csrf
                            <div id="courses-list">
                                @if(old('courses'))
                                    {{-- If validation failed, restore all rows from old input --}}
                                    @foreach(old('courses') as $idx => $oldCourse)
                                        <div class="course-item row g-3 align-items-end border rounded p-2 mb-3">
                                            <div class="col-md-4">
                                                <label class="form-label">عنوان دوره <span class="text-danger">*</span></label>
                                                <select class="form-select select-course" name="courses[{{ $idx }}][federation_course_id]">
                                                    <option value="">انتخاب کنید...</option>
                                                    @foreach($federationCourses as $course)
                                                        <option value="{{ $course->id }}" {{ $oldCourse['federation_course_id'] == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                                    @endforeach
                                                    <option value="_custom" {{ ($oldCourse['federation_course_id'] ?? '') == '_custom' || ($oldCourse['federation_course_id'] == null && !empty($oldCourse['custom_course_title'])) ? 'selected' : '' }}>سایر (دوره سفارشی)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 custom-course-wrap" style="display:none;">
                                                <label class="form-label">نام دوره سفارشی <span class="text-danger">*</span></label>
                                                <input type="text" name="courses[{{ $idx }}][custom_course_title]" class="form-control" placeholder="نام دوره" value="{{ $oldCourse['custom_course_title'] ?? '' }}">
                                                <small class="form-text text-muted">نام دوره را وارد کنید</small>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">تاریخ صدور مدرک</label>
                                                <div class="input-group">
                                                    <input type="text" name="courses[{{ $idx }}][issue_date]" class="form-control" data-jdp value="{{ $oldCourse['issue_date'] ?? '' }}">
                                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                                </div>
                                            </div>
                                            <div class="col-12 mt-2 mb-3">
                                                <label class="form-label">فایل مدرک (اختیاری)</label>
                                                <input type="file" name="courses[{{ $idx }}][certificate_file]" class="filepond" accept="image/*,application/pdf">
                                            </div>
                                            <div class="col-md-1 text-end">
                                                <button type="button" class="btn btn-outline-danger remove-course" title="حذف"><i class="bi bi-x-lg"></i></button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    {{-- Initial empty row --}}
                                    <div class="course-item row g-3 align-items-end border rounded p-2 mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">عنوان دوره <span class="text-danger">*</span></label>
                                            <select class="form-select select-course" name="courses[0][federation_course_id]">
                                                <option value="">انتخاب کنید...</option>
                                                @foreach($federationCourses as $course)
                                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                                @endforeach
                                                <option value="_custom">سایر (دوره سفارشی)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 custom-course-wrap" style="display:none;">
                                            <label class="form-label">نام دوره سفارشی <span class="text-danger">*</span></label>
                                            <input type="text" name="courses[0][custom_course_title]" class="form-control" placeholder="نام دوره">
                                            <small class="form-text text-muted">نام دوره را وارد کنید</small>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">تاریخ صدور مدرک</label>
                                            <div class="input-group">
                                                <input type="text" name="courses[0][issue_date]" class="form-control" data-jdp>
                                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-2 mb-3">
                                            <label class="form-label">فایل مدرک (اختیاری)</label>
                                            <input type="file" name="courses[0][certificate_file]" class="filepond" accept="image/*,application/pdf">
                                        </div>
                                        <div class="col-md-1 text-end">
                                            <button type="button" class="btn btn-outline-danger remove-course" title="حذف"><i class="bi bi-x-lg"></i></button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="text-end mt-3">
                                <button type="button" id="add-course-row" class="btn btn-outline-primary me-2">
                                    <i class="bi bi-plus-circle"></i> افزودن دوره
                                </button>
                                <button type="submit" class="btn btn-success">ذخیره</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @if($histories->isEmpty())
                <div class="alert alert-light text-center">هیچ سابقه آموزشی ثبت نشده است. می‌توانید با کلیک روی «افزودن سابقه جدید» شروع کنید.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ردیف</th>
                                <th>عنوان دوره</th>
                                <th>مدرک</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($histories as $index => $history)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $history->federationCourse->title ?? ($history->custom_course_title ?? '---') }}</td>

                                    <td>
                                        @if($history->certificate_file)
                                            <a href="{{ asset('storage/' . $history->certificate_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-file-earmark-arrow-down"></i> مشاهده
                                            </a>
                                        @else
                                            <span class="text-muted">ندارد</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="collapse" data-bs-target="#editRow{{ $history->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('dashboard.educationalHistory.destroy', $history->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('آیا از حذف این سابقه مطمئن هستید؟')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Inline edit collapse -->
                                <tr class="collapse" id="editRow{{ $history->id }}">
                                    <td colspan="4">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-body">
                                            <form method="POST" action="{{ route('dashboard.educationalHistory.update', $history->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                        <label class="form-label">عنوان دوره</label>
                                                            <select class="form-select select-course" name="federation_course_id">
                                                                <option value="">انتخاب کنید...</option>
                                                            @foreach($federationCourses as $course)
                                                                    <option value="{{ $course->id }}" {{ $course->id == $history->federation_course_id ? 'selected' : '' }}>
                                                                    {{ $course->title }}
                                                                </option>
                                                            @endforeach
                                                                <option value="_custom" {{ !$history->federation_course_id ? 'selected' : '' }}>سایر (دوره سفارشی)</option>
                                                        </select>
                                                        <small class="form-text text-muted">از لیست دوره مرتبط را انتخاب کنید</small>
                                                    </div>
                                                        <div class="col-md-6 custom-course-wrap" style="{{ $history->federation_course_id ? 'display:none;' : '' }}">
                                                            <label class="form-label">نام دوره سفارشی</label>
                                                            <input type="text" class="form-control" name="custom_course_title" value="{{ old('custom_course_title', $history->custom_course_title) }}">
                                                            <small class="form-text text-muted">در صورت نبودن در لیست، نام دوره را اینجا وارد کنید</small>
                                                        </div>
                                                        <div class="col-md-6">
                                                        <label class="form-label">تاریخ صدور مدرک</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" data-jdp name="issue_date" value="{{ $history->issue_date_jalali }}">
                                                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                                            </div>
                                                        <small class="form-text text-muted">تاریخ به فرمت شمسی</small>
                                                    </div>
                                                        <div class="col-12 mt-2 mb-4">
                                                        <label class="form-label">فایل مدرک (اختیاری)</label>
                                                            <input type="file" name="certificate_file" class="filepond" accept="image/*,application/pdf">
                                                        @if($history->certificate_file)
                                                            <div class="mt-1"><small class="text-muted">فایل فعلی: {{ basename($history->certificate_file) }}</small></div>
                                                        @endif
                                                    </div>
                                                    </div>
                                                <div class="text-end mt-3">
                                                    <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#editRow{{ $history->id }}">
                                                        انصراف
                                                    </button>
                                                    <button type="submit" class="btn btn-success">ذخیره تغییرات</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $histories->links() }}
                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-success" data-bs-toggle="collapse" data-bs-target="#addForm" aria-expanded="false" aria-controls="addForm">
                        <i class="bi bi-plus-circle"></i> افزودن سابقه جدید
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-3 text-muted small">
        راهنما: بهتر است حداقل یک سابقه وارد کنید تا ثبت‌نام کامل شود. اما می‌توانید بعداً نیز اضافه کنید.
    </div>
</div>

@push('styles')
<style>
  /* Ensure Jalali datepicker renders over Bootstrap modals */
  .jalali-datepicker { z-index: 200000 !important; }
  .jalali-datepicker .jalali-datepicker-legend { z-index: 200001 !important; }
  .jalali-datepicker-portal { z-index: 200000 !important; position: fixed !important; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toastr (snackbar) include and setup
        (function(){
            if (!document.querySelector('link[href*="toastr.min.css"]')) {
                const l = document.createElement('link');
                l.rel = 'stylesheet';
                l.href = 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css';
                document.head.appendChild(l);
            }
            const s = document.createElement('script');
            s.src = 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js';
            s.onload = function(){
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-bottom-center',
                    timeOut: 6000,
                    rtl: true,
                };
                @if(session('success'))
                    toastr.success(@json(session('success')));
                @endif
                @if ($errors ?? false)
                    @foreach (($errors->all() ?? []) as $error)
                        toastr.error(@json($error));
                    @endforeach
                @endif
            };
            document.body.appendChild(s);
        })();
        if (window.jalaliDatepicker && jalaliDatepicker.startWatch) {
            jalaliDatepicker.startWatch({ persianDigits: true });
        }

        @if(session('onboarding'))
        const modalEl = document.getElementById('onboardingEduModal');
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
        @endif
        
        // --- Unified helper for course logic ---
        function setupRowLogic(row) {
            if (row.dataset.logicInitialized) return;
            row.dataset.logicInitialized = "true";

            // 1. Custom course toggle
            const sel = row.querySelector('.select-course');
            const wrap = row.querySelector('.custom-course-wrap');
            if (sel && wrap) {
                const sync = () => {
                    const isCustom = sel.value === '_custom';
                    wrap.style.display = isCustom ? '' : 'none';
                };
                sel.addEventListener('change', sync);
                sync();
            }
            // 2. Lock used courses
            const syncLocks = () => {
                const allSelects = document.querySelectorAll('.select-course');
                const used = new Set();
                allSelects.forEach(s => { if (s.value && s.value !== '_custom') used.add(s.value); });
                allSelects.forEach(s => {
                    const current = s.value;
                    s.querySelectorAll('option').forEach(opt => {
                        if (!opt.value || opt.value === '_custom') return;
                        opt.disabled = used.has(opt.value) && opt.value !== current;
                    });
                });
            };
            if (sel) {
                sel.addEventListener('change', syncLocks);
                syncLocks(); // Initial sync
            }
        }

        // Setup existing rows
        document.querySelectorAll('.course-item, .modal-body, .card-body').forEach(setupRowLogic);

        document.addEventListener('shown.bs.collapse', function(e) {
            const root = e.target;
            if (window.jalaliDatepicker && jalaliDatepicker.startWatch) {
                jalaliDatepicker.startWatch({ persianDigits: true });
            }
            root.querySelectorAll('.select-course').forEach(s => setupRowLogic(s.closest('.row, .card-body')));
            // Lazy load FilePond when section is shown
            initFilePond(root);
        });

        // --- FilePond Loader ---
        let pondFactory = null;
        function initFilePond(root) {
            if (!pondFactory) return; // Wait for script load
            root.querySelectorAll('.filepond').forEach(el => {
                if (el._pond) return; 
                pondFactory(el, {
                    credits: false,
                    allowMultiple: false,
                    storeAsFile: true,
                    allowProcess: false,
                    instantUpload: false,
                    labelIdle: 'فایل خود را اینجا رها کنید یا <span class="filepond--label-action">برای آپلود کلیک کنید</span>',
                    labelInvalidField: 'برخی فیلدها نامعتبر هستند.',
                    labelFileWaitingForSize: 'در حال محاسبه اندازه...',
                    labelFileSizeNotAvailable: 'اندازه نامشخص',
                    labelFileLoading: 'در حال بارگذاری...',
                    labelFileLoadError: 'خطا در بارگذاری فایل.',
                    labelFileProcessing: 'در حال پردازش...',
                    labelFileProcessingComplete: 'بارگذاری کامل شد.',
                    labelFileProcessingAborted: 'بارگذاری لغو شد.',
                    labelFileProcessingError: 'خطا در پردازش فایل. در صورت تداوم با پشتیبانی تماس بگیرید.',
                    labelFileProcessingRevertError: 'خطا در بازگردانی.',
                    labelTapToCancel: 'برای لغو لمس کنید',
                    labelTapToRetry: 'برای تلاش دوباره لمس کنید',
                    labelTapToUndo: 'برای بازگردانی لمس کنید',
                    labelButtonRemoveItem: 'حذف',
                    labelButtonAbortItemLoad: 'لغو',
                    labelButtonRetryItemLoad: 'تلاش دوباره',
                    labelButtonAbortItemProcessing: 'لغو',
                    labelButtonUndoItemProcessing: 'بازگردانی',
                    labelButtonRetryItemProcessing: 'تلاش دوباره',
                    labelButtonProcessItem: 'آپلود',
                    labelMaxFileSizeExceeded: 'حجم فایل بیش از حد مجاز است.',
                    labelMaxFileSize: 'حداکثر حجم مجاز: {filesize}.',
                    labelFileTypeNotAllowed: 'نوع فایل مجاز نیست.',
                    fileValidateTypeLabelExpectedTypes: 'انواع مجاز: {allTypes}',
                });
            });
        }

        (function(){
            if (!document.querySelector('link[href*="filepond.min.css"]')) {
                const css = document.createElement('link');
                css.rel = 'stylesheet';
                css.href = 'https://unpkg.com/filepond@^4/dist/filepond.min.css';
                document.head.appendChild(css);
            }
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/filepond@^4/dist/filepond.min.js';
            script.onload = function(){
                pondFactory = FilePond.create;
                initFilePond(document);
            };
            document.body.appendChild(script);
        })();

        // --- Dynamic Add Row ---
        (function(){
            const list = document.getElementById('courses-list');
            const addBtn = document.getElementById('add-course-row');
            
            // If there are existing rows (e.g. from validation error repopulation), start idx from count
            let idx = list.querySelectorAll('.course-item').length; 
            if (idx === 0) idx = 1; // default start

            const template = (i) => `
                <div class="course-item row g-3 align-items-end border rounded p-2 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">عنوان دوره <span class="text-danger">*</span></label>
                        <select class="form-select select-course" name="courses[${i}][federation_course_id]">
                            <option value="">انتخاب کنید...</option>
                            @foreach($federationCourses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                            <option value="_custom">سایر (دوره سفارشی)</option>
                        </select>
                    </div>
                    <div class="col-md-4 custom-course-wrap" style="display:none;">
                        <label class="form-label">نام دوره سفارشی <span class="text-danger">*</span></label>
                        <input type="text" name="courses[${i}][custom_course_title]" class="form-control" placeholder="نام دوره">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">تاریخ صدور مدرک</label>
                        <div class="input-group">
                            <input type="text" name="courses[${i}][issue_date]" class="form-control" data-jdp>
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        </div>
                    </div>
                    <div class="col-12 mt-2 mb-3">
                        <label class="form-label">فایل مدرک (اختیاری)</label>
                        <input type="file" name="courses[${i}][certificate_file]" class="filepond" accept="image/*,application/pdf">
                    </div>
                    <div class="col-md-1 text-end">
                        <button type="button" class="btn btn-outline-danger remove-course" title="حذف"><i class="bi bi-x-lg"></i></button>
                    </div>
                </div>`;

            addBtn?.addEventListener('click', function(){
                // Use createElement + appendChild to avoid destroying existing DOM (and FilePond instances)
                const div = document.createElement('div');
                div.innerHTML = template(idx);
                const newItem = div.firstElementChild;
                list.appendChild(newItem);
                idx++;
                
                if (window.jalaliDatepicker && jalaliDatepicker.startWatch) {
                    jalaliDatepicker.startWatch({ persianDigits: true });
                }
                
                setupRowLogic(newItem);
                initFilePond(newItem);
            });

            list?.addEventListener('click', function(e){
                if (e.target.closest('.remove-course')) {
                    const item = e.target.closest('.course-item');
                    if (item && list.children.length > 1) {
                        item.remove();
                        // re-sync locks
                        const s = document.querySelector('.select-course'); 
                        if(s) s.dispatchEvent(new Event('change'));
                    }
                }
            });
        })();

        // Basic client-side validation for multi add
        (function(){
            const form = document.getElementById('multi-course-form');
            const errorBox = document.getElementById('client-errors-edu');
            function showErrors(errors){
                if (!errors.length) { errorBox.classList.add('d-none'); errorBox.innerHTML=''; return; }
                errorBox.classList.remove('d-none');
                errorBox.innerHTML = '<ul class="mb-0">' + errors.map(e=>'<li>'+e+'</li>').join('') + '</ul>';
                window.scrollTo({ top: form.getBoundingClientRect().top + window.scrollY - 120, behavior: 'smooth' });
            }
            form?.addEventListener('submit', function(e){
                const errs = [];
                const items = form.querySelectorAll('.course-item');
                items.forEach((item, i) => {
                    const sel = item.querySelector('.select-course');
                    const custom = item.querySelector('.custom-course-wrap input');
                    const date = item.querySelector('input[name^="courses"][name$="[issue_date]"]')?.value?.trim() || '';
                    const selVal = sel?.value || '';
                    const customVal = custom?.value?.trim() || '';
                    if ((selVal === '' || selVal === '_custom') && customVal.length < 3) {
                        errs.push(`ردیف ${i+1}: نام دوره سفارشی را حداقل با ۳ کاراکتر وارد کنید یا یک دوره از فهرست انتخاب کنید.`);
                    }
                    if (date && !/^\d{4}\/\d{2}\/\d{2}$/.test(date)) {
                        errs.push(`ردیف ${i+1}: فرمت تاریخ صحیح نیست (YYYY/MM/DD).`);
                    }
                });
                if (errs.length){ 
                    e.preventDefault(); 
                    if (window.toastr) errs.forEach(m => toastr.error(m));
                    else showErrors(errs);
                }
            });
        })();
    });
</script>
@endpush
