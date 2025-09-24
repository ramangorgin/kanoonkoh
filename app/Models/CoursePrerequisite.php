<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePrerequisite extends Model
{
    use HasFactory;

    protected $table = 'course_prerequisites';

    protected $fillable = [
        'course_id',
        'prerequisite_id',  
    ];

    public function course()
    {
        return $this->belongsTo(FederationCourse::class, 'course_id');
    }

    public function prerequisite()
    {
        return $this->belongsTo(FederationCourse::class, 'prerequisite_id');
    }
}

