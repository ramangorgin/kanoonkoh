<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('course_registrations', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
            $table->string('guest_name')->nullable();
            $table->string('guest_phone')->nullable();
            $table->string('guest_national_id')->nullable();
        
            $table->string('transaction_code')->nullable();
            $table->string('receipt_file')->nullable();
            $table->boolean('approved')->default(false);
        
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('course_registrations');
    }
};
