<?php

namespace App\Http\Controllers\Alumni;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\ScoutingBatch;
use App\Models\Talent_Scouting;

class ScoutingController extends Controller
{
    public function index()
    {
        return view ('alumni.scoutings.index', [
            'batches' => ScoutingBatch::all(),
            'appliedBatchIds' => Talent_Scouting::where('user_id', auth()->user()->id)->pluck('batch_id')->toArray()
        ]);
    }

    public function registration(ScoutingBatch $scouting)
    {
        return view('alumni.scoutings.register', [
            'scouting' => $scouting
        ]);
    }

    public function apply(ScoutingBatch $scouting , Request $request)
    {
        $request->validate([
            'cv' => 'required|file|mimes:pdf|max:2048', 
            'ijazah' => 'required|file|mimes:pdf|max:2048', 
            'pernyataan' => 'required|file|mimes:pdf|max:2048' 
        ]);

        Talent_Scouting::create([
            'batch_id' => $scouting->id,
            'file_cv' => $request->file('cv')->store('talent_scoutings/cv', 'public'),
            'file_ijazah' => $request->file('ijazah')->store('talent_scoutings/ijazah', 'public'),
            'file_pernyataan' => $request->file('pernyataan')->store('talent_scoutings/pernyataan', 'public'),
            'status_seleksi' => 'proses',
            'user_id' => auth()->user()->id
        ]);

        return redirect()->route('alumni-scouting-index');
    }
    public function status()
    {
        $talents = Talent_Scouting::with('batch.perusahaan')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('alumni.scoutings.status', compact('talents'));
    }
}
