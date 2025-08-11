<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\ProgramUserRole;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
    public function archive()
    {
        $programs = Program::all();
        return view('programs.archive', compact('programs'));
    }

    public function index()
    {
        $programs = Program::latest()->get();
        return view('programs.index', compact('programs'));
    }

    public function create()
    {
        $users = User::all();
        return view('programs.create' , compact('users'));
    }

    public function show($id)
    {
        $program = Program::findOrFail($id);
        $user = auth()->user();

        $userHasParticipated = Auth::check()
        ? $program->registrations()->where('user_id', Auth::id())->exists()
        : false;


        // آیا فرم نظرسنجی پر کرده؟
        $userHasSubmittedSurvey = Auth::check()
        ? $program->surveys()->where('user_id', Auth::id())->exists()
        : false;


        return view('programs.show', compact(
            'program',
            'userHasParticipated',
            'userHasSubmittedSurvey'
        ));
    }


    public function destroy(Program $program)
    {
        $program->delete();

        return redirect()->route('admin.programs.index')->with('success', 'برنامه با موفقیت حذف شد.');
    }
    public function edit(Program $program)
    {
        $users = User::with('profile')->get(); // اگر پروفایل هم لازم باشه
        $program->load('roles'); // لود مسئولین
        return view('admin.programs.edit', compact('program', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|string',
            'end_date' => 'required|string',

            'has_transport' => 'required|in:0,1',
            'departure_dateTime_tehran' => 'nullable|required_if:has_transport,1',
            'departure_place_tehran' => 'nullable|required_if:has_transport,1',
            'departure_lat_tehran' => 'nullable|required_if:has_transport,1',
            'departure_lon_tehran' => 'nullable|required_if:has_transport,1',

            'departure_dateTime_karaj' => 'nullable|required_if:has_transport,1',
            'departure_place_karaj' => 'nullable|required_if:has_transport,1',
            'departure_lat_karaj' => 'nullable|required_if:has_transport,1',
            'departure_lon_karaj' => 'nullable|required_if:has_transport,1',

            'required_equipment' => 'nullable|array',
            'required_meals' => 'nullable|array',

            'is_free' => 'required|in:0,1',
            'member_cost' => 'nullable|required_if:is_free,0|numeric|min:0',
            'guest_cost' => 'nullable|required_if:is_free,0|numeric|min:0',
            'card_number' => 'nullable|required_if:is_free,0',
            'sheba_number' => 'nullable|required_if:is_free,0',
            'card_holder' => 'nullable|required_if:is_free,0',
            'bank_name' => 'nullable|required_if:is_free,0',

            'is_registration_open' => 'required|in:0,1',
            'registration_deadline' => 'nullable|required_if:is_registration_open,1',

            'report_photos.*' => 'nullable|image|max:2048',
            'description' => 'nullable|string',

            'roles' => 'nullable|array',
            'roles.*.role_title' => 'required|string|max:255',
            'roles.*.user_id' => 'nullable|exists:users,id',
            'roles.*.user_name' => 'nullable|string|max:255',
        ]);

        // عکس‌ها
        $photos = [];
        if ($request->hasFile('report_photos')) {
            $files = $request->file('report_photos');
            if (count($files) > 10) {
                return back()->withErrors(['report_photos' => 'حداکثر ۱۰ تصویر مجاز است.'])->withInput();
            }

            foreach ($files as $photo) {
                $photos[] = $photo->store('program_photos', 'public');
            }
        }

        DB::transaction(function () use ($validated, $photos, $request) {
            $program = Program::create([
                'title' => $validated['title'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'has_transport' => $validated['has_transport'],
                'departure_dateTime_tehran' => $validated['departure_dateTime_tehran'] ?? null,
                'departure_place_tehran' => $validated['departure_place_tehran'] ?? null,
                'departure_lat_tehran' => $validated['departure_lat_tehran'] ?? null,
                'departure_lon_tehran' => $validated['departure_lon_tehran'] ?? null,
                'departure_dateTime_karaj' => $validated['departure_dateTime_karaj'] ?? null,
                'departure_place_karaj' => $validated['departure_place_karaj'] ?? null,
                'departure_lat_karaj' => $validated['departure_lat_karaj'] ?? null,
                'departure_lon_karaj' => $validated['departure_lon_karaj'] ?? null,
                'required_equipment' => json_encode($validated['required_equipment'] ?? []),
                'required_meals' => json_encode($validated['required_meals'] ?? []),
                'is_free' => $validated['is_free'],
                'member_cost' => $validated['member_cost'] ?? null,
                'guest_cost' => $validated['guest_cost'] ?? null,
                'card_number' => $validated['card_number'] ?? null,
                'sheba_number' => $validated['sheba_number'] ?? null,
                'card_holder' => $validated['card_holder'] ?? null,
                'bank_name' => $validated['bank_name'] ?? null,
                'is_registration_open' => $validated['is_registration_open'],
                'registration_deadline' => $validated['registration_deadline'] ?? null,
                'photos' => $photos,
                'description' => $validated['description'] ?? null,
            ]);

            // ذخیره مسئولین (اگر وجود دارند)
            if ($request->filled('roles')) {
                foreach ($request->input('roles') as $role) {
                    if (empty($role['user_id']) && empty($role['user_name'])) {
                        continue; // هیچ‌کدام وارد نشده‌اند
                    }

                    ProgramUserRole::create([
                        'program_id' => $program->id,
                        'user_id' => $role['user_id'] ?? null,
                        'user_name' => $role['user_name'] ?? null,
                        'role_title' => $role['role_title'],
                    ]);
                }
            }
        });

        return redirect()->route('admin.programs.index')->with('success', 'برنامه با موفقیت ثبت شد.');
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|string',
            'end_date' => 'required|string',

            'has_transport' => 'required|in:0,1',
            'departure_dateTime_tehran' => 'nullable|required_if:has_transport,1',
            'departure_place_tehran' => 'nullable|required_if:has_transport,1',
            'departure_lat_tehran' => 'nullable|required_if:has_transport,1',
            'departure_lon_tehran' => 'nullable|required_if:has_transport,1',
            'departure_dateTime_karaj' => 'nullable|required_if:has_transport,1',
            'departure_place_karaj' => 'nullable|required_if:has_transport,1',
            'departure_lat_karaj' => 'nullable|required_if:has_transport,1',
            'departure_lon_karaj' => 'nullable|required_if:has_transport,1',

            'required_equipment' => 'nullable|array',
            'required_meals' => 'nullable|array',

            'is_free' => 'required|in:0,1',
            'member_cost' => 'nullable|required_if:is_free,0|numeric|min:0',
            'guest_cost' => 'nullable|required_if:is_free,0|numeric|min:0',
            'card_number' => 'nullable|required_if:is_free,0',
            'sheba_number' => 'nullable|required_if:is_free,0',
            'card_holder' => 'nullable|required_if:is_free,0',
            'bank_name' => 'nullable|required_if:is_free,0',

            'is_registration_open' => 'required|in:0,1',
            'registration_deadline' => 'nullable|required_if:is_registration_open,1',

            'report_photos.*' => 'nullable|image|max:2048',
            'description' => 'nullable|string',

            'roles' => 'nullable|array',
            'roles.*.role_title' => 'required|string|max:255',
            'roles.*.user_id' => 'nullable|exists:users,id',
            'roles.*.user_name' => 'nullable|string|max:255',
        ]);

        $photos = $program->photos ?? [];
        if ($request->hasFile('report_photos')) {
            $files = $request->file('report_photos');
            if (count($files) > 10) {
                return back()->withErrors(['report_photos' => 'حداکثر ۱۰ تصویر مجاز است.'])->withInput();
            }

            foreach ($files as $photo) {
                $photos[] = $photo->store('program_photos', 'public');
            }
        }

        DB::transaction(function () use ($program, $validated, $photos, $request) {
            $program->update([
                'title' => $validated['title'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'has_transport' => $validated['has_transport'],
                'departure_dateTime_tehran' => $validated['departure_dateTime_tehran'] ?? null,
                'departure_place_tehran' => $validated['departure_place_tehran'] ?? null,
                'departure_lat_tehran' => $validated['departure_lat_tehran'] ?? null,
                'departure_lon_tehran' => $validated['departure_lon_tehran'] ?? null,
                'departure_dateTime_karaj' => $validated['departure_dateTime_karaj'] ?? null,
                'departure_place_karaj' => $validated['departure_place_karaj'] ?? null,
                'departure_lat_karaj' => $validated['departure_lat_karaj'] ?? null,
                'departure_lon_karaj' => $validated['departure_lon_karaj'] ?? null,
                'required_equipment' => json_encode($validated['required_equipment'] ?? []),
                'required_meals' => json_encode($validated['required_meals'] ?? []),
                'is_free' => $validated['is_free'],
                'member_cost' => $validated['member_cost'] ?? null,
                'guest_cost' => $validated['guest_cost'] ?? null,
                'card_number' => $validated['card_number'] ?? null,
                'sheba_number' => $validated['sheba_number'] ?? null,
                'card_holder' => $validated['card_holder'] ?? null,
                'bank_name' => $validated['bank_name'] ?? null,
                'is_registration_open' => $validated['is_registration_open'],
                'registration_deadline' => $validated['registration_deadline'] ?? null,
                'photos' => $photos,
                'description' => $validated['description'] ?? null,
            ]);

            // حذف مسئولین قبلی
            $program->roles()->delete();

            // ذخیره مسئولین جدید
            if ($request->filled('roles')) {
                foreach ($request->input('roles') as $role) {
                    if (empty($role['user_id']) && empty($role['user_name'])) continue;

                    $program->roles()->create([
                        'user_id' => $role['user_id'] ?? null,
                        'user_name' => $role['user_name'] ?? null,
                        'role_title' => $role['role_title'],
                    ]);
                }
            }
        });

        return redirect()->route('admin.programs.index')->with('success', 'برنامه با موفقیت ویرایش شد.');
    }


}
