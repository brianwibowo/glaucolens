<?php

namespace App\Http\Controllers\WakaKurikulum;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KurikulumController extends Controller
{
    /**
     * Display a listing of kurikulums submitted by the waka kurikulum
     */    public function index()
    {
        return view('waka_kurikulum.kurikulum.list-diajukan', [
            'kurikulums' => Kurikulum::where(function($query) {
                // Show curricula created by this waka kurikulum
                $query->where('pengirim_id', auth()->user()->id)
                // Or created by admin on behalf of school (using submission_type='school' logic)
                ->orWhere(function($q) {
                    $q->whereHas('pengirim', function($sq) {
                        $sq->role('admin');
                    })
                    ->whereNotNull('perusahaan_id') // Has a target company
                    ->where('validasi_sekolah', 'disetujui'); // School-side already approved
                });
            })->orderBy('created_at', 'desc')->get()
        ]);
    }
    
    /**
     * Display a listing of kurikulums submitted by perusahaan for review
     */    public function validasi()
    {
        // Get all kurikulums submitted by perusahaan users or admin on behalf of perusahaan,
        // regardless of validation status
        return view('waka_kurikulum.kurikulum.list-validasi', [
            'kurikulums' => Kurikulum::where(function($query) {
                // Kurikulums from perusahaan
                $query->whereHas('pengirim', function($q) {
                    $q->role('perusahaan');
                })
                // Or created by admin on behalf of perusahaan (company submission)
                ->orWhere(function($q) {
                    $q->whereHas('pengirim', function($sq) {
                        $sq->role('admin');
                    })->where('validasi_perusahaan', 'disetujui')
                      ->where('validasi_sekolah', 'proses');
                });
            })->orderBy('validasi_sekolah', 'asc') // Show 'proses' status first
              ->orderBy('created_at', 'desc')
              ->get()
        ]);
    }    /**
     * Show the form for creating a new kurikulum
     */
    public function create()
    {
        $perusahaanUsers = User::role('perusahaan')->get();
        return view('waka_kurikulum.kurikulum.create', [
            'perusahaanUsers' => $perusahaanUsers
        ]);
    }

    /**
     * Store a newly created kurikulum
     */    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'tahun' => 'required|string',
            'deskripsi' => 'required|string',
            'file' => 'required|mimes:pdf',
            'perusahaan_id' => 'required|exists:users,id',
        ]);

        $path = $request->file('file')->store('kurikulum/', 'public');

        // Create kurikulum that will be validated by perusahaan
        Kurikulum::create([
            'nama_kurikulum' => $request->nama,
            'pengirim_id' => auth()->user()->id,
            'perusahaan_id' => $request->perusahaan_id, // Store the target company ID
            'tahun_ajaran' => $request->tahun,
            'deskripsi' => $request->deskripsi,
            'file_kurikulum' => $path,
            'validasi_sekolah' => 'disetujui', // Waka Kurikulum already approves from school side
            'validasi_perusahaan' => 'proses'  // Needs perusahaan validation
        ]);

        return redirect()->route('waka-kurikulum-list-diajukan')
            ->with('success', 'Kurikulum berhasil diajukan untuk validasi perusahaan');
    }
    
    /**
     * Show the form for editing the specified kurikulum
     */    public function edit(Kurikulum $kurikulum)
    {
        // Make sure the user is authorized to edit this kurikulum
        if ($kurikulum->pengirim_id !== auth()->id()) {
            return redirect()->route('waka-kurikulum-list-diajukan')
                ->with('error', 'Anda tidak diizinkan mengedit kurikulum ini');
        }
        
        $perusahaanUsers = User::role('perusahaan')->get();
        return view('waka_kurikulum.kurikulum.edit', [
            'kurikulum' => $kurikulum,
            'perusahaanUsers' => $perusahaanUsers
        ]);
    }

    /**
     * Update the specified kurikulum
     */    public function update(Request $request, Kurikulum $kurikulum)
    {
        // Make sure the user is authorized to update this kurikulum
        if ($kurikulum->pengirim_id !== auth()->id()) {
            return redirect()->route('waka-kurikulum-list-diajukan')
                ->with('error', 'Anda tidak diizinkan mengedit kurikulum ini');
        }
        
        // If kurikulum is already fully approved, don't allow edit
        if ($kurikulum->validasi_sekolah == 'disetujui' && $kurikulum->validasi_perusahaan == 'disetujui') {
            return redirect()->route('waka-kurikulum-list-diajukan')
                ->with('error', 'Kurikulum yang telah disetujui tidak dapat diubah');
        }        $request->validate([
            'nama' => 'required|string',
            'tahun' => 'required|string',
            'deskripsi' => 'required|string',
            'file' => 'nullable|mimes:pdf',
            'perusahaan_id' => 'required|exists:users,id',
        ]);        // Update file if needed
        if ($request->hasFile('file')) {
            // Generate a unique filename to avoid overwriting existing files
            $filename = uniqid() . '_' . $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->storeAs('kurikulum', $filename, 'public');
            $kurikulum->update([
                'file_kurikulum' => $path
            ]);
        }// Update kurikulum data
        $kurikulum->update([
            'nama_kurikulum' => $request->nama,
            'tahun_ajaran' => $request->tahun,
            'deskripsi' => $request->deskripsi,
            'perusahaan_id' => $request->perusahaan_id,
            'validasi_perusahaan' => 'proses', // Reset validation status as it's been modified
        ]);

        return redirect()->route('waka-kurikulum-list-diajukan')
            ->with('success', 'Kurikulum berhasil diperbarui. Kurikulum akan kembali ke status Menunggu Validasi.');
    }
    
    /**
     * Remove the specified kurikulum
     */    public function destroy(Kurikulum $kurikulum)
    {
        // Make sure the user is authorized to delete this kurikulum
        if ($kurikulum->pengirim_id !== auth()->id()) {
            return redirect()->route('waka-kurikulum-list-diajukan')
                ->with('error', 'Anda tidak diizinkan menghapus kurikulum ini');
        }
        
        // If kurikulum is already fully approved, don't allow delete
        if ($kurikulum->validasi_sekolah == 'disetujui' && $kurikulum->validasi_perusahaan == 'disetujui') {
            return redirect()->route('waka-kurikulum-list-diajukan')
                ->with('error', 'Kurikulum yang telah disetujui tidak dapat dihapus');
        }

        $kurikulum->delete();
        return redirect()->route('waka-kurikulum-list-diajukan')
            ->with('success', 'Kurikulum berhasil dihapus');
    }

    /**
     * Approve a kurikulum
     */
    public function setuju(Kurikulum $kurikulum)
    {
        // Waka Kurikulum can only validate kurikulum from perusahaan
        if (!$kurikulum->pengirim->hasRole('perusahaan')) {
            return redirect()->route('waka-kurikulum-list-validasi')
                ->with('error', 'Anda hanya dapat memvalidasi kurikulum dari perusahaan');
        }
        
        $kurikulum->update([
            'validasi_sekolah' => 'disetujui',
        ]);
        
        return redirect()->route('waka-kurikulum-list-validasi')
            ->with('success', 'Kurikulum berhasil disetujui');
    }

    /**
     * Reject a kurikulum
     */
    public function tolak(Request $request, Kurikulum $kurikulum)
    {
        // Waka Kurikulum can only validate kurikulum from perusahaan
        if (!$kurikulum->pengirim->hasRole('perusahaan')) {
            return redirect()->route('waka-kurikulum-list-validasi')
                ->with('error', 'Anda hanya dapat memvalidasi kurikulum dari perusahaan');
        }
        
        $request->validate([
            'komentar' => 'required|string',
        ]);
        
        $kurikulum->update([
            'validasi_sekolah' => 'tidak_disetujui',
            'komentar' => $request->komentar
        ]);

        return redirect()->route('waka-kurikulum-list-validasi')
            ->with('success', 'Kurikulum berhasil ditolak');
    }
}
