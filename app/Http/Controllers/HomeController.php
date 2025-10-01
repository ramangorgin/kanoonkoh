<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Course;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'latestPrograms' => Program::latest()->take(3)->get(),
            'latestCourses' => Course::latest()->take(3)->get(),
        ]);
    }
}
