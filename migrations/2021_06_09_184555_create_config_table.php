<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateConfigTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('blog_name',50)->default('Heusnfly Blog');
            $table->string('logo_img')->default('');
            $table->unsignedTinyInteger('page_size')->default(15);
            $table->string('icp_record', 30)->default('');
            $table->string('reward_code_img')->default('');
            $table->string('reward_desc')->default('');
            $table->string('email', 50)->default('');
            $table->string('github', 50)->default('');
            $table->string('gitee', 50)->default('');
            $table->timestamps();
        });
        \Hyperf\DbConnection\Db::statement("ALTER TABLE `config` comment '配置表'");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config');
    }
}
