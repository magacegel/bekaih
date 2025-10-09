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
      Schema::create('form_data_twos', function (Blueprint $table) {
        $table->id();

        $table->integer('form_id');
        $table->integer('plate_position');
        $table->string('position_txt')->nullable();
        $table->string('no_letter')->nullable();

        $table->string('item_no')->nullable();
        $table->decimal('org_thickness', 5,1)->nullable();
        $table->decimal('min_thickness', 5,1)->nullable();
        $table->decimal('gauged_p', 5,1)->nullable();
        $table->decimal('gauged_s', 5,1)->nullable();
        $table->decimal('dim_lvl', 5,1)->nullable();
        $table->decimal('dim_p_mm', 5,1)->nullable();
        $table->decimal('dim_p_pct', 5,1)->nullable();
        $table->decimal('dim_s_mm', 5,1)->nullable();
        $table->decimal('dim_s_pct', 5,1)->nullable();

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
        Schema::dropIfExists('form_data_twos');
    }
};
