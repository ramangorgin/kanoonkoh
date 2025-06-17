<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminReportController extends Controller
{
    public function index()
    {
        $reports = Report::latest()->with(['program', 'user'])->get();
        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load(['program', 'user']);
        return view('admin.reports.show', compact('report'));
    }

    public function edit(Report $report)
    {
        return view('admin.reports.edit', compact('report'));
    }

    public function update(Request $request, Report $report)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'type' => 'nullable|string',
            'approved' => 'boolean',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'pdf_path' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $report->title = $request->title;
        $report->content = $request->content;
        $report->type = $request->type;
        $report->approved = $request->approved;

        // آپلود گالری جدید
        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('reports/gallery', 'public');
                $galleryPaths[] = $path;
            }
            // ادغام با گالری قبلی
            $report->gallery = array_merge($report->gallery ?? [], $galleryPaths);
        }

        // آپلود فایل PDF جدید
        if ($request->hasFile('pdf_path')) {
            if ($report->pdf_path) {
                Storage::disk('public')->delete($report->pdf_path);
            }
            $report->pdf_path = $request->file('pdf_path')->store('reports/pdfs', 'public');
        }

        $report->save();

        return redirect()->route('admin.reports.index')->with('success', 'گزارش با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(Report $report)
    {
        if ($report->pdf_path) {
            Storage::disk('public')->delete($report->pdf_path);
        }

        if (is_array($report->gallery)) {
            foreach ($report->gallery as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $report->delete();

        return redirect()->route('admin.reports.index')->with('success', 'گزارش حذف شد.');
    }

    public function create()
    {

        $users = \App\Models\User::all(); 

        return view('admin.reports.create', compact('users'));
    }
}
