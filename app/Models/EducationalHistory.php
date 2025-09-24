<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'federation_course_id',
        'completion_date',
        'certificate_file',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function federationCourse()
    {
        return $this->belongsTo(FederationCourse::class, 'federation_course_id');
    }
}
