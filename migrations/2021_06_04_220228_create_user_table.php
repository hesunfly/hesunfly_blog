<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 50);
            $table->string('user_name', 50);
            $table->string('password');
            $table->string('avatar');
            $table->timestamps();
            $table->softDeletes();
            $table->index('email');
            $table->index('user_name');
        });

        \Hyperf\DbConnection\Db::statement("ALTER TABLE `user` comment '用户表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
}
