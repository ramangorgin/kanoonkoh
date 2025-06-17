<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'gender',
        'birth_date',
        'father_name',
        'national_id',
        'phone',
        'emergency_phone',
        'province',
        'city',
        'address',
        'postal_code',
        'previous_courses',
        'personal_photo',
        'blood_type',
        'job',
        'referrer',
        'height',
        'weight',
        'medical_conditions',
        'allergies',
        'medications',
        'had_surgery',
        'emergency_contact_name',
        'emergency_contact_relation',
        'membership_level',
        'membership_status',
        'membership_date',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
