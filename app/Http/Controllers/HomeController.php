<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Course;
use App\Models\Report;

class HomeController extends Controller
{
    public function index()
    {
        return view('index', [
            'latestPrograms' => Program::latest()->take(3)->get(),
            'latestCourses' => Course::latest()->take(3)->get(),
            'latestReports' => Report::where('approved', true)->latest()->take(3)->get(),
        ]);
    }
}
