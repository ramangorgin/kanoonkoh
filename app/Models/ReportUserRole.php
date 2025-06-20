<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportUserRole extends Model
{
    protected $fillable = [
        'report_id',
        'user_id',
        'user_name',
        'role_title',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
