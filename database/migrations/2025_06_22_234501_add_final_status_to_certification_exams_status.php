<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddFinalStatusToCertificationExamsStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `certification_exams` CHANGE `status_ujian` `status_ujian` ENUM('draft','published','archived','final') NOT NULL DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE `certification_exams` CHANGE `status_ujian` `status_ujian` ENUM('draft','published','archived') NOT NULL DEFAULT 'draft'");
    }
}