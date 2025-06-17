<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;

class AdminCourseController extends Controller
{
    public function index()
    {
        $courses = Course::orderBy('start_date', 'desc')->get();
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teacher' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
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
            'registration_deadline' => 'nullable|date',
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
        return view('admin.courses.show', compact('course'));
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
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
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
            'registration_deadline' => 'nullable|date',
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
