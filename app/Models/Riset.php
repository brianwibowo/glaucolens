<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Riset extends Model
{
    use HasFactory;

    protected $table = 'risets';

    protected $fillable = [
        'topik',
        'deskripsi',
        'tim_riset',
        'file_proposal',
        'dokumentasi',
        'status', // <<< PASTIKAN INI ADA DI FILLABLE
    ];

    protected $casts = [
        'tim_riset' => 'array',
    ];

    public function anggota(): HasMany
    {
        return $this->hasMany(Anggota_Riset::class, 'id_risets');
    }

    // Pastikan jika kamu punya default status di database, atau set di controller
    // Contoh: protected $attributes = ['status' => 'proses'];
}