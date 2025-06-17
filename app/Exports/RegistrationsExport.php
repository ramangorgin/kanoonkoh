<?php

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RegistrationsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Registration::with('user')
            ->where('approved', 1)
            ->get()
            ->map(function ($item) {
                return [
                    'نام' => $item->user ? $item->user->name : $item->guest_name,
                    'کد ملی' => $item->user ? $item->user->profile->national_id ?? '' : $item->guest_national_id,
                    'تاریخ تولد' => $item->user ? $item->user->profile->birth_date ?? '' : $item->guest_birth_date,
                    'نام پدر' => $item->user ? $item->user->profile->father_name ?? '' : $item->guest_father_name,
                    'شماره تماس' => $item->user ? $item->user->profile->phone ?? '' : $item->guest_phone,
                    'شماره تماس اضطراری' => $item->user ? $item->user->profile->emergency_phone ?? '' : $item->guest_emergency_phone,
                    'نوع' => $item->type === 'program' ? 'برنامه' : 'دوره',
                    'محل سوار شدن' => $item->ride_location ?? '',
                    'تاریخ ثبت‌نام' => $item->created_at->format('Y-m-d H:i'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'نام',
            'کد ملی',
            'تاریخ تولد',
            'نام پدر',
            'شماره تماس',
            'شماره تماس اضطراری',
            'نوع',
            'محل سوار شدن',
            'تاریخ ثبت‌نام',
        ];
    }
}

