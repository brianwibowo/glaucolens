<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('perusahaan')
            ->select('*')
            ->selectRaw('DATE(tanggal_mulai) as tanggal_mulai')
            ->selectRaw('DATE(tanggal_selesai) as tanggal_selesai')
            ->get();
            
        return view('admin.project.index', compact('projects'));
    }    public function uploadLaporan(Request $request, Project $project)
    {
        $request->validate([
            'file_laporan' => 'required|file|mimes:pdf,doc,docx|max:10240', // Maksimal 10MB
        ]);

        $today = now();
        $endDate = \Carbon\Carbon::parse($project->tanggal_selesai);
        $isAfterTimeline = $today > $endDate;

        if ($project->file_laporan) {
            Storage::disk('public')->delete($project->file_laporan);
        }

        $path = $request->file('file_laporan')->store('project/laporan', 'public');
        
        $notes = $request->notes;
        if (empty($notes)) {
            $notes = $isAfterTimeline 
                ? 'Laporan diupload oleh admin setelah timeline project berakhir. ' . $endDate->format('d M Y')
                : 'Diupload oleh admin';
        }
        
        $project->update([
            'file_laporan' => $path,
            'is_manual_upload' => true, // Tandai bahwa ini upload manual oleh admin
            'upload_notes' => $notes
        ]);

        $successMessage = $isAfterTimeline 
            ? 'Laporan berhasil diupload. Perhatikan bahwa Guru tidak akan dapat mengubah laporan ini.'
            : 'Laporan berhasil diupload.';

        return redirect()->route('admin-project-index')
            ->with('success', $successMessage);
    }    public function updateLaporan(Request $request, Project $project)
    {
        $request->validate([
            'file_laporan' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $today = now();
        $endDate = \Carbon\Carbon::parse($project->tanggal_selesai);
        $isAfterTimeline = $today > $endDate;

        if ($project->file_laporan) {
            Storage::disk('public')->delete($project->file_laporan);
        }

        $path = $request->file('file_laporan')->store('project/laporan', 'public');
        
        $notes = $request->notes;
        if (empty($notes)) {
            $notes = $isAfterTimeline 
                ? 'Laporan diperbarui oleh admin setelah timeline project berakhir. ' . $endDate->format('d M Y')
                : 'Diperbarui oleh admin';
        }
        
        $project->update([
            'file_laporan' => $path,
            'is_manual_upload' => true,
            'upload_notes' => $notes
        ]);

        $successMessage = $isAfterTimeline 
            ? 'Laporan berhasil diperbarui. Perhatikan bahwa Guru tidak akan dapat mengubah laporan ini.'
            : 'Laporan berhasil diperbarui.';

        return redirect()->route('admin-project-index')
            ->with('success', $successMessage);
    }

    public function deleteLaporan(Request $request, Project $project)
    {
        if ($project->file_laporan) {
            Storage::disk('public')->delete($project->file_laporan);
            $project->update([
                'file_laporan' => null,
                'is_manual_upload' => false,
                'upload_notes' => null
            ]);
            
            return redirect()->route('admin-project-index')
                ->with('success', 'Laporan berhasil dihapus');
        }
        
        return redirect()->route('admin-project-index')
            ->with('error', 'Tidak ada laporan yang bisa dihapus');
    }

    public function create()
    {
        // Get all users with perusahaan role
        $perusahaans = \App\Models\User::role('perusahaan')->get();
        return view('admin.project.create', compact('perusahaans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'file_brief' => 'required|file|mimes:pdf,doc,docx|max:10240', // Max 10MB
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'perusahaan_id' => 'required|exists:users,id'
        ]);

        // Handle file upload
        $path = $request->file('file_brief')->store('project/brief', 'public');

        Project::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_brief' => $path,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'perusahaan_id' => $request->perusahaan_id
        ]);

        return redirect()->route('admin-project-index')
            ->with('success', 'Project berhasil ditambahkan');
    }

    public function edit(Project $project)
    {
        // Get all users with perusahaan role
        $perusahaans = \App\Models\User::role('perusahaan')->get();
        return view('admin.project.edit', compact('project', 'perusahaans'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'file_brief' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // Max 10MB
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'perusahaan_id' => 'required|exists:users,id'
        ]);

        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'perusahaan_id' => $request->perusahaan_id
        ];

        // Handle file upload if a new brief file is provided
        if ($request->hasFile('file_brief')) {
            // Delete old file if exists
            if ($project->file_brief && Storage::disk('public')->exists($project->file_brief)) {
                Storage::disk('public')->delete($project->file_brief);
            }
            
            $path = $request->file('file_brief')->store('project/brief', 'public');
            $data['file_brief'] = $path;
        }

        $project->update($data);

        return redirect()->route('admin-project-index')
            ->with('success', 'Project berhasil diperbarui');
    }

    public function destroy(Project $project)
    {
        // Project deletion will automatically clean up associated files
        // because of the boot method in the Project model
        $project->delete();
        
        return redirect()->route('admin-project-index')
            ->with('success', 'Project berhasil dihapus');
    }
}
