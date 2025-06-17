<table>
    <thead>
        <tr>
            <th>نام</th>
            <th>نام خانوادگی</th>
            <th>ایمیل</th>
            <th>کد ملی</th>
            <th>شماره تماس</th>
            <th>وضعیت</th>
        </tr>
    </thead>
    <tbody>
        @foreach($registrations as $reg)
            <tr>
                <td>{{ $reg->user?->first_name ?? 'مهمان' }}</td>
                <td>{{ $reg->user?->last_name ?? '-' }}</td>
                <td>{{ $reg->user?->email ?? '-' }}</td>
                <td>{{ $reg->national_id }}</td>
                <td>{{ $reg->phone }}</td>
                <td>
                    @if($reg->status === 'approved') تایید شده
                    @elseif($reg->status === 'rejected') رد شده
                    @else در انتظار تایید @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
