@extends('layouts.admin')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">بیمه‌ها</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container mt-4">
<h4 class="mb-4">لیست بیمه‌های ورزشی</h4>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table id="insuranceTable" class="table table-bordered table-striped table-hover">
<thead class="thead-dark">
<tr>
<th>نام عضو</th>
<th>شماره بیمه</th>
<th>تاریخ صدور</th>
<th>تاریخ اعتبار</th>
<th>فایل بیمه</th>
</tr>
</thead>
<tbody>
@foreach($insurances as $insurance)
<tr>
<td>{{ $insurance->user->profile->first_name ?? '' }} {{ $insurance->user->profile->last_name ?? '' }}</td>
<td>{{ $insurance->insurance_number }}</td>
<td>{{ jdate($insurance->issued_at)->format('Y/m/d') }}</td>
<td>{{ jdate($insurance->expires_at)->format('Y/m/d') }}</td>
<td>
@if($insurance->file_path)
<a href="{{ asset('storage/' . $insurance->file_path) }}" target="_blank" class="btn btn-sm btn-primary">
دانلود فایل
</a>
@else
<span class="text-danger">ندارد</span>
@endif
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    $('#insuranceTable').DataTable({
        language: {
            "search": "جستجو:",
            "lengthMenu": "نمایش _MENU_ رکورد در هر صفحه",
            "zeroRecords": "موردی یافت نشد",
            "info": "نمایش صفحه _PAGE_ از _PAGES_",
            "infoEmpty": "رکوردی موجود نیست",
            "infoFiltered": "(فیلتر شده از _MAX_ رکورد)"
        }
    });
});
</script>
@endsection
