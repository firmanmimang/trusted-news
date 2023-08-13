<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->boolean('is_crawl');
            $table->string('author_crawl')->nullable();
            $table->string('source_crawl')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('image')->nullable();
            $table->text('image_description')->nullable();
            $table->text('excerpt');
            $table->text('body');
            $table->boolean('is_highlight');
            $table->boolean('publish_status');
            $table->boolean('comment_status');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
