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
        if (!Schema::hasTable('user_competencies')) {
            Schema::create('user_competencies', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('qualification')->nullable();
                $table->string('certificate_number')->nullable();
                $table->string('certificate_file')->nullable();
                $table->string('expired_date')->nullable();
                $table->timestamps();
                $table->json('metadata')->nullable()->default(null);
            });
        } else if (!Schema::hasColumn('user_competencies', 'metadata')) {
            Schema::table('user_competencies', function (Blueprint $table) {
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
        Schema::table('user_competencies', function (Blueprint $table) {
            $table->dropColumn('metadata');
        });
    }
};
