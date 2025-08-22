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
        Schema::create('menu_notif', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('menu', 100);
            $table->string('role', 15);
            $table->tinyInteger('status');
            $table->tinyInteger('is_email');
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
        Schema::dropIfExists('menu_notif');
    }
};
