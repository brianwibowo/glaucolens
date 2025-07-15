<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferenceOnUserPklSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom hanya jika belum ada
            if (!Schema::hasColumn('users', 'pkl_id')) {
                $table->unsignedBigInteger('pkl_id')->nullable();
                $table->foreign('pkl_id')->references('id')->on('pkls')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom hanya jika ada
            if (Schema::hasColumn('users', 'pkl_id')) {
                $table->dropForeign(['pkl_id']);
                $table->dropColumn('pkl_id');
            }
        });
    }
}
