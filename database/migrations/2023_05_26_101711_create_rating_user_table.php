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
        Schema::create('rating_user', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('provinsi_id')->unsigned()->index();
            $table->bigInteger('satker_id')->unsigned()->index();
            $table->bigInteger('layanan_id')->unsigned()->index();
            $table->bigInteger('antrian_id')->unsigned()->index();
            $table->integer('user_id')->nullable();
            // $table->bigInteger('question_id')->unsigned()->index();
            $table->string('rating');
            $table->enum('answer_1',['1','2','3','4','5']);
            $table->enum('answer_2',['1','2','3','4','5']);
            $table->enum('answer_3',['1','2','3','4','5']);
            $table->enum('answer_4',['1','2','3','4','5']);
            $table->enum('answer_5',['1','2','3','4','5']);
            // $table->year('periode');
            $table->timestamps();

            $table->foreign('provinsi_id')->references('id')->on('provinsi');
            $table->foreign('satker_id')->references('id')->on('satker');
            $table->foreign('layanan_id')->references('id')->on('layanan');
            $table->foreign('antrian_id')->references('id')->on('antrian');
            // $table->foreign('question_id')->references('id')->on('question');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rating_user');
    }
};
