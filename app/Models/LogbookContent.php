<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LogbookContent extends Model
{
    use HasFactory;

    protected $table = 'logbook_content';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_validasi_pembimbing' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($logbook) {
            if ($logbook->dokumentasi) {
                Storage::disk('public')->delete($logbook->dokumentasi);
            }
        });
    }

    /**
     * Get the status validasi options.
     *
     * @return array
     */
    public static function getStatusValidasiOptions()
    {
        return [
            'belum_validasi' => 'Belum Validasi',
            'valid' => 'Valid',
            'revisi' => 'Perlu Revisi',
        ];
    }

    public function logbook()
    {
        return $this->belongsTo(Logbook::class);
    }
}
