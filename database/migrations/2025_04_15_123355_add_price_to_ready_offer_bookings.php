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
        Schema::table('ready_offer_bookings', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->after('ready_offer_id'); // Prix avec 2 décimales
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ready_offer_bookings', function (Blueprint $table) {
            //
        });
    }
};
