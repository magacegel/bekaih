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
        Schema::create('ship_lists', function (Blueprint $table) {
            $table->id();

            $table->string('NOREG');
            $table->string('NOIMO')->nullable();
            $table->string('NMKPL')->nullable();
            $table->string('TYSHP')->nullable();
            $table->string('BRT')->nullable();

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
        Schema::dropIfExists('ship_lists');
    }
};
