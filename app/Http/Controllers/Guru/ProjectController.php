<?php

namespace App\Http\Controllers\Guru;

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
            
        return view('guru.project.index', compact('projects'));
    }

    public function uploadLaporan(Request $request, Project $project)
    {
        $today = now();
        $endDate = \Carbon\Carbon::parse($project->tanggal_selesai);
        
        if ($today > $endDate) {
            return redirect()->route('guru-project-index')
                ->with('error', 'Tidak dapat mengupload laporan karena timeline project telah berakhir');
        }
        
        $request->validate([
            'file_laporan' => 'required|file|mimes:pdf,doc,docx|max:10240', // Maksimal 10MB
        ]);

        if ($project->file_laporan) {
            Storage::disk('public')->delete($project->file_laporan);
        }

        $path = $request->file('file_laporan')->store('project/laporan', 'public');
        $project->update(['file_laporan' => $path]);

        return redirect()->route('guru-project-index')
            ->with('success', 'Laporan berhasil diupload');
    }

    public function updateLaporan(Request $request, Project $project)
    {
        $today = now();
        $endDate = \Carbon\Carbon::parse($project->tanggal_selesai);
        
        if ($today > $endDate) {
            return redirect()->route('guru-project-index')
                ->with('error', 'Tidak dapat memperbarui laporan karena timeline project telah berakhir');
        }
        
        $request->validate([
            'file_laporan' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($project->file_laporan) {
            Storage::disk('public')->delete($project->file_laporan);
        }

        $path = $request->file('file_laporan')->store('project/laporan', 'public');
        $project->update(['file_laporan' => $path]);

        return redirect()->route('guru-project-index')
            ->with('success', 'Laporan berhasil diperbarui');
    }

    public function deleteLaporan(Request $request, Project $project)
    {
        $today = now();
        $endDate = \Carbon\Carbon::parse($project->tanggal_selesai);
        
        if ($today > $endDate) {
            return redirect()->route('guru-project-index')
                ->with('error', 'Tidak dapat menghapus laporan karena timeline project telah berakhir');
        }
        
        if ($project->is_manual_upload) {
            return redirect()->route('guru-project-index')
                ->with('error', 'Tidak dapat menghapus laporan yang diupload oleh admin');
        }
        
        if ($project->file_laporan) {
            Storage::disk('public')->delete($project->file_laporan);
            $project->update(['file_laporan' => null]);
            
            return redirect()->route('guru-project-index')
                ->with('success', 'Laporan berhasil dihapus');
        }
        
        return redirect()->route('guru-project-index')
            ->with('error', 'Tidak ada laporan yang bisa dihapus');
    }
}
