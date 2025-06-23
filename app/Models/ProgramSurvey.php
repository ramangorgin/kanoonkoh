<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramSurvey extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'program_id',
        'planning_quality',
        'execution_quality',
        'leadership_quality',
        'team_spirit',
        'safety_and_support',
        'feedback_text',
        'is_anonymous',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
