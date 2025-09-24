<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FederationCourse extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'title', 'description'];
    public $incrementing = false; // چون id دستی وارد میشه
    protected $keyType = 'int';

    public function prerequisites()
    {
        return $this->belongsToMany(
            FederationCourse::class,
            'course_prerequisites',
            'course_id',
            'prerequisite_id'
        );
    }

    public function histories()
    {
        return $this->hasMany(EducationalHistory::class);
    }
}
