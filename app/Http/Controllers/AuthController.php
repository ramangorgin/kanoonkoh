<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Carbon\Carbon;
use Ipe\Sdk\Facades\SmsIr;
use App\Models\Profile;
use App\Models\MedicalRecord;
use App\Models\Enrollment;
use App\Models\EducationalHistory;
use App\Models\FederationCourse;
use Illuminate\Support\Facades\Validator;


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
        $rules = [
            'phone' => 'required|digits:11',
            'arcaptcha-token' => 'arcaptcha'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->withErrors($errors)->withInput();
        }

        $phone = $request->input('phone') ?? Session::get('auth_phone');

        if (!$phone) {
            return redirect()->route('auth.phone')
                ->withErrors(['phone' => 'شماره تلفن یافت نشد']);
        }

        $otp = rand(1000, 9999);

        $user = User::firstOrCreate(['phone' => $phone]);
        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

            if (config('app.env') === 'local') {
                $templateId = 123456; 
            } else {
                $templateId = 218734;
            }
        $parameters = [
            [
                "name" => "CODE",
                "value" => (string) $otp
            ]
        ];

        try {
            SmsIr::verifySend($phone, $templateId, $parameters);
        } catch (\Exception $e) {
            return back()->withErrors(['sms' => 'خطا در ارسال پیامک: ' . $e->getMessage()]);
        }

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
            if ($user->isRegistrationComplete()) {
                Auth::login($user);
                return redirect()->route('dashboard.index');
            }

            // if the User is New → Go to next step
            return redirect()->route('auth.register.step1');
        }

        return back()->withErrors(['otp' => 'کد تایید اشتباه یا منقضی شده است']);
    }
    // ==========================

    private function nd($v){
        $map = ['۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9',
                '٠'=>'0','١'=>'1','٢'=>'2','٣'=>'3','٤'=>'4','٥'=>'5','٦'=>'6','٧'=>'7','٨'=>'8','٩'=>'9'];
        return strtr((string)$v, $map);
    }
    // ==========================
    // Wizard ثبت‌نام - مرحله ۱
    // ==========================
    public function showRegisterStep1()
    {
        return view('auth.register_wizard.step1');
    }

    public function storeRegisterStep1(Request $request)
    {

        $request->merge([
            'id_number'       => $this->nd($request->input('id_number')),
            'national_id'     => $this->nd($request->input('national_id')),
            'emergency_phone' => $this->nd($request->input('emergency_phone')),
            'birth_date'      => $this->nd($request->input('birth_date')), 
         ]);

        $request->validate([
            'first_name' => ['required', 'string', 'max:50', 'regex:/^[آ-ی\s]+$/u'],
            'last_name'  => ['required', 'string', 'max:50', 'regex:/^[آ-ی\s]+$/u'],
            'father_name'=> ['nullable', 'string', 'max:50', 'regex:/^[آ-ی\s]+$/u'],
            'id_number'  => ['nullable', 'numeric', 'digits_between:1,10'],
            'id_place'   => ['nullable', 'string', 'max:50'],
            'birth_date' => ['required', 'string'], // شمسی میاد
            'national_id'=> ['required', 'digits:10'],
            'photo'      => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'national_card' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'marital_status' => ['nullable', 'in:مجرد,متاهل'],
            'emergency_phone' => ['nullable'],
            'referrer'   => ['nullable', 'string', 'max:100'],
            'education'  => ['nullable', 'string', 'max:100'],
            'job'        => ['nullable', 'string', 'max:100'],
            'home_address' => ['nullable', 'string', 'max:500'],
            'work_address' => ['nullable', 'string', 'max:500'],
        ]);

        // ==============================
        // 2. گرفتن کاربر از سشن
        // ==============================
        $phone = session('auth_phone');
        $user = User::where('phone', $phone)->firstOrFail();

        // ==============================
        // 3. آپلود فایل‌ها
        // ==============================
        $photoPath = $request->file('photo')->store('photos', 'public');
        $cardPath  = $request->file('national_card')->store('national_cards', 'public');

        // ==============================
        // 4. ساخت پروفایل با مدل
        // ==============================
        $profile = new Profile([
            'membership_id'  => Profile::generateMembershipId(),
            'membership_type'=> null, // ادمین بعداً پر می‌کند
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'father_name'    => $request->father_name,
            'id_number'      => $request->id_number,
            'id_place'       => $request->id_place,
            'birth_date'     => $request->birth_date, // Mutator آن را میلادی می‌کند
            'national_id'    => $request->national_id,
            'photo'          => $photoPath,
            'national_card'  => $cardPath,
            'marital_status' => $request->marital_status,
            'emergency_phone'=> $request->emergency_phone,
            'referrer'       => $request->referrer,
            'education'      => $request->education,
            'job'            => $request->job,
            'home_address'   => $request->home_address,
            'work_address'   => $request->work_address,
        ]);

        // 📅 تبدیل تاریخ تولد شمسی → میلادی
        if ($request->filled('birth_date')) {
            try {
                // اعداد فارسی به انگلیسی
                $birthDateEnglish = str_replace(
                    ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'],
                    ['0','1','2','3','4','5','6','7','8','9'],
                    $request->birth_date
                );

                // تبدیل شمسی به میلادی
                [$y, $m, $d] = explode('/', $birthDateEnglish);
                $birthDateGregorian = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', "$y/$m/$d")
                    ->toCarbon()
                    ->toDateString(); // YYYY-MM-DD

                $validated['birth_date'] = $birthDateGregorian;

            } catch (\Exception $e) {
                return back()->withErrors(['birth_date' => 'تاریخ تولد وارد شده معتبر نیست.'])->withInput();
            }
        }


        $user->profile()->save($profile);

        // ==============================
        // 5. هدایت به مرحله بعد
        // ==============================
        return redirect()->route('auth.register.step2')
                        ->with('status', 'مشخصات پایه با موفقیت ثبت شد');
    }


    // ==========================
    // Wizard ثبت‌نام - مرحله ۲
    // ==========================
    public function showRegisterStep2()
    {     
        $phone = session('auth_phone');
        $user  = User::where('phone', $phone)->with('profile')->firstOrFail();

        $age = null;
        if ($user->profile && $user->profile->birth_date) {
            $age = Carbon::parse($user->profile->birth_date)->age; 
        }
        return view('auth.register_wizard.step2', compact('age'));
    }


    public function storeRegisterStep2(Request $request)
    {
        // ✅ ولیدیشن
        $request->validate([
            'insurance_issue_date' => ['nullable', 'string'],
            'insurance_expiry_date'=> ['nullable', 'string'],
            'insurance_file'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],

            'blood_type'   => ['nullable', 'in:O+,O-,A+,A-,B+,B-,AB+,AB-'],
            'height'       => ['nullable', 'integer', 'min:50', 'max:250'],
            'weight'       => ['nullable', 'integer', 'min:20', 'max:250'],

            // سؤالات پزشکی (همه بله/خیر)
            'head_injury'          => ['nullable', 'boolean'],
            'eye_ear_problems'     => ['nullable', 'boolean'],
            'seizures'             => ['nullable', 'boolean'],
            'respiratory'          => ['nullable', 'boolean'],
            'heart'                => ['nullable', 'boolean'],
            'blood_pressure'       => ['nullable', 'boolean'],
            'blood_disorders'      => ['nullable', 'boolean'],
            'diabetes_hepatitis'   => ['nullable', 'boolean'],
            'stomach'              => ['nullable', 'boolean'],
            'kidney'               => ['nullable', 'boolean'],
            'mental'               => ['nullable', 'boolean'],
            'addiction'            => ['nullable', 'boolean'],
            'surgery'              => ['nullable', 'boolean'],
            'skin_allergy'         => ['nullable', 'boolean'],
            'drug_allergy'         => ['nullable', 'boolean'],
            'insect_allergy'       => ['nullable', 'boolean'],
            'dust_allergy'         => ['nullable', 'boolean'],
            'medications'          => ['nullable', 'boolean'],
            'bone_joint'           => ['nullable', 'boolean'],
            'hiv'                  => ['nullable', 'boolean'],
            'treatment'            => ['nullable', 'boolean'],

            // توضیحات شرطی و اضافی
            'head_injury_details'        => ['nullable', 'string', 'max:500'],
            'eye_ear_problems_details'   => ['nullable', 'string', 'max:500'],
            'surgery_details'            => ['nullable', 'string', 'max:500'],
            'medications_details'        => ['nullable', 'string', 'max:500'],
            'treatment_details'          => ['nullable', 'string', 'max:500'],
            'other_conditions'           => ['nullable', 'string', 'max:1000'],

            // تعهدنامه
            'commitment_signed' => ['required', 'boolean'],
        ]);

        // ✅ گرفتن کاربر
        $phone = session('auth_phone');
        $user = User::where('phone', $phone)->firstOrFail();

        // ✅ فایل بیمه
        $insuranceFilePath = null;
        if ($request->hasFile('insurance_file')) {
            $insuranceFilePath = $request->file('insurance_file')->store('insurance', 'public');
        }

        // ✅ تبدیل تاریخ‌ها (شمسی → میلادی با morilog/jalali)
        $insuranceIssueDate = null;
        $insuranceExpiryDate = null;
        try {
            if ($request->insurance_issue_date) {
                [$y, $m, $d] = explode('/', $request->insurance_issue_date);
                $insuranceIssueDate = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', "$y/$m/$d")->toCarbon()->toDateString();
            }
            if ($request->insurance_expiry_date) {
                [$y, $m, $d] = explode('/', $request->insurance_expiry_date);
                $insuranceExpiryDate = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', "$y/$m/$d")->toCarbon()->toDateString();
            }
        } catch (\Exception $e) {
            return back()->withErrors(['date' => 'تاریخ وارد شده معتبر نیست']);
        }

        // ✅ ذخیره رکورد
        $medical = new MedicalRecord([
            'insurance_issue_date' => $insuranceIssueDate,
            'insurance_expiry_date'=> $insuranceExpiryDate,
            'insurance_file'       => $insuranceFilePath,
            'blood_type'           => $request->blood_type,
            'height'               => $request->height,
            'weight'               => $request->weight,
            'head_injury'          => $request->head_injury,
            'head_injury_details'  => $request->head_injury_details,
            'eye_ear_problems'     => $request->eye_ear_problems,
            'eye_ear_problems_details' => $request->eye_ear_problems_details,
            'seizures'             => $request->seizures,
            'respiratory'          => $request->respiratory,
            'heart'                => $request->heart,
            'blood_pressure'       => $request->blood_pressure,
            'blood_disorders'      => $request->blood_disorders,
            'diabetes_hepatitis'   => $request->diabetes_hepatitis,
            'stomach'              => $request->stomach,
            'kidney'               => $request->kidney,
            'mental'               => $request->mental,
            'addiction'            => $request->addiction,
            'surgery'              => $request->surgery,
            'surgery_details'      => $request->surgery_details,
            'skin_allergy'         => $request->skin_allergy,
            'drug_allergy'         => $request->drug_allergy,
            'insect_allergy'       => $request->insect_allergy,
            'dust_allergy'         => $request->dust_allergy,
            'medications'          => $request->medications,
            'medications_details'  => $request->medications_details,
            'bone_joint'           => $request->bone_joint,
            'hiv'                  => $request->hiv,
            'treatment'            => $request->treatment,
            'treatment_details'    => $request->treatment_details,
            'other_conditions'     => $request->other_conditions,
            'commitment_signed'    => $request->commitment_signed,
        ]);

        $user->medicalRecord()->save($medical);

        return redirect()->route('auth.register.step3')
                        ->with('status', 'پرونده پزشکی با موفقیت ثبت شد');
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
            'courses' => ['required', 'array', 'min:1'],
            'courses.*.course' => ['required', 'integer', 'exists:federation_courses,id'],
            'courses.*.date' => ['required', 'string'],
            'courses.*.certificate' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
        ], [
            'courses.required' => 'حداقل یک دوره باید ثبت شود.',
            'courses.*.course.required' => 'انتخاب نام دوره الزامی است.',
            'courses.*.date.required' => 'تاریخ اخذ مدرک الزامی است.',
        ]);

        $phone = session('auth_phone');
        $user = User::where('phone', $phone)->firstOrFail();

        foreach ($request->courses as $index => $courseData) {

            $dateInput = str_replace('-', '/', $this->nd($courseData['date']));
            try {
                $issueDate = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $dateInput)
                    ->toCarbon()
                    ->toDateString();
            } catch (\Exception $e) {
                return back()->withErrors([
                    "courses.$index.date" => "تاریخ وارد شده معتبر نیست"
                ])->withInput();
            }

            $certificatePath = null;
            if (!empty($courseData['certificate'])) {
                $certificatePath = $courseData['certificate']->store('educational_certificates', 'public');
            }

            $history = new \App\Models\EducationalHistory([
                'federation_course_id' => (int) $courseData['course'],
                'issue_date'           => $issueDate, // ✅ اصلاح شد
                'certificate_file'     => $certificatePath,
            ]);

            $user->educationalHistories()->save($history);
        }

        return redirect()->route('auth.register.complete')
                        ->with('status', 'سوابق آموزشی شما با موفقیت ثبت شد.');
    }


    // ==========================
    // Wizard ثبت‌نام - پایان
    // ==========================
    public function registerComplete()
    {
        return view('auth.register_wizard.complete');
    }

    // ==========================
    // Logout
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/auth/phone')->with('status', 'با موفقیت خارج شدید.');
    }
    // ==========================
}
