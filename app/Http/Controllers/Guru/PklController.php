<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PKL;
use App\Models\User;
use App\Models\Logbook;
use App\Models\LogbookContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PklController extends Controller
{
    /**
     * Display a listing of the PKL assigned to the authenticated guru.
     */
    public function index(Request $request)
    {
        $guruId = auth()->id();
        
        // Retrieve PKLs where the current guru is assigned as pembimbing
        $pkls = PKL::where('pembimbing_id', $guruId)
            ->with(['siswas', 'perusahaan'])
            ->latest()
            ->paginate(10);
        
        return view('guru.pkl.index', compact('pkls'));
    }

    /**
     * Display the specified PKL details.
     */
    public function show(PKL $pkl)
    {
        $this->checkAuthorizationForPkl($pkl);
        
        $pkl->load(['siswas', 'perusahaan']);
        return view('guru.pkl.show', compact('pkl'));
    }

    /**
     * Display a list of students for a specific PKL.
     */
    public function siswaList(PKL $pkl)
    {
        $this->checkAuthorizationForPkl($pkl);
        
        $pkl->load(['siswas']);
        return view('guru.pkl.siswa_list', compact('pkl'));
    }

    /**
     * Display the logbook for a specific student.
     */
    public function siswaLogbook(User $siswa)
    {
        $logbook = Logbook::where('siswa_id', $siswa->id)->first();
        
        if (!$logbook) {
            return redirect()->route('guru-pkl-index')
                ->with('error', 'Logbook siswa tidak ditemukan');
        }
        
        // Check if the guru is authorized to view this student's logbook
        $pkl = PKL::find($logbook->pkl_id);
        $this->checkAuthorizationForPkl($pkl);
        
        // Get logbook contents ordered by date (newest first)
        $logbookContents = $logbook->logbookContents()
            ->orderBy('tanggal', 'desc')
            ->paginate(10);
        
        return view('guru.pkl.siswa_logbook', compact('siswa', 'logbook', 'logbookContents', 'pkl'));
    }

    /**
     * Validate a student's final report.
     */
    public function validateFinalReport(Request $request, User $siswa)
    {
        $request->validate([
            'status' => 'required|in:disetujui,revisi',
            'catatan' => 'nullable|string|max:1000'
        ]);
        
        if (!$siswa->pkl_id) {
            return redirect()->route('guru-pkl-index')
                ->with('error', 'Siswa tidak terdaftar dalam program PKL');
        }
        
        $pkl = PKL::find($siswa->pkl_id);
        $this->checkAuthorizationForPkl($pkl);
        
        // Update the validation status in PKL record
        $pkl->update([
            'status_pembimbing' => $request->status,
            'catatan_pembimbing' => $request->catatan ?? null,
            'tanggal_validasi_pembimbing' => now()
        ]);
        
        // Return back with success message
        return redirect()->route('guru-pkl-show', $pkl)
            ->with('success', 'Laporan akhir PKL berhasil divalidasi');
    }    /**
     * Validate all logbook entries at once.
     * This method is kept for route compatibility but is not used actively
     * as per requirements - only final reports need validation.
     */
    public function validateFullLogbook(Logbook $logbook)
    {
        // Check if the guru is authorized to validate this logbook
        $pkl = PKL::find($logbook->pkl_id);
        $this->checkAuthorizationForPkl($pkl);
        
        // For now, just redirect back with notice as logbooks don't need validation
        return redirect()->back()
            ->with('info', 'Sesuai kebijakan baru, validasi hanya diperlukan untuk laporan akhir PKL, tidak untuk entri logbook.');
    }

    /**
     * Check if the authenticated guru is authorized to access the specified PKL.
     */
    private function checkAuthorizationForPkl($pkl)
    {
        if (!$pkl || $pkl->pembimbing_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke PKL ini');
        }
    }
}
