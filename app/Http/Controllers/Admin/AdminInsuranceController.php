<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Insurance;
use Illuminate\Http\Request;

class AdminInsuranceController extends Controller
{
    public function index()
    {
        $insurances = Insurance::with('user.profile')->latest()->get();
        return view('admin.insurances.index', compact('insurances'));
    }
}
