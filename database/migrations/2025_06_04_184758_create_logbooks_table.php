<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogbooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logbooks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('pkl_id');
            $table->enum('status', ['proses','disetujui', 'revisi']);
            $table->text('komentar_pembimbing')->nullable();
            $table->timestamps();
            
            $table->foreign('siswa_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pkl_id')->references('id')->on('pkls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logbooks');
    }
}
