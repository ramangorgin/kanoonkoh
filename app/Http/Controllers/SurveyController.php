<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Program;
use App\Models\CourseSurvey;
use App\Models\ProgramSurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    public function courseForm($course_id)
    {
        $course = Course::findOrFail($course_id);
        return view('admin.surveys.courses.create', compact('course'));
    }

    public function programForm($program_id)
    {
        $program = Program::findOrFail($program_id);
        return view('admin.surveys.programs.create', compact('program'));
    }

    public function submitCourse(Request $request, $course_id)
    {
        $request->validate([
            'content_quality' => 'required|integer|min:1|max:5',
            'teaching_skill' => 'required|integer|min:1|max:5',
            'materials_quality' => 'required|integer|min:1|max:5',
            'usefulness' => 'required|integer|min:1|max:5',
            'instructor_behavior' => 'required|integer|min:1|max:5',
            'feedback_text' => 'nullable|string',
        ]);

        CourseSurvey::create([
            'user_id' => Auth::id(),
            'course_id' => $course_id,
            'is_anonymous' => $request->has('is_anonymous'),
            'content_quality' => $request->content_quality,
            'teaching_skill' => $request->teaching_skill,
            'materials_quality' => $request->materials_quality,
            'usefulness' => $request->usefulness,
            'instructor_behavior' => $request->instructor_behavior,
            'feedback_text' => $request->feedback_text,
        ]);

        return redirect()->back()->with('success', 'نظرسنجی با موفقیت ثبت شد.');
    }

     public function submitProgram(Request $request, $program_id)
    {
        $request->validate([
            'planning_quality' => 'required|integer|min:1|max:5',
            'execution_quality' => 'required|integer|min:1|max:5',
            'leadership_quality' => 'required|integer|min:1|max:5',
            'team_spirit' => 'required|integer|min:1|max:5',
            'safety_and_support' => 'required|integer|min:1|max:5',
            'feedback_text' => 'nullable|string',
        ]);

        ProgramSurvey::create([
            'user_id' => Auth::id(),
            'program_id' => $program_id,
            'is_anonymous' => $request->has('is_anonymous'),
            'planning_quality' => $request->planning_quality,
            'execution_quality' => $request->execution_quality,
            'leadership_quality' => $request->leadership_quality,
            'team_spirit' => $request->team_spirit,
            'safety_and_support' => $request->safety_and_support,
            'feedback_text' => $request->feedback_text,
        ]);

        return redirect()->back()->with('success', 'نظرسنجی با موفقیت ثبت شد.');
    }

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
        $courseSurveys = \App\Models\CourseSurvey::with('course')->latest()->get();
        $programSurveys = \App\Models\ProgramSurvey::with('program')->latest()->get();

        return view('admin.surveys.stats', compact('courseSurveys', 'programSurveys'));
    }


}
