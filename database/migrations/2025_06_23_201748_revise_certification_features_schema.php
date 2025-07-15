<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ReviseCertificationFeaturesSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // TEMPORARILY DISABLE FOREIGN KEY CHECKS - Safety net
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // 1. Drop columns from certification_exams table
        Schema::table('certification_exams', function (Blueprint $table) {
            if (Schema::hasColumn('certification_exams', 'durasi_menit')) {
                $table->dropColumn('durasi_menit');
            }
            if (Schema::hasColumn('certification_exams', 'nilai_minimum_lulus')) {
                $table->dropColumn('nilai_minimum_lulus');
            }
            if (Schema::hasColumn('certification_exams', 'status_ujian')) {
                $table->dropColumn('status_ujian');
            }
        });

        // 2. Drop the tables no longer needed
        Schema::dropIfExists('certification_exam_questions'); // Formerly soal_lsps
        Schema::dropIfExists('student_exam_attempts');    // Formerly kuis_lsps

        // 3. Update sertifikasis table - remove FKs that point to dropped tables if they exist
        Schema::table('sertifikasis', function (Blueprint $table) {
            // If certification_exam_id no longer makes sense without quizzes
            // And assuming certification_exam_id is still needed to link to CertificationExam (the name/batch)
            // You might need to adjust this based on whether 'CertificationExam' is still related.
            // For now, I assume it's still related to 'CertificationExam' (the name/batch)
        });

        // RE-ENABLE FOREIGN KEY CHECKS
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // TEMPORARILY DISABLE FOREIGN KEY CHECKS
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // 1. Re-add columns to certification_exams (with default values)
        Schema::table('certification_exams', function (Blueprint $table) {
            $table->integer('durasi_menit')->nullable()->after('pembuat_user_id');
            $table->integer('nilai_minimum_lulus')->after('durasi_menit')->default(0);
            // Re-add status_ujian with old ENUM values
            DB::statement("ALTER TABLE `certification_exams` ADD `status_ujian` ENUM('draft','published','archived') NOT NULL DEFAULT 'draft' AFTER `nilai_minimum_lulus`");
        });

        // 2. Re-create the tables
        Schema::create('student_exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('certification_exam_id')->constrained('certification_exams')->onDelete('cascade');
            $table->integer('nilai')->nullable();
            $table->timestamps();
        });

        Schema::create('certification_exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certification_exam_id')->constrained('certification_exams')->onDelete('cascade');
            $table->text('soal');
            $table->string('pilihan_jawaban_1');
            $table->string('pilihan_jawaban_2');
            $table->string('pilihan_jawaban_3');
            $table->string('pilihan_jawaban_4');
            $table->integer('jawaban_benar');
            $table->integer('nilai_akhir');
            $table->timestamps();
        });

        // RE-ENABLE FOREIGN KEY CHECKS
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}