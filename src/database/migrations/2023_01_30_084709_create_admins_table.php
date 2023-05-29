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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->rememberToken();
            $table->string('avatar')->nullable();
            $table->tinyInteger('type')->default(0)->comment('0: super-admin, 1: bus operator');
            $table->tinyInteger('gender')->nullable()->comment('0: female, 1: male');
            $table->date('birth_date')->nullable()->comment('Y-m-d');
            $table->string('phone_number', 20);
            $table->string('address');
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
        Schema::dropIfExists('admins');
    }
};
