<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PKL extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pkls';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'nilai' => 'integer',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_validasi_pembimbing' => 'datetime',
        'tanggal_validasi_waka_humas' => 'datetime',
    ];

    /**
     * Get the status pembimbing options.
     *
     * @return array
     */
    public static function getStatusPembimbingOptions()
    {
        return [
            'disetujui' => 'Disetujui',
            'revisi' => 'Revisi',
            'proses' => 'Proses',
        ];
    }

    /**
     * Get the status waka humas options.
     *
     * @return array
     */
    public static function getStatusWakaHumasOptions()
    {
        return [
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'proses' => 'Proses',
        ];
    }

    public function siswas()
    {
        return $this->hasMany(User::class, 'pkl_id');
    }

    public function pembimbing()
    {
        return $this->belongsTo(User::class, 'pembimbing_id');
    }

    public function perusahaan()
    {
        return $this->belongsTo(User::class, 'perusahaan_id');
    }    public function logbooks()
    {
        return $this->hasMany(Logbook::class, 'pkl_id');
    }
    
    /**
     * Calculate the progress percentage of the PKL program
     * 
     * @return array with percentage and status
     */
    public function calculateProgress()
    {
        $today = now();
        $startDate = $this->tanggal_mulai;
        $endDate = $this->tanggal_selesai;
        
        // If PKL hasn't started yet
        if ($today < $startDate) {
            return [
                'percentage' => 0,
                'status' => 'belum_mulai'
            ];
        }
        
        // If PKL has ended
        if ($today > $endDate) {
            return [
                'percentage' => 100,
                'status' => 'selesai'
            ];
        }
        
        // Calculate percentage if PKL is in progress
        $totalDuration = $startDate->diffInDays($endDate);
        $elapsedDuration = $startDate->diffInDays($today);
        
        if ($totalDuration == 0) {
            $percentage = 100; // If start and end date are the same, consider it 100%
        } else {
            $percentage = min(100, round(($elapsedDuration / $totalDuration) * 100));
        }
        
        return [
            'percentage' => $percentage,
            'status' => 'berlangsung'
        ];
    }

    /**
     * Get the user that created this PKL
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the company being represented by admin
     */
    public function representedCompany()
    {
        return $this->belongsTo(User::class, 'admin_representing');
    }
}
