<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'file_type',
        'file_path',
        'caption',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
