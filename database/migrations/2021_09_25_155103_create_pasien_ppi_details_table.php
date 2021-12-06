<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasienPpiDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pasien_ppi_details', function (Blueprint $table) {
            $table->id();
            $table->integer('pasien_ppi_id');
            $table->integer('ruang_id');
            $table->json('diagnosa'); // [1,2,3]
            $table->tinyInteger('is_operasi')->default(0); // 0 = Tidak Operasi, 1 = Operasi
            $table->integer('tindakan_operasi_id')->nullable();
            $table->integer('jenis_operasi_id')->nullable();
            $table->integer('lama_operasi_id')->nullable();
            $table->integer('asa_score_id')->nullable();
            $table->integer('risk_score_id')->nullable();
            $table->dateTime('tgl_sensus');
            // $table->integer('alat_digunakan_id');
            $table->json('alat_digunakan_id');
            $table->integer('kegiatan_sensus_id');
            $table->text('hasil_rontgen')->nullable();
            $table->json('foto_hasil_rontgen')->nullable();
            $table->date('tgl_rontgen')->nullable();
            $table->json('jenis_infeksi_rs')->nullable(); // [1=>'2020-04-02',2=>'2020-01-11']
            $table->json('jenis_kumen')->nullable(); // [1,2,3]
            $table->date('tgl_infeksi_kuman')->nullable();
            $table->json('jenis_kultur_pendukun_hais')->nullable(); // [1,2,3]
            $table->json('antibiotik')->nullable(); // [1,2,3]
            $table->integer('transmisi_id')->nullable();
            $table->json('infeksi_rs_lain')->nullable(); // [1=>'2020-04-02',2=>'2020-01-11']
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pasien_ppi_details');
    }
}
