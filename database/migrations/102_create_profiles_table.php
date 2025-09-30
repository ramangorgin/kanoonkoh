<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            // Connection With users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Membership
            $table->enum('membership_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('membership_id')->unique(); 
            $table->string('membership_type')->nullable();       
            $table->date('membership_start')->nullable();      
            $table->date('membership_expiry')->nullable(); 
            $table->date('leave_date')->nullable(); 

            // Personal Details
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('father_name', 50)->nullable();
            $table->string('id_number', 20)->nullable();
            $table->string('id_place', 50)->nullable();
            $table->date('birth_date')->nullable();            
            $table->string('national_id', 10);       

            // Files
            $table->string('photo');                          
            $table->string('national_card');

            // Other Infos
            $table->enum('marital_status', ['مجرد', 'متاهل'])->nullable();
            $table->string('emergency_phone', 15)->nullable();
            $table->string('referrer', 100)->nullable();
            $table->string('education', 100)->nullable();
            $table->string('job', 100)->nullable();

            // Adresses
            $table->text('home_address')->nullable();
            $table->text('work_address')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
