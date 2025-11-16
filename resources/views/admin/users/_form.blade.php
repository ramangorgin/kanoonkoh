@php
    $user = $user ?? new \App\Models\User();
@endphp

<div class="row g-4">

    {{-- اطلاعات حساب --}}
    <h5 class="text-primary mt-3"><i class="bi bi-person-lines-fill"></i> اطلاعات حساب</h5>
    <div class="col-md-4">
        <label class="form-label">شماره تماس</label>
        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">نقش</label>
        <select name="role" class="form-select">
            <option value="member" {{ old('role', $user->role ?? '') == 'member' ? 'selected' : '' }}>عضو</option>
            <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>ادمین</option>
        </select>
    </div>

    {{-- اطلاعات شخصی --}}
    <h5 class="text-success mt-4"><i class="bi bi-person-vcard-fill"></i> اطلاعات پروفایل</h5>
    <div class="col-md-3">
        <label class="form-label">نام</label>
        <input type="text" name="first_name" value="{{ old('first_name', $user->profile->first_name ?? '') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">نام خانوادگی</label>
        <input type="text" name="last_name" value="{{ old('last_name', $user->profile->last_name ?? '') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">کد ملی</label>
        <input type="text" name="national_id" value="{{ old('national_id', $user->profile->national_id ?? '') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">وضعیت عضویت</label>
        <select name="membership_status" class="form-select">
            <option value="pending" {{ old('membership_status', $user->profile->membership_status ?? '') == 'pending' ? 'selected' : '' }}>در انتظار</option>
            <option value="approved" {{ old('membership_status', $user->profile->membership_status ?? '') == 'approved' ? 'selected' : '' }}>تایید شده</option>
            <option value="rejected" {{ old('membership_status', $user->profile->membership_status ?? '') == 'rejected' ? 'selected' : '' }}>رد شده</option>
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">تحصیلات</label>
        <input type="text" name="education" value="{{ old('education', $user->profile->education ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">شغل</label>
        <input type="text" name="job" value="{{ old('job', $user->profile->job ?? '') }}" class="form-control">
    </div>

    {{-- پرونده پزشکی --}}
    <h5 class="text-danger mt-4"><i class="bi bi-heart-pulse-fill"></i> پرونده پزشکی</h5>
    <div class="col-md-3">
        <label class="form-label">گروه خونی</label>
        <input type="text" name="blood_type" value="{{ old('blood_type', $user->medicalRecord->blood_type ?? '') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">قد (سانتی‌متر)</label>
        <input type="number" name="height" value="{{ old('height', $user->medicalRecord->height ?? '') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">وزن (کیلوگرم)</label>
        <input type="number" name="weight" value="{{ old('weight', $user->medicalRecord->weight ?? '') }}" class="form-control">
    </div>

    {{-- سوابق آموزشی --}}
    <h5 class="text-info mt-4"><i class="bi bi-book-fill"></i> سوابق آموزشی</h5>
    <div class="col-md-4">
        <label class="form-label">کد دوره فدراسیون</label>
        <input type="number" name="federation_course_id" value="{{ old('federation_course_id', $user->educationalHistories->first()->federation_course_id ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">تاریخ صدور</label>
        <input type="date" name="issue_date" value="{{ old('issue_date', $user->educationalHistories->first()->issue_date ?? '') }}" class="form-control">
    </div>

</div>
