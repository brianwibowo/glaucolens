<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Import DB facade

class CleanUpMoocQuizAndModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Temporarily disable foreign key checks for aggressive cleanup
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // 1. Drop the mooc_evals table (quiz table)
        Schema::dropIfExists('mooc_evals');

        // 2. Modify mooc_modules table
        Schema::table('mooc_modules', function (Blueprint $table) {
            // Drop quiz-related columns if they exist
            if (Schema::hasColumn('mooc_modules', 'question')) {
                $table->dropColumn('question');
            }
            if (Schema::hasColumn('mooc_modules', 'pilihan_jawaban_1')) {
                $table->dropColumn('pilihan_jawaban_1');
            }
            if (Schema::hasColumn('mooc_modules', 'pilihan_jawaban_2')) {
                $table->dropColumn('pilihan_jawaban_2');
            }
            if (Schema::hasColumn('mooc_modules', 'pilihan_jawaban_3')) {
                $table->dropColumn('pilihan_jawaban_3');
            }
            if (Schema::hasColumn('mooc_modules', 'pilihan_jawaban_4')) {
                $table->dropColumn('pilihan_jawaban_4');
            }
            if (Schema::hasColumn('mooc_modules', 'answer')) {
                $table->dropColumn('answer');
            }
            if (Schema::hasColumn('mooc_modules', 'link_eval')) {
                $table->dropColumn('link_eval');
            }

            // Add new 'deskripsi_modul' column
            if (!Schema::hasColumn('mooc_modules', 'deskripsi_modul')) {
                $table->text('deskripsi_modul')->nullable()->after('module_name');
            }
        });

        // 3. mooc_scores table: No explicit changes needed based on provided model,
        //    but if it had a 'nilai' column related to quiz score, it would be dropped here.
        //    (Your MoocScore model only has file_sertifikat, so it's fine)

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // 1. Re-create mooc_evals table (if needed for rollback)
        Schema::create('mooc_evals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mooc_id')->constrained('moocs')->onDelete('cascade');
            $table->string('soal');
            $table->string('pilihan_jawaban_1');
            $table->string('pilihan_jawaban_2');
            $table->string('pilihan_jawaban_3');
            $table->string('pilihan_jawaban_4');
            $table->string('jawaban_benar');
            $table->bigInteger('nilai_akhir')->default(0); // Assuming default was 0
            $table->timestamps();
        });

        // 2. Revert mooc_modules table
        Schema::table('mooc_modules', function (Blueprint $table) {
            // Drop deskripsi_modul
            if (Schema::hasColumn('mooc_modules', 'deskripsi_modul')) {
                $table->dropColumn('deskripsi_modul');
            }
            // Re-add old quiz-related columns if necessary (based on your original migration for mooc_modules)
            $table->text('question')->nullable();
            $table->text('pilihan_jawaban_1')->nullable();
            $table->text('pilihan_jawaban_2')->nullable();
            $table->text('pilihan_jawaban_3')->nullable();
            $table->text('pilihan_jawaban_4')->nullable();
            $table->string('answer')->nullable();
            $table->string('link_eval')->nullable();
        });

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}