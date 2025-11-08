<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('pricing_type')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('price_per_person', 8, 2)->nullable();
            $table->decimal('price_per_table', 8, 2)->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->boolean('is_active')->default(true);

            // Si tu veux ajouter la relation avec la table categories :
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'pricing_type',
                'price',
                'price_per_person',
                'price_per_table',
                'category_id',
                'is_active'
            ]);
        });
    }
};
