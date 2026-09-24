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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_type_id')->constrained()->restrictOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
            $table->foreignId('driver_id')->constrained()->restrictOnDelete();

            $table->string('from_location');
            $table->string('to_location');
            $table->date('trip_date')->index();

            $table->decimal('price', 14, 2);
            $table->decimal('driver_percentage', 5, 2);

            // Derived, stored, calculated in the app layer (Trip::recalculate)
            $table->decimal('total_expenses', 14, 2)->default(0);
            $table->decimal('net_amount', 14, 2)->default(0);      // signed: can be negative
            $table->decimal('driver_amount', 14, 2)->default(0);   // signed: not clamped
            $table->decimal('company_amount', 14, 2)->default(0);  // signed

            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
