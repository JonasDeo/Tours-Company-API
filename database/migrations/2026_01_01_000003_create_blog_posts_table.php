<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category');
            $table->json('tags')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('content');               // Markdown
            $table->string('cover_image')->nullable(); // Cloudinary URL
            $table->boolean('published')->default(false);
            $table->unsignedInteger('read_count')->default(0);
            $table->string('author')->default('Balbina Safaris');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
