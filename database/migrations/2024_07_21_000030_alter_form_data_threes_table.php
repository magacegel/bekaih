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
      Schema::table('form_data_threes', function(Blueprint $t) {
        $t->decimal('org_thickness_1', 5,2)->change();
        $t->decimal('min_thickness_1', 5,2)->change();
        $t->decimal('gauged_p_1', 5,2)->change();
        $t->decimal('gauged_s_1', 5,2)->change();
        $t->decimal('dim_lvl_1', 5,2)->change();
        $t->decimal('dim_p_mm_1', 5,2)->change();
        $t->decimal('dim_p_pct_1', 5,2)->change();
        $t->decimal('dim_s_mm_1', 5,2)->change();
        $t->decimal('dim_s_pct_1', 5,2)->change();

        $t->decimal('org_thickness_2', 5,2)->change();
        $t->decimal('min_thickness_2', 5,2)->change();
        $t->decimal('gauged_p_2', 5,2)->change();
        $t->decimal('gauged_s_2', 5,2)->change();
        $t->decimal('dim_lvl_2', 5,2)->change();
        $t->decimal('dim_p_mm_2', 5,2)->change();
        $t->decimal('dim_p_pct_2', 5,2)->change();
        $t->decimal('dim_s_mm_2', 5,2)->change();
        $t->decimal('dim_s_pct_2', 5,2)->change();

        $t->decimal('org_thickness_3', 5,2)->change();
        $t->decimal('min_thickness_3', 5,2)->change();
        $t->decimal('gauged_p_3', 5,2)->change();
        $t->decimal('gauged_s_3', 5,2)->change();
        $t->decimal('dim_lvl_3', 5,2)->change();
        $t->decimal('dim_p_mm_3', 5,2)->change();
        $t->decimal('dim_p_pct_3', 5,2)->change();
        $t->decimal('dim_s_mm_3', 5,2)->change();
        $t->decimal('dim_s_pct_3', 5,2)->change(); 

      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('form_data_threes', function(Blueprint $t) {
        $t->decimal('org_thickness_1', 5,1)->change();
        $t->decimal('min_thickness_1', 5,1)->change();
        $t->decimal('gauged_p_1', 5,1)->change();
        $t->decimal('gauged_s_1', 5,1)->change();
        $t->decimal('dim_lvl_1', 5,1)->change();
        $t->decimal('dim_p_mm_1', 5,1)->change();
        $t->decimal('dim_p_pct_1', 5,1)->change();
        $t->decimal('dim_s_mm_1', 5,1)->change();
        $t->decimal('dim_s_pct_1', 5,1)->change();

        $t->decimal('org_thickness_2', 5,1)->change();
        $t->decimal('min_thickness_2', 5,1)->change();
        $t->decimal('gauged_p_2', 5,1)->change();
        $t->decimal('gauged_s_2', 5,1)->change();
        $t->decimal('dim_lvl_2', 5,1)->change();
        $t->decimal('dim_p_mm_2', 5,1)->change();
        $t->decimal('dim_p_pct_2', 5,1)->change();
        $t->decimal('dim_s_mm_2', 5,1)->change();
        $t->decimal('dim_s_pct_2', 5,1)->change();

        $t->decimal('org_thickness_3', 5,1)->change();
        $t->decimal('min_thickness_3', 5,1)->change();
        $t->decimal('gauged_p_3', 5,1)->change();
        $t->decimal('gauged_s_3', 5,1)->change();
        $t->decimal('dim_lvl_3', 5,1)->change();
        $t->decimal('dim_p_mm_3', 5,1)->change();
        $t->decimal('dim_p_pct_3', 5,1)->change();
        $t->decimal('dim_s_mm_3', 5,1)->change();
        $t->decimal('dim_s_pct_3', 5,1)->change(); 
      });
    }
};
