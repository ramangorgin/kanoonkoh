<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('user.myDashboard', [
            'user' => Auth::user()->load('profile'),
            'needsCompletion' => !$user->profile || !$user->profile->first_name,
            'programs' => $user->programs()->latest()->get(),
            'courses' => $user->courses()->latest()->get(),
            'reports' => $user->reports()->latest()->get(),
        ]);
    }
    public function profile()
    {
        return view('user.myProfile');
    }

    public function insurance()
    {
        return view('user.myInsurance');
    }

    public function payments()
    {
        return view('user.myPayments');
    }

    public function courses()
    {
        return view('user.myCourses');
    }

    public function programs()
    {
        return view('user.myPrograms');
    }

    public function settings()
    {
        return view('user.mySettings');
    }

    // ----------------- Reports -------------------
    public function reportsIndex()
    {
        return view('myReports');
    }

    public function reportsCreate()
    {
        return view('reports.create');
    }

    public function reportsEdit()
    {
        return view('reports.edit');
    }

    public function reportsShow()
    {
        return view('reports.show');
    }
}
