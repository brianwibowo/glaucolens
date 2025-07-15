<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;

class MoocModule extends Model
{
    use HasFactory;

    protected $table = 'mooc_modules';

    protected $guarded = ['id']; // Memastikan kolom baru 'deskripsi_modul' bisa diisi

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($materi) {
            if ($materi->dokumen_materi) {
                Storage::disk('public')->delete($materi->dokumen_materi);
            }
        });
    }

    public function mooc()
    {
        return $this->belongsTo(Mooc::class);
    }
}