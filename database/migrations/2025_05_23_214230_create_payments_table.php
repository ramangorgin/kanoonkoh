<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->enum('type', ['membership', 'course', 'program']);
            $table->unsignedBigInteger('related_id')->nullable(); 
            $table->integer('year')->nullable(); 
            $table->integer('amount')->nullable(); 
            $table->date('payment_date')->nullable();
            $table->string('transaction_code');
            $table->string('receipt_file')->nullable();
            $table->boolean('approved')->default(false); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
