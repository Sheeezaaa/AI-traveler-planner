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
        Schema::create('itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->json('day_plans'); // holds array of days, e.g. [{"day": 1, "title": "Check-in", "activities": ["Move to hotel", "Visit Attabad lake"]}]
            $table->decimal('estimated_transport', 10, 2)->default(0.00);
            $table->decimal('estimated_food', 10, 2)->default(0.00);
            $table->decimal('estimated_activities', 10, 2)->default(0.00);
            $table->decimal('estimated_hotels', 10, 2)->default(0.00);
            $table->decimal('total_estimated', 12, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itineraries');
    }
};
