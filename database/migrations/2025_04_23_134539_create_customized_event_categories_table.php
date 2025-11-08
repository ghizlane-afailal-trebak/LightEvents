<?php

// database/migrations/xxxx_xx_xx_create_customized_event_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomizedEventCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('customized_event_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customized_event_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customized_event_categories');
    }
}
