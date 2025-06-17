<?php

namespace App\Services;

use Morilog\Jalali\Jalalian;

class DateService
{
    public static function convertJalaliToGregorian($jalaliDate)
    {
        if (!$jalaliDate || !str_contains($jalaliDate, '/')) {
            return null;
        }

        list($year, $month, $day) = explode('/', $jalaliDate);

        try {
            $jalalian = new Jalalian((int)$year, (int)$month, (int)$day);
            return $jalalian->format('Y-m-d'); 
        } catch (\Exception $e) {
            return null;
        }
    }
}
