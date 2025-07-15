<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers\Siswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\LogbookContent;
use App\Models\PKL;

class LogbookController extends Controller
{
    public function index()
    {
        if (auth()->user()->logbook === null) {
            return redirect()->route('siswa-pkl-index');    
        };

        $logbooks = auth()->user()->logbook->logbookContents()->paginate(10);

        return view('siswa.pkl.logbook.index', [
            'logbooks' => $logbooks,
            'pkl' => auth()->user()->pklSiswa->nama
        ]);
    }

    public function create()
    {
        return view('siswa.pkl.logbook.create');
    }    public function store(Request $request)
    {        $request->validate([
            'tanggal' => 'required',
            'nama' => 'required',
            'detail' => 'required',
            'dokumentasi' => 'required|mimes:jpeg,png,jpg|max:2048',
        ]);

        $dokumentasiPath = $request->file('dokumentasi')->store('public/logbook');
        $dokumentasiPath = str_replace('public/', '', $dokumentasiPath);
        
        LogbookContent::create([
            'tanggal' => $request->tanggal,
            'nama' => $request->nama,
            'detail' => $request->detail,
            'dokumentasi' => $dokumentasiPath,
            'logbook_id' => auth()->user()->logbook->id
        ]);

        return redirect()->route('siswa-logbook-index');
    }

    public function edit(LogbookContent $logbook)
    {
        return view('siswa.pkl.logbook.edit', [
            'logbook' => $logbook
        ]);
    }    public function update(Request $request, LogbookContent $logbook)
    {
        $request->validate([
            'tanggal' => 'required',
            'nama' => 'required',
            'detail' => 'required',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('dokumentasi')) {
            Storage::delete($logbook->dokumentasi);
            $dokumentasiPath = $request->file('dokumentasi')->store('public/logbook');
            $dokumentasiPath = str_replace('public/', '', $dokumentasiPath);
            $logbook->update([
                'dokumentasi' => $dokumentasiPath
            ]);
        }
        
        $logbook->update([
            'tanggal' => $request->tanggal,
            'nama' => $request->nama,
            'detail' => $request->detail
        ]);
        
        // Reset validation status for the entire logbook if any entry is modified
        $userLogbook = auth()->user()->logbook;
        if ($userLogbook) {
            $userLogbook->update([
                'status_validasi_pembimbing' => 'belum_validasi',
                'status_validasi_waka_humas' => 'belum_validasi'
            ]);
        }

        return redirect()->route('siswa-logbook-index');
    }

    public function destroy(LogbookContent $logbook)
    {
        Storage::delete($logbook->dokumentasi);
        $logbook->delete();
        return redirect()->route('siswa-logbook-index');
    }
}
