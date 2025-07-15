<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Project;

class CleanupProjectFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:cleanup-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up orphaned project files that are not associated with any project';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting project file cleanup...');

        // Get all existing project files from the database
        $briefFiles = Project::whereNotNull('file_brief')->pluck('file_brief')->toArray();
        $laporanFiles = Project::whereNotNull('file_laporan')->pluck('file_laporan')->toArray();
        
        $this->info('Found ' . count($briefFiles) . ' brief files in database');
        $this->info('Found ' . count($laporanFiles) . ' laporan files in database');

        // Check brief files in storage
        $this->cleanupFiles('project/brief/', $briefFiles, 'brief');
        
        // Check laporan files in storage
        $this->cleanupFiles('project/laporan/', $laporanFiles, 'laporan');

        $this->info('Project file cleanup completed successfully');
        return 0;
    }

    /**
     * Clean up orphaned files in a specific directory
     * 
     * @param string $directory The directory to clean up
     * @param array $existingFiles Files that should be kept
     * @param string $fileType Type of files being cleaned up (for logging)
     * @return void
     */
    private function cleanupFiles($directory, $existingFiles, $fileType)
    {
        // Get all files in the storage directory
        $filesInStorage = Storage::disk('public')->files($directory);
        $this->info('Found ' . count($filesInStorage) . ' ' . $fileType . ' files in storage');
        
        $deletedCount = 0;
        
        // Check each file in storage
        foreach ($filesInStorage as $file) {
            // If the file doesn't exist in the database, delete it
            if (!in_array($file, $existingFiles)) {
                $this->warn('Deleting orphaned ' . $fileType . ' file: ' . $file);
                
                try {
                    Storage::disk('public')->delete($file);
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error('Failed to delete file ' . $file . ': ' . $e->getMessage());
                }
            }
        }
        
        $this->info('Deleted ' . $deletedCount . ' orphaned ' . $fileType . ' files');
    }
}
