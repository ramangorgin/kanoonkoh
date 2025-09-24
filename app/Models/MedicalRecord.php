<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $table = 'medical_records';

    protected $fillable = [
        'user_id',
        'insurance_issue_date',
        'insurance_expiry_date',
        'insurance_file',
        'blood_type',
        'height',
        'weight',
        'head_injury','head_injury_details',
        'eye_ear_problems','eye_ear_problems_details',
        'seizures','respiratory','heart','blood_pressure',
        'blood_disorders','diabetes_hepatitis','stomach',
        'kidney','mental','addiction',
        'surgery','surgery_details',
        'skin_allergy','drug_allergy','insect_allergy','dust_allergy',
        'medications','medications_details',
        'bone_joint','hiv','treatment','treatment_details',
        'other_conditions',
        'commitment_signed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
