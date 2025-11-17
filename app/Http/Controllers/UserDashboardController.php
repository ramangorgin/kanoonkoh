<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class UserDashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user()->load([
            'profile',
            'medicalRecord',
        ]);

        return view('user.myDashboard', [
            'user' => $user,
            'profile' => $user->profile,
            'medicalRecord' => $user->medicalRecord,
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

}
