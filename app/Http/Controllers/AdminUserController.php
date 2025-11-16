<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\Payment;
use App\Models\EducationalHistory;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminUserController extends Controller
{
    /** نمایش لیست کاربران **/
    public function index(Request $request)
    {
        $query = User::with('profile');

        // جستجو بر اساس نام یا شماره تماس
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('profile', function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%");
            })->orWhere('phone', 'like', "%$search%");
        }

        $users = $query->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /** مشاهده جزئیات یک کاربر **/
    public function show($id)
    {
        $user = User::with([
            'profile',
            'medicalRecord',
            'educationalHistories',
            'payments'
        ])->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

        /** نمایش فرم ایجاد کاربر جدید **/
    public function create()
    {
        return view('admin.users.create');
    }

    /** ذخیره کاربر جدید **/
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|unique:users,phone',
            'first_name' => 'required',
            'last_name' => 'required',
            'national_id' => 'required',
        ]);

        DB::transaction(function() use ($request) {
            // جدول users
            $user = User::create([
                'phone' => $request->phone,
                'role'  => $request->role ?? 'member',
            ]);

            // جدول profiles
            $profile = new Profile([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'national_id' => $request->national_id,
                'membership_status' => 'pending',
                'membership_id' => Profile::generateMembershipId(),
                'education' => $request->education,
                'job' => $request->job,
                'membership_type' => $request->membership_type,
            ]);
            $profile->save();

            // جدول medical_records
            $medical = new MedicalRecord([
                'user_id' => $user->id,
                'blood_type' => $request->blood_type,
                'height' => $request->height,
                'weight' => $request->weight,
            ]);
            $medical->save();

            // جدول educational_histories
            if ($request->filled('federation_course_id')) {
                EducationalHistory::create([
                    'user_id' => $user->id,
                    'federation_course_id' => $request->federation_course_id,
                    'issue_date' => $request->issue_date,
                ]);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'کاربر جدید با موفقیت ایجاد شد ✅');
    }

    /** ویرایش کاربر **/
    public function edit($id)
    {
        $user = User::with('profile')->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /** بروزرسانی اطلاعات **/
    public function update(Request $request, $id)
    {
        $user = User::with(['profile', 'medicalRecord', 'educationalHistories'])->findOrFail($id);

        $request->validate([
            'phone' => 'required|unique:users,phone,' . $user->id,
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        DB::transaction(function() use ($request, $user) {
            $user->update([
                'phone' => $request->phone,
                'role' => $request->role,
            ]);

            $user->profile->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'education' => $request->education,
                'job' => $request->job,
                'membership_type' => $request->membership_type,
                'membership_status' => $request->membership_status,
            ]);

            if ($user->medicalRecord) {
                $user->medicalRecord->update([
                    'blood_type' => $request->blood_type,
                    'height' => $request->height,
                    'weight' => $request->weight,
                ]);
            }

            if ($user->educationalHistories->count()) {
                $user->educationalHistories->first()->update([
                    'federation_course_id' => $request->federation_course_id,
                    'issue_date' => $request->issue_date,
                ]);
            }
        });

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'اطلاعات کاربر با موفقیت بروزرسانی شد ✅');
    }

    /** حذف کاربر **/
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['success' => true]);
    }

    /** خروجی اکسل **/
    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

        public function pendingMemberships()
    {
        $pendingProfiles = \App\Models\Profile::where('membership_status', 'pending')
            ->with('user')
            ->latest()
            ->get();

        return view('admin.users.pending', compact('pendingProfiles'));
    }

    public function approveMembership($id)
    {
        $profile = \App\Models\Profile::findOrFail($id);
        $profile->update([
            'membership_status' => 'approved',
            'membership_id' => $profile->membership_id ?? \App\Models\Profile::generateMembershipId(),
            'membership_start' => now(),
            'membership_expiry' => now()->addYear(),
        ]);

        return response()->json(['success' => true]);
    }

    public function rejectMembership($id)
    {
        $profile = \App\Models\Profile::findOrFail($id);
        $profile->update(['membership_status' => 'rejected']);
        return response()->json(['success' => true]);
    }

}