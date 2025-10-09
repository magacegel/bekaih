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
        if (!Schema::hasTable('company_certificates')) {
            Schema::create('company_certificates', function (Blueprint $table) {
                $table->id();
                $table->text('certificate_file');
                $table->text('certificate_resized_file')->nullable();
                $table->text('number');
                $table->text('approval_number');
                $table->date('approval_date')->nullable();
                $table->date('expired_date')->default('2025-01-01');
                $table->foreignId('company_id')->default(0);
                $table->timestamps();
                $table->json('metadata')->nullable()->default(null);
            });
        } elseif (!Schema::hasColumn('company_certificates', 'metadata')) {
            Schema::table('company_certificates', function (Blueprint $table) {
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
        Schema::table('company_certificates', function (Blueprint $table) {
            $table->dropColumn('metadata');
        });
    }
};
