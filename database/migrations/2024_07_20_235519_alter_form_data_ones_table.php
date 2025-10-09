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

      Schema::table('form_data_ones', function(Blueprint $t) {
        $t->decimal('org_thickness', 5,2)->change();
        $t->decimal('min_thickness', 5,2)->change();
        $t->decimal('aft_gauged_s', 5,2)->change();
        $t->decimal('aft_gauged_p', 5,2)->change();
        $t->decimal('aft_dim_lvl', 5,2)->change();
        $t->decimal('aft_dim_s_mm', 5,2)->change();
        $t->decimal('aft_dim_s_pct', 5,2)->change();
        $t->decimal('aft_dim_p_mm', 5,2)->change();
        $t->decimal('aft_dim_p_pct', 5,2)->change();
        $t->decimal('forward_gauged_s', 5,2)->change();
        $t->decimal('forward_gauged_p', 5,2)->change();
        $t->decimal('forward_dim_lvl', 5,2)->change();
        $t->decimal('forward_dim_s_mm', 5,2)->change();
        $t->decimal('forward_dim_s_pct', 5,2)->change();
        $t->decimal('forward_dim_p_mm', 5,2)->change();
        $t->decimal('forward_dim_p_pct', 5,2)->change();
        $t->decimal('mean_dim_s', 5,2)->change();
        $t->decimal('mean_dim_p', 5,2)->change();
      });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('form_data_ones', function(Blueprint $t) {
        $t->decimal('org_thickness', 5,1)->change();
        $t->decimal('min_thickness', 5,1)->change();
        $t->decimal('aft_gauged_s', 5,1)->change();
        $t->decimal('aft_gauged_p', 5,1)->change();
        $t->decimal('aft_dim_lvl', 5,1)->change();
        $t->decimal('aft_dim_s_mm', 5,1)->change();
        $t->decimal('aft_dim_s_pct', 5,1)->change();
        $t->decimal('aft_dim_p_mm', 5,1)->change();
        $t->decimal('aft_dim_p_pct', 5,1)->change();
        $t->decimal('forward_gauged_s', 5,1)->change();
        $t->decimal('forward_gauged_p', 5,1)->change();
        $t->decimal('forward_dim_lvl', 5,1)->change();
        $t->decimal('forward_dim_s_mm', 5,1)->change();
        $t->decimal('forward_dim_s_pct', 5,1)->change();
        $t->decimal('forward_dim_p_mm', 5,1)->change();
        $t->decimal('forward_dim_p_pct', 5,1)->change();
        $t->decimal('mean_dim_s', 5,1)->change();
        $t->decimal('mean_dim_p', 5,1)->change();
      });
    }
};
