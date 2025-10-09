<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ships', function (Blueprint $table) {
            $table->foreignId('ship_type_id')->nullable()->change();
        });

        Schema::table('forms', function (Blueprint $table) {
            $table->foreignId('report_id')->change();
            $table->foreignId('form_type_id')->change();
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->foreignId('ship_id')->change();
        });

        Schema::table('form_data_ones', function (Blueprint $table) {
            $table->foreignId('form_id')->change();
        });

        Schema::table('form_data_twos', function (Blueprint $table) {
            $table->foreignId('form_id')->change();
        });

        Schema::table('form_tsect_data', function (Blueprint $table) {
            $table->foreignId('form_id')->change();
        });

        Schema::table('report_images', function (Blueprint $table) {
            $table->foreignId('report_id')->change();
            $table->foreignId('category_id')->nullable()->change();
            $table->foreignId('form_type_id')->nullable()->change();
        });

        Schema::table('ship_type_categories', function (Blueprint $table) {
            $table->foreignId('ship_type_id')->change();
            $table->foreignId('category_id')->change();
            $table->foreignId('form_type_id')->change();
        });

        Schema::table('form_data_threes', function (Blueprint $table) {
            $table->foreignId('form_id')->change();
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->default(1)->nullable()->change();
        });

        Schema::table('certifications', function (Blueprint $table) {
            $table->foreignId('report_id')->change();
        });

        Schema::table('equipment_certificates', function (Blueprint $table) {
            $table->foreignId('equipment_id')->default(0)->change();
            $table->foreignId('report_id')->default(0)->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ships', function (Blueprint $table) {
            $table->integer('ship_type_id')->nullable()->change();
        });

        Schema::table('forms', function (Blueprint $table) {
            $table->integer('report_id')->change();
            $table->integer('form_type_id')->change();
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->integer('ship_id')->change();
        });

        Schema::table('form_data_ones', function (Blueprint $table) {
            $table->integer('form_id')->change();
        });

        Schema::table('form_data_twos', function (Blueprint $table) {
            $table->integer('form_id')->change();
        });

        Schema::table('form_tsect_data', function (Blueprint $table) {
            $table->integer('form_id')->change();
        });

        Schema::table('report_images', function (Blueprint $table) {
            $table->integer('report_id')->change();
            $table->integer('category_id')->nullable()->change();
            $table->integer('form_type_id')->nullable()->change();
        });

        Schema::table('ship_type_categories', function (Blueprint $table) {
            $table->integer('ship_type_id')->change();
            $table->integer('category_id')->change();
            $table->integer('form_type_id')->change();
        });

        Schema::table('form_data_threes', function (Blueprint $table) {
            $table->integer('form_id')->change();
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->integer('user_id')->after('id')->default(1)->nullable()->change();
        });

        Schema::table('certifications', function (Blueprint $table) {
            $table->integer('report_id')->change();
        });

        Schema::table('equipment_certificates', function (Blueprint $table) {
            $table->integer('equipment_id')->default(0)->change();
            $table->integer('report_id')->default(0)->change();
        });
    }
};
