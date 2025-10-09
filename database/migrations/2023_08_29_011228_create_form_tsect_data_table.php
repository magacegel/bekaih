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
      Schema::create('form_tsect_data', function (Blueprint $table) {
        $table->id();
        $table->integer('form_id');
        $table->string('port_side')->nullable();
        $table->decimal('org_thickness', 5,2)->nullable();

        $table->decimal('heat_gauged', 5,2)->nullable();
        $table->decimal('heat_dim_lvl', 5,2)->nullable();
        $table->decimal('heat_dim_mm', 5,2)->nullable();
        $table->decimal('heat_dim_pct', 5,2)->nullable();

        $table->decimal('face_gauged', 5,2)->nullable();
        $table->decimal('face_dim_lvl', 5,2)->nullable();
        $table->decimal('face_dim_mm', 5,2)->nullable();
        $table->decimal('face_dim_pct', 5,2)->nullable();

        $table->decimal('max_allwb', 5,2)->nullable();

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
        Schema::dropIfExists('form_tsect_data');
    }
};
