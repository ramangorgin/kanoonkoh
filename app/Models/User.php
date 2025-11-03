<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'phone', 'otp_code', 'otp_expires_at', 'role'
    ];

    public function Admin()
    {
        return $this->role === 'admin';
    }

    public function isRegistrationComplete()
    {
        return $this->profile 
            && $this->medicalRecord 
            && $this->educationalHistories()->exists();
    }

    public function getFullNameAttribute()
    {
        return optional($this->profile)->first_name . ' ' . optional($this->profile)->last_name;
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function educationalHistories()
    {
        return $this->hasMany(EducationalHistory::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    public function programRegistrations()
    {
        return $this->hasMany(ProgramRegistration::class);
    }

    public function courseRegistrations()
    {
        return $this->hasMany(CourseRegistration::class);
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'program_registrations')
                    ->withPivot('approved', 'guest_name', 'guest_phone');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_registrations')
                    ->withPivot('approved', 'guest_name', 'guest_phone');
    }

}
