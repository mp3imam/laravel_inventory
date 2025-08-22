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
        Schema::table('question', function (Blueprint $table) {
            $table->dropForeign(['provinsi_id']);
            $table->dropForeign(['satker_id']);
            $table->dropForeign(['layanan_id']);
            $table->dropColumn('provinsi_id');
            $table->dropColumn('satker_id');
            $table->dropColumn('layanan_id');
            $table->dropColumn('periode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};