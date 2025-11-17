@php
    $medical = $medical ?? new \App\Models\MedicalRecord();
@endphp

<div class="row g-3">
    {{-- مشخصات فیزیکی --}}
    <div class="col-md-2">
        <label class="form-label">گروه خونی</label>
        <input type="text" name="blood_type" value="{{ old('blood_type', $medical->blood_type ?? '') }}" class="form-control">
    </div>
    <div class="col-md-2">
        <label class="form-label">قد (cm)</label>
        <input type="number" name="height" value="{{ old('height', $medical->height ?? '') }}" class="form-control">
    </div>
    <div class="col-md-2">
        <label class="form-label">وزن (kg)</label>
        <input type="number" name="weight" value="{{ old('weight', $medical->weight ?? '') }}" class="form-control">
    </div>

    {{-- اطلاعات بیمه --}}
    <div class="col-md-3">
        <label class="form-label">تاریخ صدور بیمه</label>
        <input type="text" id="insurance_issue_date" name="insurance_issue_date" value="{{ old('insurance_issue_date', $medical->insurance_issue_date ?? '') }}" class="form-control jalali-date" autocomplete="off">

    </div>
    <div class="col-md-3">
        <label class="form-label">تاریخ انقضای بیمه</label>
        <input type="text" id="insurance_expiry_date" name="insurance_expiry_date" value="{{ old('insurance_expiry_date', $medical->insurance_expiry_date ?? '') }}" class="form-control jalali-date" autocomplete="off">
    </div>
    <div class="col-md-6">
        <label class="form-label">فایل بیمه</label>
        <input type="file" name="insurance_file" class="form-control">
        @if($medical->insurance_file)
            <small class="text-muted">فایل فعلی: {{ basename($medical->insurance_file) }}</small>
        @endif
    </div>

    <hr class="mt-4 mb-2">

    {{-- پرسش‌های پزشکی --}}
    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-heart-pulse"></i> وضعیت‌های سلامتی</h6>

    @php
        $questions = [
            'head_injury' => 'ضربه به سر',
            'eye_ear_problems' => 'مشکلات چشم یا گوش',
            'seizures' => 'تشنج',
            'respiratory' => 'مشکلات تنفسی',
            'heart' => 'مشکلات قلبی',
            'blood_pressure' => 'فشار خون',
            'blood_disorders' => 'اختلالات خونی',
            'diabetes_hepatitis' => 'دیابت / هپاتیت',
            'stomach' => 'مشکلات معده',
            'kidney' => 'مشکلات کلیه',
            'mental' => 'اختلالات روانی',
            'addiction' => 'اعتیاد',
            'surgery' => 'جراحی',
            'skin_allergy' => 'آلرژی پوستی',
            'drug_allergy' => 'آلرژی دارویی',
            'insect_allergy' => 'آلرژی به نیش حشرات',
            'dust_allergy' => 'آلرژی به گرد و غبار',
            'medications' => 'مصرف دارو',
            'bone_joint' => 'مشکلات استخوان و مفصل',
            'hiv' => 'HIV / ایدز',
            'treatment' => 'درمان در حال انجام',
        ];
    @endphp

    @foreach(array_chunk($questions, 3, true) as $chunk)
        @foreach($chunk as $field => $label)
            <div class="col-md-4">
                <label class="form-check-label">
                    {{-- add data-target to link checkbox with its detail wrapper --}}
                    <input type="checkbox"
                           name="{{ $field }}"
                           value="1"
                           class="form-check-input me-1 medical-toggle"
                           data-target="#{{ $field }}_details_wrapper"
                           {{ old($field, $medical->$field ?? false) ? 'checked' : '' }}>
                    {{ $label }}
                </label>
            </div>
        @endforeach
    @endforeach

    {{-- details wrappers (hidden unless related checkbox is checked) --}}
    @foreach($questions as $field => $label)
        @php
            $detailsName = $field . '_details';
            $detailsValue = old($detailsName, $medical->{$detailsName} ?? '');
            $visible = old($field, $medical->$field ?? false) ? '' : 'display:none';
        @endphp
        <div id="{{ $field }}_details_wrapper" class="col-12 mt-3 detail-wrapper" style="{{ $visible }}">
            <label class="form-label">جزئیات {{ $label }}</label>
            <textarea name="{{ $detailsName }}" class="form-control">{{ $detailsValue }}</textarea>
        </div>
    @endforeach

    <div class="col-12 mt-3">
        <label class="form-label">سایر شرایط خاص</label>
        <textarea name="other_conditions" class="form-control">{{ old('other_conditions', $medical->other_conditions ?? '') }}</textarea>
    </div>
</div>

@push('scripts')
<script>
    (function($){
        $(document).ready(function(){
            // toggle detail wrapper on change
            $(document).on('change', '.medical-toggle', function(){
                var $cb = $(this);
                var target = $cb.data('target');
                if (!target) return;
                if ($cb.is(':checked')) {
                    $(target).slideDown(150);
                } else {
                    // clear textarea inside wrapper and hide
                    $(target).find('textarea, input').val('');
                    $(target).slideUp(150);
                }
            });

            // ensure initial state matches checkboxes (in case server-render differs)
            $('.medical-toggle').each(function(){
                var $cb = $(this);
                var target = $cb.data('target');
                if (!target) return;
                if ($cb.is(':checked')) {
                    $(target).show();
                } else {
                    $(target).hide();
                }
            });

            // optional: before submit ensure unchecked detail fields are cleared (redundant with change handler)
            $('form').on('submit', function(){
                $('.medical-toggle').each(function(){
                    var $cb = $(this);
                    var target = $cb.data('target');
                    if (!target) return;
                    if (!$cb.is(':checked')) {
                        $(target).find('textarea, input').val('');
                    }
                });
            });
        });
    })(jQuery);
</script>
@endpush
