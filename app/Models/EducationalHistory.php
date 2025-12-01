<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;

class EducationalHistory extends Model
{
    use HasFactory;

    protected $table = 'educational_histories';

    protected $fillable = [
        'user_id',
        'federation_course_id',
        'custom_course_title',
        'issue_date', 
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

    public function getIssueDateJalaliAttribute()
    {
        return $this->issue_date
            ? Jalalian::fromCarbon(Carbon::parse($this->issue_date))->format('Y/m/d')
            : null;
    }
}
