<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CertificationExam;
use App\Models\Sertifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SertifikasiController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $availableExams = CertificationExam::all();

        $myRegistrations = Sertifikasi::where('user_id', $userId)
                                    ->with(['exam', 'lsp', 'perusahaan'])
                                    ->get();

        return view('siswa.sertifikasi.index', compact('availableExams', 'myRegistrations'));
    }

    public function registerForm(CertificationExam $certificationExam)
    {
        $userId = Auth::id();

        $existingRegistration = Sertifikasi::where('user_id', $userId)
                                           ->where('certification_exam_id', $certificationExam->id)
                                           ->first();

        if ($existingRegistration) {
            return redirect()->route('siswa-sertifikasi-status')->with('info', 'Anda sudah terdaftar untuk sertifikasi ini. Silakan cek status pendaftaran Anda.');
        }

        return view('siswa.sertifikasi.register', compact('certificationExam'));
    }

    public function storeRegistration(Request $request, CertificationExam $certificationExam)
    {
        $userId = Auth::id();

        $existingRegistration = Sertifikasi::where('user_id', $userId)
                                           ->where('certification_exam_id', $certificationExam->id)
                                           ->first();

        if ($existingRegistration) {
            return redirect()->route('siswa-sertifikasi-status')->with('error', 'Anda sudah terdaftar untuk sertifikasi ini.');
        }

        $request->validate([
            'dokumen_persyaratan' => 'required|file|mimes:pdf|max:5120',
            'kompetensi' => 'required|string|max:255',
        ]);

        $dokumenPath = $request->file('dokumen_persyaratan')->store('sertifikasi_dokumen_persyaratan', 'public');

        Sertifikasi::create([
            'user_id' => $userId,
            'certification_exam_id' => $certificationExam->id,
            'dokumen_persyaratan' => $dokumenPath,
            'kompetensi' => $request->kompetensi,
            'status_pendaftaran_ujian' => 'terdaftar',
            'lsp_user_id' => null,
            'perusahaan_user_id' => $certificationExam->pembuat_user_id,
        ]);

        return redirect()->route('siswa-sertifikasi-status')->with('success', 'Pendaftaran sertifikasi berhasil. Mohon tunggu informasi lebih lanjut.');
    }

    public function showStatus()
    {
        $userId = Auth::id();
        $myRegistrations = Sertifikasi::where('user_id', $userId)
                                    ->with(['exam', 'lsp', 'perusahaan'])
                                    ->get();

        return view('siswa.sertifikasi.status', compact('myRegistrations'));
    }

    public function downloadCertificate(Sertifikasi $registration)
    {
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh sertifikat ini.');
        }

        if (!$registration->sertifikat_kelulusan) {
            return back()->with('error', 'Sertifikat belum tersedia atau belum diunggah.');
        }

        if (!Storage::disk('public')->exists($registration->sertifikat_kelulusan)) {
            return back()->with('error', 'File sertifikat tidak ditemukan.');
        }

        return Storage::disk('public')->download($registration->sertifikat_kelulusan);
    }
}