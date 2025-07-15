<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beasiswa extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'beasiswas';

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
        'raport' => 'string',
        'surat_rekomendasi' => 'string',
        'surat_motivasi' => 'string',
        'portofolio' => 'string',
    ];

    /**
     * Get the status options for the beasiswa.
     *
     * @return array
     */
    public static function getStatusOptions()
    {
        return [
            'lolos' => 'Diterima',
            'tidak lolos' => 'Ditolak',
            'proses' => 'Menunggu Review',
        ];
    }


    public function batch()
    {
        return $this->belongsTo(BeasiswaBatch::class, 'batch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
