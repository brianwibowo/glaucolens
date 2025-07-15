<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers\Siswa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PKL;
use Illuminate\Support\Facades\Storage;

class PklController extends Controller
{
    public function index()
    {
        return view('siswa.pkl.index', [
            'pkls' => PKL::with('perusahaan')->get(),
            'currentStudentPklId' => auth()->user()->pkl_id 
        ]);
    }

    public function show()
    {
        $pkl_id = auth()->user()->pkl_id;
        return view('siswa.pkl.show', [
            'pkl' => PKL::with('perusahaan')->find($pkl_id)
        ]);
    }    public function register(PKL $pkl){
        auth()->user()->pkl_id = $pkl->id;
        auth()->user()->pkl_status = 'proses'; // 'proses' is equivalent to pending
        auth()->user()->save();
        
        // Update PKL status to 'berjalan' if it was 'proses' and now has applicants
        if ($pkl->status === 'proses' && $pkl->siswas()->count() > 0) {
            $pkl->status = 'berjalan';
            $pkl->save();
        }
        
        return redirect()->route('siswa-pkl-index')->with('success', 'Pendaftaran PKL berhasil diproses. Status: Proses');
    }  

    public function batal(){
        auth()->user()->pkl_id = null;
        auth()->user()->pkl_status = null;
        auth()->user()->save();
        return redirect()->route('siswa-pkl-index');
    }    public function uploadLaporan(Request $request){
        $request->validate([
            'laporan_akhir' => 'required|mimes:pdf'
        ]);

        $user = auth()->user();
        
        // Jika sudah pernah upload, hapus file lama
        if ($user->laporan_pkl) {
            Storage::disk('public')->delete($user->laporan_pkl);
        }

        $laporanPath = $request->file('laporan_akhir')->store('laporan_pkl', 'public');
        $user->update([
            'laporan_pkl' => $laporanPath
        ]);
        
        // Update status PKL jika diperlukan
        $pkl = PKL::find($user->pkl_id);
        if ($pkl) {
            $pkl->update([
                'status_pembimbing' => 'proses',  // Reset status validasi dari pembimbing
                'status_waka_humas' => 'proses'   // Reset status validasi dari waka humas
            ]);
        }

        return redirect()->route('siswa-pkl-show')->with('success', 'Laporan akhir PKL berhasil diunggah');
    }
}
