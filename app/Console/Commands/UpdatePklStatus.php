<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PKL;
use App\Models\User;
use Carbon\Carbon;

class UpdatePklStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pkl:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update PKL status based on progress and applications';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Start updating PKL statuses...');
        
        $pkls = PKL::all();
        $updated = 0;
        
        foreach ($pkls as $pkl) {
            $oldStatus = $pkl->status;
            $progress = $pkl->calculateProgress();
            $hasApplicants = $pkl->siswas()->count() > 0;
            
            // Update status logic
            if ($progress['percentage'] >= 100) {
                $newStatus = 'selesai';
                
                // Also update all students to "selesai" status
                foreach ($pkl->siswas as $student) {
                    if ($student->pkl_status === 'disetujui') {
                        $student->pkl_status = 'selesai';
                        $student->save();
                    }
                }
            } elseif ($hasApplicants) {
                $newStatus = 'berjalan';
            } else {
                $newStatus = 'proses';
            }
            
            // Only save if status changed
            if ($oldStatus !== $newStatus) {
                $pkl->status = $newStatus;
                $pkl->save();
                $updated++;
            }
        }
        
        $this->info("Completed! {$updated} PKL records updated.");
        return 0;
    }
}
