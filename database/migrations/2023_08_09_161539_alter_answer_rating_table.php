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
            $table->dropColumn('answer_1');
            $table->dropColumn('answer_2');
            $table->dropColumn('answer_3');
            $table->dropColumn('answer_4');
            $table->dropColumn('answer_5');
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