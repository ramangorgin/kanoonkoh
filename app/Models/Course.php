<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title', 'description', 'date',
        'capacity', 'cost',
        'is_registration_open', 'registration_deadline',
        'card_number', 'sheba_number', 'card_holder', 'bank_name',
    ];

    protected $casts = [
        'date' => 'date',
        'registration_deadline' => 'datetime',
        'is_registration_open' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

}

