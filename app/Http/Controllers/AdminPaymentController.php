<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsExport;

class AdminPaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('user.profile')->latest()->get();
        return view('admin.payments.index', compact('payments'));
    }

    public function approve($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->status = 'approved';
        $payment->approved = true;
        $payment->save();
        return response()->json(['success' => true]);
    }

    public function reject($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->status = 'rejected';
        $payment->approved = false;
        $payment->save();
        return response()->json(['success' => true]);
    }

    public function export()
    {
        return "Export route works ✅";
    }

    /*
    public function export()
    {
        return Excel::download(new PaymentsExport, 'payments.xlsx');
    }
    */

    public function show($id)
    {
        $payment = \App\Models\Payment::with('user.profile')->findOrFail($id);

        $typeMap = [
            'membership' => 'حق عضویت',
            'program' => 'برنامه',
            'course' => 'دوره',
        ];

        $statusMap = [
            'pending' => ['text' => 'در انتظار بررسی', 'color' => 'secondary'],
            'approved' => ['text' => 'تأیید شده', 'color' => 'success'],
            'rejected' => ['text' => 'رد شده', 'color' => 'danger'],
        ];

        $relatedLink = null;
        if ($payment->type == 'program')
            $relatedLink = "<a href='/admin/programs/{$payment->related_id}' class='btn btn-outline-success mt-2'><i class='bi bi-calendar-event'></i> مشاهده برنامه</a>";
        elseif ($payment->type == 'course')
            $relatedLink = "<a href='/admin/courses/{$payment->related_id}' class='btn btn-outline-warning mt-2'><i class='bi bi-book'></i> مشاهده دوره</a>";

        return response()->json([
            'id' => $payment->id,
            'transaction_code' => $payment->transaction_code,
            'amount' => $payment->amount,
            'type_fa' => $typeMap[$payment->type],
            'date' => jdate($payment->created_at)->format('Y/m/d H:i'),
            'status_text' => $statusMap[$payment->status]['text'],
            'status_color' => $statusMap[$payment->status]['color'],
            'membership_code' => $payment->user->profile->membership_id ?? '-',
            'user_id' => $payment->user->id,
            'user_name' => $payment->user->profile->first_name . ' ' . $payment->user->profile->last_name,
            'user_phone' => $payment->user->phone,
            'related_link' => $relatedLink
        ]);
    }

}
