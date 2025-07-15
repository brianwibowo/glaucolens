<?php

namespace App\Http\Controllers\Perusahaan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;

use App\Models\GuruTamu;

class GuruTamuController extends Controller
{    public function index()
    {
        $userId = auth()->id();
        return view('perusahaan.guru_tamu.index', [
            'gurutamus' => GuruTamu::where('submitted_by', $userId)->get()
        ]);
    }

    public function create()
    {
        return view('perusahaan.guru_tamu.create');
    }    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jabatan' => 'required',
            'keahlian' => 'required',
            'deskripsi' => 'required',
            'jadwal' => 'required',
            'file_materi' => 'required | file | mimes:pdf',
            'file_cv' => 'nullable | file | mimes:pdf'
        ]);

        $path = $request->file('file_materi')->store('guru_tamu/materi/', 'public');

        $gurutamu = GuruTamu::create([
            'nama_karyawan' => $request->nama,
            'jabatan' => $request->jabatan,
            'keahlian' => $request->keahlian,
            'deskripsi' => $request->deskripsi,
            'jadwal' => $request->jadwal,
            'file_materi' => $path,
            'status' => 'proses',
            'submitted_by' => auth()->id()
        ]);

        if ($request->hasFile('file_cv')) {
            $path = $request->file('file_cv')->store('guru_tamu/cv/', 'public');
            $gurutamu->file_cv = $path;
            $gurutamu->save();
        }

        return redirect()->route('perusahaan-guru-tamu-index');
    }    public function list()
    {
        $userId = auth()->id();
        return view('perusahaan.guru_tamu.list', [
            'gurutamus' => GuruTamu::where('status', 'disetujui')
                               ->where('submitted_by', $userId)
                               ->get()
        ]);
    }    public function edit(GuruTamu $guruTamu)
    {
        // Cek apakah guru tamu ini milik perusahaan yang login
        if ($guruTamu->submitted_by !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengakses pengajuan guru tamu ini');
        }        // Cek apakah status sudah disetujui
        if ($guruTamu->status === 'disetujui') {
            return redirect()->route('perusahaan-guru-tamu-index')
                ->with('error', 'Pengajuan guru tamu yang sudah disetujui tidak dapat diedit lagi.');
        }
        
        return view('perusahaan.guru_tamu.edit', [
            'guruTamu' => $guruTamu
        ]);
    }    public function update(Request $request, GuruTamu $guruTamu)
    {
        if ($guruTamu->submitted_by !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengubah pengajuan guru tamu ini');
        }        // Cek apakah status sudah disetujui
        if ($guruTamu->status === 'disetujui') {
            return redirect()->route('perusahaan-guru-tamu-index')
                ->with('error', 'Pengajuan guru tamu yang sudah disetujui tidak dapat diubah lagi.');
        }
        
        $request->validate([
            'nama' => 'required',
            'jabatan' => 'required',
            'keahlian' => 'required',
            'deskripsi' => 'required',
            'jadwal' => 'required',
            'file_materi' => 'nullable | file | mimes:pdf',
            'file_cv' => 'nullable | file | mimes:pdf'
        ]);

        if ($request->hasFile('file_materi')) {
            if ($guruTamu->file_materi) {
                Storage::disk('public')->delete($guruTamu->file_materi);
            }
            $path = $request->file('file_materi')->store('guru_tamu/materi/', 'public');
            $guruTamu->file_materi = $path;
            $guruTamu->save();
        }

        if ($request->hasFile('file_cv')) {
            if ($guruTamu->file_cv) {
                Storage::disk('public')->delete($guruTamu->file_cv);
            }
            $path = $request->file('file_cv')->store('guru_tamu/cv/', 'public');
            $guruTamu->file_cv = $path;
            $guruTamu->save();
        }

        $guruTamu->update([
            'nama_karyawan' => $request->nama,
            'jabatan' => $request->jabatan,
            'keahlian' => $request->keahlian,
            'deskripsi' => $request->deskripsi,
            'jadwal' => $request->jadwal,
        ]);
        return redirect()->route('perusahaan-guru-tamu-index');
    }    public function destroy(GuruTamu $guruTamu)
    {
        if ($guruTamu->submitted_by !== auth()->id()) {
            abort(403, 'Anda tidak berhak menghapus pengajuan guru tamu ini');
        }        // Cek apakah status sudah disetujui
        if ($guruTamu->status === 'disetujui') {
            return redirect()->route('perusahaan-guru-tamu-index')
                ->with('error', 'Pengajuan guru tamu yang sudah disetujui tidak dapat dihapus lagi.');
        }
        
        $guruTamu->delete();
        return redirect()->route('perusahaan-guru-tamu-index')
            ->with('success', 'Pengajuan guru tamu berhasil dihapus.');
    }
}
