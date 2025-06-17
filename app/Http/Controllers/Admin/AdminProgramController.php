<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Program;

class AdminProgramController extends Controller
{
    public function index()
    {
        $programs = Program::latest()->get();
        return view('admin.programs.index', compact('programs'));
    }

    public function create()
    {
        return view('admin.programs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
           
                'title' => 'required|string|max:255',
                'date' => 'required|string', // چون شمسیه
                'leader_name' => 'nullable|string|max:255',
                'assistant_leader_name' => 'nullable|string|max:255',
                'technical_manager_name' => 'nullable|string|max:255',
                'support_name' => 'nullable|string|max:255',
                'guide_name' => 'nullable|string|max:255',
                'has_transport' => 'required|in:0,1',
                'departure_time_tehran' => 'nullable|required_if:has_transport,1',
                'departure_place_tehran' => 'nullable|required_if:has_transport,1',
                'departure_lat_tehran' => 'nullable|required_if:has_transport,1',
                'departure_lon_tehran' => 'nullable|required_if:has_transport,1',
                'departure_time_karaj' => 'nullable|required_if:has_transport,1',
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
                'registration_deadline' => 'nullable|required_if:is_registration_open,1|date',
                'report_photos.*' => 'nullable|image|max:2048',
                'description' => 'nullable|string',
            
        ]);

        $photos = [];
        if ($request->hasFile('report_photos')) {
            foreach ($request->file('report_photos') as $photo) {
                $photos[] = $photo->store('program_photos', 'public');
            }
        }

        $validated['report_photos'] = json_encode($photos);
        Program::create($validated);

        return redirect()->route('admin.programs.index')->with('success', 'برنامه با موفقیت ثبت شد.');
    }

    public function destroy(Program $program)
    {
        $program->delete();

        return redirect()->route('admin.programs.index')->with('success', 'برنامه با موفقیت حذف شد.');
    }

}
