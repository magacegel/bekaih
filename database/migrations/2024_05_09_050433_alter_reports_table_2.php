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
        Schema::table('reports', function (Blueprint $table) {
            $table->integer('surveyor_nup')->after('report_date')->nullable();
            $table->string('status')->after('surveyor_nup')->default('available');
            $table->integer('updated_by')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('surveyor_nup');
            $table->dropColumn('status');
            $table->dropColumn('updated_by');
        });
    }
};
