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
        Schema::create('antrian', function (Blueprint $table) {
            $table->id();
            $table->integer('provinsi_id');
            $table->string('provinsi');
            $table->integer('satker_id');
            $table->string('satker');
            $table->integer('user_id')->nullable();
            $table->string('user')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('image')->nullable();
            $table->integer('layanan_id');
            $table->string('layanan');
            $table->string('nomor_antrian');
            $table->string('qrcode');
            $table->date('tanggal_hadir');
            $table->string('keterangan')->nullable();
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
        Schema::dropIfExists('antrian');
    }
};
