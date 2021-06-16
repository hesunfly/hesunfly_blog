<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateSubscribeTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscribe', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->default('');
            $table->unsignedInteger('times')->default(0);
            $table->tinyInteger('status')->default(-1);
            $table->timestamps();
            $table->softDeletes();
            $table->index('email');
        });
        \Hyperf\DbConnection\Db::statement("ALTER TABLE `subscribe` comment '订阅'");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribe');
    }
}
