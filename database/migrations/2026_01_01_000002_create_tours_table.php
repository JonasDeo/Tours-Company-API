<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('destination');
            $table->enum('type', ['GUIDED', 'SELF_DRIVE', 'MOUNTAIN', 'BEACH']);
            $table->unsignedSmallInteger('duration_days');
            $table->unsignedInteger('price');
            $table->string('currency', 3)->default('USD');
            $table->text('description');
            $table->text('excerpt')->nullable();
            $table->json('images')->nullable();          // Cloudinary URLs
            $table->json('included')->nullable();        // array of strings
            $table->json('excluded')->nullable();        // array of strings
            $table->json('tags')->nullable();
            $table->boolean('published')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
