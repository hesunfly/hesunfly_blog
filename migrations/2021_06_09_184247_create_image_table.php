<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateImageTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('image', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->default('');
            $table->string('disk')->default('');
            $table->unsignedMediumInteger('size')->default(0);
            $table->string('path')->default('');
            $table->timestamps();
            $table->softDeletes();
        });
        \Hyperf\DbConnection\Db::statement("ALTER TABLE `image` comment '图片资源表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image');
    }
}
