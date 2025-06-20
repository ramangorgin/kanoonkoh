<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportUserRolesTable extends Migration
{
    public function up()
    {
        Schema::create('report_user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('user_name')->nullable(); // اگر اکانت نداشت
            $table->string('role_title');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('report_user_roles');
    }
}
