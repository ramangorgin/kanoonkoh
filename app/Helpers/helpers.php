<?php
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;

if (! function_exists('toPersianDate')) {
    function toPersianDate($date)
    {
        if (! $date) return '';
        try {
            return Jalalian::fromCarbon(Carbon::parse($date))->format('Y/m/d');
        } catch (\Throwable $e) {
            return '';
        }
    }
}