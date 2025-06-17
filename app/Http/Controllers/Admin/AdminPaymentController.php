<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['user.profile', 'relatedProgram', 'relatedCourse'])->latest()->get();
        return view('admin.payments.index', compact('payments'));
    }

    public function approve(Payment $payment)
    {
        $payment->approved = true;
        $payment->save();

        return redirect()->back()->with('success', 'پرداخت تایید شد.');
    }

    public function reject(Payment $payment)
    {
        $payment->approved = null; // یا می‌تونی false بزاری برای رد
        $payment->save();

        return redirect()->back()->with('error', 'پرداخت رد شد.');
    }
}
