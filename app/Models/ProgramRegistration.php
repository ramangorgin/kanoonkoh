<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramRegistration extends Model
{
    protected $fillable = [
        'program_id', 'user_id',
        'guest_name', 'guest_phone', 'guest_national_id',
        'transaction_code', 'pickup_location',
        'receipt_file', 'approved',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

