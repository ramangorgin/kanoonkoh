<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>لیست ثبت‌نام‌ها</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; direction: rtl; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 8px; font-size: 12px; }
        th { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <h2>لیست {{ $approved ? 'تأیید شده' : 'رد شده' }} ثبت‌نام‌ها</h2>

    <table>
        <thead>
            <tr>
                <th>نام</th>
                <th>کد ملی</th>
                <th>تاریخ تولد</th>
                <th>نام پدر</th>
                <th>شماره تماس</th>
                <th>تماس اضطراری</th>
                <th>نوع</th>
                <th>محل سوار شدن</th>
                <th>تاریخ ثبت‌نام</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registrations as $item)
                <tr>
                    <td>{{ $item->user ? $item->user->name : $item->guest_name }}</td>
                    <td>{{ $item->user->profile->national_id ?? $item->guest_national_id }}</td>
                    <td>{{ $item->user->profile->birth_date ?? $item->guest_birth_date }}</td>
                    <td>{{ $item->user->profile->father_name ?? $item->guest_father_name }}</td>
                    <td>{{ $item->user->profile->phone ?? $item->guest_phone }}</td>
                    <td>{{ $item->user->profile->emergency_phone ?? $item->guest_emergency_phone }}</td>
                    <td>{{ $item->type === 'program' ? 'برنامه' : 'دوره' }}</td>
                    <td>{{ $item->ride_location ?? '-' }}</td>
                    <td>{{ jdate($item->created_at)->format('Y/m/d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
