@php
    $user = $user ?? new \App\Models\User();
    $profile = $user->profile ?? new \App\Models\Profile();
    $medical = $user->medicalRecord ?? new \App\Models\MedicalRecord();
    $educations = $user->educationalHistories ?? collect();
    $federationCourses = \App\Models\FederationCourse::all();
@endphp
<div class="accordion" id="userAccordion">

    <div class="accordion-item">
        <h2 class="accordion-header" id="accountHeading">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accountSection">
                <i class="bi bi-person-lines-fill me-2 text-primary"></i> اطلاعات حساب
            </button>
        </h2>
        <div id="accountSection" class="accordion-collapse collapse show">
            <div class="accordion-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">شماره تماس</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="form-control convert-english" inputmode="numeric" pattern="[0-9]*">
                </div>
                <div class="col-md-4">
                    <label class="form-label">نقش</label>
                    <select name="role" class="form-select">
                        <option value="member" {{ old('role', $user->role ?? '') == 'member' ? 'selected' : '' }}>عضو</option>
                        <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>ادمین</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="membershipHeading">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#membershipSection">
                <i class="bi bi-person-badge-fill me-2 text-success"></i> اطلاعات عضویت
            </button>
        </h2>
        <div id="membershipSection" class="accordion-collapse collapse">
            <div class="accordion-body row g-3">
                <div class="col-md-3">
                    <label class="form-label">شناسه عضویت</label>
                    <input type="number" name="membership_id" value="{{ old('membership_id', $profile->membership_id ?? '') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">وضعیت عضویت</label>
                    <select name="membership_status" class="form-select">
                        <option value="pending" {{ old('membership_status', $profile->membership_status ?? '') == 'pending' ? 'selected' : '' }}>در انتظار</option>
                        <option value="approved" {{ old('membership_status', $profile->membership_status ?? '') == 'approved' ? 'selected' : '' }}>تأیید شده</option>
                        <option value="rejected" {{ old('membership_status', $profile->membership_status ?? '') == 'rejected' ? 'selected' : '' }}>رد شده</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">نوع عضویت</label>
                    <input type="text" name="membership_type" value="{{ old('membership_type', $profile->membership_type ?? '') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">تاریخ شروع عضویت</label>
                    <input type="text" id="membership_start" name="membership_start"
                           value="{{ old('membership_start', $jalali['membership_start'] ?? '') }}"
                           class="form-control jalali-date" autocomplete="off">
                </div>
                <div class="col-md-3">
                    <label class="form-label">تاریخ انقضا</label>
                    <input type="text" id="membership_expiry" name="membership_expiry"
                           value="{{ old('membership_expiry', $jalali['membership_expiry'] ?? '') }}"
                           class="form-control jalali-date" autocomplete="off">
                </div>
                <div class="col-md-3">
                    <label class="form-label">تاریخ خروج</label>
                    <input type="text" id="leave_date" name="leave_date"
                           value="{{ old('leave_date', $jalali['leave_date'] ?? '') }}"
                           class="form-control jalali-date" autocomplete="off">
                </div>
            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="personalHeading">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#personalSection">
                <i class="bi bi-person-vcard-fill me-2 text-info"></i> اطلاعات شخصی
            </button>
        </h2>
        <div id="personalSection" class="accordion-collapse collapse">
            <div class="accordion-body row g-3">
                <div class="col-md-3"><label class="form-label">نام</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $profile->first_name ?? '') }}" class="form-control">
                </div>
                <div class="col-md-3"><label class="form-label">نام خانوادگی</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $profile->last_name ?? '') }}" class="form-control">
                </div>
                <div class="col-md-3"><label class="form-label">نام پدر</label>
                    <input type="text" name="father_name" value="{{ old('father_name', $profile->father_name ?? '') }}" class="form-control">
                </div>
                <div class="col-md-3"><label class="form-label">کد ملی</label>
                    <input type="text" name="national_id" value="{{ old('national_id', $profile->national_id ?? '') }}" class="form-control">
                </div>
                <div class="col-md-3"><label class="form-label">شماره شناسنامه</label>
                    <input type="text" name="id_number" value="{{ old('id_number', $profile->id_number ?? '') }}" class="form-control">
                </div>
                <div class="col-md-3"><label class="form-label">محل صدور</label>
                    <input type="text" name="id_place" value="{{ old('id_place', $profile->id_place ?? '') }}" class="form-control">
                </div>
                <div class="col-md-3"><label class="form-label">تاریخ تولد</label>
                    <input type="text" id="birth_date" name="birth_date"
                           value="{{ old('birth_date', $jalali['birth_date'] ?? '') }}"
                           class="form-control jalali-date" autocomplete="off">
                </div>
                <div class="col-md-3"><label class="form-label">وضعیت تأهل</label>
                    <select name="marital_status" class="form-select">
                        <option value="">انتخاب کنید...</option>
                        <option value="مجرد" {{ old('marital_status', $profile->marital_status ?? '') == 'مجرد' ? 'selected' : '' }}>مجرد</option>
                        <option value="متاهل" {{ old('marital_status', $profile->marital_status ?? '') == 'متاهل' ? 'selected' : '' }}>متأهل</option>
                    </select>
                </div>
                <div class="col-md-3"><label class="form-label">شماره اضطراری</label>
                    <input type="text" name="emergency_phone" value="{{ old('emergency_phone', $profile->emergency_phone ?? '') }}" class="form-control">
                </div>
                <div class="col-md-3"><label class="form-label">تحصیلات</label>
                    <input type="text" name="education" value="{{ old('education', $profile->education ?? '') }}" class="form-control">
                </div>
                <div class="col-md-3"><label class="form-label">شغل</label>
                    <input type="text" name="job" value="{{ old('job', $profile->job ?? '') }}" class="form-control">
                </div>
                <div class="col-md-3"><label class="form-label">ارجاع‌دهنده</label>
                    <input type="text" name="referrer" value="{{ old('referrer', $profile->referrer ?? '') }}" class="form-control">
                </div>
                <div class="col-md-6"><label class="form-label">آدرس منزل</label>
                    <textarea name="home_address" class="form-control">{{ old('home_address', $profile->home_address ?? '') }}</textarea>
                </div>
                <div class="col-md-6"><label class="form-label">آدرس محل کار</label>
                    <textarea name="work_address" class="form-control">{{ old('work_address', $profile->work_address ?? '') }}</textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label">عکس پرسنلی</label>
                    <input type="file" name="photo" class="form-control">
                    @php $photo = $profile->photo ?? null; @endphp
                    @if($photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($photo))
                        @php $url = \Illuminate\Support\Facades\Storage::url($photo); $ext = strtolower(pathinfo($photo, PATHINFO_EXTENSION)); @endphp
                        @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                            <img src="{{ $url }}" alt="photo" class="img-thumbnail mt-2" style="max-height:120px;">
                        @else
                            <a href="{{ $url }}" target="_blank" class="d-block mt-2">مشاهده فایل</a>
                        @endif
                    @else
                        @if($photo)
                            <small class="text-muted d-block">(thumbnail not found) stored path: {{ $photo }}</small>
                        @endif
                    @endif
                </div>
                <div class="col-md-3">
                    <label class="form-label">کارت ملی (اسکن)</label>
                    <input type="file" name="national_card" class="form-control">
                    @php $nat = $profile->national_card ?? null; @endphp
                    @if($nat && \Illuminate\Support\Facades\Storage::disk('public')->exists($nat))
                        @if(in_array(strtolower(pathinfo($nat, PATHINFO_EXTENSION)), ['jpg','jpeg','png','gif','webp']))
                            <img src="{{ asset('storage/'.$nat) }}" alt="national_card" class="img-thumbnail mt-2" style="max-height:120px;">
                        @else
                            <a href="{{ asset('storage/'.$nat) }}" target="_blank" class="d-block mt-2">مشاهده فایل</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="medicalHeading">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#medicalSection">
                <i class="bi bi-heart-pulse-fill me-2 text-danger"></i> پرونده پزشکی
            </button>
        </h2>
        <div id="medicalSection" class="accordion-collapse collapse">
            <div class="accordion-body">
                @include('admin.users.partials._medical_form', ['medical' => $medical])
            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="eduHeading">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#eduSection">
                <i class="bi bi-book-fill me-2 text-warning"></i> سوابق آموزشی
            </button>
        </h2>
        <div id="eduSection" class="accordion-collapse collapse">
            <div class="accordion-body">
                @include('admin.users.partials._education_form', [
                    'educations' => $educations,
                    'federationCourses' => $federationCourses
                ])
            </div>
        </div>
    </div>

</div>

@once
@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
<script>
(function(){
    function toPersianDigits(str){
        if(!str) return '';
        const map = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        return String(str).replace(/\d/g,d=>map[+d]);
    }
    function fixPersianNumbers(str) {
        if (!str) return str;
        var pers = [/۰/g,/۱/g,/۲/g,/۳/g,/۴/g,/۵/g,/۶/g,/۷/g,/۸/g,/۹/g],
            eng  = ['0','1','2','3','4','5','6','7','8','9'];
        for (var i=0;i<10;i++) str = str.replace(pers[i], eng[i]);
        var ar = [/٠/g,/١/g,/٢/g,/٣/g,/٤/g,/٥/g,/٦/g,/٧/g,/٨/g,/٩/g];
        for (var j=0;j<10;j++) str = str.replace(ar[j], eng[j]);
        return str;
    }

    $(function(){
        $('.jalali-date').each(function(){
            var $el = $(this);
            $el.persianDatepicker({
                format: 'YYYY/MM/DD',
                initialValue: false,
                autoClose: true,
                observer: true,
                persianDigit: true,
                calendar: { persian: { locale: 'fa' } }
            });
        }); 
        $(document).on('submit', 'form', function(){
            $(this).find('.jalali-date').each(function(){
                $(this).val(fixPersianNumbers($(this).val()));
            });
        });

    });
})();
</script>
@endpush
@endonce
