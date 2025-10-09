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
        if (Schema::hasTable('equipments')) return;

        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('name')->default('');
            $table->string('manufactur')->default(''); // Note: Typo in column name (should be 'manufacturer')
            $table->string('model')->default('');
            $table->string('serial')->default('');
            $table->string('tolerancy')->default('');
            $table->string('probe_type')->default('');
            $table->timestamps();

            // Add indexes for better performance
            $table->index('user_id');
            $table->index('company_id');
            $table->index('serial');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
