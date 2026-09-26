<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The routes report groups by from_location + to_location (and the trips
     * index filters with a LIKE on either). Since from_location and to_location
     * have no index, the group-by runs a full scan once the table grows.
     */
    public function up(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->index(['from_location', 'to_location']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropIndex(['from_location', 'to_location']);
        });
    }
};