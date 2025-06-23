<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title', 'description', 'teacher',
        'start_date' ,	'end_date'	,
        'start_time' ,	'end_time'	,
        'place'	, 'place_lat' ,	'place_lon',
        'capacity',
        'is_free', 'member_cost', 'guests_costs',
        'is_registration_open', 'registration_deadline',
        'card_number', 'sheba_number', 'card_holder', 'bank_name',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'is_registration_open' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'related_id')
                    ->where('type', 'course');
    }
    
    public function surveys()
    {
        return $this->hasMany(CourseSurvey::class);
    }

}