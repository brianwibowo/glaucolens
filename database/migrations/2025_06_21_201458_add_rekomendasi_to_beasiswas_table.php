<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRekomendasiToBeasiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('beasiswas', function (Blueprint $table) {
            $table->boolean('direkomendasikan')->default(false);
            $table->text('catatan_rekomendasi')->nullable();
            $table->timestamp('tanggal_rekomendasi')->nullable();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('beasiswas', function (Blueprint $table) {
            //
        });
    }
}
