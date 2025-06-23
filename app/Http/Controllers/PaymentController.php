<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class PaymentController extends Controller
{
      public function UserIndex()
    {
        $user = Auth::user();

        $recentPrograms = $user->programs()->latest('program_user.created_at')->take(10)->get(['programs.id', 'programs.title']);
        $recentCourses = $user->courses()->latest('course_user.created_at')->take(10)->get(['courses.id', 'courses.title']);

        $currentYear = Jalalian::now()->getYear();
        $membershipYears = range($currentYear - 5, $currentYear + 5);

        $payments = $user->payments()->latest()->get();

        return view('user.myPayments', compact(
            'recentPrograms',
            'recentCourses',
            'membershipYears',
            'payments'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:membership,program,course',
            'related_id' => 'nullable|numeric',
            'year' => 'nullable|string',
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'nullable|date',
            'transaction_code' => 'required|string',
            'receipt_file' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ]);

        $validated['payment_date'] = Jalalian::fromFormat('Y/m/d', $this->convertNumbersToEnglish($validated['payment_date']))->toCarbon()->toDateString();


        if ($request->hasFile('receipt_file')) {
            $validated['receipt_file'] = $request->file('receipt_file')->store('receipts', 'public');
        }

        $validated['user_id'] = Auth::id();

        Payment::create($validated);

        return redirect()->back()->with('success', 'پرداخت با موفقیت ثبت شد.');
    }
    private function convertNumbersToEnglish($string)
    {
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $english = ['0','1','2','3','4','5','6','7','8','9'];
        return str_replace($persian, $english, $string);
    }

    public function AdminIndex()
    {
        $payments = Payment::with(['user.profile', 'relatedProgram', 'relatedCourse'])
            ->latest()
            ->get();

        return view('admin.payments', compact('payments'));
    }


    public function approve(Payment $payment)
    {
        $payment->approved = true;
        $payment->save();

        return redirect()->back()->with('success', 'پرداخت تایید شد.');
    }

    public function reject(Payment $payment)
    {
        $payment->approved = null;
        $payment->save();

        return redirect()->back()->with('error', 'پرداخت رد شد.');
    }
}
