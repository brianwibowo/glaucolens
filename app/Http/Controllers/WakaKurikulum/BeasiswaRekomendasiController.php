<?php

namespace App\Http\Controllers\WakaKurikulum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Beasiswa;
use App\Models\BeasiswaBatch;

class BeasiswaRekomendasiController extends Controller
{
    public function index()
    {
        $sekolahId = Auth::user()->sekolah_id;

        $pendaftar = Beasiswa::with(['user', 'batch.perusahaan'])
            ->whereHas('user', fn ($q) => $q->where('sekolah_id', $sekolahId))
            ->orderByDesc('created_at')
            ->get();

        return view('waka_kurikulum.beasiswas.index', compact('pendaftar'));
    }


    public function show(Beasiswa $beasiswa)
    {
        abort_if($beasiswa->user->sekolah_id !== Auth::user()->sekolah_id, 403);

        return view('waka_kurikulum.beasiswas.show', compact('beasiswa'));
    }

    public function rekomendasi(Request $request, Beasiswa $beasiswa)
    {
        abort_if($beasiswa->user->sekolah_id !== Auth::user()->sekolah_id, 403);

        $request->validate([
            'catatan' => 'nullable|string|max:1000',
            'direkomendasikan' => 'required|in:0,1',
        ]);

        $beasiswa->update([
            'catatan' => $request->catatan,
            'direkomendasikan' => (bool) $request->direkomendasikan,
            'tanggal_rekomendasi' => now(),
        ]);

        return redirect()
            ->route('waka_kurikulum.beasiswas.index')
            ->with('success', 'Rekomendasi berhasil disimpan.');
    }

    public function batchAktifViewOnly()
    {
        $batches = \App\Models\BeasiswaBatch::with('perusahaan')
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->orderByDesc('created_at')
            ->paginate(10); // <--- aktifkan pagination

        return view('waka_kurikulum.beasiswas.batches_list', compact('batches'));
    }


    public function hasil()
    {
        $sekolahId = Auth::user()->sekolah_id;

        $batches = BeasiswaBatch::with([
            'beasiswas' => function ($query) use ($sekolahId) {
                $query->whereHas('user', fn ($q) => $q->where('sekolah_id', $sekolahId));
            },
            'beasiswas.user',
            'perusahaan'
        ])
            ->whereHas('beasiswas', fn ($q) => $q->whereHas('user', fn ($q2) => $q2->where('sekolah_id', $sekolahId)))
            ->orderByDesc('created_at')
            ->get();

        return view('waka_kurikulum.beasiswas.hasil', compact('batches'));
    }
    public function hasilDetail(BeasiswaBatch $batch)
    {
        $sekolahId = Auth::user()->sekolah_id;

        $beasiswas = $batch->beasiswas()
            ->with('user')
            ->whereHas('user', fn ($q) => $q->where('sekolah_id', $sekolahId))
            ->get();

        return view('waka_kurikulum.beasiswas.hasil_detail', compact('batch', 'beasiswas'));
    }
}
