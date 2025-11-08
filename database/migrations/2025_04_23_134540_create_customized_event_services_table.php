<?php

// database/migrations/xxxx_xx_xx_create_customized_event_services_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomizedEventServicesTable extends Migration
{
    public function up()
    {
        Schema::create('customized_event_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customized_event_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->decimal('calculated_price', 10, 2);
            $table->text('calculation_note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customized_event_services');
    }
}
