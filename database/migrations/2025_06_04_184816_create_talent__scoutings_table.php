<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalentScoutingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('talent_scoutings', function (Blueprint $table) {
            $table->id();
            $table->string('file_cv');
            $table->string('file_ijazah');
            $table->string('file_pernyataan');
            $table->enum('status_seleksi', ['lolos', 'proses', 'ditolak']);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('batch_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('batch_id')->references('id')->on('scouting_batches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('talent_scoutings');
    }
}
