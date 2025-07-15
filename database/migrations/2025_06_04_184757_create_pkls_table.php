<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePKLSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pkls', function (Blueprint $table) {
            $table->id();
            $table->string('nama');    
            $table->text('laporan_akhir')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->unsignedBigInteger('perusahaan_id')->nullable();
            $table->unsignedBigInteger('pembimbing_id')->nullable();
            $table->enum('status_pembimbing', ['disetujui', 'revisi', 'proses']);
            $table->enum('status_waka_humas', ['disetujui', 'proses']);
            $table->enum('status', ['proses', 'berjalan', 'selesai']);
            $table->timestamps();

            $table->foreign('perusahaan_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pembimbing_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pkls');
    }
}
