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
        return view('surveys.course', compact('course'));
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

        return redirect()->route('dashboard')->with('success', 'نظرسنجی با موفقیت ثبت شد.');
    }

    public function programForm($program_id)
    {
        $program = Program::findOrFail($program_id);
        return view('surveys.program', compact('program'));
    }

    // app/Http/Controllers/Admin/SurveyController.php

    public function stats($type, $id)
    {
        if ($type === 'course') {
            $model = \App\Models\Course::findOrFail($id);
            $surveys = \App\Models\CourseSurvey::where('course_id', $id)->get();
        } elseif ($type === 'program') {
            $model = \App\Models\Program::findOrFail($id);
            $surveys = \App\Models\ProgramSurvey::where('program_id', $id)->get();
        } else {
            abort(404);
        }

        if ($surveys->isEmpty()) {
            return back()->with('error', 'هیچ پاسخی برای این نظرسنجی وجود ندارد.');
        }

        // استخراج میانگین امتیازها
        $questions = [
            'q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'q9', 'q10',
        ];

        $averages = [];
        foreach ($questions as $q) {
            $avg = round($surveys->avg($q), 2);
            $averages[$q] = $avg;
        }

        // درصد ناشناس‌ها
        $anonymous_count = $surveys->where('is_anonymous', true)->count();
        $named_count = $surveys->count() - $anonymous_count;

        // نظرات متنی
        $comments = $surveys->pluck('comments')->filter();

        return view('admin.surveys.stats', compact(
            'type', 'model', 'averages', 'anonymous_count', 'named_count', 'comments'
        ));
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

        return redirect()->route('dashboard')->with('success', 'نظرسنجی با موفقیت ثبت شد.');
    }
}
