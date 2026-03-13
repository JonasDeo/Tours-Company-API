<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add author_bio and read_time to blog_posts
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('author_bio', 1000)->nullable()->after('author');
            $table->unsignedSmallInteger('read_time')->default(5)->after('author_bio');
        });

        // Create blog_comments table
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->text('body');
            $table->boolean('approved')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_comments');
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn(['author_bio', 'read_time']);
        });
    }
};