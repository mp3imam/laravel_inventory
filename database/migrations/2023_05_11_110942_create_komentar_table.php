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
        Schema::create('komentar', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('keluhan_id')->unsigned()->index();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->text('komentar');
            $table->text('image')->nullable();
            $table->enum('status',[0,1])->default(0);
            $table->timestamps();

            $table->foreign('keluhan_id')->references('id')->on('keluhan')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('komentar');
    }
};
