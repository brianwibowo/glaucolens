<?php

use App\Models\Project;
use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\KurikulumController as AdminKurikulumController;
use App\Http\Controllers\Admin\GuruTamuController as AdminGuruTamuController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\SertifikasiController as AdminSertifikasiController;
use App\Http\Controllers\Admin\ScoutingController as AdminScoutingController;
use App\Http\Controllers\Admin\BeasiswaScoutingController as AdminBeasiswaScoutingController;
use App\Http\Controllers\Admin\MoocController as AdminMoocController;
use App\Http\Controllers\Admin\MoocModuleController as AdminMoocModuleController;
use App\Http\Controllers\Admin\RisetController as AdminRisetController; 

// Perusahaan Controllers
use App\Http\Controllers\Perusahaan\KurikulumController as PerusahaanKurikulumController;
use App\Http\Controllers\Perusahaan\ProjectController as PerusahaanProjectController;
use App\Http\Controllers\Perusahaan\GuruTamuController as PerusahaanGuruTamuController;
use App\Http\Controllers\Perusahaan\PklController as PerusahaanPklController;
use App\Http\Controllers\Perusahaan\MoocController as PerusahaanMoocController;
use App\Http\Controllers\Perusahaan\MoocModuleController as PerusahaanMoocModuleController;
use App\Http\Controllers\Perusahaan\ScoutingController as PerusahaanScoutingController;
use App\Http\Controllers\Perusahaan\BeasiswaScoutingController as PerusahaanBeasiswaScoutingController;
use App\Http\Controllers\Perusahaan\SertifikasiController as PerusahaanSertifikasiController; // <<< ADDED THIS LINE
use App\Http\Controllers\Perusahaan\RisetController as PerusahaanRisetController; // <<< ADDED THIS LINE

// Siswa Controllers
use App\Http\Controllers\Siswa\BeasiswaScoutingController as SiswaBeasiswaScoutingController;
use App\Http\Controllers\Siswa\PklController as SiswaPklController;
use App\Http\Controllers\Siswa\LogbookController as SiswaLogbookController;
use App\Http\Controllers\Siswa\SertifikasiController as SiswaSertifikasiController;

// Alumni Controllers
use App\Http\Controllers\Alumni\ScoutingController as AlumniScoutingController;

// Waka Kurikulum Controllers
use App\Http\Controllers\WakaKurikulum\BeasiswaRekomendasiController as WakaKurikulumBeasiswaRekomendasiController;
use App\Http\Controllers\WakaKurikulum\KurikulumController as WakaKurikulumController; // Added for WakaKurikulum Kurikulum routes

// Waka Humas Controllers
use App\Http\Controllers\WakaHumas\RisetController as WakaHumasRisetController;
use App\Http\Controllers\WakaHumas\GuruTamuController as WakaHumasGuruTamuController;
use App\Http\Controllers\WakaHumas\PklController as WakaHumasPklController;

// Guru Controllers
use App\Http\Controllers\Guru\ProjectController as GuruProjectController;
use App\Http\Controllers\Guru\MoocController as GuruMoocController;
use App\Http\Controllers\Guru\PklController as GuruPklController;
use App\Http\Controllers\Guru\ReflectionController as GuruReflectionController;

// LSP Controllers
use App\Http\Controllers\Lsp\SertifikasiController as LspSertifikasiController;



/*
|----------------------
----------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () { // Group for authenticated users
    Route::get('/user', function () {
        return view('user');
    })->name('user');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('admin-dashboard');

        // START: Rute Baru untuk Sertifikasi Kompetensi oleh Admin
        Route::prefix('sertifikasi')->name('admin-sertifikasi-')->group(function () {
            Route::get('/', [AdminSertifikasiController::class, 'index'])->name('index');
            Route::get('/create', [AdminSertifikasiController::class, 'create'])->name('create');
            Route::post('/', [AdminSertifikasiController::class, 'store'])->name('store');
            Route::get('/{certificationExam}', [AdminSertifikasiController::class, 'show'])->name('show');
            Route::get('/{certificationExam}/edit', [AdminSertifikasiController::class, 'edit'])->name('edit');
            Route::put('/{certificationExam}', [AdminSertifikasiController::class, 'update'])->name('update');
            Route::delete('/{certificationExam}', [AdminSertifikasiController::class, 'destroy'])->name('destroy');

            // Rute untuk melihat hasil pendaftaran dan memberikan sertifikat
            Route::get('/results/inspect', [AdminSertifikasiController::class, 'listResults'])->name('results');
            Route::get('/results/{registration}/give-certificate', [AdminSertifikasiController::class, 'giveCertificateForm'])->name('results.give_certificate_form');
            Route::post('/results/{registration}/store-certificate', [AdminSertifikasiController::class, 'storeCertificate'])->name('results.store_certificate');
        });

        Route::get('/users', [AdminUserController::class, 'index'])->name('admin-users-index');
        Route::post('/users/{user}/update-role', [AdminUserController::class, 'updateRole'])->name('admin-users-update-role');

        
        // Riset & Inovasi Produk (Admin)
        Route::resource('riset', AdminRisetController::class)->names([
            'index' => 'admin-riset-index',
            'create' => 'admin-riset-create',
            'store' => 'admin-riset-store',
            'show' => 'admin-riset-show',
            'edit' => 'admin-riset-edit',
            'update' => 'admin-riset-update',
            'destroy' => 'admin-riset-destroy',
        ]);

        Route::post('riset/{riset}/dokumentasi', [AdminRisetController::class, 'dokumentasi'])->name('admin-riset-dokumentasi');
        Route::patch('riset/{riset}/terima', [AdminRisetController::class, 'terima'])->name('admin-riset-terima');
        Route::patch('riset/{riset}/tolak', [AdminRisetController::class, 'tolak'])->name('admin-riset-tolak');
        Route::get('riset/results', [AdminRisetController::class, 'results'])->name('admin-riset-results');

        // Admin PKL Routes - New Feature
        Route::prefix('pkl')->name('admin-pkl-')->group(function () {
            // Dashboard & Main PKL routes
            Route::get('/', [App\Http\Controllers\Admin\PklController::class, 'index'])->name('index');

            // Company representation routes
            Route::get('/represent-company', [App\Http\Controllers\Admin\PklController::class, 'selectCompany'])->name('select-company');
            Route::post('/represent-company', [App\Http\Controllers\Admin\PklController::class, 'representCompany'])->name('represent-company');
            Route::get('/company/{company}/create', [App\Http\Controllers\Admin\PklController::class, 'createForCompany'])->name('create-for-company');
            Route::post('/company/{company}', [App\Http\Controllers\Admin\PklController::class, 'storeForCompany'])->name('store-for-company');
            Route::get('/company/{company}/{pkl}/edit', [App\Http\Controllers\Admin\PklController::class, 'editForCompany'])->name('edit-for-company');
            Route::put('/company/{company}/{pkl}', [App\Http\Controllers\Admin\PklController::class, 'updateForCompany'])->name('update-for-company');
            Route::delete('/company/{company}/{pkl}', [App\Http\Controllers\Admin\PklController::class, 'destroyForCompany'])->name('destroy-for-company');
            Route::get('/company/{company}/{pkl}/students', [App\Http\Controllers\Admin\PklController::class, 'studentsForCompany'])->name('students-for-company');
            Route::post('/company/{company}/student/{user}/approve', [App\Http\Controllers\Admin\PklController::class, 'approveStudentForCompany'])->name('approve-student-for-company');
            Route::post('/company/{company}/student/{user}/reject', [App\Http\Controllers\Admin\PklController::class, 'rejectStudentForCompany'])->name('reject-student-for-company');
            Route::post('/company/{company}/student/{user}/grade', [App\Http\Controllers\Admin\PklController::class, 'gradeStudentForCompany'])->name('grade-student-for-company');

            // Guru representation routes
            Route::get('/represent-guru', [App\Http\Controllers\Admin\PklController::class, 'selectGuru'])->name('select-guru');
            Route::post('/represent-guru', [App\Http\Controllers\Admin\PklController::class, 'representGuru'])->name('represent-guru');
            Route::get('/guru/{guru}/siswa/{siswa}/logbook', [App\Http\Controllers\Admin\PklController::class, 'siswaLogbook'])->name('siswa-logbook');
            Route::post('/guru/{guru}/siswa/{siswa}/validate-report', [App\Http\Controllers\Admin\PklController::class, 'validateReportForGuru'])->name('validate-report-for-guru');

            // Waka Humas like actions
            Route::get('/assign-pembimbing', [App\Http\Controllers\Admin\PklController::class, 'assignPembimbingList'])->name('assign-pembimbing-list');
            Route::get('/assign-pembimbing/{pkl}', [App\Http\Controllers\Admin\PklController::class, 'assignPembimbingForm'])->name('assign-pembimbing-form');
            Route::post('/assign-pembimbing/{pkl}', [App\Http\Controllers\Admin\PklController::class, 'assignPembimbingStore'])->name('assign-pembimbing-store');
            Route::post('/validate-report/{pkl}', [App\Http\Controllers\Admin\PklController::class, 'validateReport'])->name('validate-report');
        });

        // MOOC Routes (Admin)
        Route::resource('mooc', AdminMoocController::class)->names([
            'index' => 'admin-mooc-index',
            'create' => 'admin-mooc-create',
            'store' => 'admin-mooc-store',
            'edit' => 'admin-mooc-edit',
            'update' => 'admin-mooc-update',
            'destroy' => 'admin-mooc-destroy',
            'show' => 'admin-mooc-show',
        ]);
        Route::post('mooc/{mooc}/sertifikat/{user}/upload', [AdminMoocController::class, 'uploadSertifikat'])->name('admin-mooc-sertifikat-upload');

        // MOOC Module Routes (Admin) - Didefinisikan secara eksplisit
        Route::prefix('module')->name('admin-module-')->group(function () {
            Route::get('/{mooc}/create', [AdminMoocModuleController::class, 'create'])->name('create');
            Route::post('/{mooc}', [AdminMoocModuleController::class, 'store'])->name('store');
            Route::get('/{mooc}/{module}', [AdminMoocModuleController::class, 'show'])->name('show');
            Route::get('/{mooc}/{module}/edit', [AdminMoocModuleController::class, 'edit'])->name('edit');
            Route::put('/{mooc}/{module}', [AdminMoocModuleController::class, 'update'])->name('update');
            Route::delete('/{module}', [AdminMoocModuleController::class, 'destroy'])->name('destroy');
        });

        // PKL Pembimbing Assignment Routes
        Route::prefix('pkl/assign')->name('admin-pkl-assign-')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\PklController::class, 'index'])->name('index');
            Route::get('/{pkl}', [App\Http\Controllers\Admin\PklController::class, 'show'])->name('show');
            Route::get('/{pkl}/form', [App\Http\Controllers\Admin\PklController::class, 'showAssignForm'])->name('form');
            Route::post('/{pkl}', [App\Http\Controllers\Admin\PklController::class, 'assignPembimbing'])->name('store');
            Route::delete('/{pkl}', [App\Http\Controllers\Admin\PklController::class, 'removePembimbing'])->name('remove');
        });

        Route::resource('scouting', AdminScoutingController::class)->names([
            'index' => 'admin-scouting-index',
            'create' => 'admin-scouting-create',
            'store' => 'admin-scouting-store',
            'edit' => 'admin-scouting-edit',
            'update' => 'admin-scouting-update',
            'destroy' => 'admin-scouting-destroy',
            'show' => 'admin-scouting-show',
        ]);
        Route::get('/detail-talents/{user}/{scouting}', [AdminScoutingController::class, 'siswa'])->name('admin-scouting-siswa');
        Route::post('/scouting/seleksi/{talent}', [AdminScoutingController::class, 'seleksi'])->name('admin-scouting-seleksi');

        Route::resource('beasiswa', AdminBeasiswaScoutingController::class)->names([
            'index'   => 'admin-beasiswa-index',
            'create'  => 'admin-beasiswa-create',
            'store'   => 'admin-beasiswa-store',
            'edit'    => 'admin-beasiswa-edit',
            'update'  => 'admin-beasiswa-update',
            'destroy' => 'admin-beasiswa-destroy',
            'show'    => 'admin-beasiswa-show',
        ]);
        Route::get('beasiswa/{beasiswa}/siswa/{user}', [AdminBeasiswaScoutingController::class, 'siswa'])->name('admin-beasiswa-siswa');
        Route::post('beasiswa/{beasiswa}/siswa/{pendaftar}/seleksi', [AdminBeasiswaScoutingController::class, 'seleksi'])->name('admin-beasiswa-seleksi');

        // Project Routes for Admin
        Route::prefix('project')->name('admin-project-')->group(function () {
            Route::get('/', [AdminProjectController::class, 'index'])->name('index');
            Route::get('/create', [AdminProjectController::class, 'create'])->name('create');
            Route::post('/', [AdminProjectController::class, 'store'])->name('store');
            Route::get('/{project}/edit', [AdminProjectController::class, 'edit'])->name('edit');
            Route::put('/{project}', [AdminProjectController::class, 'update'])->name('update');
            Route::delete('/{project}', [AdminProjectController::class, 'destroy'])->name('destroy');
            Route::post('/{project}/laporan', [AdminProjectController::class, 'uploadLaporan'])->name('laporan-upload');
            Route::put('/{project}/laporan', [AdminProjectController::class, 'updateLaporan'])->name('laporan-update');
            Route::delete('/{project}/laporan', [AdminProjectController::class, 'deleteLaporan'])->name('laporan-delete');
        });

        // Kurikulum Routes
        Route::get('/kurikulum', [AdminKurikulumController::class, 'index'])->name('admin-kurikulum-list-diajukan');
        Route::get('/kurikulum/validasi', [AdminKurikulumController::class, 'validasi'])->name('admin-kurikulum-list-validasi');
        Route::get('/kurikulum/validasi-sekolah', [AdminKurikulumController::class, 'validasiSekolah'])->name('admin-kurikulum-list-validasi-sekolah');
        Route::patch('/kurikulum/{kurikulum}/tolak-sekolah', [AdminKurikulumController::class, 'tolakSekolah'])->name('admin-kurikulum-tolak-sekolah');
        Route::get('/kurikulum/create', [AdminKurikulumController::class, 'create'])->name('admin-kurikulum-create');
        Route::get('/kurikulum/create-for-school', [AdminKurikulumController::class, 'createForSchool'])->name('admin-kurikulum-create-for-school');
        Route::get('/kurikulum/create-for-company', [AdminKurikulumController::class, 'createForCompany'])->name('admin-kurikulum-create-for-company');
        Route::post('/kurikulum', [AdminKurikulumController::class, 'store'])->name('admin-kurikulum-store');
        Route::get('/kurikulum/{kurikulum}/edit', [AdminKurikulumController::class, 'edit'])->name('admin-kurikulum-edit');
        Route::put('/kurikulum/{kurikulum}', [AdminKurikulumController::class, 'update'])->name('admin-kurikulum-update');
        Route::delete('/kurikulum/{kurikulum}', [AdminKurikulumController::class, 'destroy'])->name('admin-kurikulum-destroy');
        Route::patch('/kurikulum/{kurikulum}/setuju', [AdminKurikulumController::class, 'setuju'])->name('admin-kurikulum-setuju');
        Route::patch('/kurikulum/{kurikulum}/tolak', [AdminKurikulumController::class, 'tolak'])->name('admin-kurikulum-tolak');

        // Guru Tamu Admin Routes
        Route::resource('guru-tamu', AdminGuruTamuController::class)->names([
            'index' => 'admin-guru-tamu-index',
            'create' => 'admin-guru-tamu-create',
            'store' => 'admin-guru-tamu-store',
            'show' => 'admin-guru-tamu-show',
            'edit' => 'admin-guru-tamu-edit',
            'update' => 'admin-guru-tamu-update',
            'destroy' => 'admin-guru-tamu-destroy',
        ]);
        Route::put('guru-tamu/{guruTamu}/approve', [AdminGuruTamuController::class, 'approve'])->name('admin-guru-tamu-approve');
        Route::put('guru-tamu/{guruTamu}/reject', [AdminGuruTamuController::class, 'reject'])->name('admin-guru-tamu-reject');
    });

    // Perusahaan Routes
    Route::middleware(['role:perusahaan'])->prefix('perusahaan')->group(function () {
        Route::get('/', function () {
            return view('perusahaan.dashboard');
        })->name('perusahaan-dashboard');

        Route::resource('kurikulum', PerusahaanKurikulumController::class)->names([
            'index' => 'perusahaan-kurikulum-list-diajukan',
            'store' => 'perusahaan-kurikulum-store',
            'edit' => 'perusahaan-kurikulum-edit',
            'update' => 'perusahaan-kurikulum-update',
            'destroy' => 'perusahaan-kurikulum-destroy',
            'create' => 'perusahaan-kurikulum-create',
        ])->except('show');
        Route::get('/kurikulum/validasi', [PerusahaanKurikulumController::class, 'validasi'])->name('perusahaan-kurikulum-list-validasi');
        Route::put('/kurikulum/{kurikulum}/setuju', [PerusahaanKurikulumController::class, 'setuju'])->name('perusahaan-kurikulum-setuju');
        Route::put('/kurikulum/{kurikulum}/tolak', [PerusahaanKurikulumController::class, 'tolak'])->name('perusahaan-kurikulum-tolak');
        Route::patch('/kurikulum/{kurikulum}/cancel-approval', [PerusahaanKurikulumController::class, 'cancelApproval'])->name('perusahaan-kurikulum-cancel-approval');

        Route::resource('project', PerusahaanProjectController::class)->names([
            'index' => 'perusahaan-project-index',
            'create' => 'perusahaan-project-create',
            'store' => 'perusahaan-project-store',
            'show' => 'perusahaan-project-show',
            'edit' => 'perusahaan-project-edit',
            'update' => 'perusahaan-project-update',
            'destroy' => 'perusahaan-project-destroy',
        ]);

        Route::resource('guru-tamu', PerusahaanGuruTamuController::class)->names([
            'index' => 'perusahaan-guru-tamu-index',
            'create' => 'perusahaan-guru-tamu-create',
            'store' => 'perusahaan-guru-tamu-store',
            'edit' => 'perusahaan-guru-tamu-edit',
            'update' => 'perusahaan-guru-tamu-update',
            'destroy' => 'perusahaan-guru-tamu-destroy',
        ])->except('show');
        Route::get('/guru-tamu/list', [PerusahaanGuruTamuController::class, 'list'])->name('perusahaan-guru-tamu-list');

        Route::resource('pkl', PerusahaanPklController::class)->names([
            'index' => 'perusahaan-pkl-index',
            'create' => 'perusahaan-pkl-create',
            'store' => 'perusahaan-pkl-store',
            'edit' => 'perusahaan-pkl-edit',
            'update' => 'perusahaan-pkl-update',
            'destroy' => 'perusahaan-pkl-destroy',
            'show' => 'perusahaan-pkl-show',
        ]);
        Route::get('/pkl/siswa/{user}', [PerusahaanPklController::class, 'siswa'])->name('perusahaan-pkl-siswa');
        Route::get('/list-pendaftar', [PerusahaanPklController::class, 'list'])->name('perusahaan-pkl-list');
        Route::post('/pkl/{user}/terima', [PerusahaanPklController::class, 'terima'])->name('perusahaan-pkl-terima');
        Route::post('/pkl/{user}/tolak', [PerusahaanPklController::class, 'tolak'])->name('perusahaan-pkl-tolak');
        Route::post('/pkl/{user}/nilai', [PerusahaanPklController::class, 'nilai'])->name('perusahaan-pkl-nilai');
        Route::delete('/pkl/{user}/remove-applicant', [PerusahaanPklController::class, 'removeApplicant'])->name('perusahaan-pkl-remove-applicant');
        Route::patch('/pkl/{user}/archive-applicant', [PerusahaanPklController::class, 'archiveApplicant'])->name('perusahaan-pkl-archive-applicant');

        Route::resource('mooc', PerusahaanMoocController::class)->names([
            'index' => 'perusahaan-mooc-index',
            'create' => 'perusahaan-mooc-create',
            'store' => 'perusahaan-mooc-store',
            'edit' => 'perusahaan-mooc-edit',
            'update' => 'perusahaan-mooc-update',
            'destroy' => 'perusahaan-mooc-destroy',
            'show' => 'perusahaan-mooc-show',
        ]);

        Route::prefix('module')->name('perusahaan-module-')->group(function () {
            Route::get('/{mooc}/create', [PerusahaanMoocModuleController::class, 'create'])->name('create');
            Route::post('/{mooc}', [PerusahaanMoocModuleController::class, 'store'])->name('store'); // Store harusnya ke mooc_id
            Route::get('/{mooc}/{module}', [PerusahaanMoocModuleController::class, 'show'])->name('show'); // show dengan 2 parameter
            Route::get('/{mooc}/{module}/edit', [PerusahaanMoocModuleController::class, 'edit'])->name('edit'); // edit dengan 2 parameter
            Route::put('/{mooc}/{module}', [PerusahaanMoocModuleController::class, 'update'])->name('update'); // update dengan 2 parameter
            Route::delete('/{module}', [PerusahaanMoocModuleController::class, 'destroy'])->name('destroy'); // destroy masih 1 parameter {module}
        });

        Route::resource('beasiswa', PerusahaanBeasiswaScoutingController::class)->names([
            'index'   => 'perusahaan-beasiswa-index',
            'create'  => 'perusahaan-beasiswa-create',
            'store'   => 'perusahaan-beasiswa-store',
            'edit'    => 'perusahaan-beasiswa-edit',
            'update'  => 'perusahaan-beasiswa-update',
            'destroy' => 'perusahaan-beasiswa-destroy',
            'show'    => 'perusahaan-beasiswa-show',
        ]);
        Route::get('beasiswa/{beasiswa}/siswa/{user}', [PerusahaanBeasiswaScoutingController::class, 'siswa'])->name('perusahaan-beasiswa-siswa');
        Route::post('beasiswa/{beasiswa}/siswa/{pendaftar}/seleksi', [PerusahaanBeasiswaScoutingController::class, 'seleksi'])->name('perusahaan-beasiswa-seleksi');

        Route::resource('scouting', PerusahaanScoutingController::class)->names([
            'index' => 'perusahaan-scouting-index',
            'create' => 'perusahaan-scouting-create',
            'store' => 'perusahaan-scouting-store',
            'edit' => 'perusahaan-scouting-edit',
            'update' => 'perusahaan-scouting-update',
            'destroy' => 'perusahaan-scouting-destroy',
            'show' => 'perusahaan-scouting-show',
        ]);
        Route::get('/detail-talents/{user}/{scouting}', [PerusahaanScoutingController::class, 'siswa'])->name('perusahaan-scouting-siswa');
        Route::post('/scouting/seleksi/{talent}', [PerusahaanScoutingController::class, 'seleksi'])->name('perusahaan-scouting-seleksi');

        // Rute untuk Fitur Sertifikasi Kompetensi oleh Perusahaan
        Route::prefix('sertifikasi')->name('perusahaan-sertifikasi-')->group(function () {
            Route::get('/', [PerusahaanSertifikasiController::class, 'index'])->name('index');
            Route::get('/create', [PerusahaanSertifikasiController::class, 'create'])->name('create');
            Route::post('/', [PerusahaanSertifikasiController::class, 'store'])->name('store');
            Route::get('/{certificationExam}', [PerusahaanSertifikasiController::class, 'show'])->name('show');
            Route::get('/{certificationExam}/edit', [PerusahaanSertifikasiController::class, 'edit'])->name('edit');
            Route::put('/{certificationExam}', [PerusahaanSertifikasiController::class, 'update'])->name('update');
            Route::delete('/{certificationExam}', [PerusahaanSertifikasiController::class, 'destroy'])->name('destroy');

            // Rute untuk melihat hasil pendaftaran dan memberikan sertifikat
            Route::get('/results/inspect', [PerusahaanSertifikasiController::class, 'listResults'])->name('results');
            Route::get('/results/{registration}/give-certificate', [PerusahaanSertifikasiController::class, 'giveCertificateForm'])->name('results.give_certificate_form');
            Route::post('/results/{registration}/store-certificate', [PerusahaanSertifikasiController::class, 'storeCertificate'])->name('results.store_certificate');
        });

        Route::post('mooc/{mooc}/sertifikat/{user}/upload', [PerusahaanMoocController::class, 'uploadSertifikat'])->name('perusahaan-mooc-sertifikat-upload');

        Route::get('riset', [PerusahaanRisetController::class, 'index'])->name('perusahaan-riset-index');
        Route::get('riset/results', [PerusahaanRisetController::class, 'results'])->name('perusahaan-riset-results');
        Route::patch('riset/{riset}/terima', [PerusahaanRisetController::class, 'terima'])->name('perusahaan-riset-terima');
        Route::patch('riset/{riset}/tolak', [PerusahaanRisetController::class, 'tolak'])->name('perusahaan-riset-tolak');
    });

    // Siswa Routes
    Route::middleware(['role:siswa'])->prefix('siswa')->group(function () {
        Route::get('/', function () {
            return view('siswa.dashboard');
        })->name('siswa-dashboard');

        Route::get('/pkl', [SiswaPklController::class, 'index'])->name('siswa-pkl-index');
        Route::post('/pkl/{pkl}/register', [SiswaPklController::class, 'register'])->name('siswa-pkl-register');
        Route::get('/pkl/show', [SiswaPklController::class, 'show'])->name('siswa-pkl-show');
        Route::delete('/pkl/batal', [SiswaPklController::class, 'batal'])->name('siswa-pkl-batal');
        Route::post('/pkl/{pkl}/upload', [SiswaPklController::class, 'uploadLaporan'])->name('siswa-pkl-uploadLaporan');

        Route::resource('pkl/logbook', SiswaLogbookController::class)->names([
            'index' => 'siswa-logbook-index',
            'create' => 'siswa-logbook-create',
            'store' => 'siswa-logbook-store',
            'edit' => 'siswa-logbook-edit',
            'update' => 'siswa-logbook-update',
            'destroy' => 'siswa-logbook-destroy',
        ])->except('show');

        Route::get('beasiswa', [SiswaBeasiswaScoutingController::class, 'index'])->name('siswa-beasiswa-index');
        Route::get('beasiswa/daftar/{beasiswa}', [SiswaBeasiswaScoutingController::class, 'register'])->name('siswa-beasiswa-register');
        Route::post('beasiswa/daftar/{beasiswa}', [SiswaBeasiswaScoutingController::class, 'apply'])->name('siswa-beasiswa-apply');
        Route::get('beasiswa/status', [SiswaBeasiswaScoutingController::class, 'status'])->name('siswa-beasiswa-status');

        // START: Rute Baru untuk Sertifikasi Kompetensi oleh Siswa
        Route::prefix('sertifikasi')->name('siswa-sertifikasi-')->group(function () {
            Route::get('/', [SiswaSertifikasiController::class, 'index'])->name('index'); // Daftar sertifikasi & pendaftaran saya
            Route::get('/register/{certificationExam}', [SiswaSertifikasiController::class, 'registerForm'])->name('register'); // Form pendaftaran
            Route::post('/register/{certificationExam}', [SiswaSertifikasiController::class, 'storeRegistration'])->name('store_registration'); // Simpan pendaftaran
            Route::get('/status', [SiswaSertifikasiController::class, 'showStatus'])->name('status'); // Status pendaftaran saya
            Route::get('/{registration}/download-certificate', [SiswaSertifikasiController::class, 'downloadCertificate'])->name('download_certificate'); // Unduh sertifikat
        });
    });


    // Guru Routes
    Route::middleware(['role:guru'])->prefix('guru')->group(function () {
        Route::get('/', function () {
            return view('guru.dashboard');
        })->name('guru-dashboard');

        Route::prefix('project')->name('guru-project-')->group(function () {
            Route::get('/', [GuruProjectController::class, 'index'])->name('index');
            Route::post('/{project}/laporan', [GuruProjectController::class, 'uploadLaporan'])->name('laporan-upload');
            Route::put('/{project}/laporan', [GuruProjectController::class, 'updateLaporan'])->name('laporan-update');
            Route::delete('/{project}/laporan', [GuruProjectController::class, 'deleteLaporan'])->name('laporan-delete');
        });

        Route::get('/mooc', [GuruMoocController::class, 'index'])->name('guru-mooc-index');
        // PKL Routes
        Route::prefix('pkl')->name('guru-pkl-')->group(function () {
            Route::get('/', [GuruPklController::class, 'index'])->name('index');
            Route::get('/{pkl}', [GuruPklController::class, 'show'])->name('show');
            Route::get('/{pkl}/siswa', [GuruPklController::class, 'siswaList'])->name('siswa-list');
            Route::get('/siswa/{siswa}/logbook', [GuruPklController::class, 'siswaLogbook'])->name('siswa-logbook');
            Route::post('/logbook/{logbook}/validasi-semua', [GuruPklController::class, 'validateFullLogbook'])->name('validate-full-logbook');
            Route::post('/siswa/{siswa}/validasi-laporan', [GuruPklController::class, 'validateFinalReport'])->name('validate-report');
        });
        Route::get('/mooc/{mooc}', [GuruMoocController::class, 'show'])->name('guru-mooc-show');
        Route::get('/mooc/{mooc}/eval', [GuruMoocController::class, 'eval'])->name('guru-mooc-eval');
        Route::post('/mooc/{mooc}/nilai', [GuruMoocController::class, 'nilai'])->name('guru-mooc-nilai');
    });

    // Waka Kurikulum Routes
    Route::middleware(['role:waka_kurikulum'])->prefix('waka_kurikulum')->group(function () {
        Route::get('/', function () {
            return view('waka_kurikulum.dashboard');
        })->name('waka-kurikulum-dashboard');

        // Kurikulum Routes
        Route::get('/kurikulum', [WakaKurikulumController::class, 'index'])->name('waka-kurikulum-list-diajukan');
        Route::get('/kurikulum/validasi', [WakaKurikulumController::class, 'validasi'])->name('waka-kurikulum-list-validasi');
        Route::get('/kurikulum/create', [WakaKurikulumController::class, 'create'])->name('waka-kurikulum-create');
        Route::post('/kurikulum', [WakaKurikulumController::class, 'store'])->name('waka-kurikulum-store');
        Route::get('/kurikulum/{kurikulum}/edit', [WakaKurikulumController::class, 'edit'])->name('waka-kurikulum-edit');
        Route::put('/kurikulum/{kurikulum}', [WakaKurikulumController::class, 'update'])->name('waka-kurikulum-update');
        Route::delete('/kurikulum/{kurikulum}', [WakaKurikulumController::class, 'destroy'])->name('waka-kurikulum-destroy');
        Route::patch('/kurikulum/{kurikulum}/setuju', [WakaKurikulumController::class, 'setuju'])->name('waka-kurikulum-setuju');
        Route::patch('/kurikulum/{kurikulum}/tolak', [WakaKurikulumController::class, 'tolak'])->name('waka-kurikulum-tolak');

        // Beasiswa Rekomendasi
        Route::get('/beasiswa', [WakaKurikulumBeasiswaRekomendasiController::class, 'index'])->name('waka_kurikulum.beasiswas.index');
        Route::get('/beasiswa/{beasiswa}', [WakaKurikulumBeasiswaRekomendasiController::class, 'show'])->name('waka_kurikulum.beasiswas.show');
        Route::put('/beasiswa/{beasiswa}', [WakaKurikulumBeasiswaRekomendasiController::class, 'rekomendasi'])->name('waka_kurikulum.beasiswas.rekomendasi');
        Route::get('/beasiswa-hasil', [WakaKurikulumBeasiswaRekomendasiController::class, 'hasil'])->name('waka_kurikulum.beasiswas.hasil');
        Route::get('/beasiswa-aktif', [WakaKurikulumBeasiswaRekomendasiController::class, 'batchAktifViewOnly'])->name('waka_kurikulum.beasiswas.batches.list');
        Route::get('/beasiswa-hasil/{batch}', [WakaKurikulumBeasiswaRekomendasiController::class, 'hasilDetail'])->name('waka_kurikulum.beasiswas.hasil.detail');
    });

    // Waka Humas Routes
    Route::middleware(['role:waka_humas'])->prefix('waka_humas')->group(function () {
        Route::get('/', function () {
            return view('waka_humas.dashboard');
        })->name('waka-humas-dashboard');

        Route::resource('guru-tamu', WakaHumasGuruTamuController::class)->names([
            'index' => 'waka-humas-guru-tamu-index',
            'create' => 'waka-humas-guru-tamu-create',
            'store' => 'waka-humas-guru-tamu-store',
            'show' => 'waka-humas-guru-tamu-show',
            'edit' => 'waka-humas-guru-tamu-edit',
            'update' => 'waka-humas-guru-tamu-update',
            'destroy' => 'waka-humas-guru-tamu-destroy',
        ]);
        Route::put('guru-tamu/{guru_tamu}/approve', [WakaHumasGuruTamuController::class, 'approve'])->where('guru_tamu', '[0-9]+')->name('waka-humas-guru-tamu-approve');
        Route::put('guru-tamu/{guru_tamu}/reject', [WakaHumasGuruTamuController::class, 'reject'])->where('guru_tamu', '[0-9]+')->name('waka-humas-guru-tamu-reject');

        Route::resource('riset', WakaHumasRisetController::class)->names([
            'index' => 'waka-humas-riset-index',
            'create' => 'waka-humas-riset-create',
            'store' => 'waka-humas-riset-store',
            'show' => 'waka-humas-riset-show',
            'edit' => 'waka-humas-riset-edit',
            'update' => 'waka-humas-riset-update',
            'destroy' => 'waka-humas-riset-destroy',
        ]);        // PKL Logbook Routes - These need to come first to prevent conflict with {pkl} parameter routes

        Route::post('riset/{riset}/upload', [WakaHumasRisetController::class, 'dokumentasi'])->name('waka-humas-riset-dokumentasi');
        Route::get('pkl/logbook/validasi', [WakaHumasPklController::class, 'logbookValidationIndex'])->name('waka-humas-pkl-logbook-validation-index');
        Route::get('pkl/siswa/{siswa}/logbook', [WakaHumasPklController::class, 'siswaLogbook'])->name('waka-humas-pkl-siswa-logbook');

        // PKL Pembimbing Assignment Routes - These also need to come before the resource routes
        Route::prefix('pkl/assign')->name('waka-humas-pkl-assign-')->group(function () {
            Route::get('/', [WakaHumasPklController::class, 'assignIndex'])->name('index');
            Route::get('/{pkl}/form', [WakaHumasPklController::class, 'assignForm'])->name('form');
            Route::get('/{pkl}', [WakaHumasPklController::class, 'assignShow'])->name('show');
            Route::post('/{pkl}', [WakaHumasPklController::class, 'assignStore'])->name('store');
            Route::delete('/{pkl}', [WakaHumasPklController::class, 'assignRemove'])->name('remove');
        });

        // General PKL Routes
        Route::resource('pkl', WakaHumasPklController::class)->only(['index', 'show'])->names([
            'index' => 'waka-humas-pkl-index',
            'show' => 'waka-humas-pkl-show',
        ]);
        Route::post('pkl/{pkl}/validate', [WakaHumasPklController::class, 'validateReport'])->name('waka-humas-pkl-validate');
        Route::get('pkl/{pkl}/download', [WakaHumasPklController::class, 'downloadReport'])->name('waka-humas-pkl-download');
    });

    // LSP Routes
    Route::middleware(['role:lsp'])->prefix('lsp')->group(function () {
        Route::get('/', function () {
            return view('lsp.dashboard');
        })->name('lsp-dashboard');

        // START: Rute Baru untuk Sertifikasi Kompetensi oleh LSP
        Route::prefix('sertifikasi')->name('lsp-sertifikasi-')->group(function () {
            Route::get('/', [LspSertifikasiController::class, 'index'])->name('index');
            Route::get('/create', [LspSertifikasiController::class, 'create'])->name('create');
            Route::post('/', [LspSertifikasiController::class, 'store'])->name('store');
            Route::get('/{certificationExam}', [LspSertifikasiController::class, 'show'])->name('show');
            Route::get('/{certificationExam}/edit', [LspSertifikasiController::class, 'edit'])->name('edit');
            Route::put('/{certificationExam}', [LspSertifikasiController::class, 'update'])->name('update');
            Route::delete('/{certificationExam}', [LspSertifikasiController::class, 'destroy'])->name('destroy');

            // Rute untuk melihat hasil pendaftaran dan memberikan sertifikat
            Route::get('/results/inspect', [LspSertifikasiController::class, 'listResults'])->name('results');
            Route::get('/results/{registration}/give-certificate', [LspSertifikasiController::class, 'giveCertificateForm'])->name('results.give_certificate_form');
            Route::post('/results/{registration}/store-certificate', [LspSertifikasiController::class, 'storeCertificate'])->name('results.store_certificate');
        });
    });
    // Alumni Routes
    Route::middleware(['role:alumni'])->prefix('alumni')->group(function () {
        Route::get('/', function () {
            return view('alumni.dashboard');
        })->name('alumni-dashboard');

        Route::get('scouting/', [AlumniScoutingController::class, 'index'])->name('alumni-scouting-index');
        Route::get('scouting/{scouting}', [AlumniScoutingController::class, 'registration'])->name('alumni-scouting-register');
        Route::post('scouting/{scouting}/apply', [AlumniScoutingController::class, 'apply'])->name('alumni-scouting-apply');
        Route::get('scouting/status/riwayat', [AlumniScoutingController::class, 'status'])->name('alumni-scouting-status');
    });

    // LSP Routes
    Route::middleware(['role:lsp'])->prefix('lsp')->group(function () {
        Route::get('/', function () {
            return view('lsp.dashboard');
        })->name('lsp-dashboard');
    });
}); // End of main 'auth' middleware group

require __DIR__ . '/auth.php';
