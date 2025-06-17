<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Course;
use App\Models\Registration;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RegistrationsExport;
use Barryvdh\DomPDF\Facade\Pdf;


class AdminRegistrationController extends Controller
{
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
            ->where($type . '_id', $id)
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
