<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'membership_level',
        'membership_date', 'score', 'avatar',
        'email', 'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

  

    public function Admin()
    {
        return $this->role === 'admin';
    }
    public function programRoles()
    {
        return $this->hasMany(ProgramUserRole::class);
    }


    public function getFullNameAttribute()
    {
        return optional($this->profile)->first_name . ' ' . optional($this->profile)->last_name;
    }
    

    protected static function booted()
    {
        static::created(function ($user) {
            \App\Models\Report::whereNull('user_id')
                ->where('writer_name', $user->full_name)
                ->update(['user_id' => $user->id]);
        });
    }

    public function programParticipations()
    {
        return $this->hasMany(UserProgramParticipation::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function insurance()
    {
        return $this->hasOne(Insurance::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class)->withTimestamps();
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class)->withTimestamps();
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function programRegistrations()
    {
        return $this->hasMany(ProgramRegistration::class);
    }

    public function courseRegistrations()
    {
        return $this->hasMany(CourseRegistration::class);
    }
   
    public function isProfileComplete()
    {
        return $this->first_name && $this->last_name && $this->avatar &&
            $this->membership_level && $this->membership_date && $this->score;
    }
    
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($role)
    {
        return $this->roles->pluck('name')->contains($role);
    }

    public function courseCertificates()
    {
        return $this->hasMany(UserCourseCertificate::class);
    }

}
