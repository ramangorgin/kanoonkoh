<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Morilog\Jalali\Jalalian;
use App\Models\MedicalRecord;

class MedicalRecordController extends Controller
{

    public function show()
    {
        $user = Auth::user();
        $medical = $user->medicalRecord; 
        return view('user.myMedicalRecord', compact('medical'));
    }


    public function update(Request $request)
    {
        $user = Auth::user();
        $medical = $user->medicalRecord;

        if (!$medical) {
            return redirect()->back()->withErrors(['notfound' => 'پرونده پزشکی یافت نشد.']);
        }

        $validated = $request->validate([
            'insurance_issue_date' => ['nullable', 'string'],
            'insurance_expiry_date'=> ['nullable', 'string'],
            'insurance_file'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'blood_type'           => ['nullable', 'in:O+,O-,A+,A-,B+,B-,AB+,AB-'],
            'height'               => ['nullable', 'integer', 'min:50', 'max:250'],
            'weight'               => ['nullable', 'integer', 'min:20', 'max:250'],
            'commitment_signed'    => ['required', 'boolean'],
        ]);

        if ($request->insurance_issue_date) {
            try {
                $validated['insurance_issue_date'] =
                    Jalalian::fromFormat('Y/m/d', $request->insurance_issue_date)->toCarbon()->format('Y-m-d');
            } catch (\Exception $e) {
                $validated['insurance_issue_date'] = null;
            }
        }

        if ($request->insurance_issue_date) {
            $date = $this->fixPersianNumbers($request->insurance_issue_date);
            try {
                $validated['insurance_issue_date'] =
                    Jalalian::fromFormat('Y/m/d', $date)->toCarbon()->format('Y-m-d');
            } catch (\Exception $e) {
                $validated['insurance_issue_date'] = null;
            }
        }

        if ($request->insurance_expiry_date) {
            $date = $this->fixPersianNumbers($request->insurance_expiry_date);
            try {
                $validated['insurance_expiry_date'] =
                    Jalalian::fromFormat('Y/m/d', $date)->toCarbon()->format('Y-m-d');
            } catch (\Exception $e) {
                $validated['insurance_expiry_date'] = null;
            }
        }

        $validated = array_merge(
            $validated,
            $request->except(['insurance_file', '_token', '_method', 'insurance_issue_date', 'insurance_expiry_date'])
        );

        $medical->update($validated);


        return redirect()->back()->with('success', 'پرونده پزشکی با موفقیت به‌روزرسانی شد.');

    }

    private function fixPersianNumbers($string)
    {
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $english = ['0','1','2','3','4','5','6','7','8','9'];
        return str_replace($persian, $english, $string);
    }

}
