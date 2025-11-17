<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $payments = Payment::with('user.profile')->latest()->get();

        return $payments->map(function ($p) {

            $typeMap = [
                'membership' => 'حق عضویت',
                'program'    => 'برنامه',
                'course'     => 'دوره',
            ];

            $statusMap = [
                'pending'  => 'در انتظار بررسی',
                'approved' => 'تأیید شده',
                'rejected' => 'رد شده',
            ];

            return [
                'شناسه عضویت'   => $p->user->profile->membership_id ?? '-',
                'نام کاربر'      => trim(($p->user->profile->first_name ?? '') . ' ' . ($p->user->profile->last_name ?? '')),
                'شماره تماس'     => $p->user->phone ?? '-',
                'نوع پرداخت'     => $typeMap[$p->type] ?? $p->type,
                'مبلغ (تومان)'   => toPersianNumber(number_format($p->amount ?? 0)),
                'شناسه واریز'    => $p->transaction_code ?? '-',
                'وضعیت'          => $statusMap[$p->status] ?? $p->status,
                'تاریخ ایجاد'    => toPersianNumber(jdate($p->created_at)->format('Y/m/d')),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'شناسه عضویت',
            'نام کاربر',
            'شماره تماس',
            'نوع پرداخت',
            'مبلغ (تومان)',
            'شناسه واریز',
            'وضعیت',
            'تاریخ ایجاد',
        ];
    }
}
