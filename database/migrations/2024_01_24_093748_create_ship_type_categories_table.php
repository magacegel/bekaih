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
        Schema::create('ship_type_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('ship_type_id');
            $table->integer('category_id');
            $table->integer('form_type_id');
            $table->integer('order')->default('999');
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
        Schema::dropIfExists('ship_type_categories');
    }
};
