<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'teacher_id',
        'code',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'place',
        'place_lat',
        'place_lon',
        'capacity',
        'is_free',
        'member_cost',
        'guest_cost',
        'card_number',
        'sheba_number',
        'card_holder',
        'bank_name',
        'is_registration_open',
        'registration_deadline',
        'is_special',
    ];

    protected $dates = ['start_date', 'end_date'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function registrations()
    {
        return $this->hasMany(CourseRegistration::class);
    }

    public function files()
    {
        return $this->hasMany(CourseFile::class);
    }

    public function prerequisites()
    {
        return $this->hasMany(CoursePrerequisite::class);
    }

}