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
        Schema::table('form_data_ones', function (Blueprint $table) {
            $table->boolean('newplate_aft')->nullable()->after('aft_dim_p_pct');
            $table->boolean('newplate_forward')->nullable()->after('forward_dim_p_pct');
        });

        Schema::table('form_data_twos', function (Blueprint $table) {
            $table->boolean('newplate')->nullable()->after('item_no');
        });

        Schema::table('form_data_threes', function (Blueprint $table) {
            $table->boolean('newplate_1')->nullable()->after('item_no_1');
            $table->boolean('newplate_2')->nullable()->after('item_no_2');
            $table->boolean('newplate_3')->nullable()->after('item_no_3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_data_ones', function (Blueprint $table) {
            $table->dropColumn('newplate_aft');
            $table->dropColumn('newplate_forward');
        });

        Schema::table('form_data_twos', function (Blueprint $table) {
            $table->dropColumn('newplate');
        });

        Schema::table('form_data_threes', function (Blueprint $table) {
            $table->dropColumn('newplate_1');
            $table->dropColumn('newplate_2');
            $table->dropColumn('newplate_3');
        });
    }
};
