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
        if (!Schema::hasTable('form_histories')) {
            Schema::create('form_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('form_id')->constrained('forms')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('status');
                $table->string('actor_type');
                $table->text('notes')->nullable();
                $table->timestamps();

                // Add indexes
                $table->index('form_id');
                $table->index('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_histories');
    }
};
