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
        Schema::create('form_types', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('form_data_format');
            $table->integer('order')->default(0);

            $table->string('unit_title')->nullable();
            $table->string('number_wording')->nullable();
            $table->string('unit_type')->enum(['plate','prefix','alphabet','number'])->default('plate')->nullable();
            $table->string('unit_prefix')->nullable();

            $table->string('gauged_p_title')->nullable();
            $table->string('gauged_s_title')->nullable();
            $table->string('dim_p_title')->nullable();
            $table->string('dim_s_title')->nullable();

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
        Schema::dropIfExists('form_types');
    }
};
