<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Morilog\Jalali\Jalalian;
use App\Models\EducationalHistory;
use App\Models\FederationCourse;

class EducationalHistoryController extends Controller
{
    /**
     * نمایش لیست سوابق آموزشی کاربر
     */
    public function index()
    {
        $user = Auth::user();

        // سوابق آموزشی کاربر همراه با عنوان دوره فدراسیون
        $histories = EducationalHistory::where('user_id', $user->id)
            ->with('federationCourse')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // لیست دوره‌های فدراسیونی برای منوی انتخاب
        $federationCourses = FederationCourse::orderBy('title', 'asc')->get();

        return view('user.myEducationalHistories', compact('histories', 'federationCourses'));
    }

    /**
     * افزودن سابقه جدید
     */
    public function store(Request $request)
    {
        $request->validate([
            'federation_course_id' => 'required|exists:federation_courses,id',
            'issue_date'           => 'nullable|string',
            'certificate_file'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = Auth::user();

        // تبدیل تاریخ شمسی به میلادی
        $issueDate = null;
        if ($request->filled('issue_date')) {
            try {
                $issueDate = Jalalian::fromFormat('Y/m/d', $this->convertToEnglish($request->issue_date))->toCarbon()->toDateString();
            } catch (\Exception $e) {
                return back()->withErrors(['issue_date' => 'تاریخ وارد شده معتبر نیست'])->withInput();
            }
        }

        // آپلود فایل مدرک
        $filePath = null;
        if ($request->hasFile('certificate_file')) {
            $filePath = $request->file('certificate_file')->store('educational_certificates', 'public');
        }

        // ذخیره رکورد جدید
        EducationalHistory::create([
            'user_id'             => $user->id,
            'federation_course_id'=> $request->federation_course_id,
            'certificate_file'    => $filePath,
            'issue_date'          => $issueDate,
        ]);

        return redirect()->back()->with('success', 'سابقه آموزشی جدید با موفقیت ثبت شد.');
    }

    /**
     * به‌روزرسانی سابقه آموزشی
     */
    public function update(Request $request, $id)
    {
        $history = EducationalHistory::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'federation_course_id' => 'required|exists:federation_courses,id',
            'issue_date'           => 'nullable|string',
            'certificate_file'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // تبدیل تاریخ
        $issueDate = $history->issue_date;
        if ($request->filled('issue_date')) {
            try {
                $issueDate = Jalalian::fromFormat('Y/m/d', $this->convertToEnglish($request->issue_date))->toCarbon()->toDateString();
            } catch (\Exception $e) {
                return back()->withErrors(['issue_date' => 'تاریخ وارد شده معتبر نیست'])->withInput();
            }
        }

        // اگر فایل جدید ارسال شده
        if ($request->hasFile('certificate_file')) {
            // حذف فایل قدیمی
            if ($history->certificate_file && Storage::disk('public')->exists($history->certificate_file)) {
                Storage::disk('public')->delete($history->certificate_file);
            }

            $filePath = $request->file('certificate_file')->store('educational_certificates', 'public');
            $history->certificate_file = $filePath;
        }

        $history->update([
            'federation_course_id' => $request->federation_course_id,
            'issue_date'           => $issueDate,
            'certificate_file'     => $filePath ?? $history->certificate_file,
        ]);


        return redirect()->back()->with('success', 'سابقه آموزشی با موفقیت به‌روزرسانی شد.');
    }

    /**
     * حذف سابقه آموزشی
     */
    public function destroy($id)
    {
        $history = EducationalHistory::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($history->certificate_file && Storage::disk('public')->exists($history->certificate_file)) {
            Storage::disk('public')->delete($history->certificate_file);
        }

        $history->delete();

        return redirect()->back()->with('success', 'سابقه آموزشی با موفقیت حذف شد.');
    }

    /**
     * تبدیل اعداد فارسی به انگلیسی
     */
    private function convertToEnglish($string)
    {
        return strtr($string, [
            '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
            '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
        ]);
    }
}
