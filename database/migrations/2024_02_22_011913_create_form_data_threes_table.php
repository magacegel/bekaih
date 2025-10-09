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
        Schema::create('form_data_threes', function (Blueprint $table) {
            $table->id();


            $table->integer('form_id');
            $table->integer('plate_position');
            $table->string('position_txt')->nullable();
            $table->string('no_letter')->nullable();

            $table->string('item_no_1')->nullable();
            $table->decimal('org_thickness_1', 5,1)->nullable();
            $table->decimal('min_thickness_1', 5,1)->nullable();
            $table->decimal('gauged_p_1', 5,1)->nullable();
            $table->decimal('gauged_s_1', 5,1)->nullable();
            $table->decimal('dim_lvl_1', 5,1)->nullable();
            $table->decimal('dim_p_mm_1', 5,1)->nullable();
            $table->decimal('dim_p_pct_1', 5,1)->nullable();
            $table->decimal('dim_s_mm_1', 5,1)->nullable();
            $table->decimal('dim_s_pct_1', 5,1)->nullable();

            $table->string('item_no_2')->nullable();
            $table->decimal('org_thickness_2', 5,1)->nullable();
            $table->decimal('min_thickness_2', 5,1)->nullable();
            $table->decimal('gauged_p_2', 5,1)->nullable();
            $table->decimal('gauged_s_2', 5,1)->nullable();
            $table->decimal('dim_lvl_2', 5,1)->nullable();
            $table->decimal('dim_p_mm_2', 5,1)->nullable();
            $table->decimal('dim_p_pct_2', 5,1)->nullable();
            $table->decimal('dim_s_mm_2', 5,1)->nullable();
            $table->decimal('dim_s_pct_2', 5,1)->nullable();

            $table->string('item_no_3')->nullable();
            $table->decimal('org_thickness_3', 5,1)->nullable();
            $table->decimal('min_thickness_3', 5,1)->nullable();
            $table->decimal('gauged_p_3', 5,1)->nullable();
            $table->decimal('gauged_s_3', 5,1)->nullable();
            $table->decimal('dim_lvl_3', 5,1)->nullable();
            $table->decimal('dim_p_mm_3', 5,1)->nullable();
            $table->decimal('dim_p_pct_3', 5,1)->nullable();
            $table->decimal('dim_s_mm_3', 5,1)->nullable();
            $table->decimal('dim_s_pct_3', 5,1)->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_data_threes');
    }
};
