<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRisetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('risets', function (Blueprint $table) {
            $table->id();
            $table->string('topik');
            $table->string('deskripsi');
            $table->string('tim_riset');
            $table->text('file_proposal');
            $table->text('dokumentasi')->nullable();
            $table->enum('status', ['proses', 'disetujui', 'ditolak'])->default('proses');
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
        Schema::dropIfExists('risets');
    }
}
