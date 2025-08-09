<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Course;
use App\Models\Registration;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RegistrationsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Morilog\Jalali\Jalalian;


class RegistrationController extends Controller
{

    public function createProgram($programId)
    {
        $program = Program::findOrFail($programId);
        return view('admin.registrations.create', [
            'type' => 'program',
            'related_id' => $program->id,
            'is_free' => $program->is_free,
            'has_transport' => $program->has_transport,
            'member_cost' => $program->is_free ? 0 : $program->member_cost,
            'guest_cost' => $program->is_free ? 0 : $program->guest_cost,
            'program' => $program
        ]);
    }


    public function createCourse($courseId)
    {
        $course = Course::findOrFail($courseId);
        return view('admin.registrations.create', [
            'type' => 'course',
            'related_id' => $course->id,
            'is_free' => $course->is_free,
            'amount' => $course->cost,
            'course' => $course
        ]);
    }

    public function ProgramStore(Request $request, Program $program)
    {
        $rules = [
            'transaction_code'       => $program->is_free ? 'nullable' : 'required|string',
            'payment_date'           => $program->is_free ? 'nullable' : 'required|string', 
            'receipt_file'           => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'pickup_location'        => $program->has_transport ? 'required|in:tehran,karaj' : 'nullable',


            'guest_name'             => 'nullable|string',
            'guest_national_id'      => 'nullable|string',
            'guest_birth_date'       => 'nullable|string',
            'guest_father_name'      => 'nullable|string',
            'guest_phone'            => 'nullable|string',
            'guest_emergency_phone'  => 'nullable|string',
            'guest_insurance_file'   => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ];

        if (!Auth::check()) {
            $rules = array_merge($rules, [
                'guest_name'             => 'required|string',
                'guest_national_id'      => 'required|string',
                'guest_birth_date'       => 'required|string',
                'guest_father_name'      => 'required|string',
                'guest_phone'            => 'required|string',
                'guest_emergency_phone'  => 'required|string',
                'guest_insurance_file'   => 'required|file|mimes:jpg,png,pdf|max:2048',
            ]);
        }

        $data = $request->validate($rules);

        if (!$program->is_free && !empty($data['payment_date'])) {
            $shamsi = $this->toEnglishDigits($data['payment_date']);    
            $data['payment_date'] = Jalalian::fromFormat('Y/m/d', $shamsi)
                ->toCarbon()
                ->toDateString(); 
        } else {
            $data['payment_date']   = null;
            $data['transaction_code'] = null;
            $data['receipt_file']   = null;
        }

        if ($request->hasFile('receipt_file')) {
            $data['receipt_file'] = $request->file('receipt_file')->store('receipts/programs', 'public');
        }
        if ($request->hasFile('guest_insurance_file')) {
            $data['guest_insurance_file'] = $request->file('guest_insurance_file')->store('insurances/guests', 'public');
        }
        if ($program->has_transport) {
            $data['pickup_location'] = in_array($request->pickup_location, ['tehran','karaj'], true)
                ? $request->pickup_location
                : null; 
        } else {
            $data['pickup_location'] = null;
        }


        $data['type']       = 'program';      
        $data['related_id'] = $program->id;  
        
        unset($data['program_id']);

        if (Auth::check()) {
            $data['user_id'] = Auth::id();
            $data['guest_name']            = null;
            $data['guest_phone']           = null;
            $data['guest_national_id']     = null;
            $data['guest_birth_date']      = null;
            $data['guest_father_name']     = null;
            $data['guest_emergency_phone'] = null;
        }

        // چک تکراری با اسکیمای جدید
        $already = Auth::check()
            ? \App\Models\Registration::where('type','program')
                ->where('related_id', $program->id)
                ->where('user_id', Auth::id())
                ->exists()
            : \App\Models\Registration::where('type','program')
                ->where('related_id', $program->id)
                ->where('guest_national_id', $this->toEnglishDigits($request->guest_national_id))
                ->exists();

        if ($already) {
            return back()->with('error', 'قبلاً در این برنامه ثبت‌نام کرده‌اید.');
        }

        Registration::create($data);

        return redirect()
            ->route('programs.show', $program->id)
            ->with('success', 'ثبت‌نام شما با موفقیت انجام شد. پس از تأیید اطلاع‌رسانی خواهد شد.');
    }

    private function toEnglishDigits(string $str): string
    {
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹','٫','،'];
        $latin   = ['0','1','2','3','4','5','6','7','8','9','. ', ','];
        return str_replace($persian, $latin, $str);
    }

    public function CourseStore(Request $request, Course $course)
    {
        $isFree = (property_exists($course, 'is_free') && $course->is_free)
            || (isset($course->cost) && (int)$course->cost === 0);

        $rules = [
            'transaction_code'       => $isFree ? 'nullable' : 'required|string',
            'payment_date'           => $isFree ? 'nullable' : 'required|string', // از فرم شمسی می‌آید
            'receipt_file'           => 'nullable|file|mimes:jpg,png,pdf|max:2048',

            'guest_name'             => 'nullable|string',
            'guest_national_id'      => 'nullable|string',
            'guest_birth_date'       => 'nullable|string',
            'guest_father_name'      => 'nullable|string',
            'guest_phone'            => 'nullable|string',
            'guest_emergency_phone'  => 'nullable|string',
            'guest_insurance_file'   => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ];

        if (!Auth::check()) {
            $rules = array_merge($rules, [
                'guest_name'             => 'required|string',
                'guest_national_id'      => 'required|string',
                'guest_birth_date'       => 'required|string',
                'guest_father_name'      => 'required|string',
                'guest_phone'            => 'required|string',
                'guest_emergency_phone'  => 'required|string',
                'guest_insurance_file'   => 'required|file|mimes:jpg,png,pdf|max:2048',
            ]);
        }

        $data = $request->validate($rules);

        if (!$isFree && !empty($data['payment_date'])) {
            $shamsi = $this->toEnglishDigits($data['payment_date']);
            $data['payment_date'] = Jalalian::fromFormat('Y/m/d', $shamsi)
                ->toCarbon()
                ->toDateString(); 
        } else {
            $data['payment_date']    = null;
            $data['transaction_code'] = null;
            $data['receipt_file']    = null;
        }

        if ($request->hasFile('receipt_file')) {
            $data['receipt_file'] = $request->file('receipt_file')->store('receipts/courses', 'public');
        }
        if ($request->hasFile('guest_insurance_file')) {
            $data['guest_insurance_file'] = $request->file('guest_insurance_file')->store('insurances/guests', 'public');
        }

        $data['type']            = 'course';
        $data['related_id']      = $course->id;
        $data['payment_id']      = null;
        $data['pickup_location'] = null; 

        if (Auth::check()) {
            $data['user_id'] = Auth::id();
            $data['guest_name'] = $data['guest_phone'] = $data['guest_national_id'] =
            $data['guest_birth_date'] = $data['guest_father_name'] =
            $data['guest_emergency_phone'] = null;
        }

        $already = Auth::check()
            ? Registration::where('type', 'course')
                ->where('related_id', $course->id)
                ->where('user_id', Auth::id())
                ->exists()
            : Registration::where('type', 'course')
                ->where('related_id', $course->id)
                ->where('guest_national_id', $this->toEnglishDigits($request->guest_national_id))
                ->exists();

        if ($already) {
            return back()->with('error', 'قبلاً در این دوره ثبت‌نام کرده‌اید.');
        }

        if (isset($course->capacity) && (int)$course->capacity > 0) {
            $regCount = Registration::where('type', 'course')
                ->where('related_id', $course->id)
                ->count();

            if ($regCount >= (int)$course->capacity) {
                return back()->with('error', 'ظرفیت این دوره تکمیل شده است.');
            }
        }

        Registration::create($data);

        return redirect()
            ->route('courses.show', $course->id)
            ->with('success', 'ثبت‌نام شما با موفقیت انجام شد. پس از تأیید اطلاع‌رسانی خواهد شد.');
    }

    public function index()
    {
        $programs = Program::latest()->take(10)->get();
        $courses = Course::latest()->take(10)->get();

        return view('admin.registrations.index', compact('programs', 'courses'));
    }

    public function show(Request $request, $type, $id)
    {
        if (!in_array($type, ['program', 'course'])) {
            abort(404);
        }

        $model = $type === 'program' ? Program::findOrFail($id) : Course::findOrFail($id);

       $registrations = Registration::with('user')
            ->where('type', $type)
            ->where('related_id', $id)
            ->when($request->has('filter'), function ($query) use ($request) {
                if ($request->filter === 'approved') {
                    $query->where('is_approved', true);
                } elseif ($request->filter === 'rejected') {
                    $query->where('is_approved', false);
                }
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('first_name', 'like', "%{$request->search}%")
                    ->orWhere('last_name', 'like', "%{$request->search}%");
                });
            })
            ->get();


        return view('admin.registrations.show', [
            'type' => $type,
            'model' => $model,
            'registrations' => $registrations,
        ]);
    }

    public function approve(Registration $registration)
    {
        $registration->update(['is_approved' => true]);
        return back()->with('success', 'ثبت‌نام تایید شد.');
    }

    public function reject(Registration $registration)
    {
        $registration->update(['is_approved' => false]);
        return back()->with('error', 'ثبت‌نام رد شد.');
    }

    public function export($type, $id, Request $request)
    {
        if (!in_array($type, ['program', 'course'])) {
            abort(404);
        }

        $approved = $request->input('filter') === 'approved';
        $filename = $type . "_{$id}_" . ($approved ? 'approved' : 'rejected') . "_registrations.xlsx";

        return Excel::download(new RegistrationsExport($type, $id, $approved), $filename);
    }


    public function exportPdf($type, $id, Request $request)
    {
        if (!in_array($type, ['program', 'course'])) {
            abort(404);
        }

        $approved = $request->input('filter') === 'approved';

        $registrations = Registration::with(['user.profile'])
            ->where('type', $type)
            ->where('type_id', $id)
            ->where('approved', $approved)
            ->get();

        $pdf = Pdf::loadView('admin.registrations.export-pdf', [
            'registrations' => $registrations,
            'approved' => $approved,
        ])->setPaper('a4', 'landscape');

        $filename = $type . "_{$id}_" . ($approved ? 'approved' : 'rejected') . "_registrations.pdf";
        return $pdf->download($filename);
    }

}
