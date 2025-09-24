<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'profile_image',
        'birth_date',
        'biography',
        'skills',
        'certificates',
    ];

    // یک مدرس می‌تواند چندین دوره داشته باشد
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
