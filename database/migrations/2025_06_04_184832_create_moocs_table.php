<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMOOCSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moocs', function (Blueprint $table) {
            $table->id();
            $table->string('judul_pelatihan');
            $table->text('deskripsi');
            $table->unsignedBigInteger('perusahaan_id');
            $table->timestamps();

            $table->foreign('perusahaan_id')->references('id')->on('users')->onDelete('cascade');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('moocs');
    }
}
