<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
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
            'photo' => 'nullable|image|max:2048',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $profileData = $request->except(['email', 'password', 'role']);
        $profileData['user_id'] = $user->id;

        if ($request->hasFile('photo')) {
            $profileData['photo'] = $request->file('photo')->store('photos', 'public');
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
            'email'          => ['required','email', Rule::unique('users')->ignore($user->id)],
            'password'       => ['nullable','min:6'],
            'role'           => ['required', Rule::in(['admin', 'user'])],

            // فیلدهای پروفایل
            'first_name'     => ['required','string','max:100'],
            'last_name'      => ['required','string','max:100'],
            'gender'         => ['nullable','in:male,female'],
            'birth_date'     => ['nullable','string'],
            'father_name'    => ['nullable','string','max:100'],
            'national_id'    => ['nullable','string','max:20'],
            'phone'          => ['nullable','string','max:20'],
            'province'       => ['nullable','string','max:100'],
            'city'           => ['nullable','string','max:100'],
            'postal_code'    => ['nullable','string','max:20'],
            'address'        => ['nullable','string','max:500'],
            'height'         => ['nullable','numeric'],
            'weight'         => ['nullable','numeric'],
            'blood_type'     => ['nullable','in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'has_surgery'    => ['nullable','in:0,1'],
            'physical_condition' => ['nullable','string','max:255'],
            'allergies'      => ['nullable','string','max:255'],
            'medications'    => ['nullable','string','max:255'],
            'job'            => ['nullable','string','max:100'],
            'referrer'       => ['nullable','string','max:100'],
            'emergency_phone'=> ['nullable','string','max:20'],
            'emergency_contact_name'      => ['nullable','string','max:100'],
            'emergency_contact_relation'  => ['nullable','string','max:100'],

            // فایل
            'photo' => ['nullable','image','mimes:jpg,jpeg,png','max:2048'],
        ]);

        DB::beginTransaction();
        try {
            // بروزرسانی user
            $user->email = $validated['email'];
            $user->role  = $validated['role'];
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();

            // اطمینان از وجود پروفایل
            $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

            // داده‌های پروفایل را به‌صورت امن انتخاب کن
            $profileData = $request->only([
                'first_name','last_name','gender','birth_date','father_name','national_id',
                'phone','province','city','postal_code','address',
                'height','weight','blood_type','has_surgery','physical_condition','allergies',
                'medications','job','referrer','emergency_phone','emergency_contact_name','emergency_contact_relation',
            ]);
            $profile->fill($profileData);

            // اگر عکس جدید آپلود شد
            if ($request->hasFile('photo')) {
                // حذف فایل قبلی (اگر هست)
                if (!empty($profile->photo)) {
                    Storage::disk('public')->delete($profile->photo);
                }
                // ذخیره فایل جدید
                $profile->photo = $request->file('photo')->store('photos', 'public');
            }

            $profile->save();

            DB::commit();
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'کاربر با موفقیت ویرایش شد.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors(['general' => 'خطایی رخ داد. لطفاً دوباره تلاش کنید.'])->withInput();
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->profile && $user->profile->photo) {
            Storage::disk('public')->delete($user->profile->photo);
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
