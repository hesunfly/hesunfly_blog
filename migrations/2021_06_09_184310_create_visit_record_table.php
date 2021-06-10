<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateVisitRecordTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visit_record', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->ipAddress('ip')->default('');
            $table->string('address')->default('');
            $table->string('uri');
            $table->timestamps();
        });
        \Hyperf\DbConnection\Db::statement("ALTER TABLE `visit_record` comment '访问记录'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_record');
    }
}
