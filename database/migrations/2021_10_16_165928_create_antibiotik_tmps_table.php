<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntibiotikTmpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antibiotik_tmps', function (Blueprint $table) {
            $table->id();
            $table->string('no_rm');
            $table->string('nama_antibiotik');
            $table->integer('antibiotik_id');
            $table->json('kategori')->nullable();
            $table->boolean('keduanya')->nullable();
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
        Schema::dropIfExists('antibiotik_tmps');
    }
}
