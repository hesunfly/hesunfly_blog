<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateEmailConfirmCodesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('email_confirm_code', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email', 50)->default('');
            $table->string('key', 100)->default('');
            $table->char('code', 6)->default('');
            $table->tinyInteger('status')->default(-1);
            $table->timestamps();
            $table->index('email');
        });
        \Hyperf\DbConnection\Db::statement("ALTER TABLE `email_confirm_code` comment '邮件订阅验证码'");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_confirm_code');
    }
}
