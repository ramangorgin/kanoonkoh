<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{

    public function addCertificate(Request $request, $userId)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'instructor' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('certificate_file')) {
            $path = $request->file('certificate_file')->store('certificates', 'public');
        }

        \App\Models\UserCourseCertificate::create([
            'user_id' => $userId,
            'course_name' => $request->course_name,
            'instructor' => $request->instructor,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'certificate_path' => $path,
        ]);

        return back()->with('success', 'دوره با موفقیت ثبت شد.');
    }

    public function index(Request $request)
    {
        $query = User::with('profile');
    
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%$search%"]);
            });
        }
    
        $users = $query->orderBy('created_at', 'desc')->paginate(15);
    
        return view('admin.users.index', compact('users'));
    }
    

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => ['required', Rule::in(['admin', 'user'])],
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
            'national_id' => 'nullable|string|max:20',
            'personal_photo' => 'nullable|image|max:2048',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $profileData = $request->except(['email', 'password', 'role']);
        $profileData['user_id'] = $user->id;

        if ($request->hasFile('personal_photo')) {
            $profileData['personal_photo'] = $request->file('personal_photo')->store('photos', 'public');
        }

        Profile::create($profileData);

        return redirect()->route('admin.users.index')->with('success', 'کاربر با موفقیت ایجاد شد.');
    }

    public function edit($id)
    {
        $user = User::with('profile')->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::with('profile')->findOrFail($id);

        $validated = $request->validate([
            'email' => ['required','email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:6',
            'role' => ['required', Rule::in(['admin', 'user'])],
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'personal_photo' => 'nullable|image|max:2048',
        ]);

        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->role = $request->role;
        $user->save();

        $profile = $user->profile;
        $profile->fill($request->except(['email', 'password', 'role']));

        if ($request->hasFile('personal_photo')) {
            if ($profile->personal_photo) {
                Storage::disk('public')->delete($profile->personal_photo);
            }
            $profile->personal_photo = $request->file('personal_photo')->store('photos', 'public');
        }

        $profile->save();

        return redirect()->route('admin.users.index')->with('success', 'کاربر با موفقیت ویرایش شد.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->profile && $user->profile->personal_photo) {
            Storage::disk('public')->delete($user->profile->personal_photo);
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'کاربر حذف شد.');
    }

    public function show($id)
    {
        $user = User::with(['profile', 'insurance', 'payments'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }
}
