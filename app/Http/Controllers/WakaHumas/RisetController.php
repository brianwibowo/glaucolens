<?php

namespace App\Http\Controllers\WakaHumas;

use App\Http\Controllers\Controller;
use App\Models\Riset;
use App\Models\Anggota_Riset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RisetController extends Controller
{
    public function index()
    {
        $risets = Riset::with('anggota.user')
                     ->latest()
                     ->paginate(10);
        return view('waka_humas.riset_inovasi_produk.index', compact('risets'));
    }

    public function create()
    {
        // Mendapatkan semua user kecuali admin, atau yang tidak punya role
        // Pastikan role 'admin' adalah role yang tidak boleh jadi anggota riset
        $users = User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'admin');
        })->get();
        
        // Memastikan setiap user memiliki role untuk ditampilkan
        $users = $users->map(function ($user) {
            $user->role = $user->getRoleNames()->first() ?? 'Tidak ada role';
            return $user;
        });

        return view('waka_humas.riset_inovasi_produk.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'topik' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tim_riset' => 'required|array',
            'tim_riset.*' => 'exists:users,id',
            'file_proposal' => 'required|file|mimes:pdf|max:10240', // max 10MB
        ]);

        $proposalPath = $request->file('file_proposal')->store('riset/proposal', 'public'); // Simpan di public

        $riset = Riset::create([
            'topik' => $validated['topik'],
            'deskripsi' => $validated['deskripsi'],
            'tim_riset' => $validated['tim_riset'], // Akan disimpan sebagai JSON oleh Laravel
            'file_proposal' => $proposalPath,
            'status' => 'proses', // <<< PASTIKAN STATUS AWAL 'proses'
            'dokumentasi' => null, // Pastikan kolom dokumentasi defaultnya null
        ]);

        foreach ($validated['tim_riset'] as $userId) {
            Anggota_Riset::create([
                'id_risets' => $riset->id,
                'user_id' => $userId,
            ]);
        }

        return redirect()->route('waka-humas-riset-index')
            ->with('success', 'Riset berhasil diajukan');
    }

    public function show(Riset $riset)
    {
        $riset->load('anggota.user');
        return view('waka_humas.riset_inovasi_produk.show', compact('riset'));
    }

    public function edit(Riset $riset)
    {
        $users = User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'admin');
        })->get();

        $users = $users->map(function ($user) {
            $user->role = $user->getRoleNames()->first() ?? 'Tidak ada role';
            return $user;
        });
        
        $selectedMembers = $riset->anggota->pluck('user_id')->toArray();
        
        return view('waka_humas.riset_inovasi_produk.edit', 
                   compact('riset', 'users', 'selectedMembers'));
    }

    public function update(Request $request, Riset $riset)
    {
        $validated = $request->validate([
            'topik' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tim_riset' => 'required|array|min:1',
            'tim_riset.*' => 'exists:users,id',
            'file_proposal' => 'nullable|file|mimes:pdf|max:10240',
            // 'dokumentasi' tidak divalidasi di sini karena punya metode upload sendiri
            // 'status' tidak divalidasi di sini karena ini form pengajuan/edit, bukan validasi
        ]);

        $updateData = [
            'topik' => $validated['topik'],
            'deskripsi' => $validated['deskripsi'],
            'tim_riset' => $validated['tim_riset'],
        ];

        if ($request->hasFile('file_proposal')) {
            if ($riset->file_proposal) {
                Storage::disk('public')->delete($riset->file_proposal);
            }
            $updateData['file_proposal'] = $request->file('file_proposal')->store('riset/proposal', 'public');
        }

        // Hapus dokumentasi dari validasi request karena punya metode terpisah.
        // if ($request->hasFile('dokumentasi')) {
        //     if ($riset->dokumentasi) {
        //         Storage::disk('public')->delete($riset->dokumentasi);
        //     }
        //     $updateData['dokumentasi'] = $request->file('dokumentasi')->store('public/riset/dokumentasi');
        // }

        $riset->update($updateData);

        // Update anggota tim riset
        $riset->anggota()->delete(); // Hapus semua anggota lama
        foreach ($validated['tim_riset'] as $userId) {
            Anggota_Riset::create([
                'id_risets' => $riset->id,
                'user_id' => $userId,
            ]);
        }

        return redirect()->route('waka-humas-riset-show', $riset)
            ->with('success', 'Riset berhasil diperbarui');
    }

    public function destroy(Riset $riset)
    {
        // Hapus file proposal dan dokumentasi jika ada
        if ($riset->file_proposal) {
            Storage::disk('public')->delete($riset->file_proposal);
        }
        if ($riset->dokumentasi) {
            Storage::disk('public')->delete($riset->dokumentasi);
        }
        
        $riset->delete(); // Ini akan menghapus Anggota_Riset karena cascade
        return redirect()->route('waka-humas-riset-index')
            ->with('success', 'Riset berhasil dihapus');
    }

    public function dokumentasi(Riset $riset, Request $request)
    {
        $request->validate([
            'dokumentasi' => 'required|file|image|max:2048', // validasi image, max 2MB
        ]);

        if ($riset->dokumentasi) { // Hapus dokumentasi lama jika ada
            Storage::disk('public')->delete($riset->dokumentasi);
        }
        $dokumentasiPath = $request->file('dokumentasi')->store('riset/dokumentasi', 'public');

        $riset->dokumentasi = $dokumentasiPath;
        $riset->save();
        
        return redirect()->route('waka-humas-riset-show', $riset)
            ->with('success', 'Dokumentasi berhasil diunggah!');
    }
}