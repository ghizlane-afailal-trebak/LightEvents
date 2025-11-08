<?php

// database/migrations/xxxx_xx_xx_create_customized_events_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomizedEventsTable extends Migration
{
    public function up()
    {
        Schema::create('customized_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('event_type');
            $table->date('date');
            $table->integer('person_count')->default(1);
            $table->decimal('final_price', 10, 2)->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customized_events');
    }
}
