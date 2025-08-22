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
        Schema::table('upload_banner', function (Blueprint $table) {
            $table->string('role')->nullable();
            $table->string('satker_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('upload_banner', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('satker_id');
        });
    }
};