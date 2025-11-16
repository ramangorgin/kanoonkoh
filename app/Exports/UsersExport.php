<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::with('profile')
            ->get()
            ->map(function ($user) {
                return [
                    'ID' => $user->id,
                    'نام' => $user->profile->first_name ?? '',
                    'نام خانوادگی' => $user->profile->last_name ?? '',
                    'شماره تماس' => $user->phone,
                    'وضعیت عضویت' => $user->profile->membership_status ?? '-',
                    'شناسه عضویت' => $user->profile->membership_id ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return ['ID', 'نام', 'نام خانوادگی', 'شماره تماس', 'وضعیت عضویت', 'شناسه عضویت'];
    }
}
