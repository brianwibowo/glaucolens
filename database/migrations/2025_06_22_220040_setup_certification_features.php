<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Add this line

class SetupCertificationFeatures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // TEMPORARILY DISABLE FOREIGN KEY CHECKS
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // Drop existing tables if they exist to recreate them with correct structure and FKs
        // This is safe because we just did a migrate:fresh
        Schema::dropIfExists('sertifikasis');
        Schema::dropIfExists('kuis_lsps'); // Old table name
        Schema::dropIfExists('soal_lsps'); // Old table name
        Schema::dropIfExists('certification_exams'); // New table, in case it wasn't fully dropped before
        Schema::dropIfExists('student_exam_attempts'); // Target new name
        Schema::dropIfExists('certification_exam_questions'); // Target new name

        // Drop the pkl_id foreign key from users table if it exists
        // This ensures no conflict when recreating tables if users has a FK to pkls
        Schema::table('users', function (Blueprint $table) {
            // Check if the foreign key exists by its typical name
            $fkName = 'users_pkl_id_foreign'; // Common Laravel FK name
            $hasFk = false;
            $foreignKeys = Schema::getConnection()->getDoctrineSchemaManager()->listTableForeignKeys('users');
            foreach ($foreignKeys as $fk) {
                if ($fk->getName() === $fkName) {
                    $hasFk = true;
                    break;
                }
            }

            if ($hasFk) {
                $table->dropForeign($fkName);
            }
            if (Schema::hasColumn('users', 'pkl_id')) {
                $table->dropColumn('pkl_id');
            }
        });


        // 1. Create certification_exams table (the parent/master table for exams)
        Schema::create('certification_exams', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ujian');
            $table->text('deskripsi')->nullable();
            $table->string('kompetensi_terkait')->nullable();
            $table->foreignId('pembuat_user_id')->constrained('users')->onDelete('cascade'); // Perusahaan atau LSP
            $table->integer('durasi_menit')->nullable();
            $table->integer('nilai_minimum_lulus');
            $table->enum('status_ujian', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamps();
        });

        // 2. Create student_exam_attempts table (replacement for kuis_lsps)
        Schema::create('student_exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Siswa
            $table->foreignId('certification_exam_id')->constrained('certification_exams')->onDelete('cascade'); // Ujian yang dikerjakan
            $table->integer('nilai')->nullable(); // Nilai akhir dari upaya ujian ini
            $table->timestamps();
        });

        // 3. Create certification_exam_questions table (replacement for soal_lsps)
        Schema::create('certification_exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certification_exam_id')->constrained('certification_exams')->onDelete('cascade'); // Terhubung ke ujian mana
            $table->text('soal');
            $table->string('pilihan_jawaban_1');
            $table->string('pilihan_jawaban_2');
            $table->string('pilihan_jawaban_3');
            $table->string('pilihan_jawaban_4');
            $table->integer('jawaban_benar'); // 1, 2, 3, atau 4
            $table->integer('nilai_akhir'); // Bobot nilai untuk soal ini
            $table->timestamps();
        });

        // 4. Create sertifikasis table (recreation with new foreign keys and columns)
        // Based on simila.sql, original sertifikasis columns were: id, nama_siswa, nama_lsp, kompetensi, dokumen_persyaratan, nilai, sertifikat_kelulusan.
        // We're adapting it to use FKs and a new status.
        Schema::create('sertifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Siswa yang mendaftar
            $table->foreignId('lsp_user_id')->nullable()->constrained('users')->onDelete('set null'); // Jika LSP yang terkait
            $table->foreignId('perusahaan_user_id')->nullable()->constrained('users')->onDelete('set null'); // Jika Perusahaan yang terkait
            $table->foreignId('certification_exam_id')->constrained('certification_exams')->onDelete('cascade'); // Ujian yang didaftar
            $table->string('kompetensi')->nullable(); // Keep if relevant from original table
            $table->text('dokumen_persyaratan'); // Path ke dokumen
            $table->integer('nilai')->nullable(); // Nilai akhir yang diinput LSP/Perusahaan
            $table->text('sertifikat_kelulusan')->nullable(); // Path ke file sertifikat
            $table->enum('status_pendaftaran_ujian', ['terdaftar', 'selesai_ujian', 'lulus', 'tidak_lulus'])->default('terdaftar');
            $table->timestamps();
        });

        // 5. Re-add pkl_id foreign key on users table if needed and pkls table already exists
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasTable('pkls')) { // Only re-add if pkls table exists
                $table->foreignId('pkl_id')->nullable()->after('updated_at')->constrained('pkls')->onDelete('cascade');
            }
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

        // Drop tables in reverse order of creation
        Schema::table('users', function (Blueprint $table) {
            // Drop FK to pkls before dropping pkls itself (if it's handled by another migration)
            if (Schema::hasColumn('users', 'pkl_id')) {
                $table->dropForeign(['pkl_id']);
                $table->dropColumn('pkl_id');
            }
        });

        Schema::dropIfExists('sertifikasis');
        Schema::dropIfExists('certification_exam_questions');
        Schema::dropIfExists('student_exam_attempts');
        Schema::dropIfExists('certification_exams');

        // RE-ENABLE FOREIGN KEY CHECKS
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}