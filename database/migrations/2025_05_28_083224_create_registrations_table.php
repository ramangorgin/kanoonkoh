<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();

            // عمومی
            $table->unsignedBigInteger('user_id')->nullable(); // اگر عضو نیست، null میشه
            $table->string('type'); // course یا program
            $table->unsignedBigInteger('related_id');
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->enum('ride_location', ['tehran', 'karaj'])->nullable();

            // مهمان
            $table->string('guest_name')->nullable();
            $table->string('guest_national_id')->nullable();
            $table->string('guest_birth_date')->nullable();
            $table->string('guest_father_name')->nullable();
            $table->string('guest_phone')->nullable();
            $table->string('guest_emergency_phone')->nullable();
            $table->string('guest_insurance_file')->nullable();

            // وضعیت
            $table->boolean('approved')->default(false);

            $table->timestamps();

            // ایندکس و کلید خارجی
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('payment_id')->references('id')->on('payments')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('registrations');
    }
}

