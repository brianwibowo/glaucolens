<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLinkMateriToTextInMoocModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mooc_modules', function (Blueprint $table) {
            // Change 'link_materi' column type to TEXT
            $table->text('link_materi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mooc_modules', function (Blueprint $table) {
            // Revert 'link_materi' column type back to string (VARCHAR)
            // This might truncate data if you have long links already stored
            $table->string('link_materi')->nullable()->change();
        });
    }
}