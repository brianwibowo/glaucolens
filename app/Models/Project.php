<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_manual_upload' => 'boolean',
    ];    protected static function boot()
    {
        parent::boot(); 

        // Clean up files when project is deleted
        static::deleting(function ($project) {
            // Delete brief file if exists
            if ($project->file_brief) {
                if (Storage::disk('public')->exists($project->file_brief)) {
                    Storage::disk('public')->delete($project->file_brief);
                }
            }
            
            // Delete laporan file if exists
            if ($project->file_laporan) {
                if (Storage::disk('public')->exists($project->file_laporan)) {
                    Storage::disk('public')->delete($project->file_laporan);
                }
            }
            
            // Also check for any potential path variations to ensure complete cleanup
            $briefBasePath = 'project/brief/';
            $laporanBasePath = 'project/laporan/';
            
            if ($project->id) {
                // Check if there are any files with project ID as part of filename
                $briefPattern = $briefBasePath . '*' . $project->id . '*';
                $laporanPattern = $laporanBasePath . '*' . $project->id . '*';
                
                foreach (Storage::disk('public')->files($briefBasePath) as $file) {
                    if (str_contains($file, (string)$project->id)) {
                        Storage::disk('public')->delete($file);
                    }
                }
                
                foreach (Storage::disk('public')->files($laporanBasePath) as $file) {
                    if (str_contains($file, (string)$project->id)) {
                        Storage::disk('public')->delete($file);
                    }
                }
            }
        });
        
        // Clean up old files when updated
        static::updating(function ($project) {
            // Clean up old brief file when updated
            if ($project->isDirty('file_brief') && $project->getOriginal('file_brief')) {
                if (Storage::disk('public')->exists($project->getOriginal('file_brief'))) {
                    Storage::disk('public')->delete($project->getOriginal('file_brief'));
                }
            }
            
            // Clean up old laporan file when updated
            if ($project->isDirty('file_laporan') && $project->getOriginal('file_laporan')) {
                if (Storage::disk('public')->exists($project->getOriginal('file_laporan'))) {
                    Storage::disk('public')->delete($project->getOriginal('file_laporan'));
                }
            }
        });
    }

    public function perusahaan()
    {
        return $this->belongsTo(User::class, 'perusahaan_id');
    }
}