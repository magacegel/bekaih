<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'company_logo')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('company_logo')->nullable()->after('profile_image');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'company_logo')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('company_logo');
            });
        }
    }
};
