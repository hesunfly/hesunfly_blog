<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->string('title')->default('');
            $table->string('description')->default('');
            $table->string('slug')->default('');
            $table->longText('html_content');
            $table->longText('content');
            $table->tinyInteger('status')->default(-1)->comment('是否发布 @-1:未发布，@1：已发布');
            $table->unsignedInteger('view_count')->default(0);
            $table->string('qr_path')->nullable()->default('');
            $table->timestamp('publish_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('category_id');
            $table->index('title');
            $table->index('publish_at');
            $table->index('slug');
        });
        \Hyperf\DbConnection\Db::statement("ALTER TABLE `article` comment '文章表'");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article');
    }
}
