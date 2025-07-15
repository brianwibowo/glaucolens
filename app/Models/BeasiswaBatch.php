<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BeasiswaBatch extends Model
{
    use HasFactory;

    protected $table = 'beasiswa_batches';

    protected $guarded = ['id'];

    public function talents()
    {
        return $this->hasMany(Talent_Scouting::class, 'batch_id');
    }

    public function perusahaan()
    {
        return $this->belongsTo(User::class, 'perusahaan_id');
    }
    
    public function beasiswas()
    {
        return $this->hasMany(Beasiswa::class, 'batch_id');
    }
}
