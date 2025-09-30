<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'description',
        'poster_path',
        'base_height',
        'peak_height',
        'area',
        'peak_lat',
        'peak_lon',
        'start_date',
        'end_date',
        'registration_deadline',
        'is_registration_open',
        'has_transport',
        'departure_dateTime_tehran',
        'departure_place_tehran',
        'departure_lat_tehran',
        'departure_lon_tehran',
        'departure_dateTime_karaj',
        'departure_place_karaj',
        'departure_lat_karaj',
        'departure_lon_karaj',
        'required_equipment',
        'required_meals',
        'is_free',
        'member_cost',
        'guest_cost',
        'card_number',
        'sheba_number',
        'card_holder',
        'bank_name',
        'difficulty',
        'status',
    ];

    protected $casts = [
        'is_registration_open' => 'boolean',
        'has_transport' => 'boolean',
        'is_free' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_deadline' => 'datetime',
    ];

    public function registrations()
    {
        return $this->hasMany(ProgramRegistration::class);
    }

    public function files()
    {
        return $this->hasMany(ProgramFile::class);
    }

    public function userRoles()
    {
        return $this->hasMany(ProgramUserRole::class);
    }

    public function details()
    {
        return $this->hasOne(ProgramDetail::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'related_id')->where('type', 'program');
    }
    
}