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
        Schema::create('booking_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // <== TRÈS important
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->dateTime('reservation_date');
            $table->enum('status', ['pending', 'confirmed', 'canceled'])->default('pending');
            $table->decimal('price', 8, 2)->nullable(); // Add price column as decimal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_services');
    }
};
