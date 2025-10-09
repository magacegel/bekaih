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
        if (!Schema::hasTable('equipment_certificates')) {
            Schema::create('equipment_certificates', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('equipment_id')->default(0);
                $table->bigInteger('report_id')->default(0);
                $table->string('certificate_number', 500)->nullable();
                $table->date('certificate_date')->nullable();
                $table->string('url', 150);
                $table->string('url_resized', 150)->nullable();
                $table->integer('active');
                $table->integer('order')->default(0);
                $table->json('metadata')->nullable()->default(null);
                $table->timestamps();
            });
        } else if (!Schema::hasColumn('equipment_certificates', 'metadata')) {
            Schema::table('equipment_certificates', function (Blueprint $table) {
                $table->json('metadata')->nullable()->default(null);
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equipment_certificates', function (Blueprint $table) {
            $table->dropColumn('metadata');
        });
    }
};
