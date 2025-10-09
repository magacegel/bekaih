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
        if (!Schema::hasTable('companies')) {
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_certificate_id');
                $table->string('name');
                $table->string('branch')->nullable();
                $table->string('address')->nullable();
                $table->string('city')->nullable();
                $table->string('zip_code')->nullable();
                $table->text('logo')->nullable();
                $table->text('logo_resized')->nullable();
                $table->timestamps();

                // Add index for better performance on company_certificate_id
                $table->index('company_certificate_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
