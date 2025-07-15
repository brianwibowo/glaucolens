<?php

namespace App\Http\Controllers\WakaHumas;

use App\Http\Controllers\Controller;
use App\Models\Pkl;
use App\Models\Logbook;
use App\Models\User;
use App\Models\LogbookContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PklController extends Controller
{    /**
     * Display a listing of the PKL reports.
     */    public function index(Request $request)
    {
        $query = Pkl::with(['siswas', 'pembimbing', 'perusahaan']);
        
        $pkls = $query->latest()->paginate(10);
        
        return view('waka_humas.pkl.index', compact('pkls'));
    }

    /**
     * Display the specified PKL report.
     */
    public function show(Pkl $pkl)
    {
        $pkl->load(['siswas', 'pembimbing', 'perusahaan']);
        return view('waka_humas.pkl.show', compact('pkl'));
    }/**
     * Validate the PKL report.
     */
    public function validateReport(Request $request, Pkl $pkl)
    {
        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan' => 'nullable|string|max:1000'
        ]);

        $pkl->update([
            'status_waka_humas' => $validated['status'],
            'catatan_waka_humas' => $validated['catatan'] ?? null,
            'tanggal_validasi_waka_humas' => now()
        ]);

        return redirect()->route('waka-humas-pkl-show', $pkl)
            ->with('success', 'Laporan PKL berhasil divalidasi');
    }    /**
     * Download the PKL report file.
     */    public function downloadReport(Pkl $pkl)
    {
        $siswa = $pkl->siswas->first();
        if (!$siswa) {
            abort(404, 'Data siswa tidak ditemukan');
        }
        
        if (!$siswa->laporan_pkl) {
            abort(404, 'Laporan PKL belum diunggah');
        }
        
        // Redirect to the public storage URL instead of forcing a download
        return redirect('/storage/' . $siswa->laporan_pkl);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showId($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }    /**
     * Show student logbook for validation.
     *
     * @param  \App\Models\User  $siswa
     * @return \Illuminate\Http\Response
     */    public function siswaLogbook(User $siswa)
    {
        $logbook = Logbook::where('siswa_id', $siswa->id)->first();
        
        if (!$logbook) {
            return redirect()->route('waka-humas-pkl-logbook-validation-index')->with('error', 'Logbook tidak ditemukan');
        }
        
        // Get logbook contents ordered by date (newest first)
        $logbookContents = $logbook->logbookContents()->orderBy('tanggal', 'desc')->paginate(10);
        
        return view('waka_humas.pkl.siswa_logbook', compact('siswa', 'logbook', 'logbookContents'));
    }// Validation methods removed as per requirement - only final reports need validation

    /**
     * Display a listing of the PKL programs for assignment.
     *
     * @return \Illuminate\Http\Response
     */
    public function assignIndex()
    {
        $pkls = PKL::with(['perusahaan', 'pembimbing'])->paginate(10);
        return view('waka_humas.pkl.assign.index', compact('pkls'));
    }

    /**
     * Show the form for assigning a pembimbing to PKL.
     *
     * @param  \App\Models\PKL  $pkl
     * @return \Illuminate\Http\Response
     */    public function assignForm(PKL $pkl)
    {
        // Get all users with guru role and jenis_guru = 'guru pembimbing'
        $pembimbings = User::role('guru')
            ->where('jenis_guru', 'guru pembimbing')
            ->get();
            
        return view('waka_humas.pkl.assign.form', compact('pkl', 'pembimbings'));
    }

    /**
     * Assign a pembimbing to the PKL.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PKL  $pkl
     * @return \Illuminate\Http\Response
     */
    public function assignStore(Request $request, PKL $pkl)
    {
        $request->validate([
            'pembimbing_id' => 'required|exists:users,id'
        ]);

        $pkl->update([
            'pembimbing_id' => $request->pembimbing_id
        ]);

        return redirect()->route('waka-humas-pkl-assign-index')
            ->with('success', 'Pembimbing berhasil ditugaskan ke program PKL.');
    }

    /**
     * Show the PKL details with assigned pembimbing.
     *
     * @param  \App\Models\PKL  $pkl
     * @return \Illuminate\Http\Response
     */
    public function assignShow(PKL $pkl)
    {
        $pkl->load(['perusahaan', 'pembimbing', 'siswas']);
        return view('waka_humas.pkl.assign.show', compact('pkl'));
    }

    /**
     * Remove the assignment of pembimbing from PKL.
     *
     * @param  \App\Models\PKL  $pkl
     * @return \Illuminate\Http\Response
     */
    public function assignRemove(PKL $pkl)
    {
        $pkl->update([
            'pembimbing_id' => null
        ]);

        return redirect()->route('waka-humas-pkl-assign-index')
            ->with('success', 'Pembimbing berhasil dihapus dari program PKL.');
    }    /**
     * Display logbooks that need validation.
     *
     * @return \Illuminate\Http\Response
     */    public function logbookValidationIndex()
    {
        // Get all logbooks with their relationships
        $logbooks = Logbook::with(['siswa', 'pkl'])->paginate(10);
        
        return view('waka_humas.pkl.logbook_validation', compact('logbooks'));
    }
}
