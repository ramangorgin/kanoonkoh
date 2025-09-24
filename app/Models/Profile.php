<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';

    protected $fillable = [
        'user_id',
        'membership_id',
        'membership_type',
        'membership_start',
        'membership_expiry',
        'leave_date',
        'first_name',
        'last_name',
        'father_name',
        'id_number',
        'id_place',
        'birth_date',
        'national_id',
        'photo',
        'national_card',
        'marital_status',
        'emergency_phone',
        'referrer',
        'education',
        'job',
        'home_address',
        'work_address',
    ];

    /**
     * رابطه با کاربر
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * تولید شماره عضویت یکتا از 1000 به بالا
     */
    public static function generateMembershipId()
    {
        $lastId = self::max('membership_id');
        return $lastId ? $lastId + 1 : 1000;
    }

    /**
     * تبدیل تاریخ تولد (شمسی → میلادی) قبل از ذخیره
     */
    public function setBirthDateAttribute($value)
    {
        try {
            // value مثل 1400/05/10
            [$year, $month, $day] = explode('/', $value);
            $this->attributes['birth_date'] = (new Jalalian($year, $month, $day))
                ->toCarbon()
                ->toDateString(); // 2021-07-31
        } catch (\Exception $e) {
            $this->attributes['birth_date'] = null;
        }
    }

    /**
     * تاریخ‌های عضویت → بعداً توسط ادمین ست می‌شن
     */
}
