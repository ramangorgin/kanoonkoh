<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Carbon\Carbon;
use Ipe\Sdk\Facades\SmsIr;

class AuthController extends Controller
{
    // Entering Phone Form
    // ==========================
    public function showPhoneForm()
    {
        return view('auth.phone');
    }
    // ==========================

    // Sending OTP Request
    // ==========================
    public function requestOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:11'
        ]);

        $phone = $request->phone;

        // Generating 4-digts Code
        $otp = rand(1000, 9999);

        // Finding the User or Creating One
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            // Creating the User
            $user = new User();
            $user->phone = $phone;
        }

        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        // Sending SMS
        $templateId = 218734; // sms.ir لیست قالب‌های
        $parameters = [
            [
                "name" => "CODE",
                "value" => (string) $otp
            ]
        ];

        try {
            $response = SmsIr::verifySend($phone, $templateId, $parameters);
        } catch (\Exception $e) {
            return back()->withErrors(['sms' => 'خطا در ارسال پیامک: ' . $e->getMessage()]);
        }

        // Saving the Phone Number for next steps
        Session::put('auth_phone', $phone);

        return redirect()->route('auth.verifyForm')->with('status', 'کد تایید ارسال شد');
    }
    // ==========================

    // Entering 4-digits Code Form
    // ==========================
    public function showVerifyForm()
    {
        return view('auth.verify');
    }
    // ==========================

    // Varificating OTP Code
    // ==========================
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:4'
        ]);

        $phone = Session::get('auth_phone');
        if (!$phone) {
            return redirect()->route('auth.phone')->withErrors(['phone' => 'شماره تلفن یافت نشد']);
        }

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return redirect()->route('auth.phone')->withErrors(['phone' => 'کاربر یافت نشد']);
        }

        // Checking the Code
        if ($user->otp_code == $request->otp && Carbon::now()->lt($user->otp_expires_at)) {
            // Deleting Code after using
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->save();

            // if User has other data → Go to Dashboard
            if ($user->name) {
                Auth::login($user);
                return redirect()->route('dashboard');
            }

            // if the User is New → Go to next step
            return redirect()->route('auth.register.step1');
        }

        return back()->withErrors(['otp' => 'کد تایید اشتباه یا منقضی شده است']);
    }
    // ==========================

    // ==========================
    // Wizard ثبت‌نام - مرحله ۱
    // ==========================
    public function showRegisterStep1()
    {
        return view('auth.register_wizard.step1');
    }

    public function storeRegisterStep1(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $phone = Session::get('auth_phone');
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return redirect()->route('auth.phone')->withErrors(['phone' => 'کاربر یافت نشد']);
        }

        $user->name = $request->name;
        $user->save();

        return redirect()->route('auth.register.step2');
    }

    // ==========================
    // Wizard ثبت‌نام - مرحله ۲
    // ==========================
    public function showRegisterStep2()
    {
        return view('auth.register_wizard.step2');
    }

    public function storeRegisterStep2(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email|unique:users,email'
        ]);

        $phone = Session::get('auth_phone');
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return redirect()->route('auth.phone')->withErrors(['phone' => 'کاربر یافت نشد']);
        }

        $user->email = $request->email;
        $user->save();

        return redirect()->route('auth.register.step3');
    }

    // ==========================
    // Wizard ثبت‌نام - مرحله ۳
    // ==========================
    public function showRegisterStep3()
    {
        return view('auth.register_wizard.step3');
    }

    public function storeRegisterStep3(Request $request)
    {
        $request->validate([
            'extra_info' => 'nullable|string|max:500'
        ]);

        $phone = Session::get('auth_phone');
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return redirect()->route('auth.phone')->withErrors(['phone' => 'کاربر یافت نشد']);
        }

        // اینجا هر فیلد اضافی خواستی ذخیره کن
        // مثلا: $user->address = $request->extra_info;

        $user->save();

        return redirect()->route('auth.register.complete');
    }

    // ==========================
    // Wizard ثبت‌نام - پایان
    // ==========================
    public function registerComplete()
    {
        $phone = Session::get('auth_phone');
        $user = User::where('phone', $phone)->first();

        if ($user) {
            Auth::login($user);
        }

        return view('auth.register_wizard.complete');
    }
}
