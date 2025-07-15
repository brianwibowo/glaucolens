<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KurikulumController extends Controller
{    public function index()
    {
        // Get all curricula for both tabs
        $allCurricula = Kurikulum::where('pengirim_id', auth()->user()->id)
            ->orWhereHas('pengirim', function($q) {
                $q->whereHas('roles', function($r) {
                    $r->whereIn('name', ['perusahaan', 'waka_kurikulum']);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();              // School curricula - submitted by admin on behalf of school or by waka_kurikulum
        $schoolCurricula = $allCurricula->filter(function($kurikulum) {
            // If sender is waka_kurikulum
            if ($kurikulum->pengirim->hasRole('waka_kurikulum')) {
                return true;
            }
            
            // If sender is admin and it's on behalf of school (has perusahaan_id means it's for schools)
            if ($kurikulum->pengirim->hasRole('admin') && 
                !is_null($kurikulum->perusahaan_id)) {
                return true;
            }
            
            return false;
        })->unique('id'); // This ensures no duplicates// Company curricula - submitted by companies or admin on behalf of companies
        $companyCurricula = $allCurricula->filter(function($kurikulum) {
            // If sender is a company
            if ($kurikulum->pengirim->hasRole('perusahaan')) {
                return true;
            }
            
            // If sender is admin and it's on behalf of company (no perusahaan_id means it's for companies)
            if ($kurikulum->pengirim->hasRole('admin') && 
                is_null($kurikulum->perusahaan_id)) {
                return true;
            }
            
            // Explicitly exclude any curriculum that should be in the school tab
            if ($kurikulum->pengirim->hasRole('waka_kurikulum') || 
                !is_null($kurikulum->perusahaan_id)) {
                return false;
            }
            
            return false;
        })->unique('id'); // This ensures no duplicates
        
        return view('admin.kurikulum.list-diajukan', [
            'kurikulums' => $allCurricula,
            'schoolCurricula' => $schoolCurricula,
            'companyCurricula' => $companyCurricula
        ]);
    }public function validasi()
    {
        return view('admin.kurikulum.list-validasi', [
            'kurikulums' => Kurikulum::whereHas('pengirim', function($query) {
                $query->role('perusahaan');
            })->orderBy('validasi_sekolah', 'asc')
              ->orderBy('created_at', 'desc')
              ->get()
        ]);
    }
    
    public function validasiSekolah()
    {
        return view('admin.kurikulum.list-validasi-sekolah', [
            'kurikulums' => Kurikulum::where(function($query) {
                $query->where(function($q) {
                    // Kurikulum yang dikirim oleh waka kurikulum
                    $q->whereHas('pengirim', function($sq) {
                        $sq->role('waka_kurikulum');
                    });
                })->orWhere(function($q) {
                    // Kurikulum yang dikirim oleh admin atas nama sekolah
                    $q->whereHas('pengirim', function($sq) {
                        $sq->role('admin');
                    })->whereNotNull('perusahaan_id');
                });
            })
            ->orderBy('validasi_perusahaan', 'asc')
            ->orderBy('created_at', 'desc')
            ->get()
        ]);
    }
    
    // Method removed as per requirements
    /*
    public function monitorWakaKurikulum()
    {
        return view('admin.kurikulum.monitor-waka-kurikulum', [
            'kurikulums' => Kurikulum::whereHas('pengirim', function($query) {
                $query->role('waka_kurikulum');
            })->orderBy('validasi_perusahaan', 'asc')
              ->orderBy('created_at', 'desc')
              ->get()
        ]);
    }
    */
    
    public function create()
    {
        $perusahaanUsers = User::role('perusahaan')->get();
        return view('admin.kurikulum.create', [
            'perusahaanUsers' => $perusahaanUsers
        ]);
    }
    
    public function createForSchool()
    {
        $perusahaanUsers = User::role('perusahaan')->get();
        return view('admin.kurikulum.create-for-school', [
            'perusahaanUsers' => $perusahaanUsers
        ]);
    }
    
    public function createForCompany()
    {
        $perusahaanUsers = User::role('perusahaan')->get();
        return view('admin.kurikulum.create-for-company', [
            'perusahaanUsers' => $perusahaanUsers
        ]);
    }    public function store(Request $request)
    {        $request->validate([
            'nama' => 'required|string',
            'tahun' => 'required|string',
            'deskripsi' => 'required|string',
            'file' => 'required|mimes:pdf',
            'submission_type' => 'required|in:admin,school,company',
            'perusahaan_id' => 'required_if:submission_type,school|nullable|exists:users,id',
            'company_submitter_id' => 'required_if:submission_type,company|nullable|exists:users,id',
        ]);

        $path = $request->file('file')->store('kurikulum/', 'public');

        // Determine validation status based on submission type
        if ($request->submission_type == 'school') {
            // Admin submitting on behalf of school to company
            $validasi_sekolah = 'disetujui';
            $validasi_perusahaan = 'proses';
            $pengirim_id = auth()->user()->id;
            $perusahaan_id = $request->perusahaan_id;
            $message = 'Kurikulum sekolah berhasil diajukan untuk validasi perusahaan';
        } 
        elseif ($request->submission_type == 'company') {
            // Admin submitting on behalf of company to school
            $validasi_sekolah = 'proses';
            $validasi_perusahaan = 'disetujui';
            $pengirim_id = $request->company_submitter_id; // Use the selected company as pengirim
            $perusahaan_id = $request->company_submitter_id; // Same as pengirim since it's a company
            $message = 'Kurikulum perusahaan berhasil diajukan untuk validasi sekolah';
        } 
        else {
            // Default admin submission
            $validasi_sekolah = 'disetujui';
            $validasi_perusahaan = 'proses';
            $pengirim_id = auth()->user()->id;
            $perusahaan_id = null;
            $message = 'Kurikulum berhasil diajukan untuk validasi perusahaan';
        }

        Kurikulum::create([
            'nama_kurikulum' => $request->nama,
            'pengirim_id' => $pengirim_id,
            'perusahaan_id' => $perusahaan_id,
            'tahun_ajaran' => $request->tahun,
            'deskripsi' => $request->deskripsi,
            'file_kurikulum' => $path,
            'validasi_sekolah' => $validasi_sekolah,
            'validasi_perusahaan' => $validasi_perusahaan
        ]);

        return redirect()->route('admin-kurikulum-list-diajukan')
            ->with('success', $message);
    }    

    public function edit(Kurikulum $kurikulum)
    {
        if ($kurikulum->pengirim_id !== auth()->id()) {
            return redirect()->route('admin-kurikulum-list-diajukan')
                ->with('error', 'Anda tidak diizinkan mengedit kurikulum ini');
        }
        
        return view('admin.kurikulum.edit', [
            'kurikulum' => $kurikulum
        ]);
    }    public function update(Request $request, Kurikulum $kurikulum)
    {
        if ($kurikulum->validasi_sekolah == 'disetujui' && $kurikulum->validasi_perusahaan == 'disetujui' && !auth()->user()->hasRole('admin')) {
            return redirect()->route('admin-kurikulum-list-diajukan')
                ->with('error', 'Kurikulum yang telah disetujui tidak dapat diubah');
        }        $request->validate([
            'nama' => 'required|string',
            'tahun' => 'required|string',
            'deskripsi' => 'required|string',
            'file' => 'nullable|mimes:pdf',
        ]);if ($request->hasFile('file')) {
            // Generate a unique filename to avoid overwriting existing files
            $filename = uniqid() . '_' . $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->storeAs('kurikulum', $filename, 'public');
            $kurikulum->update([
                'file_kurikulum' => $path
            ]);
        }

        // Check who sent this kurikulum to determine what validation status to reset
        $pengirim = User::find($kurikulum->pengirim_id);
        
        if ($pengirim && $pengirim->hasRole('perusahaan')) {
            // This is a company kurikulum, reset school validation
            $kurikulum->update([
                'nama_kurikulum' => $request->nama,
                'tahun_ajaran' => $request->tahun,
                'deskripsi' => $request->deskripsi,
                'validasi_sekolah' => 'proses',
            ]);
            $message = 'Kurikulum perusahaan berhasil diperbarui. Status validasi sekolah direset.';
        } else {
            // This is a school kurikulum, reset company validation
            $kurikulum->update([
                'nama_kurikulum' => $request->nama,
                'tahun_ajaran' => $request->tahun,
                'deskripsi' => $request->deskripsi,
                'validasi_perusahaan' => 'proses',
            ]);
            $message = 'Kurikulum sekolah berhasil diperbarui. Status validasi perusahaan direset.';
        }
        
        return redirect()->route('admin-kurikulum-list-diajukan')
            ->with('success', $message);
    }    

    public function destroy(Kurikulum $kurikulum)
    {
        if ($kurikulum->pengirim_id !== auth()->id()) {
            return redirect()->route('admin-kurikulum-list-diajukan')
                ->with('error', 'Anda tidak diizinkan menghapus kurikulum ini');
        }

        $kurikulum->delete();
        return redirect()->route('admin-kurikulum-list-diajukan')
            ->with('success', 'Kurikulum berhasil dihapus');
    }    public function setuju(Kurikulum $kurikulum)
    {
        // Get the route that requested the validation
        $route = request()->route()->getName();
        $referrer = request()->headers->get('referer');
        
        // Determine which validation page called this method
        $isFromValidasiPerusahaan = $route === 'admin-kurikulum-setuju' && strpos($referrer, 'validasi-sekolah') === false;
        
        if ($isFromValidasiPerusahaan) {
            // This is for validating perusahaan curriculum - admin can validate any perusahaan curriculum
            if (!$kurikulum->pengirim->hasRole('perusahaan') && $kurikulum->validasi_perusahaan !== 'disetujui') {
                return redirect()->route('admin-kurikulum-list-validasi')
                    ->with('error', 'Halaman ini untuk memvalidasi kurikulum dari perusahaan');
            }
            
            $kurikulum->update([
                'validasi_sekolah' => 'disetujui',
            ]);
            
            return redirect()->route('admin-kurikulum-list-validasi')
                ->with('success', 'Kurikulum berhasil disetujui');
        } else {
            // This is for validating sekolah curriculum
            if (!($kurikulum->pengirim->hasRole('waka_kurikulum') || $kurikulum->pengirim->hasRole('admin'))) {
                return redirect()->route('admin-kurikulum-list-validasi-sekolah')
                    ->with('error', 'Halaman ini untuk memvalidasi kurikulum dari sekolah');
            }
            
            $kurikulum->update([
                'validasi_perusahaan' => 'disetujui',
            ]);
            
            return redirect()->route('admin-kurikulum-list-validasi-sekolah')
                ->with('success', 'Kurikulum berhasil disetujui');
        }
    }    public function tolak(Request $request, Kurikulum $kurikulum)
    {
        $request->validate([
            'komentar' => 'required|string',
        ]);
        
        // Get the route that requested the validation
        $route = request()->route()->getName();
        $referrer = request()->headers->get('referer');
        
        // Determine which validation page called this method
        $isFromValidasiPerusahaan = $route === 'admin-kurikulum-tolak' && 
            strpos($referrer, 'validasi-sekolah') === false;
        
        if ($isFromValidasiPerusahaan) {
            // This is for validating perusahaan curriculum - admin can validate any perusahaan curriculum
            if (!$kurikulum->pengirim->hasRole('perusahaan') && $kurikulum->validasi_perusahaan !== 'disetujui') {
                return redirect()->route('admin-kurikulum-list-validasi')
                    ->with('error', 'Halaman ini untuk memvalidasi kurikulum dari perusahaan');
            }
            
            $kurikulum->update([
                'validasi_sekolah' => 'tidak_disetujui',
                'komentar' => $request->komentar
            ]);
    
            return redirect()->route('admin-kurikulum-list-validasi')
                ->with('success', 'Kurikulum berhasil ditolak');
        } else {
            // Already handled by tolakSekolah method
            return redirect()->route('admin-kurikulum-list-validasi')
                ->with('error', 'Gunakan form yang sesuai untuk menolak kurikulum');
        }
    }
    
    public function tolakSekolah(Request $request, Kurikulum $kurikulum)
    {
        $request->validate([
            'komentar' => 'required|string',
        ]);
        
        // Validate only for school curricula
        if (!($kurikulum->pengirim->hasRole('waka_kurikulum') || $kurikulum->pengirim->hasRole('admin'))) {
            return redirect()->route('admin-kurikulum-list-validasi-sekolah')
                ->with('error', 'Halaman ini untuk memvalidasi kurikulum dari sekolah');
        }
        
        $kurikulum->update([
            'validasi_perusahaan' => 'tidak_disetujui',
            'komentar' => $request->komentar
        ]);

        return redirect()->route('admin-kurikulum-list-validasi-sekolah')
            ->with('success', 'Kurikulum berhasil ditolak');
    }
}