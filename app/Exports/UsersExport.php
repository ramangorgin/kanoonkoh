<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::with('profile')->latest()->get()->map(function ($user) {
            $statusMap = [
                'pending'  => 'در انتظار بررسی',
                'approved' => 'تأیید شده',
                'rejected' => 'رد شده',
            ];

            return [
                'شناسه عضویت'   => $user->profile->membership_id ?? '-',
                'نام'            => $user->profile->first_name ?? '-',
                'نام خانوادگی'   => $user->profile->last_name ?? '-',
                'شماره تماس'     => $user->phone ?? '-',
                'نوع عضویت'      => $user->profile->membership_type ?? '-',
                'وضعیت عضویت'    => $statusMap[$user->profile->membership_status ?? ''] ?? '-',
                'تاریخ شروع عضویت' => optional($user->profile->membership_start)->format('Y/m/d') ?? '-',
                'تحصیلات'        => $user->profile->education ?? '-',
                'شغل'            => $user->profile->job ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'شناسه عضویت',
            'نام',
            'نام خانوادگی',
            'شماره تماس',
            'نوع عضویت',
            'وضعیت عضویت',
            'تاریخ شروع عضویت',
            'تحصیلات',
            'شغل',
        ];
    }
}
