<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;

class UserCoursesController extends Controller
{
    /**
     * نمایش لیست تمام دوره‌های کاربر (باشگاه + سوابق)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        /**
         * 🏕 دوره‌های باشگاه (جدول course_registrations + courses + teachers)
         */
        $clubCourses = DB::table('course_registrations')
            ->join('courses', 'course_registrations.course_id', '=', 'courses.id')
            ->leftJoin('teachers', 'courses.teacher_id', '=', 'teachers.id')
            ->select(
                'courses.id as id',
                'courses.title as course_name',
                DB::raw("CONCAT(teachers.first_name, ' ', teachers.last_name) as teacher_name"),
                'courses.start_date',
                DB::raw('"کانون کوه" as source'),
            )
            ->where('course_registrations.user_id', $user->id);

        /**
         * 📘 سوابق آموزشی قبل از ثبت‌نام (educational_histories + federation_courses)
         */
        $externalCourses = DB::table('educational_histories')
            ->leftJoin('federation_courses', 'educational_histories.federation_course_id', '=', 'federation_courses.id')
            ->select(
                'educational_histories.id as id',
                DB::raw('COALESCE(federation_courses.title, educational_histories.custom_course_name) as course_name'),
                DB::raw('NULL as teacher_name'),
                'educational_histories.issue_date as start_date',
                DB::raw('"سوابق" as source'),
                'educational_histories.certificate_file'
            )
            ->where('educational_histories.user_id', $user->id);

        /**
         * ✨ ترکیب دو مجموعه (Union)
         */
        $allCourses = $clubCourses->unionAll($externalCourses);

        /**
         * 🔍 اعمال جستجو (در عنوان یا مدرس یا منبع)
         */
        $allCourses = DB::query()->fromSub($allCourses, 'courses_union')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('course_name', 'like', "%{$search}%")
                      ->orWhere('teacher_name', 'like', "%{$search}%")
                      ->orWhere('source', 'like', "%{$search}%");
                });
            })
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return view('user.myCourses', [
            'courses' => $allCourses,
        ]);
    }
}
