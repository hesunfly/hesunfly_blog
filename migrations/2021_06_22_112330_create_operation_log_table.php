<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateOperationLogTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('operation_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->default(0);
            $table->string('user_name', 50)->default('');
            $table->ipAddress('ip_address');
            $table->longText('request_info');
            $table->string('module', 20)->default('');
            $table->dateTime('operation_time');
            $table->string('operation_action', 20)->default('');
            $table->string('operation_system')->default('');
            $table->string('operation_info')->default('');
            $table->unsignedBigInteger('source_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_log');
    }
}
