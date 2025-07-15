<?php

namespace App\Http\Controllers\WakaHumas;

use App\Http\Controllers\Controller;
use App\Models\GuruTamu;
use Illuminate\Http\Request;

class GuruTamuController extends Controller
{
    public function index()
    {
        $guruTamus = GuruTamu::latest()->paginate(10);
        return view('waka_humas.guru_tamu.index', compact('guruTamus'));
    }

    public function show(GuruTamu $guru_tamu)
    {
        return view('waka_humas.guru_tamu.show', compact('guru_tamu'));
    }

    public function approve(GuruTamu $guru_tamu)
    {
        $guru_tamu->update(['status' => 'disetujui']);
        return redirect()->route('waka-humas-guru-tamu-index')
            ->with('success', 'Guru tamu berhasil disetujui');
    }    public function reject(GuruTamu $guru_tamu)
    {
        $guru_tamu->update(['status' => 'proses']);
        return redirect()->route('waka-humas-guru-tamu-index')
            ->with('success', 'Guru tamu telah dikembalikan ke status proses');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
