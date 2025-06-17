<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Program;
use App\Models\Report;
use App\Models\Payment;
use App\Models\ProgramSurvey;
use App\Models\CourseSurvey;
use App\Models\Ticket;
use App\Models\Registration;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // آمار کلی
        $usersCount = User::count();
        $coursesCount = Course::count();
        $programsCount = Program::count();
        $reportsCount = Report::count();

        // جدیدترین پرداختی‌ها
        $latestPayments = Payment::with('user')->latest()->take(5)->get();

  

        // ثبت‌نام‌های برنامه
        $latestProgramRegs = Registration::with(['user', 'relatedProgram'])
            ->where('type', 'program')
            ->latest()
            ->take(5)
            ->get();

        // ثبت‌نام‌های دوره
        $latestCourseRegs = Registration::with(['user', 'relatedCourse'])
            ->where('type', 'course')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.index', compact(
            'usersCount',
            'coursesCount',
            'programsCount',
            'reportsCount',
            'latestPayments',
            'latestProgramRegs',
            'latestCourseRegs'
        ));
    }
}

