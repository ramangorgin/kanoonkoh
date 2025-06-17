<?php

namespace App\Http\Controllers;

use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::latest()->paginate(10);
        return view('courses.index', compact('courses'));
    }

    public function showCourses()
    {
        $courses = \App\Models\Course::all();
        return view('dashboard.courses', compact('courses'));
    }
}
