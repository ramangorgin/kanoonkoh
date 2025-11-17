@php
    $educations = $educations ?? collect([new \App\Models\EducationalHistory()]);
@endphp

<div id="education-wrapper">
    @foreach($educations as $index => $edu)
    <div class="education-item border rounded-3 p-3 mb-3 bg-light position-relative">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-edu" aria-label="حذف"></button>

        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label">نام دوره فدراسیون</label>
                <select name="educations[{{ $index }}][federation_course_id]" class="form-select selectpicker" data-live-search="true">
                    <option value="">انتخاب کنید...</option>
                    @foreach($federationCourses as $course)
                        <option value="{{ $course->id }}"
                            {{ old("educations.$index.federation_course_id", $edu->federation_course_id ?? '') == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">فایل گواهینامه</label>
                <input type="file" name="educations[{{ $index }}][certificate_file]" class="form-control">
                @if($edu->certificate_file)
                    <small class="text-muted">فایل فعلی: {{ basename($edu->certificate_file) }}</small>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="text-center mt-3">
    <button type="button" id="add-education" class="btn btn-outline-primary">
        <i class="bi bi-plus-circle"></i> افزودن دوره جدید
    </button>
</div>

<script>
(function(){
    // small helpers to load external files
    function loadScript(url, cb){
        var s = document.createElement('script');
        s.src = url; s.async = true;
        s.onload = cb; s.onerror = cb;
        document.head.appendChild(s);
    }
    function loadCss(url){
        if(document.querySelector('link[href="'+url+'"]')) return;
        var l = document.createElement('link');
        l.rel = 'stylesheet'; l.href = url;
        document.head.appendChild(l);
    }

    // initialize bootstrap-select for selects in "root" (DOM node)
    function initSelectpicker(root){
        root = root || document;
        if (typeof jQuery === 'undefined') {
            console.warn('jQuery not found — selectpicker cannot initialize.');
            return;
        }
        var $root = jQuery(root);

        if (typeof $.fn.selectpicker === 'undefined') {
            // load bootstrap-select CSS + JS (CDN)
            loadCss('https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css');
            loadScript('https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js', function(){
                // small delay to ensure plugin registered
                setTimeout(function(){
                    $root.find('.selectpicker').each(function(){
                        if (!$(this).data('selectpicker')) {
                            $(this).selectpicker();
                        } else {
                            $(this).selectpicker('refresh');
                        }
                    });
                }, 50);
            });
            return;
        }

        // plugin already present -> initialize / refresh
        $root.find('.selectpicker').each(function(){
            if (!$(this).data('selectpicker')) {
                $(this).selectpicker();
            } else {
                $(this).selectpicker('refresh');
            }
        });
    }

    // DOM events
    document.addEventListener('DOMContentLoaded', function(){
        initSelectpicker(document);
    });

    document.addEventListener('click', function(e) {
        // remove education
        if(e.target.closest('.remove-edu')) {
            e.target.closest('.education-item').remove();
        }

        // add education
        if(e.target.id === 'add-education') {
            const wrapper = document.getElementById('education-wrapper');
            const index = wrapper.querySelectorAll('.education-item').length;

            const template = `
            <div class="education-item border rounded-3 p-3 mb-3 bg-light position-relative">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-edu" aria-label="حذف"></button>
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label">نام دوره فدراسیون</label>
                        <select name="educations[${index}][federation_course_id]" class="form-select selectpicker" data-live-search="true">
                            <option value="">انتخاب کنید...</option>
                            @foreach($federationCourses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">فایل گواهینامه</label>
                        <input type="file" name="educations[${index}][certificate_file]" class="form-control">
                    </div>
                </div>
            </div>`;

            wrapper.insertAdjacentHTML('beforeend', template);

            // initialize selectpicker for the newly added item only
            var newItem = wrapper.lastElementChild;
            initSelectpicker(newItem);
        }
    });
})();
</script>
