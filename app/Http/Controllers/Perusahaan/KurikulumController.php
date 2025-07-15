<?php

namespace App\Http\Controllers\Perusahaan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Kurikulum;

class KurikulumController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'tahun' => 'required',
            'deskripsi' => 'required',
            'file' => 'required | mimes:pdf',
        ]);

        $path = $request->file('file')->store('kurikulum/', 'public');

        Kurikulum::create([
            'nama_kurikulum' => $request->nama,
            'pengirim_id' => auth()->user()->id,
            // 'perusahaan_id' akan null jika pengirim adalah perusahaan, atau ID perusahaan jika dikirim ke perusahaan spesifik
            // Untuk kurikulum yang diajukan sendiri, tidak perlu set 'perusahaan_id' di sini, biarkan null atau set sesuai tujuan
            'tahun_ajaran' => $request->tahun,
            'deskripsi' => $request->deskripsi,
            'file_kurikulum' => $path,
            'validasi_sekolah' => 'proses', // Menunggu validasi dari sekolah
            'validasi_perusahaan' => 'disetujui' // Perusahaan sendiri yang mengajukan dan menyetujui versi mereka
        ]);

        return redirect()->route('perusahaan-kurikulum-list-diajukan')->with('success', 'Kurikulum berhasil diajukan!');
    }

    public function create()
    {
        return view('perusahaan.kurikulum.create');
    }

    public function index()
    {
        return view('perusahaan.kurikulum.list-diajukan', [
            // Hanya menampilkan kurikulum yang pengirimnya adalah perusahaan yang login
            'kurikulums' => Kurikulum::where('pengirim_id', auth()->user()->id)->get()
        ]);
    }

    public function validasi()
    {
        return view('perusahaan.kurikulum.list-validasi', [
            'kurikulums' => Kurikulum::where(function($query) {
                // Kurikulum yang pengirimnya adalah admin atau waka_kurikulum
                $query->whereHas('pengirim', function($q) {
                    $q->whereHas('roles', function($innerQ) {
                        $innerQ->whereIn('name', ['admin', 'waka_kurikulum']);
                    });
                })->where(function($q) {
                    // Hanya menampilkan kurikulum yang ditujukan ke perusahaan ini (perusahaan_id)
                    // atau yang tidak ditujukan secara spesifik (null) - ini untuk kurikulum publik/umum
                    $q->where('perusahaan_id', auth()->user()->id)
                      ->orWhereNull('perusahaan_id');
                });
            })->orderBy('validasi_perusahaan', 'asc') // Tampilkan status 'proses' paling atas
              ->orderBy('created_at', 'desc')
              ->get()
        ]);
    }

    public function destroy(Kurikulum $kurikulum)
    {
        // Hanya dapat menghapus kurikulum sendiri
        if ($kurikulum->pengirim_id !== auth()->id()) {
            return redirect()->route('perusahaan-kurikulum-list-diajukan')
                ->with('error', 'Anda tidak diizinkan menghapus kurikulum ini');
        }

        // Hapus file dari storage sebelum menghapus record
        if ($kurikulum->file_kurikulum && Storage::disk('public')->exists($kurikulum->file_kurikulum)) {
            Storage::disk('public')->delete($kurikulum->file_kurikulum);
        }

        $kurikulum->delete();
        return redirect()->route('perusahaan-kurikulum-list-diajukan')
            ->with('success', 'Kurikulum berhasil dihapus.');
    }

    public function edit(Kurikulum $kurikulum)
    {
        // Hanya dapat mengedit kurikulum sendiri
        if ($kurikulum->pengirim_id !== auth()->id()) {
            return redirect()->route('perusahaan-kurikulum-list-diajukan')
                ->with('error', 'Anda tidak diizinkan mengedit kurikulum ini');
        }

        return view('perusahaan.kurikulum.edit', [
            'kurikulum' => $kurikulum
        ]);
    }

    public function update(Request $request, Kurikulum $kurikulum)
    {
        // Hanya dapat mengupdate kurikulum sendiri
        if ($kurikulum->pengirim_id !== auth()->id()) {
            return redirect()->route('perusahaan-kurikulum-list-diajukan')
                ->with('error', 'Anda tidak diizinkan mengupdate kurikulum ini');
        }

        $request->validate([
            'nama' => 'required|string',
            'tahun' => 'required|string',
            'deskripsi' => 'required|string',
            'file' => 'nullable|file|mimes:pdf',
        ]);

        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($kurikulum->file_kurikulum && Storage::disk('public')->exists($kurikulum->file_kurikulum)) {
                Storage::disk('public')->delete($kurikulum->file_kurikulum);
            }
            $filename = uniqid() . '_' . $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->storeAs('kurikulum', $filename, 'public');
            $kurikulum->file_kurikulum = $path;
        }

        $kurikulum->update([
            'nama_kurikulum' => $request->nama,
            'tahun_ajaran' => $request->tahun,
            'deskripsi' => $request->deskripsi,
            'validasi_sekolah' => 'proses', // Kembali ke 'proses' jika diupdate
            'validasi_perusahaan' => 'disetujui', // Status validasi perusahaan tetap 'disetujui' karena ini diajukan perusahaan sendiri
            'komentar' => null, // Reset komentar jika diupdate
        ]);

        return redirect()->route('perusahaan-kurikulum-list-diajukan')
            ->with('success', 'Kurikulum berhasil diperbarui. Kurikulum akan kembali ke status Menunggu Validasi Sekolah.');
    }

    public function setuju(Kurikulum $kurikulum)
    {
        // Ini untuk validasi kurikulum yang diajukan oleh sekolah
        if (!$kurikulum->pengirim->hasAnyRole(['admin', 'waka_kurikulum'])) {
            return redirect()->route('perusahaan-kurikulum-list-validasi')
                ->with('error', 'Anda hanya dapat memvalidasi kurikulum dari admin atau waka kurikulum');
        }

        $kurikulum->update([
            'validasi_perusahaan' => 'disetujui'
        ]);
        return redirect()->route('perusahaan-kurikulum-list-validasi')
            ->with('success', 'Kurikulum berhasil disetujui');
    }

    public function tolak(Kurikulum $kurikulum, Request $request)
    {
        // Ini untuk validasi kurikulum yang diajukan oleh sekolah
        if (!$kurikulum->pengirim->hasAnyRole(['admin', 'waka_kurikulum'])) {
            return redirect()->route('perusahaan-kurikulum-list-validasi')
                ->with('error', 'Anda hanya dapat memvalidasi kurikulum dari admin sekolah atau waka kurikulum');
        }

        $request->validate([
            'komentar' => 'required|string',
        ]);

        $kurikulum->update([
            'validasi_perusahaan' => 'tidak_disetujui',
            'komentar' => $request->komentar
        ]);

        return redirect()->route('perusahaan-kurikulum-list-validasi')
            ->with('success', 'Kurikulum berhasil ditolak');
    }

    /**
     * Membatalkan persetujuan kurikulum oleh perusahaan (sebagai pengirim).
     * Ini hanya relevan jika perusahaan ingin mengubah kurikulum yang sudah mereka setujui (versi mereka).
     */
    public function cancelApproval(Kurikulum $kurikulum)
    {
        // Hanya dapat membatalkan persetujuan untuk kurikulum yang DIKIRIM sendiri
        if ($kurikulum->pengirim_id !== auth()->id()) {
            return redirect()->route('perusahaan-kurikulum-list-diajukan')
                ->with('error', 'Anda tidak diizinkan membatalkan persetujuan kurikulum ini');
        }

        // Jika kurikulum ini adalah yang diajukan perusahaan dan sudah disetujui oleh sekolah
        // atau jika perusahaan ingin mereset pengajuan mereka sendiri
        if ($kurikulum->validasi_perusahaan == 'disetujui') { // Hanya bisa dibatalkan jika status validasi perusahaannya 'disetujui' (diajukan)
            $kurikulum->update([
                'validasi_perusahaan' => 'proses', // Reset kembali menjadi 'proses'
                'validasi_sekolah' => 'proses', // Otomatis reset validasi sekolah juga
                'komentar' => null, // Hapus komentar
            ]);
            return redirect()->route('perusahaan-kurikulum-list-diajukan')
                ->with('success', 'Persetujuan kurikulum (pengajuan Anda) berhasil dibatalkan. Status validasi direset.');
        }

        return redirect()->route('perusahaan-kurikulum-list-diajukan')
            ->with('info', 'Kurikulum tidak dalam status yang bisa dibatalkan persetujuannya.');
    }
}