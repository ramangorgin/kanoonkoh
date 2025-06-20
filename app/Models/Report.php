<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
   use HasFactory;

    protected $fillable = [
        'user_id',
        'program_id',
        'title',
        'content',
        'gallery',
        'approved',
        'type',
        'area',
        'peak_height',
        'start_height',
        'start_date',
        'end_date',
        'start_location',
        'start_coords',
        'peak_coords',
        'participant_count',
        'writer_name',
        'technical_level',
        'road_type',
        'transportation',
        'water_type',
        'required_equipment',
        'required_skills',
        'natural_description',
        'weather',
        'wind_speed',
        'temperature',
        'wildlife',
        'local_language',
        'historical_sites',
        'important_notes',
        'food_availability',
        'route_points',
        'execution_schedule',
        'pdf_path',
        'track_file_path'
    ];

    protected $casts = [
        'gallery' => 'array',
        'transportation' => 'array',
        'water_type' => 'array',
        'required_equipment' => 'array',
        'required_skills' => 'array',
        'route_points' => 'array',
        'execution_schedule' => 'array',
        'approved' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'start_coords' => 'array',
        'peak_coords' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
    public function userRoles()
    {
        return $this->hasMany(ReportUserRole::class);
    }

    public function participants()
    {
        return $this->hasMany(ReportParticipant::class);
    }

}
