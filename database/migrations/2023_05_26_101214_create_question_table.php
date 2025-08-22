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
        Schema::create('question', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('provinsi_id')->unsigned()->index();
            $table->bigInteger('satker_id')->unsigned()->index();
            $table->bigInteger('layanan_id')->unsigned()->index();
            $table->string('pertanyaan');
            $table->year('periode');
            $table->timestamps();

            $table->foreign('provinsi_id')->references('id')->on('provinsi');
            $table->foreign('satker_id')->references('id')->on('satker');
            $table->foreign('layanan_id')->references('id')->on('layanan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question');
    }
};
