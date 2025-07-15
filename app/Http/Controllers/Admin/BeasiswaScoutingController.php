<?php

namespace App\Http\Controllers\Admin; // Namespace diubah ke Admin

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BeasiswaBatch;
use App\Models\Beasiswa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BeasiswaScoutingController extends Controller
{
    public function index()
    {
        // Admin akan melihat semua batch beasiswa di sistem
        $batches = BeasiswaBatch::latest()->get();
        return view('admin.beasiswas.index', compact('batches')); // Folder view diubah ke admin.beasiswas
    }

    public function create()
    {
        return view('admin.beasiswas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        BeasiswaBatch::create([
            'batch'           => $request->nama,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'perusahaan_id'   => Auth::id(), // ID Admin yang membuat batch (disimpan di kolom perusahaan_id)
            'status'          => 'open',
        ]);

        return redirect()->route('admin-beasiswa-index')->with('success', 'Batch berhasil ditambahkan');
    }

    public function show(BeasiswaBatch $beasiswa)
    {
        // Guard dihapus, Admin bisa melihat semua
        $pendaftar = Beasiswa::where('batch_id', $beasiswa->id)
            ->where('direkomendasikan', true) // hanya siswa yang direkomendasikan
            ->with('user')
            ->get();

        return view('admin.beasiswas.show', compact('beasiswa', 'pendaftar'));
    }

    public function edit(BeasiswaBatch $beasiswa)
    {
        // Guard dihapus, Admin bisa mengedit semua
        return view('admin.beasiswas.edit', compact('beasiswa'));
    }

    public function update(Request $request, BeasiswaBatch $beasiswa)
    {
        // Guard dihapus, Admin bisa mengupdate semua
        $request->validate([
            'nama' => 'required',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai'
        ]);

        $beasiswa->update([
            'batch' => $request->nama,
            'tanggal_selesai' => $request->tanggal_selesai
        ]);

        return redirect()->route('admin-beasiswa-index')->with('success', 'Batch berhasil diperbarui');
    }

    public function destroy(BeasiswaBatch $beasiswa)
    {
        // Guard dihapus, Admin bisa menghapus semua
        $beasiswa->delete();
        return redirect()->route('admin-beasiswa-index')->with('success', 'Batch berhasil dihapus');
    }

    public function siswa(BeasiswaBatch $beasiswa, User $user)
    {
        // Guard dihapus, Admin bisa melihat semua detail siswa
        $pendaftar = Beasiswa::where('user_id', $user->id)
            ->where('batch_id', $beasiswa->id)
            ->firstOrFail();

        return view('admin.beasiswas.siswa', compact('pendaftar', 'beasiswa'));
    }

    public function seleksi(Request $request, BeasiswaBatch $beasiswa, Beasiswa $pendaftar)
    {
        // Guard dihapus, Admin bisa menyeleksi semua
        $request->validate([
            'status' => 'required|in:lolos,tidak lolos,proses' // sesuai enum di DB
        ]);

        $pendaftar->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin-beasiswa-siswa', [
            'beasiswa' => $beasiswa->id,
            'user'     => $pendaftar->user_id
        ])->with('success', 'Status pelamar diperbarui');
    }
    // Metode guard() dihapus karena Admin memiliki akses penuh.
}