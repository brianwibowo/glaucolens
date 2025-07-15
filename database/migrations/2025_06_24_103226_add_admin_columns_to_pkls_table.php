<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminColumnsToPklsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pkls', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('updated_at');
            $table->unsignedBigInteger('admin_representing')->nullable()->after('created_by');
            $table->string('admin_representing_role')->nullable()->after('admin_representing');

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('admin_representing')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pkls', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['admin_representing']);
            $table->dropColumn(['created_by', 'admin_representing', 'admin_representing_role']);
        });
    }
}
