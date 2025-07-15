<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Beasiswa;
use App\Models\BeasiswaBatch;

class BeasiswaScoutingController extends Controller
{
    // Menampilkan daftar peluang beasiswa
    public function index()
    {
        $userId = Auth::id();

        $batches = BeasiswaBatch::withCount(['beasiswas' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])
        ->where('status', 'open')
        ->orderByDesc('created_at')
        ->get();

        $appliedBatchIds = Beasiswa::where('user_id', $userId)
            ->pluck('batch_id')
            ->toArray();

        return view('siswa.beasiswas.index', compact('batches', 'appliedBatchIds'));
    }

    // Menampilkan form pendaftaran
    public function register(BeasiswaBatch $beasiswa)
    {
        return view('siswa.beasiswas.register', [
            'beasiswaBatch' => $beasiswa
        ]);
    }

    // Menyimpan lamaran beasiswa
    public function apply(BeasiswaBatch $beasiswa, Request $request)
    {
        $request->validate([
            'raport' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'surat_rekomendasi' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'surat_motivasi' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'portofolio' => 'required|file|mimes:pdf,doc,docx|max:2048'
        ]);

        
        $userId = Auth::id();
        
        // Cegah pendaftaran ganda
        $alreadyApplied = Beasiswa::where('user_id', $userId)
        ->where('batch_id', $beasiswa->id)
        ->exists();
        
        if ($alreadyApplied) {
            return back()->with('error', 'Anda sudah mendaftar pada batch ini.');
        }
        
        try {
            Beasiswa::create([
                'batch_id'           => $beasiswa->id,
                'user_id'            => $userId,
                'nama_siswa'         => Auth::user()->name,
                'raport'             => $request->file('raport')->store('beasiswa/raport', 'public'),
                'surat_rekomendasi'  => $request->file('surat_rekomendasi')->store('beasiswa/rekomendasi', 'public'),
                'surat_motivasi'     => $request->file('surat_motivasi')->store('beasiswa/motivasi', 'public'),
                'portofolio'         => $request->file('portofolio')->store('beasiswa/portofolio', 'public'),
                'status'             => 'proses',
            ]);
            
            return redirect()->route('siswa-beasiswa-index')->with('success', 'Pendaftaran berhasil dikirim!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengupload data: ' . $e->getMessage());
        }
    }

    // Melihat status semua lamaran
    public function status()
    {
        $riwayat = Beasiswa::with('batch.perusahaan')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('siswa.beasiswas.status', compact('riwayat'));
    }
}
