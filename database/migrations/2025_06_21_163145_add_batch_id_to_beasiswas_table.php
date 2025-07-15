<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('beasiswas', function (Blueprint $table) {
            $table->unsignedBigInteger('batch_id')->nullable()->after('status');
            $table->foreign('batch_id')->references('id')->on('beasiswa_batches')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('beasiswas', function (Blueprint $table) {
            $table->dropForeign(['batch_id']);
            $table->dropColumn('batch_id');
        });
    }
};
