<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::where('user_id', auth()->id())
                 ->orWhere('writer_name', auth()->user()->full_name)
                 ->latest()->get();

        return view('dashboard.reports.index', compact('reports'));
    }

    public function show($id)
    {
        $report = Report::where(function($query) {
                        $query->where('user_id', Auth::id())
                              ->orWhere('writer_name', Auth::user()->full_name);
                    })->findOrFail($id);
    
        return view('dashboard.reports.show', compact('report'));
    }
    

    public function create()
    {
        $users = \App\Models\User::all();
        $programs = \App\Models\Program::all();

        return view('dashboard.reports.create', compact('users', 'programs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'program_id' => 'nullable|exists:programs,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'type' => 'nullable|string',
            'area' => 'nullable|string',
            'peak_name' => 'nullable|string',
            'peak_height' => 'nullable|integer',
            'start_altitude' => 'nullable|integer',
            'duration' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'technical_level' => 'nullable|string',
            'road_type' => 'nullable|string',
            'transportation' => 'nullable|array',
            'water_type' => 'nullable|array',
            'signal_status' => 'nullable|boolean',
            'required_equipment' => 'nullable|array',
            'required_skills' => 'nullable|array',
            'difficulty' => 'nullable|string',
            'slope_angle' => 'nullable|string',
            'has_stone_climbing' => 'nullable|boolean',
            'has_ice_climbing' => 'nullable|boolean',
            'average_backpack_weight' => 'nullable|string',
            'natural_description' => 'nullable|string',
            'weather' => 'nullable|string',
            'wind_speed' => 'nullable|string',
            'temperature' => 'nullable|string',
            'vegetation' => 'nullable|string',
            'wildlife' => 'nullable|string',
            'local_language' => 'nullable|string',
            'historical_sites' => 'nullable|string',
            'important_notes' => 'nullable|string',
            'food_availability' => 'nullable|string',
            'route_points' => 'nullable|array',
            'execution_schedule' => 'nullable|array',
            'full_report' => 'nullable|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|max:2048',
            'leader_name' => 'nullable|string|max:255',
            'assistant_leader_name' => 'nullable|string|max:255',
            'technical_manager_name' => 'nullable|string|max:255',
            'support_name' => 'nullable|string|max:255',
            'guide_name' => 'nullable|string|max:255',
            'start_location' => 'nullable|string',
            'start_location_coords' => 'nullable|string',
            'peak_coords' => 'nullable|string',
            'participant_count' => 'nullable|integer',
            'member_ids' => 'nullable|array',
            'member_ids.*' => 'exists:users,id',
            'guests' => 'nullable|array',
            'guests.*' => 'string|max:255',
        ]);

        $data['user_id'] = Auth::id();
        $data['approved'] = false;

        // فایل PDF
        if ($request->hasFile('pdf_file')) {
            $data['pdf_path'] = $request->file('pdf_file')->store('reports/pdfs', 'public');
        }

        // گالری تصاویر
        if ($request->hasFile('gallery')) {
            $images = [];
            foreach ($request->file('gallery') as $image) {
                $images[] = $image->store('reports/gallery', 'public');
            }
            $data['gallery'] = json_encode($images);
        }

        // فیلدهای JSON
        foreach (['transportation', 'water_type', 'required_equipment', 'required_skills', 'route_points', 'execution_schedule', 'member_ids', 'guests'] as $field) {
            $data[$field] = $request->filled($field) ? json_encode($request->$field) : null;
        }

        Report::create($data);

        return redirect()->route('user.reports.index')->with('success', 'گزارش با موفقیت ثبت شد.');
    }

    public function edit($id)
    {
        $report = Report::where(function($query) {
                        $query->where('user_id', Auth::id())
                              ->orWhere('writer_name', Auth::user()->full_name);
                    })->findOrFail($id);
    
        $programs = Program::all();
        return view('dashboard.reports.edit', compact('report', 'programs'));
    }
    

    public function update(Request $request, $id)
    {
        $report = Report::where(function($query) {
            $query->where('user_id', Auth::id())
                  ->orWhere('writer_name', Auth::user()->full_name);
        })->findOrFail($id);

        $data = $request->validate([
            'program_id' => 'nullable|exists:programs,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'type' => 'nullable|string',
            'area' => 'nullable|string',
            'peak_name' => 'nullable|string',
            'peak_height' => 'nullable|integer',
            'start_altitude' => 'nullable|integer',
            'duration' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'technical_level' => 'nullable|string',
            'road_type' => 'nullable|string',
            'transportation' => 'nullable|array',
            'water_type' => 'nullable|array',
            'signal_status' => 'nullable|boolean',
            'required_equipment' => 'nullable|array',
            'required_skills' => 'nullable|array',
            'difficulty' => 'nullable|string',
            'slope_angle' => 'nullable|string',
            'has_stone_climbing' => 'nullable|boolean',
            'has_ice_climbing' => 'nullable|boolean',
            'average_backpack_weight' => 'nullable|string',
            'natural_description' => 'nullable|string',
            'weather' => 'nullable|string',
            'wind_speed' => 'nullable|string',
            'temperature' => 'nullable|string',
            'vegetation' => 'nullable|string',
            'wildlife' => 'nullable|string',
            'local_language' => 'nullable|string',
            'historical_sites' => 'nullable|string',
            'important_notes' => 'nullable|string',
            'food_availability' => 'nullable|string',
            'route_points' => 'nullable|array',
            'execution_schedule' => 'nullable|array',
            'full_report' => 'nullable|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|max:2048',
            'leader_name' => 'nullable|string|max:255',
            'assistant_leader_name' => 'nullable|string|max:255',
            'technical_manager_name' => 'nullable|string|max:255',
            'support_name' => 'nullable|string|max:255',
            'guide_name' => 'nullable|string|max:255',
            'start_location' => 'nullable|string',
            'start_location_coords' => 'nullable|string',
            'peak_coords' => 'nullable|string',
            'participant_count' => 'nullable|integer',
            'member_ids' => 'nullable|array',
            'member_ids.*' => 'exists:users,id',
            'guests' => 'nullable|array',
            'guests.*' => 'string|max:255',
        ]);

        if ($request->hasFile('pdf_file')) {
            if ($report->pdf_path) {
                Storage::disk('public')->delete($report->pdf_path);
            }
            $data['pdf_path'] = $request->file('pdf_file')->store('reports/pdfs', 'public');
        }

        if ($request->hasFile('gallery')) {
            $images = [];
            foreach ($request->file('gallery') as $image) {
                $images[] = $image->store('reports/gallery', 'public');
            }
            $data['gallery'] = json_encode($images);
        }

        $data['transportation'] = $request->transportation ? json_encode($request->transportation) : null;
        $data['water_type'] = $request->water_type ? json_encode($request->water_type) : null;
        $data['required_equipment'] = $request->required_equipment ? json_encode($request->required_equipment) : null;
        $data['required_skills'] = $request->required_skills ? json_encode($request->required_skills) : null;
        $data['route_points'] = $request->route_points ? json_encode($request->route_points) : null;
        $data['execution_schedule'] = $request->execution_schedule ? json_encode($request->execution_schedule) : null;

        $report->update($data);

        return redirect()->route('dashboard.reports.index')->with('success', 'گزارش بروزرسانی شد.');
    }

    public function destroy($id)
    {
        $report = Report::where(function($query) {
            $query->where('user_id', Auth::id())
                  ->orWhere('writer_name', Auth::user()->full_name);
        })->findOrFail($id);

        if ($report->pdf_path) {
            Storage::disk('public')->delete($report->pdf_path);
        }

        if ($report->gallery) {
            foreach (json_decode($report->gallery) as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $report->delete();
        return redirect()->route('dashboard.reports.index')->with('success', 'گزارش حذف شد.');
    }
}
