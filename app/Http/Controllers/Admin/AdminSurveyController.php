<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseSurvey;
use App\Models\ProgramSurvey;

class AdminSurveyController extends Controller
{
    public function courseIndex()
    {
        $surveys = CourseSurvey::latest()->paginate(20);
        return view('admin.surveys.courses.index', compact('surveys'));
    }

    public function programIndex()
    {
        $surveys = ProgramSurvey::latest()->paginate(20);
        return view('admin.surveys.programs.index', compact('surveys'));
    }

    public function stats()
    {
        $courseSurveysCount = CourseSurvey::count();
        $programSurveysCount = ProgramSurvey::count();

        $programSurveys = ProgramSurvey::all();
        $courseSurveys = CourseSurvey::all();
        
        return view('admin.surveys.stats', compact('programSurveys', 'courseSurveys'));
    }
}
