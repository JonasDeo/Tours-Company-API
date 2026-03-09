<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tour_id')->nullable()->constrained()->nullOnDelete();

            // Client snapshot (in case quote is deleted)
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_phone')->nullable();

            $table->date('arrival_date')->nullable();
            $table->unsignedTinyInteger('adults')->default(1);
            $table->unsignedTinyInteger('children')->default(0);

            $table->unsignedInteger('total_amount');
            $table->string('currency', 3)->default('USD');
            $table->boolean('paid')->default(false);
            $table->timestamp('paid_at')->nullable();

            $table->enum('status', [
                'PENDING',
                'CONFIRMED',
                'COMPLETED',
                'CANCELLED',
            ])->default('PENDING');

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
