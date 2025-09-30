<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'payment_id',
        'guest_name',
        'guest_phone',
        'guest_national_id',
        'approved',
    ];

    protected $casts = [
        'approved' => 'boolean',
    ];


    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
