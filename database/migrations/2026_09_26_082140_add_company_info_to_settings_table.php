<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('company_phone')->nullable()->after('value');
            $table->string('company_location')->nullable()->after('company_phone');
            $table->text('reports_message')->nullable()->after('company_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['company_phone', 'company_location', 'reports_message']);
        });
    }
};
