<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLogbookContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logbook_content', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nama');
            $table->text('detail');
            $table->text('dokumentasi');
            $table->date('tanggal');
            $table->unsignedBigInteger('logbook_id');

            $table->foreign('logbook_id')->references('id')->on('logbooks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_logbook_content');
    }
}
