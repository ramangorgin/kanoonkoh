<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class ReportPublicController extends Controller
{
    public function index()
    {
        $reports = Report::where('approved', true)->latest()->paginate(9);
        return view('reports.index', compact('reports'));
    }

    public function show($id)
    {
        $report = Report::where('id', $id)->where('approved', true)->firstOrFail();
        return view('reports.show', compact('report'));
    }  
}
