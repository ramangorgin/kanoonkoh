<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Program;
use App\Models\Course;
use App\Models\User;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'related_id',
        'year',
        'reference_code',
        'amount',
        'payment_date',
        'approved',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registration()
    {
        return $this->hasOne(ProgramRegistration::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'related_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'related_id');
    }
}