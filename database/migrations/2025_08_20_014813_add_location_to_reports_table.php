<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationToReportsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('reports', 'location')) {
            Schema::table('reports', function (Blueprint $table) {
                $table->string('location')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('reports', 'location')) {
            Schema::table('reports', function (Blueprint $table) {
                $table->dropColumn('location');
            });
        }
    }
}
