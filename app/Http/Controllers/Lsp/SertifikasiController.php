<?php

namespace App\Http\Controllers\Lsp; // Namespace diubah ke Lsp

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
     * Menampilkan daftar nama sertifikasi/batch yang dibuat oleh LSP ini.
     */
    public function index()
    {
        $lspId = Auth::id(); // Menggunakan lspId
        $exams = CertificationExam::where('pembuat_user_id', $lspId)->get();
        return view('lsp.sertifikasi.index', compact('exams')); // Folder view diubah ke lsp.sertifikasi
    }

    /**
     * Menampilkan form untuk membuat nama sertifikasi/batch baru.
     */
    public function create()
    {
        return view('lsp.sertifikasi.create');
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
            'pembuat_user_id' => Auth::id(), // ID LSP yang membuat
        ]);

        return redirect()->route('lsp-sertifikasi-index')->with('success', 'Nama Sertifikasi berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail nama sertifikasi/batch dan pendaftarnya.
     */
    public function show(CertificationExam $certificationExam)
    {
        $registrations = $certificationExam->registrations()->with('siswa')->get();
        return view('lsp.sertifikasi.show', compact('certificationExam', 'registrations'));
    }

    /**
     * Menampilkan form untuk mengedit nama sertifikasi/batch.
     */
    public function edit(CertificationExam $certificationExam)
    {
        return view('lsp.sertifikasi.edit', compact('certificationExam'));
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

        return redirect()->route('lsp-sertifikasi-index')->with('success', 'Nama Sertifikasi berhasil diperbarui!');
    }

    /**
     * Menghapus nama sertifikasi/batch.
     */
    public function destroy(CertificationExam $certificationExam)
    {
        $certificationExam->delete();

        return redirect()->route('lsp-sertifikasi-index')->with('success', 'Nama Sertifikasi berhasil dihapus!');
    }

    // --- Manajemen Hasil Ujian & Penerbitan Sertifikat ---

    /**
     * Menampilkan daftar siswa yang mendaftar sertifikasi yang dibuat LSP ini.
     */
    public function listResults()
    {
        $lspId = Auth::id(); // Menggunakan lspId
        $registrations = Sertifikasi::whereHas('exam', function ($query) use ($lspId) {
            $query->where('pembuat_user_id', $lspId);
        })->with(['siswa', 'exam'])->get();

        return view('lsp.sertifikasi.results.index', compact('registrations'));
    }

    /**
     * Menampilkan form untuk menginput nilai dan mengunggah sertifikat untuk siswa.
     */
    public function giveCertificateForm(Sertifikasi $registration)
    {
        // Pastikan registrasi ini milik ujian yang dibuat oleh LSP yang login
        if ($registration->lsp_user_id !== Auth::id() && $registration->exam->pembuat_user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk tindakan ini.');
        }

        return view('lsp.sertifikasi.results.give_certificate', compact('registration'));
    }

    /**
     * Menyimpan nilai dan mengunggah sertifikat.
     */
    public function storeCertificate(Request $request, Sertifikasi $registration)
    {
        // Pastikan registrasi ini milik ujian yang dibuat oleh LSP yang login
        if ($registration->lsp_user_id !== Auth::id() && $registration->exam->pembuat_user_id !== Auth::id()) {
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

        return redirect()->route('lsp-sertifikasi-results')->with('success', 'Nilai dan sertifikat berhasil diperbarui.');
    }
}