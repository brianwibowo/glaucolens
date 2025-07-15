<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMOOCEvalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mooc_evals', function (Blueprint $table) {
            $table->id();
            $table->string('soal');
            $table->string('pilihan_jawaban_1');
            $table->string('pilihan_jawaban_2');
            $table->string('pilihan_jawaban_3');
            $table->string('pilihan_jawaban_4');
            $table->string('jawaban_benar');
            $table->foreignId('mooc_id')->constrained('moocs')->onDelete('cascade');
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
        Schema::dropIfExists('mooc_evals');
    }
}
