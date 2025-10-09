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
        Schema::create('txPermohonan', function (Blueprint $table) {
            $table->id();
            $table->string('no_permohonan');
            $table->string('perusahaan');
            $table->string('tipe_permohonan');
            $table->string('alamat');
            $table->string('paket_pengadaan');
            $table->string('keperluan');
            $table->string('no_dokumen');
            $table->string('pic');
            $table->string('verifikator');
            $table->string('user_entry');
            $table->string('tgl_entry');
        });

        Schema::create('txPermohonanDetail', function (Blueprint $table) {
            $table->id();
            $table->string('noorder');
            $table->string('kode_barang_jasa');
            $table->string('uraian_pekerjaan_barang');
            $table->string('spesifikasi_kualifikasi');
            $table->string('pemasok_asal');
            $table->string('kepemilikan_dibuat');
            $table->string('kepemilikan_dimiliki');
            $table->string('kepemilikan_alokasi');
            $table->string('jumlah');
            $table->string('satuan');
            $table->string('durasi');
            $table->string('harga_biaya_upah');
            $table->string('total');
            $table->string('user_entry');
            $table->string('tgl_entry');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('txPermohonan');
        Schema::dropIfExists('txPermohonanDetail');
    }
};
