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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(0)->comment('0: new, 1: already paid, 2: cancelled, 3: payment failed');
            $table->tinyInteger('payment_method')->default(0)->comment('0: cod, 1: atm, 2: international card, 3: momo');
            $table->foreignId('user_id')->nullable()->constrained('users', 'id');
            $table->string('name', 100);
            $table->string('phone', 20);
            $table->string('email', 100);
            $table->decimal('total_payment', 12, 2);
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
        Schema::dropIfExists('orders');
    }
};
