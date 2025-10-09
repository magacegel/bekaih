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
        Schema::table('forms', function (Blueprint $table) {
          $table->dropColumn('operator');
          $table->dropColumn('surveyor');
          $table->integer('surveyor_nup')->after('order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forms', function (Blueprint $table) {

          $table->string('surveyor')->after('order')->nullable();
          $table->string('operator')->after('order')->nullable();
          $table->dropColumn('surveyor_nup');

        });
    }
};
