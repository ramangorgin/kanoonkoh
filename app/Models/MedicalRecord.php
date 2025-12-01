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
        'seizures','seizures_details',
        'respiratory','respiratory_details',
        'heart','heart_details',
        'blood_pressure','blood_pressure_details',
        'blood_disorders','blood_disorders_details',
        'diabetes_hepatitis','diabetes_hepatitis_details',
        'stomach','stomach_details',
        'kidney','kidney_details',
        'mental','mental_details',
        'addiction','addiction_details',
        'surgery','surgery_details',
        'skin_allergy','skin_allergy_details',
        'drug_allergy','drug_allergy_details',
        'insect_allergy','insect_allergy_details',
        'dust_allergy','dust_allergy_details',
        'medications','medications_details',
        'bone_joint','bone_joint_details',
        'hiv','hiv_details',
        'treatment','treatment_details',
        'other_conditions',
        'commitment_signed'
    ];

    protected $casts = [
        'insurance_issue_date' => 'date',
        'insurance_expiry_date' => 'date',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
