<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();

            // Contact
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('country')->nullable();

            // Trip details
            $table->json('trip_types')->nullable();
            $table->json('destinations')->nullable();
            $table->json('experiences')->nullable();
            $table->json('occasions')->nullable();
            $table->string('accommodation')->nullable();
            $table->unsignedTinyInteger('adults')->default(1);
            $table->unsignedTinyInteger('children')->default(0);
            $table->date('arrival_date')->nullable();
            $table->text('message')->nullable();

            // Management
            $table->enum('status', [
                'PENDING',
                'REVIEWED',
                'RESPONDED',
                'CONVERTED',
                'CLOSED',
            ])->default('PENDING');

            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
