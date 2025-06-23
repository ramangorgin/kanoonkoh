<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'title', 'photos', 'description', 'start_date', 'end_date',
        'has_transport',
        'departure_place_tehran', 'departure_dateTime_tehran', 'departure_lat_tehran', 'departure_lon_tehran',
        'departure_place_karaj', 'departure_dateTime_karaj', 'departure_lat_karaj', 'departure_lon_karaj',
        'required_equipment', 'required_meals',
        'is_free', 'member_cost', 'guest_cost',
        'card_number', 'sheba_number', 'card_holder', 'bank_name',
        'report_photos',
        'is_registration_open', 'registration_deadline',
    ];

    protected $casts = [
        'photos' => 'array',
        'has_transport' => 'boolean',
        'is_free' => 'boolean',
        'is_registration_open' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
    public function roles()
    {
        return $this->hasMany(ProgramUserRole::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'related_id')
                    ->where('type', 'program');
    }
    
    public function surveys()
    {
        return $this->hasMany(ProgramSurvey::class);
    }

}

