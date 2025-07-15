<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Riset;
use App\Models\Anggota_Riset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class RisetController extends Controller
{
    // Admin bisa melihat semua riset (dari siapa pun)
    public function index()
    {
        $risets = Riset::with('anggota.user')
                     ->latest()
                     ->paginate(10);
        return view('admin.riset.index', compact('risets')); // Folder views baru
    }

    // Admin bisa mengajukan riset (seperti Waka Humas)
    public function create()
    {
        $users = User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'admin');
        })->get();
        $users = $users->map(function ($user) {
            $user->role = $user->getRoleNames()->first() ?? 'Tidak ada role';
            return $user;
        });

        return view('admin.riset.create', compact('users')); // Folder views baru
    }

    // Admin bisa menyimpan pengajuan riset (seperti Waka Humas)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'topik' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tim_riset' => 'required|array',
            'tim_riset.*' => 'exists:users,id',
            'file_proposal' => 'required|file|mimes:pdf|max:10240',
        ]);

        $proposalPath = $request->file('file_proposal')->store('riset/proposal', 'public');

        $riset = Riset::create([
            'topik' => $validated['topik'],
            'deskripsi' => $validated['deskripsi'],
            'tim_riset' => $validated['tim_riset'],
            'file_proposal' => $proposalPath,
            'status' => 'proses',
            'dokumentasi' => null,
        ]);

        foreach ($validated['tim_riset'] as $userId) {
            Anggota_Riset::create([
                'id_risets' => $riset->id,
                'user_id' => $userId,
            ]);
        }

        return redirect()->route('admin-riset-index')
            ->with('success', 'Riset berhasil diajukan');
    }

    // Admin bisa melihat detail riset (seperti Waka Humas)
    public function show(Riset $riset)
    {
        $riset->load('anggota.user');
        return view('admin.riset.show', compact('riset')); // Folder views baru
    }

    // Admin bisa mengedit riset (seperti Waka Humas)
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
        
        return view('admin.riset.edit', compact('riset', 'users', 'selectedMembers')); // Folder views baru
    }

    // Admin bisa update riset (seperti Waka Humas)
    public function update(Request $request, Riset $riset)
    {
        $validated = $request->validate([
            'topik' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tim_riset' => 'required|array|min:1',
            'tim_riset.*' => 'exists:users,id',
            'file_proposal' => 'nullable|file|mimes:pdf|max:10240',
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

        $riset->update($updateData);

        $riset->anggota()->delete();
        foreach ($validated['tim_riset'] as $userId) {
            Anggota_Riset::create([
                'id_risets' => $riset->id,
                'user_id' => $userId,
            ]);
        }

        return redirect()->route('admin-riset-show', $riset)
            ->with('success', 'Riset berhasil diperbarui');
    }

    // Admin bisa menghapus riset (seperti Waka Humas)
    public function destroy(Riset $riset)
    {
        if ($riset->file_proposal) {
            Storage::disk('public')->delete($riset->file_proposal);
        }
        if ($riset->dokumentasi) {
            Storage::disk('public')->delete($riset->dokumentasi);
        }
        
        $riset->delete();
        return redirect()->route('admin-riset-index')
            ->with('success', 'Riset berhasil dihapus');
    }

    // Admin bisa mengupload dokumentasi (seperti Waka Humas)
    public function dokumentasi(Riset $riset, Request $request)
    {
        $request->validate([
            'dokumentasi' => 'required|file|image|max:2048',
        ]);

        if ($riset->dokumentasi) {
            Storage::disk('public')->delete($riset->dokumentasi);
        }
        $dokumentasiPath = $request->file('dokumentasi')->store('riset/dokumentasi', 'public');

        $riset->dokumentasi = $dokumentasiPath;
        $riset->save();
        
        return redirect()->route('admin-riset-show', $riset)
            ->with('success', 'Dokumentasi berhasil diunggah!');
    }

    // Admin bisa menyetujui riset (seperti Perusahaan)
    public function terima(Riset $riset)
    {
        $riset->status = 'disetujui';
        $riset->save();
        return redirect()->route('admin-riset-index')->with('success', 'Riset berhasil disetujui.');
    }

    // Admin bisa menolak riset (seperti Perusahaan)
    public function tolak(Riset $riset)
    {
        $riset->status = 'ditolak';
        $riset->save();
        return redirect()->route('admin-riset-index')->with('success', 'Riset berhasil ditolak.');
    }

    // Admin bisa melihat hasil (seperti Perusahaan)
    public function results()
    {
        $risets = Riset::with('anggota.user')
                     ->latest()
                     ->paginate(10);
        return view('admin-riset-results', compact('risets')); // Folder views baru
    }
}