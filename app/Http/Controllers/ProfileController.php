<?php

namespace App\Http\Controllers;

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


        if (!$profile) {
            return redirect()->back()->withErrors(['msg' => 'پروفایل یافت نشد.']);
        }

        return view('user.myProfile', compact('user', 'profile'));
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
            'photo' => 'nullable|image|max:2048',
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

        if ($birthDateRaw && strlen(trim($birthDateRaw)) >= 8) {
            try {
                $birthDateEnglish = $this->convertNumbersToEnglish($birthDateRaw);

                $validated['birth_date'] = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $birthDateEnglish)
                    ->toCarbon()
                    ->format('Y-m-d'); 
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['birth_date' => 'تاریخ تولد معتبر نیست.'])->withInput();
            }
        } else {
            $validated['birth_date'] = null;
        }


        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        if ($request->hasFile('national_card')) {
            $validated['national_card'] = $request->file('national_card')->store('national_cards', 'public');
        }

        $profile = $user->profile;
        if ($profile) {
            $profile->update($validated);
        } else {
            $user->profile()->create($validated);
        }

        return redirect()->back()->with('success', 'مشخصات با موفقیت ذخیره شد.');
    }


    public function update(Request $request, $id)
    {
        $user = Auth::user();

        // بررسی مجوز و وجود پروفایل
        if ($user->id != $id) {
            abort(403, 'شما مجاز به ویرایش این پروفایل نیستید.');
        }

        $profile = $user->profile;
        if (!$profile) {
            return redirect()->back()->withErrors(['msg' => 'پروفایلی برای این کاربر یافت نشد.']);
        }

        // قوانین اعتبارسنجی
        $rules = [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'father_name' => 'nullable|string',
            'id_number' => 'nullable|string',
            'id_place' => 'nullable|string',
            'birth_date' => 'nullable|string',
            'national_id' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'national_card' => 'nullable|file|max:4096',
            'marital_status' => 'nullable|string',
            'emergency_phone' => 'nullable|string',
            'referrer' => 'nullable|string',
            'education' => 'nullable|string',
            'job' => 'nullable|string',
            'home_address' => 'nullable|string',
            'work_address' => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        // 🔹 فقط اعداد فارسی تاریخ رو به انگلیسی تبدیل کن
        if (!empty($validated['birth_date'])) {
            $validated['birth_date'] = $this->convertNumbersToEnglish($validated['birth_date']);
        }

        // 🔹 ذخیره‌ی فایل‌ها (در صورت وجود)
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        if ($request->hasFile('national_card')) {
            $validated['national_card'] = $request->file('national_card')->store('national_cards', 'public');
        }

        // 🔹 به‌روزرسانی اطلاعات
        $profile->update($validated);

        return redirect()->back()->with('success', 'مشخصات با موفقیت به‌روزرسانی شد.');
    }

    private function convertNumbersToEnglish($string)
    {
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $english = ['0','1','2','3','4','5','6','7','8','9'];
        return str_replace($persian, $english, $string);
    }
}
