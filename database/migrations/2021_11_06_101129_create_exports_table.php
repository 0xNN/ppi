<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exports', function (Blueprint $table) {
            $table->id();
            $table->string('bulan')->nullable();
            $table->integer('numerator')->nullable();
            $table->integer('denumerator')->nullable();
            $table->double('capaian')->nullable();
            $table->double('target')->nullable();
            $table->double('rata_rata')->nullable();
            $table->text('analisis')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->year('tahun')->nullable();
            $table->string('jenis_infeksi')->nullable();
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
        Schema::dropIfExists('exports');
    }
}
