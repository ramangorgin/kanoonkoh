@extends('layouts.admin')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">گزارش‌ها</a></li>
            <li class="breadcrumb-item active" aria-current="page">ویرایش گزارش</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h3>ویرایش گزارش</h3>

    <form method="POST" action="{{ route('admin.reports.update', $report->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>عنوان گزارش</label>
            <input type="text" name="title" class="form-control" value="{{ $report->title }}" required>
        </div>

        <div class="mb-3">
            <label>متن گزارش</label>
            <textarea name="content" class="form-control ckeditor">{{ $report->content }}</textarea>
        </div>

        <div class="mb-3">
            <label>نوع گزارش</label>
            <select name="type" class="form-control">
                <option value="تمرینی" @selected($report->type == 'تمرینی')>تمرینی</option>
                <option value="صعود" @selected($report->type == 'صعود')>صعود</option>
                <option value="آموزشی" @selected($report->type == 'آموزشی')>آموزشی</option>
            </select>
        </div>

        <div class="mb-3">
            <label>وضعیت تایید</label>
            <select name="approved" class="form-control">
                <option value="0" @selected(!$report->approved)>در انتظار تایید</option>
                <option value="1" @selected($report->approved)>تایید شده</option>
            </select>
        </div>

        <div class="mb-3">
            <label>آپلود گالری تصاویر جدید (در صورت نیاز)</label>
            <input type="file" name="gallery[]" multiple class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
            <label>فایل PDF جدید (در صورت نیاز)</label>
            <input type="file" name="pdf_path" class="form-control" accept=".pdf">
        </div>

        <button class="btn btn-primary" style="width: 100%;">ذخیره تغییرات</button>
    </form>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content');
</script>
@endsection
