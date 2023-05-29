<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('license_plate', 20)->unique();
            $table->integer('seat_number')->default(0);
            $table->tinyInteger('type')->default(0)->comment('0: seat, 1: bunk, 2: limousine');
            $table->text('content');
            $table->foreignId('admin_id')->constrained('admins', 'id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buses');
    }
};
