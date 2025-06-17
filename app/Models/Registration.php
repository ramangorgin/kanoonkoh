<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'related_id',
        'payment_id',
        'ride_location',
        'guest_name',
        'guest_national_id',
        'guest_birth_date',
        'guest_father_name',
        'guest_phone',
        'guest_emergency_phone',
        'guest_insurance_file',
        'approved',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function relatedProgram()
    {
        return $this->belongsTo(Program::class, 'related_id');
    }

    public function relatedCourse()
    {
        return $this->belongsTo(Course::class, 'related_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
