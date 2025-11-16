@extends('admin.layout')

@section('title', 'ایجاد کاربر جدید')

@section('content')
<div class="container py-4 animate__animated animate__fadeIn">
    <h4 class="fw-bold mb-4">
        <i class="bi bi-person-plus-fill text-success"></i> ایجاد کاربر جدید
    </h4>

    <form action="{{ route('admin.users.store') }}" method="POST" class="card p-4 shadow-sm border-0">
        @csrf
        @include('admin.users._form', ['user' => null])


        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-success px-4">
                <i class="bi bi-check-circle"></i> ثبت کاربر
            </button>
        </div>
    </form>
</div>
@endsection
