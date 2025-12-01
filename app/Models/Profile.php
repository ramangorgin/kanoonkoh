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

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function setBirthDateAttribute($value)
    {
        // اگر مقدار خالی یا نال بود، هیچی تنظیم نکن
        if (empty($value)) {
            $this->attributes['birth_date'] = null;
            return;
        }

        // اگر مقدار از قبل به فرمت میلادی (Y-m-d) است، مستقیم ذخیره کن
        // کنترلر تبدیل شمسی به میلادی را انجام می‌دهد
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            $this->attributes['birth_date'] = $value;
            return;
        }

        // اگر به فرمت شمسی (Y/m/d) است، تبدیل کن
        try {
            // فقط اعداد فارسی → انگلیسی
            $value = str_replace(['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'], ['0','1','2','3','4','5','6','7','8','9'], $value);

            // تبدیل از شمسی به میلادی
            [$year, $month, $day] = explode('/', $value);
            $this->attributes['birth_date'] = (new \Morilog\Jalali\Jalalian($year, $month, $day))
                ->toCarbon()
                ->toDateString(); // ذخیره به فرمت YYYY-MM-DD
        } catch (\Exception $e) {
            $this->attributes['birth_date'] = null;
        }
    }


    public function setPhotoAttribute($value)
    {
        if (!empty($value) && $value !== 'profiles/') {
            $this->attributes['photo'] = $value;
        }
    }

    public function setNationalCardAttribute($value)
    {
        if (!empty($value) && $value !== 'profiles/') {
            $this->attributes['national_card'] = $value;
        }
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public static function generateMembershipId()
    {
        $lastId = self::max('membership_id');
        return $lastId ? $lastId + 1 : 1000;
    }

}
