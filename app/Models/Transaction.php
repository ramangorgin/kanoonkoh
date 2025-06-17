<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'description',
        'type',
        'transaction_code',
        'related_id',
        'year',
        'receipt_file',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}