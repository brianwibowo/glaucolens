<?php

namespace App\Http\Controllers\Admin; // Namespace diubah ke Admin

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;

use App\Models\ScoutingBatch;
use App\Models\Talent_Scouting; // Pastikan nama model ini benar (Talent_Scouting atau TalentScouting)
use App\Models\User;
use Illuminate\Support\Facades\Auth; // Tambahkan ini jika menggunakan Auth::id()

class ScoutingController extends Controller
{
    public function index()
    {
        // Admin akan melihat semua batch, bukan hanya yang dibuat oleh user() mereka sendiri
        // Jika Admin hanya melihat batch yang dia buat:
        // $batches = ScoutingBatch::where('perusahaan_id', Auth::id())->get(); // Perusahaan_id juga perlu dipertimbangkan untuk Admin

        // Untuk kesederhanaan dan akses Admin, mari kita asumsikan Admin bisa melihat semua batch.
        // Jika Admin juga bisa membuat, maka 'perusahaan_id' di ScoutingBatch perlu diubah/diasumsikan bisa juga 'admin_id'.
        // Berdasarkan schema, ScoutingBatch.perusahaan_id adalah FK ke users.id (perusahaan).
        // Jadi, Admin tidak akan punya "batch()" relasi di model User seperti perusahaan.
        // Solusi: Admin melihat semua batch yang ada di sistem.
        $batches = ScoutingBatch::all(); // Admin bisa melihat semua batch yang dibuat oleh siapa pun

        return view('admin.scoutings.index', compact('batches')); // Folder view diubah ke admin.scoutings
    }

    public function create()
    {
        return view('admin.scoutings.create');
    }

    public function show(ScoutingBatch $scouting)
    {
        return view('admin.scoutings.show', [
            'scouting' => $scouting,
            'talents' => Talent_Scouting::where('batch_id', $scouting->id)->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'tanggal_mulai' => 'required|date', // Tambahkan validasi date
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai' // Tambahkan validasi date
        ]);

        ScoutingBatch::create([
            'batch' => $request->nama,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            // Perusahaan_id adalah FK di ScoutingBatch.
            // Jika admin membuat, maka ini perlu disimpan sebagai ID admin.
            // Asumsi: Admin juga bisa berperan sebagai 'perusahaan' dalam konteks ini,
            // atau tambahkan kolom 'admin_id' di ScoutingBatch jika perlu membedakan.
            // Untuk saat ini, kita akan simpan ID admin di kolom perusahaan_id.
            'perusahaan_id' => Auth::id(), // ID Admin yang membuat batch
            'status' => 'open'
        ]);

        return redirect()->route('admin-scouting-index')->with('success', 'Batch berhasil ditambahkan');
    }

    public function edit(ScoutingBatch $scouting)
    {
        return view('admin.scoutings.edit', [
            'scouting' => $scouting
        ]);
    }

    public function update(Request $request, ScoutingBatch $scouting)
    {
        $request->validate([
            'nama' => 'required',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai' // Tambahkan validasi date
        ]);

        $scouting->update([
            'batch' => $request->nama,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return redirect()->route('admin-scouting-index')->with('success', 'Batch berhasil diperbarui');
    }

    public function destroy(ScoutingBatch $scouting)
    {
        $scouting->delete();
        return redirect()->route('admin-scouting-index')->with('success', 'Batch berhasil dihapus');
    }

    public function siswa(User $user, ScoutingBatch $scouting)
    {
        return view('admin.scoutings.siswa', [
            'pelamar' => Talent_Scouting::where('user_id', $user->id)->where('batch_id', $scouting->id)->first()
        ]);
    }

    public function seleksi(Request $request, Talent_Scouting $talent)
    {
        $request->validate([
            'status' => 'required',
            'batch' => 'required'
        ]);

        $talent->update([
            'status_seleksi' => $request->status
        ]);

        return redirect()->route('admin-scouting-siswa', ['user' => $talent->user_id, 'scouting' => $request->batch])->with('success', 'Status pelamar berhasil diperbarui');
    }
}