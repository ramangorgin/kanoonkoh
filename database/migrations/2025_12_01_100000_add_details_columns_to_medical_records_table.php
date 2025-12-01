<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->text('seizures_details')->nullable()->after('seizures');
            $table->text('respiratory_details')->nullable()->after('respiratory');
            $table->text('heart_details')->nullable()->after('heart');
            $table->text('blood_pressure_details')->nullable()->after('blood_pressure');
            $table->text('blood_disorders_details')->nullable()->after('blood_disorders');
            $table->text('diabetes_hepatitis_details')->nullable()->after('diabetes_hepatitis');
            $table->text('stomach_details')->nullable()->after('stomach');
            $table->text('kidney_details')->nullable()->after('kidney');
            $table->text('mental_details')->nullable()->after('mental');
            $table->text('addiction_details')->nullable()->after('addiction');
            $table->text('skin_allergy_details')->nullable()->after('skin_allergy');
            $table->text('drug_allergy_details')->nullable()->after('drug_allergy');
            $table->text('insect_allergy_details')->nullable()->after('insect_allergy');
            $table->text('dust_allergy_details')->nullable()->after('dust_allergy');
            $table->text('bone_joint_details')->nullable()->after('bone_joint');
            $table->text('hiv_details')->nullable()->after('hiv');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropColumn([
                'seizures_details',
                'respiratory_details',
                'heart_details',
                'blood_pressure_details',
                'blood_disorders_details',
                'diabetes_hepatitis_details',
                'stomach_details',
                'kidney_details',
                'mental_details',
                'addiction_details',
                'skin_allergy_details',
                'drug_allergy_details',
                'insect_allergy_details',
                'dust_allergy_details',
                'bone_joint_details',
                'hiv_details',
            ]);
        });
    }
};

