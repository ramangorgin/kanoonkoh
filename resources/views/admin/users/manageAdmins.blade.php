@extends('layouts.app')

@section('content')
<div class="container">
    <h2>مدیریت ادمین‌ها</h2>

    <form action="{{ route('admin.users.assignAdmin') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="user_id">کاربر مورد نظر:</label>
            <select name="user_id" class="form-control" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success mt-2">ارتقاء به ادمین</button>
    </form>

    <hr>

    <h4 class="mt-4">لیست ادمین‌ها</h4>
    <ul class="list-group">
        @foreach ($admins as $admin)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $admin->name }} ({{ $admin->email }})
                <form action="{{ route('admin.users.removeAdmin', $admin->id) }}" method="POST" onsubmit="return confirm('مطمئنی؟')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">حذف از ادمین‌ها</button>
                </form>
            </li>
        @endforeach
    </ul>
</div>
@endsection
