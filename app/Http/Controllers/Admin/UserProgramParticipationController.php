<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Program;
use App\Models\UserProgramParticipation;
use Illuminate\Http\Request;

class UserProgramParticipationController extends Controller
{
    public function index(User $user)
    {
        $participations = $user->programParticipations()->with('program')->latest()->get();
        return view('admin.users.programs.index', compact('user', 'participations'));
    }

    public function create(User $user)
    {
        $programs = Program::all();
        return view('admin.users.programs.create', compact('user', 'programs'));
    }

    public function store(Request $request, User $user)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'program_id' => 'nullable|exists:programs,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'leader_name' => 'nullable|string|max:255',
            'assistant_leader_name' => 'nullable|string|max:255',
            'technical_manager_name' => 'nullable|string|max:255',
            'support_name' => 'nullable|string|max:255',
            'guide_name' => 'nullable|string|max:255',
        ]);

        $data['user_id'] = $user->id;
        UserProgramParticipation::create($data);

        return redirect()->route('admin.users.programs.index', $user)->with('success', 'برنامه با موفقیت اضافه شد.');
    }
}
