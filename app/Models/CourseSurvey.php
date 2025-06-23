<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseSurvey extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'content_quality',
        'teaching_skill',
        'materials_quality',
        'usefulness',
        'instructor_behavior',
        'feedback_text',
        'is_anonymous',
    ];

    /**
     * ارتباط با کاربر پرکننده فرم نظرسنجی
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ارتباط با دوره آموزشی مربوطه
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
