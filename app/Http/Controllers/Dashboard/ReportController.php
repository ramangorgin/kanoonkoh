<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Morilog\Jalali\Jalalian;
use App\Models\ReportParticipant;
use App\Models\ReportUserRole;


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
    // دریافت گزارش
    $report = Report::with(['user', 'program'])->findOrFail($id);

    $users = User::all(); 
    $programs = Program::all();

    return view('dashboard.reports.show', compact('report', 'users', 'programs'));
}


    public function create()
    {
        $users = User::all();
        $programs = Program::all();

        return view('dashboard.reports.create', compact('users', 'programs'));
    }

   public function store(Request $request)
{
    // اعتبارسنجی داده‌های ورودی
    $request->validate([
        'program_id' => 'nullable|exists:programs,id',
        'title' => 'required|string|max:255',
        'content' => 'nullable|string',
        'type' => 'nullable|string|max:255',
        'area' => 'nullable|string|max:255',
        'peak_height' => 'nullable|integer|min:0',
        'start_height' => 'nullable|integer|min:0',
        'technical_level' => 'nullable|string|max:255',
        'road_type' => 'nullable|string|max:255',
        'natural_description' => 'nullable|string',
        'weather' => 'nullable|string|max:255',
        'wind_speed' => 'nullable|string|max:255',
        'temperature' => 'nullable|string|max:255',
        'wildlife' => 'nullable|string|max:255',
        'local_language' => 'nullable|string|max:255',
        'historical_sites' => 'nullable|string|max:255',
        'important_notes' => 'nullable|string',
        'food_availability' => 'nullable|string|max:255',
        'start_location' => 'nullable|string|max:255',
        'start_coords' => 'nullable|string',
        'peak_coords' => 'nullable|string',
        'participant_count' => 'nullable|integer|min:0',
        'writer_name' => 'nullable|string|max:255',
        
        // فایل‌ها
        'pdf_file' => 'nullable|file|mimes:pdf|max:2048',
        'track_file' => 'nullable|file|mimes:gpx,kml|max:2048',
        'gallery' => 'nullable|array',
        'gallery.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        
        'transportation' => 'nullable|array',
        'transportation.*' => 'string',
        'water_type' => 'nullable|array',
        'water_type.*' => 'string',
        'required_equipment' => 'nullable|array',
        'required_equipment.*' => 'string',
        'required_skills' => 'nullable|array',
        'required_skills.*' => 'string',
        'route_points' => 'nullable|array',
        'route_points.*' => 'string',
        'execution_schedule' => 'nullable|array',
        'execution_schedule.*' => 'string',

        
        // تاریخ‌ها
        'start_date' => 'nullable|string',
        'end_date' => 'nullable|string',
        
        // شرکت‌کنندگان و نقش‌ها
        'participants' => 'nullable|array',
        'participants.*' => 'integer|exists:users,id',
        'member_ids' => 'nullable|array',
        'roles' => 'nullable|array',
    ]);

    // آماده‌سازی داده‌ها
    $data = $request->only([
        'program_id',
        'title',
        'content',
        'type',
        'area',
        'peak_height',
        'start_height',
        'technical_level',
        'road_type',
        'natural_description',
        'weather',
        'wind_speed',
        'temperature',
        'wildlife',
        'local_language',
        'historical_sites',
        'important_notes',
        'food_availability',
        'start_location',
        'start_coords',
        'peak_coords',
        'participant_count',
        'writer_name',
    ]);

    $data['transportation'] = json_encode($request->input('transportation', []));
    $data['water_type'] = json_encode($request->input('water_type', []));
    $data['required_equipment'] = json_encode($request->input('required_equipment', []));
    $data['required_skills'] = json_encode($request->input('required_skills', []));
    $data['route_points'] = json_encode($request->input('route_points', []));
    $data['execution_schedule'] = json_encode($request->input('execution_schedule', []));
    $data['user_id'] = Auth::id();
    $data['approved'] = false;

    // مدیریت فایل‌ها
    $this->handleFiles($request, $data);
    
    // ایجاد گزارش
    $report = Report::create($data);

    // ذخیره شرکت‌کنندگان
    $this->saveParticipants($request, $report);
    
    // ذخیره نقش‌ها
    $this->saveUserRoles($request, $report);

    return redirect()->back()->with('success', 'گزارش با موفقیت ثبت شد.');
}

public function edit(Report $report)
{
    $users = User::all(); // یا هر کوئری دیگری که نیاز دارید
    $programs = Program::all(); // اگر نیاز به لیست برنامه‌ها دارید
    
    return view('dashboard.reports.edit', compact('report', 'users', 'programs'));
}
/**
 * مدیریت فایل‌های آپلود شده
 */
public function handleFiles(Request $request, array &$data)
{
    // آپلود فایل PDF
    if ($request->hasFile('pdf_file')) {
        $data['pdf_path'] = $request->file('pdf_file')->store('reports/pdfs', 'public');
    }

    // آپلود فایل مسیر (GPX/KML)
    if ($request->hasFile('track_file')) {
        $data['track_file_path'] = $request->file('track_file')->store('reports/tracks', 'public');
    }

    // آپلود گالری تصاویر
    if ($request->hasFile('gallery')) {
        $galleryPaths = [];
        foreach ($request->file('gallery') as $image) {
            $galleryPaths[] = $image->store('reports/gallery', 'public');
        }
        $data['gallery'] = json_encode($galleryPaths);
    }
}

/**
 * ذخیره شرکت‌کنندگان گزارش
 */
public function saveParticipants(Request $request, Report $report)
{
    // شرکت‌کنندگان از بین کاربران سیستم
    if ($request->filled('participants')) {
        foreach ($request->participants as $userId) {
            ReportParticipant::create([
                'report_id' => $report->id,
                'user_id' => $userId,
            ]);
        }
    }

    // شرکت‌کنندگان مهمان یا کاربران سیستم
    if ($request->filled('member_ids')) {
        foreach ($request->member_ids as $entry) {
            ReportParticipant::create([
                'report_id' => $report->id,
                'user_id' => is_numeric($entry) ? $entry : null,
                'guest_name' => !is_numeric($entry) ? $entry : null,
            ]);
        }
    }
}

/**
 * ذخیره نقش‌های کاربران در گزارش
 */
public function saveUserRoles(Request $request, Report $report)
{
    if ($request->filled('roles')) {
        foreach ($request->roles as $role) {
            ReportUserRole::create([
                'report_id' => $report->id,
                'role_title' => $role['role_title'] ?? null,
                'user_id' => $role['user_id'] ?? null,
                'user_name' => $role['user_name'] ?? null,
            ]);
        }
    }
}




/**
 * حذف فایل‌های قدیمی در صورت نیاز
 */
public function deleteOldFiles(Request $request, Report $report)
{
    // حذف PDF قدیمی
    if ($request->hasFile('pdf_file') && $report->pdf_path) {
        Storage::disk('public')->delete($report->pdf_path);
    }

    // حذف فایل مسیر قدیمی
    if ($request->hasFile('track_file') && $report->track_file_path) {
        Storage::disk('public')->delete($report->track_file_path);
    }

    // حذف تصاویر قدیمی گالری
    if ($request->hasFile('gallery') && $report->gallery) {
        foreach (json_decode($report->gallery, true) as $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }
    }
}

/**
 * همگام‌سازی شرکت‌کنندگان گزارش
 */
public function syncParticipants(Request $request, Report $report)
{
    // حذف تمام شرکت‌کنندگان فعلی
    $report->participants()->delete();

    // افزودن شرکت‌کنندگان جدید (همانند متد store)
    $this->saveParticipants($request, $report);
}

/**
 * همگام‌سازی نقش‌های کاربران در گزارش
 */
public function syncUserRoles(Request $request, Report $report)
{
    // حذف تمام نقش‌های فعلی
    $report->userRoles()->delete();

    // افزودن نقش‌های جدید (همانند متد store)
    $this->saveUserRoles($request, $report);
}



    public function destroy($id)
    {
        $report = Report::where(function ($query) {
            $query->where('user_id', Auth::id())
                  ->orWhere('writer_name', Auth::user()->full_name);
        })->findOrFail($id);

        if ($report->pdf_path) {
            Storage::disk('public')->delete($report->pdf_path);
        }

        if ($report->gallery && is_array(json_decode($report->gallery))) {
            foreach (json_decode($report->gallery) as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $report->delete();
        return redirect()->route('dashboard.reports.index')->with('success', 'گزارش حذف شد.');
    }

   

    public function encodeJsonFields(Request $request, array &$data)
    {
        foreach ([
            'transportation', 'water_type', 'required_equipment', 'required_skills',
            'route_points', 'execution_schedule', 'member_ids'
        ] as $field) {
            $data[$field] = $request->filled($field) ? json_encode($request->$field) : null;
        }
    }
        public function update(Request $request, Report $report)
{
    // بررسی مجوز دسترسی کاربر
    if ($report->user_id !== Auth::id() && !Auth::user()->can('manage-reports')) {
        abort(403, 'شما مجوز ویرایش این گزارش را ندارید.');
    }

    // اعتبارسنجی داده‌های ورودی
    $validated = $request->validate([
        'program_id' => 'nullable|exists:programs,id',
        'title' => 'nullable|string|max:255',
        'content' => 'nullable|string',
        'type' => 'nullable|string|max:255',
        'area' => 'nullable|string|max:255',
        'peak_height' => 'nullable|integer|min:0',
        'start_height' => 'nullable|integer|min:0',
        'technical_level' => 'nullable|string|max:255',
        'road_type' => 'nullable|string|max:255',
        'natural_description' => 'nullable|string',
        'weather' => 'nullable|string|max:255',
        'wind_speed' => 'nullable|string|max:255',
        'temperature' => 'nullable|string|max:255',
        'wildlife' => 'nullable|string|max:255',
        'local_language' => 'nullable|string|max:255',
        'historical_sites' => 'nullable|string|max:255',
        'important_notes' => 'nullable|string',
        'food_availability' => 'nullable|string|max:255',
        'start_location' => 'nullable|string|max:255',
        'start_coords' => 'nullable|json',
        'peak_coords' => 'nullable|json',
        'participant_count' => 'nullable|integer|min:0',
        'writer_name' => 'nullable|string|max:255',
        
        // فایل‌ها
        'pdf_file' => 'nullable|file|mimes:pdf|max:2048',
        'track_file' => 'nullable|file|mimes:gpx,kml|max:2048',
        'gallery' => 'nullable|array',
        'gallery.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        
        // آرایه‌های JSON
        'transportation' => 'nullable|json',
        'water_type' => 'nullable|json',
        'required_equipment' => 'nullable|json',
        'required_skills' => 'nullable|json',
        'route_points' => 'nullable|json',
        'execution_schedule' => 'nullable|json',
        
        // تاریخ‌ها
        'start_date' => 'nullable|string',
        'end_date' => 'nullable|string',
        
        // شرکت‌کنندگان و نقش‌ها
        'participants' => 'nullable|array',
        'participants.*' => 'integer|exists:users,id',
        'member_ids' => 'nullable|array',
        'roles' => 'nullable|array',
    ]);

    // آماده‌سازی داده‌ها
    $data = $request->only([
        'program_id',
        'title',
        'content',
        'type',
        'area',
        'peak_height',
        'start_height',
        'technical_level',
        'road_type',
        'natural_description',
        'weather',
        'wind_speed',
        'temperature',
        'wildlife',
        'local_language',
        'historical_sites',
        'important_notes',
        'food_availability',
        'start_location',
        'start_coords',
        'peak_coords',
        'participant_count',
        'writer_name',
    ]);


    // مدیریت فایل‌ها
    $this->handleFiles($request, $data);
    
    // حذف فایل‌های قدیمی در صورت آپلود فایل جدید
    $this->deleteOldFiles($request, $report);

    // بروزرسانی گزارش
    $report->update($data);

    // همگام‌سازی شرکت‌کنندگان
    $this->syncParticipants($request, $report);
    
    // همگام‌سازی نقش‌ها
    $this->syncUserRoles($request, $report);

    return redirect()
        ->route('user.reports.show', $report->id)
        ->with('success', 'گزارش با موفقیت بروزرسانی شد.');
}
}
