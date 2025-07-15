<?php

namespace App\Http\Controllers\Admin; // Namespace diubah ke Admin

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CertificationExam;
use App\Models\Sertifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SertifikasiController extends Controller
{
    /**
     * Menampilkan daftar nama sertifikasi/batch yang dibuat oleh Admin ini.
     * Admin akan mengelola sertifikasi yang mereka buat sendiri.
     */
    public function index()
    {
        $adminId = Auth::id(); // Menggunakan adminId
        $exams = CertificationExam::where('pembuat_user_id', $adminId)->get();
        return view('admin.sertifikasi.index', compact('exams')); // Folder view diubah ke admin.sertifikasi
    }

    /**
     * Menampilkan form untuk membuat nama sertifikasi/batch baru.
     */
    public function create()
    {
        return view('admin.sertifikasi.create');
    }

    /**
     * Menyimpan nama sertifikasi/batch baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_ujian' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kompetensi_terkait' => 'nullable|string|max:255',
        ]);

        CertificationExam::create([
            'nama_ujian' => $request->nama_ujian,
            'deskripsi' => $request->deskripsi,
            'kompetensi_terkait' => $request->kompetensi_terkait,
            'pembuat_user_id' => Auth::id(), // ID Admin yang membuat
        ]);

        return redirect()->route('admin-sertifikasi-index')->with('success', 'Nama Sertifikasi berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail nama sertifikasi/batch dan pendaftarnya.
     */
    public function show(CertificationExam $certificationExam)
    {
        $registrations = $certificationExam->registrations()->with('siswa')->get();
        return view('admin.sertifikasi.show', compact('certificationExam', 'registrations'));
    }

    /**
     * Menampilkan form untuk mengedit nama sertifikasi/batch.
     */
    public function edit(CertificationExam $certificationExam)
    {
        return view('admin.sertifikasi.edit', compact('certificationExam'));
    }

    /**
     * Memperbarui nama sertifikasi/batch.
     */
    public function update(Request $request, CertificationExam $certificationExam)
    {
        $request->validate([
            'nama_ujian' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kompetensi_terkait' => 'nullable|string|max:255',
        ]);

        $certificationExam->update([
            'nama_ujian' => $request->nama_ujian,
            'deskripsi' => $request->deskripsi,
            'kompetensi_terkait' => $request->kompetensi_terkait,
        ]);

        return redirect()->route('admin-sertifikasi-index')->with('success', 'Nama Sertifikasi berhasil diperbarui!');
    }

    /**
     * Menghapus nama sertifikasi/batch.
     */
    public function destroy(CertificationExam $certificationExam)
    {
        $certificationExam->delete();

        return redirect()->route('admin-sertifikasi-index')->with('success', 'Nama Sertifikasi berhasil dihapus!');
    }

    // --- Manajemen Hasil Ujian & Penerbitan Sertifikat ---

    /**
     * Menampilkan daftar siswa yang mendaftar sertifikasi yang dibuat Admin ini.
     */
    public function listResults()
    {
        $adminId = Auth::id(); // Menggunakan adminId
        $registrations = Sertifikasi::whereHas('exam', function ($query) use ($adminId) {
            $query->where('pembuat_user_id', $adminId);
        })->with(['siswa', 'exam'])->get();

        return view('admin.sertifikasi.results.index', compact('registrations'));
    }

    /**
     * Menampilkan form untuk menginput nilai dan mengunggah sertifikat untuk siswa.
     */
    public function giveCertificateForm(Sertifikasi $registration)
    {
        // Pastikan registrasi ini milik ujian yang dibuat oleh Admin yang login
        if ($registration->exam->pembuat_user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk tindakan ini.');
        }

        return view('admin.sertifikasi.results.give_certificate', compact('registration'));
    }

    /**
     * Menyimpan nilai dan mengunggah sertifikat.
     */
    public function storeCertificate(Request $request, Sertifikasi $registration)
    {
        // Pastikan registrasi ini milik ujian yang dibuat oleh Admin yang login
        if ($registration->exam->pembuat_user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk tindakan ini.');
        }

        $request->validate([
            'nilai' => 'required|integer|min:0|max:100',
            'sertifikat' => 'nullable|file|mimes:pdf|max:2048',
            'status' => ['required', Rule::in(['lulus', 'tidak_lulus'])],
        ]);

        $registration->nilai = $request->nilai;
        $registration->status_pendaftaran_ujian = $request->status;

        if ($request->hasFile('sertifikat')) {
            if ($registration->sertifikat_kelulusan) {
                Storage::disk('public')->delete($registration->sertifikat_kelulusan);
            }
            $path = $request->file('sertifikat')->store('sertifikat_kelulusan', 'public');
            $registration->sertifikat_kelulusan = $path;
        }

        $registration->save();

        return redirect()->route('admin-sertifikasi-results')->with('success', 'Nilai dan sertifikat berhasil diperbarui.');
    }
}