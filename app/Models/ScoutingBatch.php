<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoutingBatch extends Model
{
    use HasFactory;

    protected $table = 'scouting_batches';

    protected $guarded = ['id'];

    public function talents()
    {
        return $this->hasMany(Talent_Scouting::class, 'batch_id');
    }

    public function perusahaan()
    {
        return $this->belongsTo(User::class, 'perusahaan_id');
    }
}
