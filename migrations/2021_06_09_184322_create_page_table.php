<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreatePageTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->default('');
            $table->string('slug')->default('');
            $table->longText('content');
            $table->longText('html_content');
            $table->tinyInteger('status')->default(-1);
            $table->unsignedTinyInteger('sort')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
        \Hyperf\DbConnection\Db::statement("ALTER TABLE `page` comment '页面'");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page');
    }
}
