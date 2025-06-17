<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseRegistrationController extends Controller
{
    public function store(Request $request, Course $course)
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
}
