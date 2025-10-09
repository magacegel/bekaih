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
        Schema::create('forms', function (Blueprint $table) {
          $table->id();
          $table->integer('report_id');
          $table->integer('form_type_id');
          $table->string('name')->nullable();
          $table->text('title_3')->nullable();
          $table->text('title_2')->nullable();
          $table->text('title_1')->nullable();
          $table->decimal('default_dim_lvl', 5,1)->nullable();
          $table->decimal('default_org_thickness', 5,1)->nullable();
          $table->decimal('default_min_thickness', 5,1)->nullable();
          $table->integer('total_line')->default(0);
          $table->integer('total_spot')->default(0);
          $table->integer('order')->default(0);
          $table->string('operator')->nullable();
          $table->string('surveyor')->nullable();
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
        Schema::dropIfExists('forms');
    }
};
