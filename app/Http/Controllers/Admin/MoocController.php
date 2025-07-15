<?php

namespace App\Http\Controllers\Admin; // Namespace diubah ke Admin

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Mooc;
use App\Models\MoocScore;
use App\Models\User;

class MoocController extends Controller
{
    public function index()
    {
        // Admin akan melihat semua batch MOOC di sistem, tidak hanya yang mereka buat
        $moocs = Mooc::all();
        return view('admin.mooc.index', compact('moocs')); // Folder view diubah ke admin.mooc
    }

    public function create()
    {
        return view('admin.mooc.create');
    }

    public function show(Mooc $mooc)
    {
        $userIds = $mooc->reflections()->where('mooc_id', $mooc->id)->get()
            ->pluck('user_id')
            ->toArray();

        return view('admin.mooc.show', [
            'mooc' => $mooc,
            'modules' => $mooc->modules()->get(),
            'reflections' => $mooc->reflections()->get(),
            'participants' => $mooc->nilai()->whereIn('user_id', $userIds)->get()
        ]);
    }

    public function edit(Mooc $mooc)
    {
        return view('admin.mooc.edit', [
            'mooc' => $mooc
        ]);
    }
    public function update(Request $request, Mooc $mooc)
    {
        $request->validate([
            'judul_pelatihan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ], [
            'judul_pelatihan.required' => 'Judul pelatihan wajib diisi.',
            'deskripsi.required' => 'Deskripsi pelatihan wajib diisi.',
        ]);

        $mooc->judul_pelatihan = $request->judul_pelatihan;
        $mooc->deskripsi = $request->deskripsi;
        $mooc->save();

        return redirect()->route('admin-mooc-index')->with('success', 'Pelatihan MOOC berhasil diperbarui!');
    }

    public function destroy(Mooc $mooc)
    {
        $mooc->delete();
        return redirect()->route('admin-mooc-index')->with('success', 'Data berhasil dihapus');
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
            'perusahaan_id' => auth()->user()->id // ID Admin yang membuat
        ]);

        return redirect()->route('admin-mooc-index')->with('success', 'Data berhasil disimpan');
    }

    public function uploadSertifikat(Request $request, Mooc $mooc, User $user){
        $request->validate([
            'sertifikat_file' => 'required|file|mimes:pdf',
        ]);

        $moocScore = MoocScore::where('mooc_id', $mooc->id)->where('user_id', $user->id)->first();

        if($moocScore){
            if ($moocScore->file_sertifikat && Storage::disk('public')->exists($moocScore->file_sertifikat)) {
                Storage::disk('public')->delete($moocScore->file_sertifikat);
            }
            $moocScore->update([
                'file_sertifikat' => $request->file('sertifikat_file')->store('mooc_sertifikat_guru', 'public'),
            ]);
        }
        else{
            MoocScore::create([
                'mooc_id' => $mooc->id,
                'user_id' => $user->id,
                'file_sertifikat' => $request->file('sertifikat_file')->store('mooc_sertifikat_guru', 'public'),
            ]);
        }

        return redirect()->route('admin-mooc-show', ['mooc' => $mooc->id])->with('success', 'Sertifikat berhasil diunggah!');
    }
}