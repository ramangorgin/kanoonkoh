<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'author_id',
        'report',
        'weather',
        'temperature',
        'wind_speed',
        'vegetation',
        'wildlife',
        'local_language',
        'historical_sites',
        'route_description',
        'important_notes',
        'execution_schedule',
    ];

    protected $casts = [
        'execution_schedule' => 'array',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
