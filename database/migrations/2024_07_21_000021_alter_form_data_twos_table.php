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
      Schema::table('form_data_twos', function(Blueprint $t) {
        $t->decimal('org_thickness', 5,2)->change();
        $t->decimal('min_thickness', 5,2)->change();
        $t->decimal('gauged_p', 5,2)->change();
        $t->decimal('gauged_s', 5,2)->change();
        $t->decimal('dim_lvl', 5,2)->change();
        $t->decimal('dim_p_mm', 5,2)->change();
        $t->decimal('dim_p_pct', 5,2)->change();
        $t->decimal('dim_s_mm', 5,2)->change();
        $t->decimal('dim_s_pct', 5,2)->change();
      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('form_data_twos', function(Blueprint $t) {
        $t->decimal('org_thickness', 5,1)->change();
        $t->decimal('min_thickness', 5,1)->change();
        $t->decimal('gauged_p', 5,1)->change();
        $t->decimal('gauged_s', 5,1)->change();
        $t->decimal('dim_lvl', 5,1)->change();
        $t->decimal('dim_p_mm', 5,1)->change();
        $t->decimal('dim_p_pct', 5,1)->change();
        $t->decimal('dim_s_mm', 5,1)->change();
        $t->decimal('dim_s_pct', 5,1)->change();
      });
    }
};
