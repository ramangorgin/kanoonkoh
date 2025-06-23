<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function archive()
    {
        $courses = Course::latest()->paginate(10);
        return view('courses.archive', compact('courses'));
    }

    public function index()
    {
        $courses = Course::orderBy('start_date', 'desc')->get();
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        $users = User::all();
        return view('courses.create' , compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teacher' => 'nullable|string|max:255',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'place' => 'nullable|string|max:255',
            'place_lat' => 'nullable|numeric',
            'place_lon' => 'nullable|numeric',
            'capacity' => 'nullable|integer',
            'is_free' => 'required|boolean',
            'member_cost' => 'nullable|numeric',
            'guest_cost' => 'nullable|numeric',
            'is_registration_open' => 'required|boolean',
            'registration_deadline' => 'nullable|string',
            'card_number' => 'nullable|string',
            'sheba_number' => 'nullable|string',
            'card_holder' => 'nullable|string',
            'bank_name' => 'nullable|string',
        ]);

        Course::create($validated);

        return redirect()->route('admin.courses.index')->with('success', 'دوره با موفقیت ایجاد شد.');
    }

   public function show(Course $course)
    {
        $user = auth()->user();

        // آیا کاربر در برنامه ثبت‌نام کرده؟
        $userHasParticipated = $course->registrations()
            ->where('user_id', $user->id)
            ->exists();

        // آیا فرم نظرسنجی پر کرده؟
        $userHasSubmittedSurvey = $course->surveys()
            ->where('user_id', $user->id)
            ->exists();

        return view('courses.show', compact(
            'course',
            'userHasParticipated',
            'userHasSubmittedSurvey'
        ));
    }


    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teacher' => 'nullable|string|max:255',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'place' => 'nullable|string|max:255',
            'place_lat' => 'nullable|numeric',
            'place_lon' => 'nullable|numeric',
            'capacity' => 'nullable|integer',
            'is_free' => 'required|boolean',
            'member_cost' => 'nullable|numeric',
            'guest_cost' => 'nullable|numeric',
            'is_registration_open' => 'required|boolean',
            'registration_deadline' => 'nullable|string',
            'card_number' => 'nullable|string',
            'sheba_number' => 'nullable|string',
            'card_holder' => 'nullable|string',
            'bank_name' => 'nullable|string',
        ]);

        $course->update($validated);

        return redirect()->route('admin.courses.index')->with('success', 'دوره با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'دوره با موفقیت حذف شد.');
    }
}
