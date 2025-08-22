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
        Schema::table('rating_user', function (Blueprint $table) {
            $table->dropColumn('answer_2');
            $table->dropColumn('answer_3');
            $table->dropColumn('answer_4');
            $table->dropColumn('answer_5');
            $table->dropColumn('answer_6');
            $table->dropColumn('answer_7');
            $table->dropColumn('answer_8');
            $table->dropColumn('answer_9');
            $table->dropColumn('answer_10');
            $table->dropColumn('answer_11');
            $table->dropColumn('answer_12');
            $table->dropColumn('answer_13');
            $table->dropColumn('answer_14');
        });

        Schema::table('rating_user', function (Blueprint $table) {
            $table->enum('answer_2',['1','2','3','4','5','6'])->nullable();
            $table->enum('answer_3',['1','2','3','4','5','6'])->nullable();
            $table->enum('answer_4',['1','2','3','4','5','6'])->nullable();
            $table->enum('answer_5',['1','2','3','4','5','6'])->nullable();
            $table->enum('answer_6',['1','2','3','4','5','6'])->nullable();
            $table->enum('answer_7',['1','2','3','4','5','6'])->nullable();
            $table->enum('answer_8',['1','2','3','4','5','6'])->nullable();
            $table->enum('answer_9',['1','2','3','4','5','6'])->nullable();
            $table->enum('answer_10',['1','2','3','4','5','6'])->nullable();
            $table->enum('answer_11',['1','2','3','4','5','6'])->nullable();
            $table->enum('answer_12',['1','2','3','4','5','6'])->nullable();
            $table->enum('answer_13',['1','2','3','4','5','6'])->nullable();
            $table->enum('answer_14',['1','2','3','4','5','6'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};