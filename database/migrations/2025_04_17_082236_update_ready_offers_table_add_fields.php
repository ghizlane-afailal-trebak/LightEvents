
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ready_offers', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->text('description')->nullable()->after('title');
            $table->string('image')->nullable()->after('description');
            $table->decimal('original_price', 10, 2)->nullable()->after('image');
            $table->decimal('discounted_price', 10, 2)->nullable()->after('original_price');
            $table->string('categorie')->nullable()->after('discounted_price');
            $table->unsignedBigInteger('admin_id')->nullable()->after('categorie');

            // Foreign key constraint
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('ready_offers', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn([
                'title',
                'description',
                'image',
                'original_price',
                'discounted_price',
                'categorie',
                'admin_id'
            ]);
        });
    }
};
