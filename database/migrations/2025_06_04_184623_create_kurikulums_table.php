<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKurikulumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kurikulums', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengirim_id');
            $table->foreign('pengirim_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('perusahaan_id')->nullable();
            $table->foreign('perusahaan_id')->references('id')->on('users')->onDelete('set null');            
            $table->string('nama_kurikulum');
            $table->longText('deskripsi');
            $table->string('file_kurikulum');
            $table->longText('tahun_ajaran');
            $table->longText('komentar')->nullable();
            $table->enum('validasi_sekolah', ['disetujui', 'proses','tidak_disetujui'])->nullable();
            $table->enum('validasi_perusahaan', ['disetujui', 'proses','tidak_disetujui'])->nullable();
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
        Schema::dropIfExists('kurikulums');
    }
}