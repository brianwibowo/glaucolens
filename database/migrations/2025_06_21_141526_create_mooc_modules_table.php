<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoocModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mooc_modules', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('module_name');
            $table->string('link_materi');
            $table->string('dokumen_materi');
            $table->unsignedBigInteger('mooc_id');
            $table->string('link_eval');

            $table->foreign('mooc_id')->references('id')->on('moocs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mooc_modules');
    }
}
