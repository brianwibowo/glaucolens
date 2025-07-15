<?php

namespace App\Http\Controllers\Perusahaan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Mooc;
use App\Models\MoocScore;
use App\Models\User;
// MOOC_Eval tidak lagi digunakan di sini

class MoocController extends Controller
{
    public function index()
    {
        return view('perusahaan.mooc.index', [
            'moocs' => auth()->user()->moocs()->get()
        ]);
    }

    public function create()
    {
        return view('perusahaan.mooc.create');
    }

    public function show(Mooc $mooc)
    {
        $userIds = $mooc->reflections()->where('mooc_id', $mooc->id)->get()
            ->pluck('user_id')
            ->toArray();

        return view('perusahaan.mooc.show', [
            'mooc' => $mooc,
            'modules' => $mooc->modules()->get(),
            'reflections' => $mooc->reflections()->get(),
            'participants' => $mooc->nilai()->whereIn('user_id', $userIds)->get()
        ]);
    }


    public function edit(Mooc $mooc)
    {
        return view('perusahaan.mooc.edit', [
            'mooc' => $mooc
        ]);
    }
    public function update(Request $request, Mooc $mooc)
    {
        $request->validate([
            'judul_pelatihan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        $mooc->judul_pelatihan = $request->judul_pelatihan;
        $mooc->deskripsi = $request->deskripsi;
        $mooc->save();

        return redirect()->route('perusahaan-mooc-index')->with('success', 'Pelatihan MOOC berhasil diperbarui!');
    }

    public function destroy(Mooc $mooc)
    {
        $mooc->delete(); // Ini akan menghapus modul, refleksi, dan nilai terkait (onDelete('cascade'))
        return redirect()->route('perusahaan-mooc-index')->with('success', 'Data berhasil dihapus');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_pelatihan' => 'required',
            'deskripsi' => 'required',
        ]);

        Mooc::create([
            'judul_pelatihan' => $request->judul_pelatihan,
            'deskripsi' => $request->deskripsi,
            'perusahaan_id' => auth()->user()->id
        ]);

        return redirect()->route('perusahaan-mooc-index')->with('success', 'Data berhasil disimpan');
    }

    public function uploadSertifikat(Request $request, Mooc $mooc, User $user){
        $request->validate([
            'sertifikat_file' => 'required|file|mimes:pdf', // Pastikan hanya PDF
        ]);

        $moocScore = MoocScore::where('mooc_id', $mooc->id)->where('user_id', $user->id)->first();

        if($moocScore){
            // Hapus sertifikat lama jika ada
            if ($moocScore->file_sertifikat && Storage::disk('public')->exists($moocScore->file_sertifikat)) {
                Storage::disk('public')->delete($moocScore->file_sertifikat);
            }
            $moocScore->update([
                'file_sertifikat' => $request->file('sertifikat_file')->store('mooc_sertifikat_guru', 'public'), // Folder baru untuk sertifikat guru
            ]);
        }
        else{
            MoocScore::create([
                'mooc_id' => $mooc->id,
                'user_id' => $user->id,
                'file_sertifikat' => $request->file('sertifikat_file')->store('mooc_sertifikat_guru', 'public'), // Folder baru
            ]);
        }

        return redirect()->route('perusahaan-mooc-show', ['mooc' => $mooc->id])->with('success', 'Sertifikat berhasil diunggah!');
    }
}