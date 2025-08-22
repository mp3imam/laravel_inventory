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
        Schema::create('active_layanan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('satker_id')->unsigned()->index();
            $table->bigInteger('layanan_id')->unsigned()->index();
            $table->bigInteger('status');
            $table->timestamps();

            $table->foreign('satker_id')->references('id')->on('satker')->onDelete('cascade');
            $table->foreign('layanan_id')->references('id')->on('layanan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('active_layanan');
    }
};
