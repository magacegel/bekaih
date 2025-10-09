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
        Schema::table('ships', function (Blueprint $table) {
            $table->string('power')->after('weight')->nullable();
            $table->string('depth')->after('weight')->nullable();
            $table->string('breadth')->after('weight')->nullable();
            $table->string('loa')->after('weight')->nullable();
            $table->string('classification')->after('weight')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ships', function (Blueprint $table) {
            $table->dropColumn('power');
            $table->dropColumn('depth');
            $table->dropColumn('breadth');
            $table->dropColumn('loa');
            $table->dropColumn('classification');
        });
    }
};
