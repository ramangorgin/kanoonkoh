<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // === COPY FILES ===
        Storage::disk('public')->put(
            'photos/raman_photo.jpg',
            File::get(database_path('seeders/files/raman_photo.jpg'))
        );

        Storage::disk('public')->put(
            'cards/raman_card.jpg',
            File::get(database_path('seeders/files/raman_card.jpg'))
        );

        Storage::disk('public')->put(
            'insurances/raman_insurance.pdf',
            File::get(database_path('seeders/files/raman_insurance.pdf'))
        );

        // === USERS ===
        DB::table('users')->insert([
            'id' => 1,
            'phone' => '09014282751',
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // === PROFILES ===
        DB::table('profiles')->insert([
            'user_id' => 1,
            'membership_status' => 'approved',
            'membership_id' => 100001, 
            'membership_type' => null,
            'membership_start' => Carbon::now()->toDateString(),
            'membership_expiry' => null,
            'leave_date' => null,
            'first_name' => 'رامان',
            'last_name' => 'گرگین پاوه',
            'father_name' => 'محمدرضا',
            'id_number' => '0150629737',
            'id_place' => 'تهران',
            'birth_date' => Carbon::createFromFormat('Y/m/d', '2004/06/03')->toDateString(),
            'national_id' => '0150629737',
            'photo' => 'raman_photo.jpg',
            'national_card' => 'raman_card.jpg',
            'marital_status' => 'مجرد',
            'emergency_phone' => '09122612493',
            'referrer' => 'غلامرضا گرگین پاوه',
            'education' => 'کارشناسی',
            'job' => 'برنامه‌نویس',
            'home_address' => 'کرج، جهانشهر',
            'work_address' => 'کرج، جهانشهر',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // === MEDICAL RECORDS ===
        DB::table('medical_records')->insert([
            'user_id' => 1,
            'insurance_issue_date' => Carbon::createFromFormat('Y/m/d', '2024/09/22')->toDateString(), 
            'insurance_expiry_date' => Carbon::createFromFormat('Y/m/d', '2025/09/22')->toDateString(),
            'insurance_file' => 'raman_insurance.pdf',
            'blood_type' => 'O-',
            'height' => 180,
            'weight' => 65,
            'commitment_signed' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // === EDUCATIONAL HISTORIES ===
        DB::table('educational_histories')->insert([
            'user_id' => 1,
            'federation_course_id' => 1,
            'certificate_file' => null,
            'issue_date' => now()->subYears(rand(0, 5))->subMonths(rand(0, 11))->subDays(rand(0, 28))->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}
