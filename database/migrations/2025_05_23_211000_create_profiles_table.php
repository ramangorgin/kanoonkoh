<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender')->nullable(); 
            $table->date('birth_date')->nullable();
            $table->string('father_name')->nullable();
            $table->string('national_id')->nullable()->unique();
            $table->string('personal_photo')->nullable();

            $table->string('phone')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->string('postal_code')->nullable();

            $table->date('membership_date')->nullable();
            $table->enum('membership_level', [
                'آزمایشی',
                'عضو رسمی پایه ۳',
                'عضو رسمی پایه ۲',
                'عضو رسمی پایه ۱',
                'عضو پایدار',
                'عضو افتخاری',
            ])->nullable();
            $table->enum('membership_status', ['فعال', 'معلق', 'لغو شده'])->default('فعال');

            $table->string('blood_type')->nullable();
            $table->unsignedSmallInteger('height')->nullable();
            $table->unsignedSmallInteger('weight')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('allergies')->nullable();
            $table->boolean('had_surgery')->default(false);
            $table->text('medications')->nullable();

            $table->string('job')->nullable();
            $table->string('referrer')->nullable();

            $table->string('emergency_phone')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relation')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
