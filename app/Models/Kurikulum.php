<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class Kurikulum extends Model
{
    use HasFactory;

    protected $guarded = ['id']; // Jika menggunakan guarded, pastikan semua kolom lain bisa diisi massal

    // Explicitly define fillable fields for clarity
    protected $fillable = [ // Ini sudah ada dan benar
        'nama_kurikulum',
        'pengirim_id',
        'perusahaan_id',
        'tahun_ajaran',
        'deskripsi',
        'file_kurikulum',
        'validasi_sekolah',
        'validasi_perusahaan',
        'komentar'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($kurikulum) {
            // Hapus file dari storage saat record dihapus
            if ($kurikulum->file_kurikulum) {
                Storage::disk('public')->delete($kurikulum->file_kurikulum);
            }
        });
    }

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }

    public function perusahaan()
    {
        return $this->belongsTo(User::class, 'perusahaan_id'); // Menambahkan relasi perusahaan
    }
}