<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PaymentsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Payment::with('user.profile')->get()->map(function($p){
            return [
                'شناسه' => $p->id,
                'نام کاربر' => $p->user->profile->first_name ?? '' . ' ' . $p->user->profile->last_name ?? '',
                'نوع پرداخت' => $p->type,
                'مبلغ (تومان)' => number_format($p->amount),
                'شناسه واریز' => $p->transaction_code,
                'وضعیت' => $p->status,
                'تاریخ ایجاد' => jdate($p->created_at)->format('Y/m/d'),
            ];
        });
    }

    public function headings(): array
    {
        return ['شناسه', 'نام کاربر', 'نوع پرداخت', 'مبلغ (تومان)', 'شناسه واریز', 'وضعیت', 'تاریخ ایجاد'];
    }
}
