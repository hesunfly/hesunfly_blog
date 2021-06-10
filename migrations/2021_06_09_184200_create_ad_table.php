<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAdTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ad', function (Blueprint $table) {
            $table->increments('id');
            $table->string('desc')->default('');
            $table->string('url')->default('');
            $table->string('image_path')->default('');
            $table->tinyInteger('status')->default(-1);
            $table->unsignedTinyInteger('sort')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
        \Hyperf\DbConnection\Db::statement("ALTER TABLE `ad` comment '广告推广'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad');
    }
}
