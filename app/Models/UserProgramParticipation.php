<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProgramParticipation extends Model
{
    protected $fillable = [
        'user_id',
        'program_id',
        'title',
        'start_date',
        'end_date',
        'leader_name',
        'assistant_leader_name',
        'technical_manager_name',
        'support_name',
        'guide_name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
