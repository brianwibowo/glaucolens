<?php

namespace App\Http\Controllers\Perusahaan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;

use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        return view('perusahaan.project_mitra.index', [
            'projects' => Project::where('perusahaan_id', auth()->id())->get()
        ]);
    }

    public function create()    {
        return view('perusahaan.project_mitra.create');
    }
    
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'file_brief' => 'required|file|mimes:pdf|max:10240' // 10MB max
        ]);

        $path = $request->file('file_brief')->store('project/brief/', 'public');

        Project::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'file_brief' => $path,
            'perusahaan_id' => auth()->id(),
        ]);

        return redirect()->route('perusahaan-project-index')->with('success', 'Project berhasil ditambahkan');
    }

    public function show()
    {
        return view('perusahaan.project_mitra.show');
    }

    public function edit(Project $project)
    {
        return view('perusahaan.project_mitra.edit',[
            'project' => $project
        ]);
    }    public function update(Request $request, Project $project)
    {        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'file_brief' => 'nullable|file|mimes:pdf|max:10240' // 10MB max
        ]);        if($request->hasFile('file_brief')) {
            // Delete old file if it exists (using the proper disk)
            if($project->file_brief) {
                if (Storage::disk('public')->exists($project->file_brief)) {
                    Storage::disk('public')->delete($project->file_brief);
                    \Log::info('Deleted old brief file during update: ' . $project->file_brief);
                } else {
                    \Log::warning('Old brief file not found during update: ' . $project->file_brief);
                }
            }
            // Store the new file
            $path = $request->file('file_brief')->store('project/brief/', 'public');
            \Log::info('Uploaded new brief file: ' . $path);
            $project->file_brief = $path;
            $project->save();
        }

        $project->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return redirect()->route('perusahaan-project-index')->with('success', 'Project berhasil diperbarui');
    }    public function destroy(Project $project)
    {
        try {
            // Log file paths before deletion for debugging
            \Log::info('Deleting project with ID: ' . $project->id);
            \Log::info('File brief path: ' . $project->file_brief);
            \Log::info('File laporan path: ' . $project->file_laporan);
            
            // Manually delete files to ensure they're properly removed
            if($project->file_brief) {
                if (Storage::disk('public')->exists($project->file_brief)) {
                    Storage::disk('public')->delete($project->file_brief);
                    \Log::info('Deleted brief file: ' . $project->file_brief);
                } else {
                    \Log::warning('Brief file not found: ' . $project->file_brief);
                }
            }
            
            if($project->file_laporan) {
                if (Storage::disk('public')->exists($project->file_laporan)) {
                    Storage::disk('public')->delete($project->file_laporan);
                    \Log::info('Deleted laporan file: ' . $project->file_laporan);
                } else {
                    \Log::warning('Laporan file not found: ' . $project->file_laporan);
                }
            }
            
            // Delete the project record - this will also trigger the deleting event in the model
            $project->delete();
            
            return redirect()->route('perusahaan-project-index')->with('success', 'Project berhasil dihapus, semua file terkait telah dibersihkan.');
        } catch (\Exception $e) {
            \Log::error('Error deleting project: ' . $e->getMessage());
            return redirect()->route('perusahaan-project-index')->with('error', 'Terjadi kesalahan saat menghapus project: ' . $e->getMessage());
        }
    }
}
