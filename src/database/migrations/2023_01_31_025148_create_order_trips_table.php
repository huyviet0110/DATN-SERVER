<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders', 'id');
            $table->foreignId('trip_id')->constrained('trips', 'id');
            $table->string('pick_up_place');
            $table->string('drop_off_place');
            $table->time('pick_up_time');
            $table->time('drop_off_time');
            $table->decimal('price', 10, 2)->default(0.00);
            $table->integer('quantity');
            $table->date('ordered_at');
            $table->tinyInteger('status')->default(0)->comment('0: not cancelled, 1: cancelled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_trips');
    }
};
