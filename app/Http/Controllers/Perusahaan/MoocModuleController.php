<?php

namespace App\Http\Controllers\Perusahaan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Mooc;
use App\Models\MoocModule;

class MoocModuleController extends Controller
{
    public function create(Mooc $mooc){
        return view('perusahaan.mooc.module.create', [
            'mooc' => $mooc
        ]);
    }

    public function edit(Mooc $mooc, MoocModule $module){
        if ($module->mooc_id !== $mooc->id) {
            abort(404, 'Modul tidak ditemukan untuk pelatihan ini.');
        }
        return view('perusahaan.mooc.module.edit', [
            'module' => $module,
            'mooc' => $mooc
        ]);
    }
    public function show(Mooc $mooc, MoocModule $module){ // <<< PERBAIKAN INI >>>
        // Memastikan modul ini milik MOOC yang ada dan sesuai dengan parameter mooc
        if ($module->mooc_id !== $mooc->id) {
            abort(404, 'Modul tidak ditemukan untuk pelatihan ini.');
        }
        return view('perusahaan.mooc.module.show', compact('module', 'mooc')); // <<< Tambah mooc ke compact
    }

    public function store(Request $request){
        $request->validate([
            'module_name' => 'required|string|max:255',
            'deskripsi_modul' => 'nullable|string',
            'link_materi' => 'nullable|url',
            'dokumen_materi' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
            'mooc_id' => 'required|exists:moocs,id',
        ]);

        $dokumenPath = null;
        if ($request->hasFile('dokumen_materi')) {
            $dokumenPath = $request->file('dokumen_materi')->store('mooc_modules', 'public');
        }

        MoocModule::create([
            'module_name' => $request->module_name,
            'deskripsi_modul' => $request->deskripsi_modul,
            'link_materi' => $request->link_materi,
            'dokumen_materi' => $dokumenPath,
            'mooc_id' => $request->mooc_id
        ]);

        return redirect()->route('perusahaan-mooc-show', ['mooc' => $request->mooc_id])->with('success', 'Modul berhasil disimpan!');
    }

    public function destroy(MoocModule $module){
        if ($module->dokumen_materi) {
            Storage::disk('public')->delete($module->dokumen_materi);
        }
        $moocId = $module->mooc_id; // Simpan ID MOOC sebelum modul dihapus
        $module->delete();
        return redirect()->route('perusahaan-mooc-show', ['mooc' => $moocId])->with('success', 'Modul berhasil dihapus!'); // Redirect ke detail MOOC
    }

    public function update(Request $request, Mooc $mooc, MoocModule $module){
        if ($module->mooc_id !== $mooc->id) {
            abort(404, 'Modul tidak ditemukan untuk pelatihan ini.');
        }

        $request->validate([
            'module_name' => 'required|string|max:255',
            'deskripsi_modul' => 'nullable|string',
            'link_materi' => 'nullable|url',
            'dokumen_materi' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);

        $dokumenPath = $module->dokumen_materi;
        if ($request->hasFile('dokumen_materi')) {
            if ($dokumenPath) {
                Storage::disk('public')->delete($dokumenPath);
            }
            $dokumenPath = $request->file('dokumen_materi')->store('mooc_modules', 'public');
        }

        $module->update([
            'module_name' => $request->module_name,
            'deskripsi_modul' => $request->deskripsi_modul,
            'link_materi' => $request->link_materi,
            'dokumen_materi' => $dokumenPath,
        ]);

        return redirect()->route('perusahaan-mooc-show', ['mooc' => $mooc->id])->with('success', 'Modul berhasil diperbarui!'); // Redirect ke detail MOOC
    }
}