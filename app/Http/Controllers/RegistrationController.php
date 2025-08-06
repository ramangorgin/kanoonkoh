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
use App\Models\ProgramRegistration;
use App\Models\CourseRegistration;
use Illuminate\Support\Facades\Auth;


class RegistrationController extends Controller
{

    public function createProgram($programId)
    {
        $program = Program::findOrFail($programId);
        return view('admin.registrations.create', [
            'type' => 'program',
            'related_id' => $program->id,
            'is_free' => $program->is_free,
            'has_transportation' => $program->has_transportation,
            'amount' => $program->is_free ? 0 : $program->cost,
            'program' => $program
        ]);
    }


    public function createCourse($courseId)
    {
        $course = Course::findOrFail($courseId);
        return view('admin.registrations.create', [
            'type' => 'course',
            'related_id' => $course->id,
            'is_free' => $program->is_free,
            'amount' => $course->cost,
            'course' => $course
        ]);
    }




     public function ProgramStore(Request $request, Program $program)
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

    public function CourseStore(Request $request, Course $course)
    {
        $data = $request->validate([
            'transaction_code' => $course->cost ? 'required|string' : 'nullable|string',
            'receipt_file' => 'nullable|file|max:2048',
        ]);

        if ($course->capacity && $course->users()->count() >= $course->capacity) {
            return redirect()->back()->with('error', 'ظرفیت این دوره تکمیل شده است.');
        }

        if ($request->hasFile('receipt_file')) {
            $data['receipt_file'] = $request->file('receipt_file')->store('receipts/courses', 'public');
        }

        $data['user_id'] = Auth::id();
        $data['course_id'] = $course->id;

        CourseRegistration::create($data);

        return redirect()->route('courses.show', $course->id)->with('success', 'ثبت‌نام شما انجام شد. در انتظار تأیید ادمین.');
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
