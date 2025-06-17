<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramRegistrationController extends Controller
{
    public function store(Request $request, Program $program)
    {
        $data = $request->validate([
            'guest_name' => 'nullable|string',
            'guest_phone' => 'nullable|string',
            'guest_national_id' => 'nullable|string',
            'transaction_code' => $program->is_free ? 'nullable' : 'required|string',
            'pickup_location' => $program->has_transport ? 'required' : 'nullable',
            'agree' => 'accepted',
        ]);

        $data['program_id'] = $program->id;

        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        } else {
            $data['guest_name'] = $request->guest_name;
            $data['guest_phone'] = $request->guest_phone;
            $data['guest_national_id'] = $request->guest_national_id;
        }

        ProgramRegistration::create($data);

        return redirect()->route('programs.show', $program->id)->with('success', 'ثبت‌نام شما با موفقیت انجام شد. پس از تأیید اطلاع‌رسانی خواهد شد.');
    }
}
