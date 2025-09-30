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

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function prerequisites()
    {
        return $this->belongsToMany(
            Course::class,
            'course_prerequisites',
            'course_id',
            'prerequisite_course_id'
        );
    }

    public function requiredFor()
    {
        return $this->belongsToMany(
            Course::class,
            'course_prerequisites',
            'prerequisite_course_id',
            'course_id'
        );
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'enrollments')
                    ->withPivot('completion_date', 'certificate_file')
                    ->withTimestamps();
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

    public function files()
    {
        return $this->hasMany(CourseFile::class);
    }

}