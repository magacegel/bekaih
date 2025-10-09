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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('cif')->unique(); // baru
            $table->string('name');
            $table->string('username')->unique(); // baru
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable(); // baru
            $table->string('user_type')->default('non_bki');
            $table->string('cabang')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('level'); // baru
            $table->rememberToken();
            $table->timestamps();
        });




        // Schema::create('msCustomer', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('cif')->unique();
        //     $table->string('nama_perusahaan');
        //     $table->string('npwp')->unique();
        //     $table->string('alamat_lengkap')->unique();
        //     $table->string('phone')->unique();
        //     $table->string('email')->unique();
        //     $table->string('account_officer');
        //     $table->string('keterangan');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        // Schema::dropIfExists('msCustomer');
    }
};
