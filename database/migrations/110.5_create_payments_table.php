<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // نوع پرداخت
            $table->enum('type', ['membership', 'course', 'program']);

            // ارتباط با آیتم مربوطه (nullable برای membership)
            $table->unsignedBigInteger('related_id')->nullable();

            // حق عضویت سالیانه
            $table->integer('year')->nullable(); 

            // شناسه واریز تولیدی توسط سیستم
            $table->string('reference_code')->unique();

            // اطلاعات پرداخت وارد شده توسط کاربر
            $table->integer('amount')->nullable();
            $table->date('payment_date')->nullable();

            // وضعیت تایید توسط ادمین
            $table->boolean('approved')->default(false);

            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
