<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntibiotiksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antibiotiks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_antibiotik');
            $table->integer('jumlah');
            $table->date('tanggal_awal')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->integer('is_active')->default(0); // 0 = tidak aktif, 1 = aktif
            $table->integer('kategori_antibiotik_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('antibiotiks');
    }
}
