<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Morilog\Jalali\Jalalian;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('user.myProfile', [
            'profile' => $profile,
            'hasProfile' => $profile !== null,
        ]);
    }

    public function store(Request $request)
    {
    
        $user = Auth::user();

        $rules = [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'father_name' => 'nullable|string',
            'gender' => 'nullable|string',
            'birth_date' => 'required|string',
            'national_id' => 'nullable|string',
            'personal_photo' => 'nullable|image|max:2048',
            'phone' => 'nullable|string',
            'province' => 'nullable|string',
            'city' => 'nullable|string',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'job' => 'nullable|string',
            'referrer' => 'nullable|string',
            'blood_type' => 'nullable|string',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',
            'medications' => 'nullable|string',
            'had_surgery' => 'nullable|boolean',
            'emergency_phone' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_relation' => 'nullable|string',
        ];

        
        $validated = $request->validate($rules);

        $birthDateEnglish = $this->convertNumbersToEnglish($validated['birth_date']);

        $birthDateRaw = $validated['birth_date'] ?? null;

        if ($birthDateRaw && strlen($birthDateRaw) >= 8) {
            $birthDateEnglish = $this->convertNumbersToEnglish($birthDateRaw);
            $validated['birth_date'] = Jalalian::fromFormat('Y/m/d', $birthDateEnglish)->toCarbon();
        } else {
            return redirect()->back()->withErrors(['birth_date' => 'تاریخ تولد وارد نشده یا نامعتبر است.'])->withInput();
        }

        if ($request->hasFile('personal_photo')) {
            $validated['personal_photo'] = $request->file('personal_photo')->store('profiles', 'public');
        }

        $profile = $user->profile;
        if ($profile) {
            $profile->update($validated);
        } else {
            $user->profile()->create($validated);
        }

        return redirect()->back()->with('success', 'مشخصات با موفقیت ذخیره شد.');
    }

    private function convertNumbersToEnglish($string)
    {
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $english = ['0','1','2','3','4','5','6','7','8','9'];
        return str_replace($persian, $english, $string);
    }
}
