<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValidationColumnsToLogbooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->enum('status_validasi_pembimbing', ['belum_validasi', 'valid', 'revisi'])
                ->default('belum_validasi')
                ->after('status');
            $table->enum('status_validasi_waka_humas', ['belum_validasi', 'valid', 'revisi'])
                ->default('belum_validasi')
                ->after('status_validasi_pembimbing');
            $table->text('komentar_waka_humas')->nullable()->after('komentar_pembimbing');
            $table->timestamp('tanggal_validasi_pembimbing')->nullable()->after('komentar_waka_humas');
            $table->timestamp('tanggal_validasi_waka_humas')->nullable()->after('tanggal_validasi_pembimbing');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->dropColumn('status_validasi_pembimbing');
            $table->dropColumn('status_validasi_waka_humas');
            $table->dropColumn('komentar_waka_humas');
            $table->dropColumn('tanggal_validasi_pembimbing');
            $table->dropColumn('tanggal_validasi_waka_humas');
        });
    }
}
