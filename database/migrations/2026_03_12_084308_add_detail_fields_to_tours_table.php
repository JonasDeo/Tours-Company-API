<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            // Only add columns that don't already exist
            if (!Schema::hasColumn('tours', 'excerpt')) {
                $table->string('excerpt', 500)->nullable()->after('description');
            }
            if (!Schema::hasColumn('tours', 'departure_location')) {
                $table->string('departure_location', 200)->nullable()->after('excerpt');
            }
            if (!Schema::hasColumn('tours', 'return_location')) {
                $table->string('return_location', 200)->nullable()->after('departure_location');
            }
            if (!Schema::hasColumn('tours', 'highlights')) {
                $table->json('highlights')->nullable()->after('return_location');
            }
            if (!Schema::hasColumn('tours', 'itinerary')) {
                $table->json('itinerary')->nullable()->after('highlights');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn([
                'departure_location',
                'return_location',
                'highlights',
                'itinerary',
            ]);
        });
    }
};