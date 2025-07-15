<?php

namespace App\Http\Controllers\WakaHumas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PKL;
use App\Models\User;

class PklAssignController extends Controller
{
    /**
     * Display a listing of the PKL programs for assignment.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pkls = PKL::with(['perusahaan', 'pembimbing'])->paginate(10);
        return view('waka_humas.pkl.assign.index', compact('pkls'));
    }

    /**
     * Show the form for assigning a pembimbing to PKL.
     *
     * @param  \App\Models\PKL  $pkl
     * @return \Illuminate\Http\Response
     */
    public function showAssignForm(PKL $pkl)
    {
        // Get all users with guru role and jenis_guru = pembimbing
        $pembimbings = User::role('guru')
            ->where('jenis_guru', 'pembimbing')
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
    public function assignPembimbing(Request $request, PKL $pkl)
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
    public function show(PKL $pkl)
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
    public function removePembimbing(PKL $pkl)
    {
        $pkl->update([
            'pembimbing_id' => null
        ]);

        return redirect()->route('waka-humas-pkl-assign-index')
            ->with('success', 'Pembimbing berhasil dihapus dari program PKL.');
    }
}
