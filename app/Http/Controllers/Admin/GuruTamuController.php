<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\GuruTamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuruTamuController extends Controller
{
    public function index()
    {
        $guruTamus = GuruTamu::latest()->paginate(10);
        return view('admin.guru_tamu.index', compact('guruTamus'));
    }

    public function create()
    {
        // Dapatkan semua user dengan role perusahaan
        $perusahaans = User::role('perusahaan')->get();
        return view('admin.guru_tamu.create', compact('perusahaans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'perusahaan_id' => 'required|exists:users,id',
            'nama' => 'required',
            'jabatan' => 'required',
            'keahlian' => 'required',
            'deskripsi' => 'required',
            'jadwal' => 'required',
            'file_materi' => 'required|file|mimes:pdf',
            'file_cv' => 'nullable|file|mimes:pdf'
        ]);

        $pathMateri = $request->file('file_materi')->store('guru_tamu/materi/', 'public');

        $guruTamu = GuruTamu::create([
            'nama_karyawan' => $request->nama,
            'jabatan' => $request->jabatan,
            'keahlian' => $request->keahlian,
            'deskripsi' => $request->deskripsi,
            'jadwal' => $request->jadwal,
            'file_materi' => $pathMateri,
            'status' => 'proses',
            'submitted_by' => $request->perusahaan_id
        ]);

        if ($request->hasFile('file_cv')) {
            $pathCV = $request->file('file_cv')->store('guru_tamu/cv/', 'public');
            $guruTamu->file_cv = $pathCV;
            $guruTamu->save();
        }

        return redirect()->route('admin-guru-tamu-index')->with('success', 'Pengajuan guru tamu berhasil dibuat.');
    }

    public function show(GuruTamu $guruTamu)
    {
        return view('admin.guru_tamu.show', compact('guruTamu'));
    }

    public function edit(GuruTamu $guruTamu)
    {
        $perusahaans = User::role('perusahaan')->get();
        return view('admin.guru_tamu.edit', compact('guruTamu', 'perusahaans'));
    }

    public function update(Request $request, GuruTamu $guruTamu)
    {
        $request->validate([
            'perusahaan_id' => 'required|exists:users,id',
            'nama' => 'required',
            'jabatan' => 'required',
            'keahlian' => 'required',
            'deskripsi' => 'required',
            'jadwal' => 'required',
            'file_materi' => 'nullable|file|mimes:pdf',
            'file_cv' => 'nullable|file|mimes:pdf'
        ]);

        if ($request->hasFile('file_materi')) {
            // Hapus file lama jika ada
            if ($guruTamu->file_materi) {
                Storage::disk('public')->delete($guruTamu->file_materi);
            }
            $path = $request->file('file_materi')->store('guru_tamu/materi/', 'public');
            $guruTamu->file_materi = $path;
        }

        if ($request->hasFile('file_cv')) {
            // Hapus file lama jika ada
            if ($guruTamu->file_cv) {
                Storage::disk('public')->delete($guruTamu->file_cv);
            }
            $path = $request->file('file_cv')->store('guru_tamu/cv/', 'public');
            $guruTamu->file_cv = $path;
        }

        $guruTamu->update([
            'nama_karyawan' => $request->nama,
            'jabatan' => $request->jabatan,
            'keahlian' => $request->keahlian,
            'deskripsi' => $request->deskripsi,
            'jadwal' => $request->jadwal,
            'submitted_by' => $request->perusahaan_id
        ]);

        return redirect()->route('admin-guru-tamu-index')->with('success', 'Data guru tamu berhasil diperbarui.');
    }

    public function destroy(GuruTamu $guruTamu)
    {
        if ($guruTamu->file_materi) {
            Storage::disk('public')->delete($guruTamu->file_materi);
        }
        if ($guruTamu->file_cv) {
            Storage::disk('public')->delete($guruTamu->file_cv);
        }

        $guruTamu->delete();
        return redirect()->route('admin-guru-tamu-index')->with('success', 'Data guru tamu berhasil dihapus.');
    }

    public function approve(GuruTamu $guruTamu)
    {
        $guruTamu->update(['status' => 'disetujui']);
        return redirect()->route('admin-guru-tamu-index')->with('success', 'Pengajuan guru tamu berhasil disetujui.');
    }    public function reject(GuruTamu $guruTamu)
    {
        $guruTamu->update(['status' => 'proses']);
        return redirect()->route('admin-guru-tamu-index')->with('success', 'Pengajuan guru tamu telah dikembalikan ke status proses.');
    }
}
