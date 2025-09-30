<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // بیمه ورزشی
            $table->date('insurance_issue_date')->nullable();
            $table->date('insurance_expiry_date')->nullable();
            $table->string('insurance_file')->nullable();

            // مشخصات فیزیکی
            $table->string('blood_type', 5)->nullable();
            $table->smallInteger('height')->nullable();
            $table->smallInteger('weight')->nullable();

            // سؤالات پزشکی
            $table->boolean('head_injury')->nullable();
            $table->text('head_injury_details')->nullable();
            $table->boolean('eye_ear_problems')->nullable();
            $table->text('eye_ear_problems_details')->nullable();
            $table->boolean('seizures')->nullable();
            $table->boolean('respiratory')->nullable();
            $table->boolean('heart')->nullable();
            $table->boolean('blood_pressure')->nullable();
            $table->boolean('blood_disorders')->nullable();
            $table->boolean('diabetes_hepatitis')->nullable();
            $table->boolean('stomach')->nullable();
            $table->boolean('kidney')->nullable();
            $table->boolean('mental')->nullable();
            $table->boolean('addiction')->nullable();
            $table->boolean('surgery')->nullable();
            $table->text('surgery_details')->nullable();
            $table->boolean('skin_allergy')->nullable();
            $table->boolean('drug_allergy')->nullable();
            $table->boolean('insect_allergy')->nullable();
            $table->boolean('dust_allergy')->nullable();
            $table->boolean('medications')->nullable();
            $table->text('medications_details')->nullable();
            $table->boolean('bone_joint')->nullable();
            $table->boolean('hiv')->nullable();
            $table->boolean('treatment')->nullable();
            $table->text('treatment_details')->nullable();

            // توضیحات اضافی
            $table->text('other_conditions')->nullable();

            // تعهدنامه
            $table->boolean('commitment_signed')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
