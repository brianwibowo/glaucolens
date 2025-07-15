<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdatePklStatusEnumValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, add the 'ditolak' value to status_waka_humas enum to match controller
        DB::statement("ALTER TABLE pkls MODIFY COLUMN status_waka_humas ENUM('disetujui', 'proses', 'ditolak')");

        // Next, ensure status enum include all necessary values
        DB::statement("ALTER TABLE pkls MODIFY COLUMN status ENUM('proses', 'berjalan', 'selesai', 'ditolak')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE pkls MODIFY COLUMN status_waka_humas ENUM('disetujui', 'proses')");
        DB::statement("ALTER TABLE pkls MODIFY COLUMN status ENUM('proses', 'berjalan', 'selesai')");
    }
}
