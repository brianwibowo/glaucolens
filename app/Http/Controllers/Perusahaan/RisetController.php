<?php

namespace App\Http\Controllers\Perusahaan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;

use App\Models\Riset;

class RisetController extends Controller
{
    public function index()
    {
        $risets = Riset::with('anggota.user')
                     ->latest()
                     ->paginate(10);
        return view('perusahaan.riset.index', compact('risets'));
    }

    public function tolak(Riset $riset)
    {
        $riset->status = 'ditolak';
        $riset->save();
        return redirect()->route('perusahaan-riset-index')->with('success', 'Riset berhasil ditolak.'); // Tambah flash message
    }

    public function terima(Riset $riset)
    {
        $riset->status = 'disetujui';
        $riset->save();
        return redirect()->route('perusahaan-riset-index')->with('success', 'Riset berhasil disetujui.'); // Tambah flash message
    }

    public function results()
    {
        $risets = Riset::with('anggota.user')
                     ->latest()
                     ->paginate(10);
        return view('perusahaan.riset.results', compact('risets'));
    }
}